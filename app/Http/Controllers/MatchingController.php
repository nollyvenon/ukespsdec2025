<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\AffiliatedCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchingController extends Controller
{
    /**
     * Display the matching interface.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Load user profile and related information
        $profile = $user->profile;
        
        // Get all available courses for matching
        $courses = Course::with('instructor')->get();
        $affiliatedCourses = AffiliatedCourse::with('university')->get();
        
        // Get user's past applications/courses taken
        $pastCourses = collect(); // This would come from course_enrollments, job_applications, etc.
        
        return view('matching.index', compact('profile', 'courses', 'affiliatedCourses', 'pastCourses'));
    }

    /**
     * Get matches for the current user.
     */
    public function getMatches(Request $request)
    {
        $user = Auth::user();
        
        // Get user profile information
        $profile = $user->profile;
        
        // Get user's qualifications (from resume, education, certificates, etc.)
        $userSkills = $profile->skills ?? [];
        $userInterests = $profile->interests ?? [];
        $userEducationLevel = $profile->education_level ?? null;
        $userResumePath = $profile->resume_path ?? null;
        
        // Find matching courses based on skills, interests, and education level
        $matches = collect();
        
        // Match based on skills and interests
        $courses = Course::with('instructor')
            ->where(function($query) use ($userSkills, $userInterests) {
                if (!empty($userSkills)) {
                    foreach ($userSkills as $skill) {
                        $query->orWhere('description', 'LIKE', '%' . $skill . '%');
                    }
                }
                
                if (!empty($userInterests)) {
                    foreach ($userInterests as $interest) {
                        $query->orWhere('description', 'LIKE', '%' . $interest . '%');
                    }
                }
            })
            ->get();
        
        $affiliatedCourses = AffiliatedCourse::with('university')
            ->where(function($query) use ($userSkills, $userInterests) {
                if (!empty($userSkills)) {
                    foreach ($userSkills as $skill) {
                        $query->orWhere('description', 'LIKE', '%' . $skill . '%');
                    }
                }
                
                if (!empty($userInterests)) {
                    foreach ($userInterests as $interest) {
                        $query->orWhere('description', 'LIKE', '%' . $interest . '%');
                    }
                }
            })
            ->get();
        
        // Combine and sort matches based on relevance
        $combinedMatches = $courses->concat($affiliatedCourses);
        
        // Calculate match scores based on various factors
        $matchData = [];
        foreach ($combinedMatches as $match) {
            $score = 0;
            
            // Score based on skills match
            if (!empty($userSkills)) {
                foreach ($userSkills as $skill) {
                    if (stripos($match->description, $skill) !== false || 
                        stripos($match->title ?? '', $skill) !== false) {
                        $score += 10;
                    }
                }
            }
            
            // Score based on interests match
            if (!empty($userInterests)) {
                foreach ($userInterests as $interest) {
                    if (stripos($match->description, $interest) !== false ||
                        stripos($match->title ?? '', $interest) !== false) {
                        $score += 5;
                    }
                }
            }
            
            // Score based on education level match
            if ($userEducationLevel && isset($match->level) && 
                stripos($match->level, $userEducationLevel) !== false) {
                $score += 15;
            }
            
            $matchData[] = [
                'match' => $match,
                'score' => $score
            ];
        }
        
        // Sort by score descending
        usort($matchData, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return response()->json([
            'matches' => $matchData,
            'user_profile' => $profile,
            'total_matches' => count($matchData)
        ]);
    }
}