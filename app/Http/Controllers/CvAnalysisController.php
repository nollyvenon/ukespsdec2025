<?php

namespace App\Http\Controllers;

use App\Models\CvUpload;
use App\Services\CvAnalysisService;
use App\Services\JobRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CvAnalysisController extends Controller
{
    protected $cvAnalysisService;
    protected $jobRecommendationService;

    public function __construct(
        CvAnalysisService $cvAnalysisService,
        JobRecommendationService $jobRecommendationService
    ) {
        $this->cvAnalysisService = $cvAnalysisService;
        $this->jobRecommendationService = $jobRecommendationService;
    }

    /**
     * Analyze a specific CV
     */
    public function analyzeCv(Request $request, $id)
    {
        $cv = CvUpload::findOrFail($id);

        // Check if user has permission to analyze this CV
        if (!Auth::user()->can('administrate') && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to analyze this CV');
        }

        // Perform CV analysis
        $analysis = $this->cvAnalysisService->analyzeCv($cv);

        return response()->json([
            'success' => true,
            'message' => 'CV analyzed successfully',
            'data' => $analysis,
        ]);
    }

    /**
     * Score a CV against a specific job
     */
    public function scoreCvAgainstJob(Request $request, $cvId, $jobId)
    {
        $cv = CvUpload::findOrFail($cvId);
        $job = \App\Models\JobListing::findOrFail($jobId);

        // Check if user has permission to score this CV
        if (!Auth::user()->can('administrate') && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to score this CV');
        }

        // Score CV against job
        $scores = $this->cvAnalysisService->scoreCvAgainstJob($cv, $job);

        return response()->json([
            'success' => true,
            'message' => 'CV scored against job successfully',
            'data' => $scores,
        ]);
    }

    /**
     * Get job recommendations for a CV
     */
    public function getJobRecommendations(Request $request, $cvId)
    {
        $cv = CvUpload::findOrFail($cvId);

        // Check if user has permission to get recommendations for this CV
        if (!Auth::user()->can('administrate') && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to get recommendations for this CV');
        }

        // Get personalized job recommendations
        $recommendations = $this->jobRecommendationService->getPersonalizedRecommendations($cv);

        return response()->json([
            'success' => true,
            'message' => 'Job recommendations retrieved successfully',
            'data' => $recommendations,
        ]);
    }

    /**
     * Get detailed analysis of a CV
     */
    public function getDetailedAnalysis($cvId)
    {
        $cv = CvUpload::findOrFail($cvId);

        // Check if user has permission to view this CV analysis
        if (!Auth::user()->can('administrate') && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to view this CV analysis');
        }

        // Return detailed CV analysis
        return response()->json([
            'success' => true,
            'data' => [
                'cv' => $cv,
                'parsed_skills' => $cv->parsed_skills,
                'parsed_qualifications' => $cv->parsed_qualifications,
                'parsed_experience' => $cv->parsed_experience,
                'parsed_education' => $cv->parsed_education,
                'cv_summary' => $cv->cv_summary,
                'total_match_score' => $cv->total_match_score,
                'recommended_jobs' => $cv->recommended_jobs,
                'match_scores' => [
                    'skill_match_scores' => $cv->skill_match_scores,
                    'qualification_match_scores' => $cv->qualification_match_scores,
                    'experience_match_scores' => $cv->experience_match_scores,
                ],
            ],
        ]);
    }

    /**
     * Attach a cover letter to a CV
     */
    public function attachCoverLetter(Request $request, $cvId)
    {
        $cv = CvUpload::findOrFail($cvId);

        // Check if user owns this CV
        if ($cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to modify this CV');
        }

        $request->validate([
            'cover_letter_content' => 'required|string|max:5000',
            'cover_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Allow file upload
        ]);

        // Handle file upload if provided
        $coverLetterPath = null;
        if ($request->hasFile('cover_letter_file')) {
            $file = $request->file('cover_letter_file');
            $coverLetterPath = $file->store('cover_letters', 'public');
        }

        // Update CV with cover letter information
        $cv->update([
            'cover_letter_content' => $request->cover_letter_content,
            'cover_letter_path' => $coverLetterPath,
        ]);

        // Extract keywords from the cover letter
        $keywords = $this->extractKeywords($request->cover_letter_content);
        $cv->update([
            'cover_letter_keywords' => $keywords,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cover letter attached successfully',
            'data' => [
                'cover_letter_path' => $coverLetterPath,
                'cover_letter_content' => $request->cover_letter_content,
                'cover_letter_keywords' => $keywords,
            ],
        ]);
    }

    /**
     * Extract keywords from text
     */
    private function extractKeywords(string $text): array
    {
        // Simple keyword extraction - in a real system you'd use NLP
        $tokens = preg_split('/[\s,\.!?;:()\-_]+/', strtolower($text));

        // Remove common stop words
        $stopWords = [
            'the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'a', 'an', 'is', 'are', 'was', 'were',
            'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might',
            'must', 'can', 'this', 'that', 'these', 'those', 'i', 'you', 'he', 'she', 'it', 'we', 'they', 'me', 'him', 'her',
            'us', 'them', 'my', 'your', 'his', 'its', 'our', 'their', 'myself', 'yourself', 'himself', 'herself', 'itself',
            'ourselves', 'yourselves', 'themselves', 'as', 'if', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each',
            'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 'than', 'too',
            'very', 'just', 'now', 'also', 'perhaps'
        ];

        $keywords = [];
        foreach ($tokens as $token) {
            $token = trim($token);
            if (strlen($token) > 2 && !in_array($token, $stopWords) && !is_numeric($token)) {
                $keywords[] = $token;
            }
        }

        // Get top 20 keywords by frequency
        $keywordCounts = array_count_values($keywords);
        arsort($keywordCounts);
        $topKeywords = array_slice(array_keys($keywordCounts), 0, 20);

        return $topKeywords;
    }

    /**
     * Get career trend analysis for a CV
     */
    public function getCareerTrendAnalysis($cvId)
    {
        $cv = CvUpload::findOrFail($cvId);

        // Check if user has permission to view this CV analysis
        if (!Auth::user()->can('administrate') && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to view this CV analysis');
        }

        // Get career trend analysis
        $trends = $this->jobRecommendationService->getCareerTrendAnalysis($cv);

        return response()->json([
            'success' => true,
            'data' => $trends,
        ]);
    }
}
