<?php

namespace App\Services;

use App\Models\CvUpload;
use App\Models\JobListing;
use App\Models\JobApplication;

class JobRecommendationService
{
    protected $cvAnalysisService;

    public function __construct(CvAnalysisService $cvAnalysisService)
    {
        $this->cvAnalysisService = $cvAnalysisService;
    }

    /**
     * Generate personalized job recommendations based on CV content
     */
    public function getPersonalizedRecommendations(CvUpload $cv, int $limit = 10): array
    {
        // First ensure the CV is analyzed
        if (!$cv->auto_parsed) {
            $this->cvAnalysisService->analyzeCv($cv);
        }

        // Get user's past applications to avoid recommending same jobs repeatedly
        $appliedJobIds = JobApplication::where('user_id', $cv->user_id)
            ->pluck('job_listing_id')
            ->toArray();

        // Get all published jobs excluding already applied ones
        $jobsQuery = JobListing::where('job_status', 'published')
            ->whereNotIn('id', $appliedJobIds);

        // Get candidate's skills and experience from CV
        $cvSkills = $cv->parsed_skills ?? $cv->extracted_skills ?? [];
        $cvExperience = $cv->parsed_experience ?? $cv->work_experience ?? [];
        $cvQualifications = $cv->parsed_qualifications ?? [];
        $cvLocation = $cv->location ?? '';

        $recommendedJobs = [];
        $scores = [];

        // Get eligible jobs
        $jobs = $jobsQuery->get();

        foreach ($jobs as $job) {
            $score = $this->calculatePersonalizedRecommendationScore($cv, $job);
            
            if ($score > 0) { // Only recommend jobs with positive match score
                $recommendedJobs[] = [
                    'job' => $job,
                    'score' => $score,
                ];
                $scores[$job->id] = $score;
            }
        }

        // Sort by score descending
        usort($recommendedJobs, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Take top results
        $topRecommended = array_slice($recommendedJobs, 0, $limit);

        // Update CV with recommendations
        $cv->update([
            'recommended_jobs' => array_column($topRecommended, 'job.id'),
            'job_recommendation_scores' => $scores,
        ]);

        return [
            'recommended_jobs' => collect(array_column($topRecommended, 'job')),
            'scores' => $scores,
            'total_candidates' => count($recommendedJobs),
        ];
    }

    /**
     * Calculate personalized recommendation score for a job
     */
    private function calculatePersonalizedRecommendationScore(CvUpload $cv, JobListing $job): float
    {
        $totalScore = 0;
        $maxPossibleScore = 0;

        // 1. Skills match (40% weight)
        $skillsWeight = 0.4;
        $skillsScore = $this->calculateSkillsMatchScore($cv->parsed_skills ?? $cv->extracted_skills ?? [], $job);
        $totalScore += $skillsScore * $skillsWeight;
        $maxPossibleScore += 100 * $skillsWeight;

        // 2. Experience match (25% weight)
        $experienceWeight = 0.25;
        $experienceScore = $this->calculateExperienceMatchScore($cv->parsed_experience ?? $cv->work_experience ?? [], $job);
        $totalScore += $experienceScore * $experienceWeight;
        $maxPossibleScore += 100 * $experienceWeight;

        // 3. Qualification match (20% weight)
        $qualificationWeight = 0.2;
        $qualificationScore = $this->calculateQualificationMatchScore($cv->parsed_qualifications ?? [], $job);
        $totalScore += $qualificationScore * $qualificationWeight;
        $maxPossibleScore += 100 * $qualificationWeight;

        // 4. Location match (10% weight)
        $locationWeight = 0.1;
        $locationScore = $this->calculateLocationMatchScore($cv->location ?? '', $job);
        $totalScore += $locationScore * $locationWeight;
        $maxPossibleScore += 100 * $locationWeight;

        // 5. Salary expectation match (5% weight)
        $salaryWeight = 0.05;
        $salaryScore = $this->calculateSalaryMatchScore($cv->desired_salary ?? 0, $job);
        $totalScore += $salaryScore * $salaryWeight;
        $maxPossibleScore += 100 * $salaryWeight;

        // Calculate final score as percentage
        if ($maxPossibleScore > 0) {
            return min(100, ($totalScore / $maxPossibleScore) * 100);
        }

        return 0;
    }

    /**
     * Calculate skills match score
     */
    private function calculateSkillsMatchScore(array $candidateSkills, JobListing $job): float
    {
        if (empty($candidateSkills)) {
            return 0;
        }

        $jobRequiredSkills = array_merge(
            explode(',', $job->required_skills ?? ''),
            explode(',', $job->preferred_skills ?? '')
        );

        $jobRequiredSkills = array_map('trim', $jobRequiredSkills);
        $jobRequiredSkills = array_filter($jobRequiredSkills);

        if (empty($jobRequiredSkills)) {
            return 50; // Neutral score if no skills specified
        }

        $matches = 0;
        $totalChecked = 0;

        // Convert candidate skills to lowercase for comparison
        $candidateSkillsLower = array_map('strtolower', $candidateSkills);
        
        foreach ($jobRequiredSkills as $jobSkill) {
            $jobSkillLower = strtolower(trim($jobSkill));
            $totalChecked++;

            // Exact match
            if (in_array($jobSkillLower, $candidateSkillsLower)) {
                $matches++;
            } else {
                // Partial match within any candidate skill
                foreach ($candidateSkillsLower as $candidateSkill) {
                    if (strpos($candidateSkill, $jobSkillLower) !== false || 
                        strpos($jobSkillLower, $candidateSkill) !== false) {
                        $matches++;
                        break;
                    }
                }
            }
        }

        if ($totalChecked === 0) {
            return 50; // Neutral score
        }

        return min(100, ($matches / $totalChecked) * 100);
    }

    /**
     * Calculate experience match score
     */
    private function calculateExperienceMatchScore(array $candidateExperience, JobListing $job): float
    {
        if (empty($candidateExperience)) {
            return 0;
        }

        $requiredMonths = ($job->required_years ?? 0) * 12;

        // Calculate total months of experience from CV
        $totalCandidateMonths = 0;
        
        foreach ($candidateExperience as $exp) {
            if (is_array($exp)) {
                if (isset($exp['duration']) || isset($exp['start_date']) || isset($exp['end_date'])) {
                    // Try to extract duration
                    $durationMonths = $this->estimateExperienceMonths($exp);
                    $totalCandidateMonths += $durationMonths;
                }
            }
        }

        if ($totalCandidateMonths == 0) {
            // If no duration info available, estimate based on number of positions
            $totalCandidateMonths = min(count($candidateExperience) * 24, 360); // Max 30 years
        }

        // Calculate match score
        if ($totalCandidateMonths >= $requiredMonths) {
            // Experience meets or exceeds requirements
            return min(100, 50 + (($totalCandidateMonths - $requiredMonths) * 2)); // Extra points for exceeding
        } else {
            // Experience is less than required
            return max(0, ($totalCandidateMonths / $requiredMonths) * 50); // Up to 50% if below requirement
        }
    }

    /**
     * Estimate experience duration in months from array
     */
    private function estimateExperienceMonths(array $experience): int
    {
        // This is a simplified estimation
        // In a real system, you'd have more detailed date handling
        
        if (isset($experience['duration'])) {
            $durationStr = strtolower($experience['duration']);
            
            // Look for patterns like "X months", "X years", etc.
            if (preg_match('/(\d+)\s*months?/', $durationStr, $matches)) {
                return (int)$matches[1];
            }
            
            if (preg_match('/(\d+)\s*years?/', $durationStr, $matches)) {
                return (int)$matches[1] * 12;
            }
        }
        
        return 12; // Default to 1 year if no duration specified
    }

    /**
     * Calculate qualification match score
     */
    private function calculateQualificationMatchScore(array $candidateQualifications, JobListing $job): float
    {
        if (empty($candidateQualifications) && empty($job->qualifications)) {
            return 50; // Neutral score if nothing specified
        }

        if (empty($candidateQualifications)) {
            return 0; // No match if no qualifications
        }

        $jobQualifications = array_filter(explode(',', $job->qualifications ?? ''));
        $jobQualifications = array_map('trim', $jobQualifications);

        if (empty($jobQualifications)) {
            return 50; // Neutral score if job has no requirements
        }

        $matches = 0;
        $totalChecked = count($jobQualifications);

        $candidateQualsLower = array_map('strtolower', $candidateQualifications);

        foreach ($jobQualifications as $jobQual) {
            $jobQualLower = strtolower($jobQual);
            
            foreach ($candidateQualsLower as $candidateQual) {
                if (strpos($candidateQual, $jobQualLower) !== false || 
                    strpos($jobQualLower, $candidateQual) !== false) {
                    $matches++;
                    break;
                }
            }
        }

        if ($totalChecked === 0) {
            return 50; // Neutral score
        }

        return min(100, ($matches / $totalChecked) * 100);
    }

    /**
     * Calculate location match score
     */
    private function calculateLocationMatchScore(string $candidateLocation, JobListing $job): float
    {
        if (empty($candidateLocation) || empty($job->location)) {
            return 50; // Neutral score
        }

        // Normalize locations for comparison
        $candidateLoc = strtolower(trim($candidateLocation));
        $jobLoc = strtolower(trim($job->location));

        // Exact match
        if ($candidateLoc === $jobLoc) {
            return 100;
        }

        // Partial match
        if (strpos($candidateLoc, $jobLoc) !== false || strpos($jobLoc, $candidateLoc) !== false) {
            return 80;
        }

        // Same region/state check (simplified)
        $candidateParts = explode(',', $candidateLoc);
        $jobParts = explode(',', $jobLoc);

        // Check if state/region matches (usually the last part)
        $candidateRegion = isset($candidateParts[1]) ? trim($candidateParts[1]) : $candidateLoc;
        $jobRegion = isset($jobParts[1]) ? trim($jobParts[1]) : $jobLoc;

        if (strtolower($candidateRegion) === strtolower($jobRegion)) {
            return 70;
        }

        // Different regions
        return 30;
    }

    /**
     * Calculate salary match score
     */
    private function calculateSalaryMatchScore(int $candidateDesiredSalary, JobListing $job): float
    {
        $jobMinSalary = $job->salary_min ?? 0;
        $jobMaxSalary = $job->salary_max ?? 0;

        if ($jobMinSalary == 0 && $jobMaxSalary == 0) {
            return 50; // Neutral score if no salary info
        }

        $candidateSalary = $candidateDesiredSalary;

        if ($candidateSalary == 0) {
            return 40; // Low-medium score if no preference
        }

        // Determine job salary range
        $jobMin = $jobMinSalary;
        $jobMax = $jobMaxSalary ?: $jobMinSalary; // If max is not specified, use min as max

        // Calculate score based on fit
        if ($candidateSalary >= $jobMin && $candidateSalary <= $jobMax) {
            // Ideal match - salary within range
            return 100;
        } elseif ($candidateSalary >= $jobMin) {
            // Candidate accepts lower (may be OK)
            return max(30, 100 - ((($candidateSalary - $jobMax) / $jobMax) * 30));
        } else {
            // Candidate wants more than offered
            return max(0, 100 - ((($jobMin - $candidateSalary) / $jobMin) * 50));
        }
    }

    /**
     * Get trend analysis for user's career path
     */
    public function getCareerTrendAnalysis(CvUpload $cv): array
    {
        $allJobs = JobListing::where('job_status', 'published')->get();
        $recommendations = [];

        foreach ($allJobs as $job) {
            $score = $this->calculatePersonalizedRecommendationScore($cv, $job);
            if ($score > 70) { // High match threshold
                $recommendations[] = [
                    'job' => $job,
                    'match_score' => $score,
                    'trend_potential' => $this->calculateTrendPotential($job),
                ];
            }
        }

        // Sort by match score and trend potential
        usort($recommendations, function ($a, $b) {
            $scoreDiff = $b['match_score'] <=> $a['match_score'];
            if ($scoreDiff === 0) {
                return $b['trend_potential'] <=> $a['trend_potential'];
            }
            return $scoreDiff;
        });

        return array_slice($recommendations, 0, 10);
    }
}