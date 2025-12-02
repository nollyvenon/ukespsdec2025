<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Course;
use App\Models\JobListing;
use App\Models\Testimonial;
use App\Models\AffiliatedCourse; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index()
    {
        $eventsCount = Event::where('event_status', 'published')->count();
        $coursesCount = Course::where('course_status', 'published')->count();
        $jobsCount = JobListing::where('job_status', 'published')->count();

        // Get latest items
        $latestEvents = Event::where('event_status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $latestCourses = Course::where('course_status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $latestJobs = JobListing::where('job_status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('eventsCount', 'coursesCount', 'jobsCount', 'latestEvents', 'latestCourses', 'latestJobs'));
    }

    /**
     * Display the home page with featured content.
     */
    public function home()
    {
        // Get featured testimonials
        $testimonials = Testimonial::where('is_approved', true)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get latest jobs
        $latestJobs = JobListing::where('job_status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get latest events
        $latestEvents = Event::where('event_status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Get featured courses
        $featuredCourses = AffiliatedCourse::where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $featuredJobs = JobListing::where('job_status', 'published')
          ->where('is_featured', true) // assuming there's an is_featured column
          ->orderBy('created_at', 'desc')
          ->limit(6)
          ->get();    

        return view('welcome', compact(
            'testimonials',
            'latestJobs',
            'latestEvents',
            'featuredCourses',
            'featuredJobs'
        ));
    }

    /**
     * Display the events portal section.
     */
    public function eventsPortal()
    {
        $events = Event::where('event_status', 'published')
            ->orderBy('start_date', 'asc')
            ->paginate(10);

        return view('portal.events', compact('events'));
    }

    /**
     * Display the courses portal section.
     */
    public function coursesPortal()
    {
        $courses = Course::where('course_status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('portal.courses', compact('courses'));
    }

    /**
     * Display the jobs portal section.
     */
    public function jobsPortal()
    {
        $jobListings = JobListing::where('job_status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('portal.jobs', compact('jobListings'));
    }
}
