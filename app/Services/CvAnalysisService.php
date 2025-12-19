<?php

namespace App\Services;

use App\Models\CvUpload;
use App\Models\JobListing;
use Illuminate\Support\Facades\Storage;

class CvAnalysisService
{
    /**
     * Analyze a CV and extract advanced information
     */
    public function analyzeCv(CvUpload $cv): array
    {
        $analysis = [
            'skills' => $this->extractSkills($cv),
            'qualifications' => $this->extractQualifications($cv),
            'experience' => $this->extractExperience($cv),
            'education' => $this->extractEducation($cv),
            'summary' => $this->generateSummary($cv),
        ];

        // Update the CV with parsed data
        $cv->update([
            'parsed_skills' => $analysis['skills'],
            'parsed_qualifications' => $analysis['qualifications'],
            'parsed_experience' => $analysis['experience'],
            'parsed_education' => $analysis['education'],
            'cv_summary' => $analysis['summary'],
            'auto_parsed' => true,
            'parsed_at' => now(),
        ]);

        return $analysis;
    }

    /**
     * Score a CV against job requirements
     */
    public function scoreCvAgainstJob(CvUpload $cv, JobListing $job): array
    {
        $matchScores = [];
        $cvParsedData = $cv->parsed_data ?? [];
        $jobRequirements = $job->requirements ?? [];

        // Score skills match
        $skillMatchScore = $this->calculateSkillMatchScore(
            $cv->parsed_skills ?? $cv->extracted_skills ?? [],
            $job->required_skills ?? []
        );
        
        $matchScores['skills'] = $skillMatchScore;

        // Score qualifications match
        $qualificationMatchScore = $this->calculateQualificationMatchScore(
            $cv->parsed_qualifications ?? [],
            $job->qualifications ?? []
        );
        
        $matchScores['qualifications'] = $qualificationMatchScore;

        // Score experience match
        $experienceMatchScore = $this->calculateExperienceMatchScore(
            $cv->parsed_experience ?? $cv->work_experience ?? [],
            $job->experience_level ?? '',
            $job->required_years ?? 0
        );
        
        $matchScores['experience'] = $experienceMatchScore;

        // Calculate total match score
        $totalMatchScore = $this->calculateTotalMatchScore($matchScores);

        // Update the CV with match scores
        $cv->update([
            'skill_match_scores' => [$job->id => $skillMatchScore],
            'qualification_match_scores' => [$job->id => $qualificationMatchScore],
            'experience_match_scores' => [$job->id => $experienceMatchScore],
            'total_match_score' => $totalMatchScore,
        ]);

        return [
            'skills_score' => $skillMatchScore,
            'qualifications_score' => $qualificationMatchScore,
            'experience_score' => $experienceMatchScore,
            'total_score' => $totalMatchScore,
        ];
    }

    /**
     * Generate AI-powered job recommendations based on CV
     */
    public function getJobRecommendations(CvUpload $cv, int $limit = 10): array
    {
        $cvSkills = $cv->parsed_skills ?? $cv->extracted_skills ?? [];
        $cvExperience = $cv->parsed_experience ?? $cv->work_experience ?? [];
        $cvQualifications = $cv->parsed_qualifications ?? [];

        // Find jobs based on skills match
        $recommendedJobs = JobListing::where('job_status', 'published')
            ->where(function ($query) use ($cvSkills) {
                foreach ($cvSkills as $skill) {
                    $query->orWhere('title', 'LIKE', "%{$skill}%")
                          ->orWhere('requirements', 'LIKE', "%{$skill}%")
                          ->orWhere('description', 'LIKE', "%{$skill}%");
                }
            })
            ->orWhere(function ($query) use ($cvExperience) {
                foreach ($cvExperience as $exp) {
                    if (is_array($exp) && isset($exp['job_title'])) {
                        $query->orWhere('title', 'LIKE', "%" . $exp['job_title'] . "%");
                    } elseif (is_string($exp)) {
                        $query->orWhere('title', 'LIKE', "%{$exp}%");
                    }
                }
            })
            ->limit($limit)
            ->get();

        $recommendationScores = [];
        foreach ($recommendedJobs as $job) {
            $score = $this->calculateRelevanceScore($cv, $job);
            $recommendationScores[$job->id] = $score;
        }

        // Sort by relevance score
        $sortedJobs = $recommendedJobs->sortByDesc(function ($job) use ($recommendationScores) {
            return $recommendationScores[$job->id] ?? 0;
        })->take($limit);

        // Update CV with recommendations
        $cv->update([
            'recommended_jobs' => $sortedJobs->pluck('id')->toArray(),
            'job_recommendation_scores' => $recommendationScores,
        ]);

        return [
            'jobs' => $sortedJobs->values(),
            'scores' => $recommendationScores,
        ];
    }

    /**
     * Extract skills from CV using enhanced parsing
     */
    private function extractSkills(CvUpload $cv): array
    {
        // This is a simplified implementation - in a real system you'd use NLP/AI
        $content = $this->extractCvText($cv);
        $technicalSkills = [
            'PHP', 'Laravel', 'Python', 'JavaScript', 'React', 'Vue', 'Angular',
            'MySQL', 'PostgreSQL', 'MongoDB', 'CSS', 'HTML', 'Java', 'C#', 'Node.js',
            'Docker', 'AWS', 'Azure', 'Git', 'CI/CD', 'DevOps', 'WordPress',
            'SEO', 'Digital Marketing', 'Project Management', 'Agile', 'Scrum'
        ];
        
        $foundSkills = [];
        foreach ($technicalSkills as $skill) {
            if (stripos($content, $skill) !== false) {
                $foundSkills[] = $skill;
            }
        }

        // Also extract from existing extracted skills if available
        if (!empty($cv->extracted_skills) && is_array($cv->extracted_skills)) {
            $foundSkills = array_unique(array_merge($foundSkills, $cv->extracted_skills));
        }

        return array_values(array_unique($foundSkills));
    }

    /**
     * Extract qualifications from CV
     */
    private function extractQualifications(CvUpload $cv): array
    {
        $content = $this->extractCvText($cv);
        
        $qualificationPatterns = [
            '/Bachelor(?: of Science| of Arts)?/i',
            '/Master(?: of Science| of Arts)?/i',
            '/PhD|Doctorate/i',
            '/MBA/i',
            '/Certified|Certification|Certified/i',
            '/Graduate|Undergraduate/i',
            '/Diploma/i',
            '/Degree/i',
            '/Qualification/i',
        ];

        $foundQualifications = [];
        
        foreach ($qualificationPatterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches)) {
                foreach ($matches[0] as $match) {
                    $foundQualifications[] = trim($match);
                }
            }
        }

        return array_unique($foundQualifications);
    }

    /**
     * Extract experience from CV
     */
    private function extractExperience(CvUpload $cv): array
    {
        $existingExperience = $cv->work_experience ?? [];
        $content = $this->extractCvText($cv);

        // If work experience is already extracted, just return it with enhanced info
        if (!empty($existingExperience) && is_array($existingExperience)) {
            return $existingExperience;
        }

        // Otherwise try to extract from content
        $experiencePattern = '/([A-Za-z\s]+?)\s*(?:-|–)\s*([A-Za-z\s]+?)\s*(?:-|–)\s*(?:Experience|Manager|Developer|Engineer|Director)/i';
        
        preg_match_all($experiencePattern, $content, $matches);
        
        $experiences = [];
        if (!empty($matches[0])) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $experiences[] = [
                    'job_title' => trim($matches[1][$i]),
                    'company' => trim($matches[2][$i]),
                    'duration' => 'Duration not specified',
                ];
            }
        }

        return $experiences;
    }

    /**
     * Extract education from CV
     */
    private function extractEducation(CvUpload $cv): array
    {
        $content = $this->extractCvText($cv);
        
        $educationPattern = '/(Bachelor|Master|PhD|MBA|Diploma|Certificate).*?(?:in|of)\s*([A-Z][a-z\s]+)/i';
        
        preg_match_all($educationPattern, $content, $matches);
        
        $education = [];
        if (!empty($matches[0])) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $education[] = [
                    'degree' => trim($matches[1][$i]),
                    'field' => trim($matches[2][$i]),
                    'institution' => 'Institution not specified',
                    'year' => 'Year not specified',
                ];
            }
        }

        return $education;
    }

    /**
     * Generate a summary of the CV
     */
    private function generateSummary(CvUpload $cv): string
    {
        $content = $this->extractCvText($cv);
        
        // Simple summary generation based on available data
        $summary = '';
        
        if (!empty($cv->summary)) {
            $summary = $cv->summary;
        } else {
            $summary = substr($content, 0, 200) . '...';
        }
        
        return $summary;
    }

    /**
     * Extract text from CV file
     */
    private function extractCvText(CvUpload $cv): string
    {
        if (Storage::exists($cv->file_path)) {
            $content = Storage::get($cv->file_path);
            
            // If it's a PDF, extract text
            if (strtolower($cv->file_type) === 'application/pdf') {
                // In a real implementation, you would use a PDF text extraction library
                // For now, we'll just return the raw content
                return strip_tags($content);
            } elseif (strtolower($cv->file_type) === 'application/msword' || 
                      strtolower($cv->file_type) === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                // For Word documents, you would use a Word parser library
                return strip_tags($content);
            }
        }
        
        return $cv->summary ?? '';
    }

    /**
     * Calculate skill match score between CV and job
     */
    private function calculateSkillMatchScore(array $cvSkills, array $jobSkills): float
    {
        if (empty($jobSkills) || empty($cvSkills)) {
            return 0.0;
        }

        $matchedSkills = 0;
        $jobSkillsLower = array_map('strtolower', $jobSkills);
        $cvSkillsLower = array_map('strtolower', $cvSkills);

        foreach ($jobSkillsLower as $jobSkill) {
            if (in_array($jobSkill, $cvSkillsLower)) {
                $matchedSkills++;
            }
        }

        // Return percentage match
        return round(($matchedSkills / count($jobSkills)) * 100, 2);
    }

    /**
     * Calculate qualification match score between CV and job
     */
    private function calculateQualificationMatchScore(array $cvQualifications, array $jobQualifications): float
    {
        if (empty($jobQualifications) || empty($cvQualifications)) {
            return 0.0;
        }

        $matchedQualifications = 0;
        $jobQualificationsLower = array_map('strtolower', $jobQualifications);
        $cvQualificationsLower = array_map('strtolower', $cvQualifications);

        foreach ($jobQualificationsLower as $jobQualification) {
            foreach ($cvQualificationsLower as $cvQualification) {
                if (strpos($cvQualification, $jobQualification) !== false ||
                    strpos($jobQualification, $cvQualification) !== false) {
                    $matchedQualifications++;
                    break;
                }
            }
        }

        // Return percentage match
        return round(($matchedQualifications / count($jobQualifications)) * 100, 2);
    }

    /**
     * Calculate experience match score between CV and job
     */
    private function calculateExperienceMatchScore(array $cvExperience, string $jobExperienceLevel, int $requiredYears): float
    {
        if (empty($cvExperience) || $requiredYears <= 0) {
            return 0.0;
        }

        // Calculate years of experience from CV
        $cvYearsOfExperience = 0;
        
        foreach ($cvExperience as $exp) {
            if (is_array($exp)) {
                // If we have duration info in the experience array
                if (isset($exp['duration'])) {
                    // Try to extract years from duration
                    preg_match('/(\d+)\s*years?/i', $exp['duration'], $matches);
                    if (!empty($matches[1])) {
                        $cvYearsOfExperience += (int)$matches[1];
                    }
                }
            }
        }

        // If we couldn't extract from duration, estimate based on number of positions
        if ($cvYearsOfExperience == 0 && !empty($cvExperience)) {
            $cvYearsOfExperience = min(count($cvExperience) * 2, 15); // Rough estimate
        }

        // Calculate score based on experience match
        if ($cvYearsOfExperience >= $requiredYears) {
            // Perfect or above: score based on how much above requirement
            return min(100.0, 100.0 + (($cvYearsOfExperience - $requiredYears) * 2));
        } else {
            // Below requirement: proportional score
            return ($cvYearsOfExperience / $requiredYears) * 100;
        }
    }

    /**
     * Calculate total match score from individual scores
     */
    private function calculateTotalMatchScore(array $matchScores): float
    {
        $totalScore = 0;
        $count = 0;
        
        foreach ($matchScores as $score) {
            if (is_numeric($score)) {
                $totalScore += $score;
                $count++;
            }
        }
        
        return $count > 0 ? round($totalScore / $count, 2) : 0.0;
    }

    /**
     * Calculate relevance score between CV and job
     */
    private function calculateRelevanceScore(CvUpload $cv, JobListing $job): float
    {
        $titleMatch = $this->calculateSimilarity($cv->parsed_skills ?? $cv->extracted_skills ?? [], [$job->title]);
        $descriptionMatch = $this->calculateTextSimilarity(strip_tags($job->description ?? ''), $cv->summary ?? '');
        
        // Weighted combination of different relevance factors
        $relevanceScore = ($titleMatch * 0.6) + ($descriptionMatch * 0.4);
        
        return min(100.0, $relevanceScore * 100);
    }

    /**
     * Calculate similarity between arrays
     */
    private function calculateSimilarity(array $arr1, array $arr2): float
    {
        if (empty($arr1) || empty($arr2)) {
            return 0.0;
        }

        $arr1 = array_map('strtolower', $arr1);
        $arr2 = array_map('strtolower', $arr2);

        $common = array_intersect($arr1, $arr2);
        $union = array_unique(array_merge($arr1, $arr2));

        return !empty($union) ? count($common) / count($union) : 0.0;
    }

    /**
     * Calculate text similarity using a simple approach
     */
    private function calculateTextSimilarity(string $text1, string $text2): float
    {
        if (empty($text1) || empty($text2)) {
            return 0.0;
        }

        $text1 = strtolower(trim($text1));
        $text2 = strtolower(trim($text2));

        // Simple word overlap approach
        $words1 = explode(' ', preg_replace('/[^a-zA-Z0-9\s]/', ' ', $text1));
        $words2 = explode(' ', preg_replace('/[^a-zA-Z0-9\s]/', ' ', $text2));
        
        $words1 = array_filter($words1);
        $words2 = array_filter($words2);

        if (empty($words1) || empty($words2)) {
            return 0.0;
        }

        $commonWords = 0;
        foreach ($words1 as $word) {
            if (in_array($word, $words2)) {
                $commonWords++;
            }
        }

        return min($commonWords / count($words1), $commonWords / count($words2));
    }
}