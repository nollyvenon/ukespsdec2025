<?php

namespace App\Http\Controllers;

use App\Models\AffiliatedCourse;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UniversitiesController extends Controller
{
    /**
     * Display the university dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isUniversityManager() && !$user->is_admin) {
            abort(403, 'Unauthorized access. Only university managers and admins can access this page.');
        }

        // Get stats for university dashboard
        $totalAffiliatedCourses = AffiliatedCourse::where('university_name', $user->profile->university_name ?? $user->name)->count();
        $totalStudents = User::whereHas('courseEnrollments', function($query) {
            $query->whereHas('course', function($q) {
                $q->where('university_id', 'like', '%'.Auth::user()->name.'%'); // Adjust based on your implementation
            });
        })->count();

        // Get affiliated courses
        $affiliatedCourses = AffiliatedCourse::where('university_name', $user->profile->university_name ?? $user->name)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return view('university.dashboard', compact(
            'totalAffiliatedCourses',
            'totalStudents',
            'affiliatedCourses'
        ));
    }

    /**
     * Display affiliated courses for the university.
     */
    public function courses()
    {
        $user = Auth::user();

        if (!$user->isUniversityManager() && !$user->is_admin) {
            abort(403, 'Unauthorized access. Only university managers and admins can access this page.');
        }

        $courses = AffiliatedCourse::where('university_name', $user->profile->university_name ?? $user->name)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('university.courses', compact('courses'));
    }

    /**
     * Show the form for creating a new affiliated course.
     */
    public function createCourse()
    {
        $user = Auth::user();

        if (!$user->isUniversityManager() && !$user->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        return view('university.create-course');
    }

    /**
     * Store a newly created affiliated course.
     */
    public function storeCourse(Request $request)
    {
        $user = Auth::user();

        if (!$user->isUniversityManager() && !$user->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'level' => 'required|in:beginner,intermediate,advanced,all_levels',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'fee' => 'required|numeric|min:0',
            'max_enrollment' => 'nullable|integer|min:1',
            'prerequisites' => 'nullable|string',
            'syllabus' => 'nullable|string',
        ]);

        $courseData = $request->all();
        $courseData['university_name'] = $user->profile->university_name ?? $user->name;
        $courseData['status'] = 'published'; // Default to published

        AffiliatedCourse::create($courseData);

        return redirect()->route('university.courses')->with('success', 'Course created successfully.');
    }
}
