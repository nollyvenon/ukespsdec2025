<?php

namespace App\Http\Controllers;

use App\Models\CourseEnrollment;
use App\Models\EventRegistration;
use App\Models\JobApplication;
use App\Models\Course;
use App\Models\Event;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentsController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user is a student or job seeker
        if (!$user->hasRole('student', 'job_seeker') && !$user->is_admin) {
            abort(403, 'Unauthorized access. Only students, job seekers and admins can access this page.');
        }

        // Get stats for student dashboard
        $totalCoursesEnrolled = CourseEnrollment::where('student_id', $user->id)->count();
        $totalEventsRegistered = EventRegistration::where('user_id', $user->id)->count();
        $totalJobApplications = JobApplication::where('applicant_id', $user->id)->count();

        // Get enrolled courses
        $enrolledCourses = Course::whereIn('id',
            CourseEnrollment::where('student_id', $user->id)->pluck('course_id')
        )
        ->with('instructor')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        // Get registered events
        $registeredEvents = Event::whereIn('id',
            EventRegistration::where('user_id', $user->id)->pluck('event_id')
        )
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        // Get applied jobs
        $appliedJobs = JobListing::whereIn('id',
            JobApplication::where('applicant_id', $user->id)->pluck('job_id')
        )
        ->with('poster')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return view('student.dashboard', compact(
            'totalCoursesEnrolled',
            'totalEventsRegistered',
            'totalJobApplications',
            'enrolledCourses',
            'registeredEvents',
            'appliedJobs'
        ));
    }

    /**
     * Display student's course enrollments.
     */
    public function courses()
    {
        $user = Auth::user();

        if (!$user->hasRole('student', 'job_seeker') && !$user->is_admin) {
            abort(403, 'Unauthorized access. Only students, job seekers and admins can access this page.');
        }

        $courses = Course::whereIn('id',
            CourseEnrollment::where('student_id', $user->id)->pluck('course_id')
        )
        ->with('instructor')
        ->paginate(10);

        return view('student.courses', compact('courses'));
    }

    /**
     * Display student's event registrations.
     */
    public function events()
    {
        $user = Auth::user();

        if (!$user->hasRole('student', 'job_seeker') && !$user->is_admin) {
            abort(403, 'Unauthorized access. Only students, job seekers and admins can access this page.');
        }

        $events = Event::whereIn('id',
            EventRegistration::where('user_id', $user->id)->pluck('event_id')
        )
        ->paginate(10);

        return view('student.events', compact('events'));
    }

    /**
     * Display student's job applications.
     */
    public function jobs()
    {
        $user = Auth::user();

        if (!$user->hasRole('student', 'job_seeker') && !$user->is_admin) {
            abort(403, 'Unauthorized access. Only students, job seekers and admins can access this page.');
        }

        $jobApplications = JobApplication::where('applicant_id', $user->id)
        ->with(['job', 'applicant'])
        ->latest()
        ->paginate(10);

        return view('student.job-applications', compact('jobApplications'));
    }
}
