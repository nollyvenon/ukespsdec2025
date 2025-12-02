<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Models\Event;
use App\Models\AffiliatedCourse;
use App\Models\Course;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get latest jobs
        $latestJobs = JobListing::where('job_status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get latest events
        $latestEvents = Event::where('event_status', 'published')
            ->orderBy('start_date', 'asc')
            ->limit(3)
            ->get();

        // Get featured courses
        $featuredCourses = AffiliatedCourse::where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get testimonials
        $testimonials = Testimonial::where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get hero content
        $heroContents = \App\Models\HeroContent::activeOrdered()->get();

        return view('welcome', compact(
            'latestJobs',
            'latestEvents',
            'featuredCourses',
            'testimonials',
            'heroContents'
        ));
    }
}