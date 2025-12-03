<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruitersController extends Controller
{
    /**
     * Display the recruiter dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user is a recruiter
        if (!$user->isRecruiter() && !$user->is_admin) {
            abort(403, 'Unauthorized access. Only recruiters and admins can access this page.');
        }

        // Get stats for recruiter dashboard
        $totalJobsPosted = JobListing::where('posted_by', $user->id)->count();
        $totalApplications = JobApplication::whereIn('job_id',
            JobListing::where('posted_by', $user->id)->pluck('id')
        )->count();
        $pendingApplications = JobApplication::whereIn('job_id',
            JobListing::where('posted_by', $user->id)->pluck('id')
        )->where('status', 'pending')->count();

        // Get recent job listings
        $recentJobs = JobListing::where('posted_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent applications
        $recentApplications = JobApplication::whereIn('job_id',
            JobListing::where('posted_by', $user->id)->pluck('id')
        )
        ->with(['job', 'applicant'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return view('recruiter.dashboard', compact(
            'totalJobsPosted',
            'totalApplications',
            'pendingApplications',
            'recentJobs',
            'recentApplications'
        ));
    }

    /**
     * Display all job applications for recruiter's jobs.
     */
    public function applications()
    {
        $user = Auth::user();

        if (!$user->isRecruiter() && !$user->is_admin) {
            abort(403, 'Unauthorized access. Only recruiters and admins can access this page.');
        }

        $applications = JobApplication::whereIn('job_id',
            JobListing::where('posted_by', $user->id)->pluck('id')
        )
        ->with(['job', 'applicant'])
        ->latest()
        ->paginate(10);

        return view('recruiter.applications', compact('applications'));
    }
}
