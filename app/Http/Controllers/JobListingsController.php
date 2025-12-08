<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobListingsController extends Controller
{
    /**
     * Display a listing of job listings.
     */
    public function index(Request $request)
    {
        $query = JobListing::where('job_status', 'published');

        // Advanced search criteria
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('requirements', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('location', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('job_type')) {
            $query->where('job_type', $request->input('job_type'));
        }

        if ($request->filled('experience_level')) {
            $query->where('experience_level', $request->input('experience_level'));
        }

        if ($request->filled('salary_min')) {
            $query->where('salary_min', '>=', $request->input('salary_min'));
        }

        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->input('location') . '%');
        }

        if ($request->filled('date_posted')) {
            $date = $request->input('date_posted');
            switch ($date) {
                case 'today':
                    $query->whereDate('created_at', now());
                    break;
                case 'week':
                    $query->whereDate('created_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $query->whereDate('created_at', '>=', now()->subMonth());
                    break;
            }
        }

        $jobListings = $query->with('poster')
            ->orderBy('is_premium', 'desc')  // Premium listings first
            ->orderBy('created_at', 'desc')  // Then by creation date
            ->paginate(10);

        // Get filter options for the view
        $jobTypes = ['full_time', 'part_time', 'contract', 'internship', 'remote'];
        $experienceLevels = ['entry', 'mid', 'senior', 'executive'];

        return view('jobs.index', compact('jobListings', 'jobTypes', 'experienceLevels'));
    }

    /**
     * Show the form for creating a new job listing.
     */
    public function create()
    {
        $this->authorize('create', JobListing::class);
        return view('jobs.create');
    }

    /**
     * Store a newly created job listing in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', JobListing::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'responsibilities' => 'required|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'job_type' => 'required|in:full_time,part_time,contract,internship,remote',
            'experience_level' => 'required|in:entry,mid,senior,executive',
            'location' => 'required|string|max:255',
            'application_deadline' => 'nullable|date|after:now',
            'job_status' => 'required|in:draft,published,closed,cancelled',
            'is_premium' => 'boolean',
        ]);

        $data = $request->all();
        $data['posted_by'] = Auth::id();

        // Set premium defaults if premium is requested but fee not specified
        if ($request->is_premium && !$request->premium_fee) {
            $data['is_premium'] = false; // Don't allow premium without payment
        }

        JobListing::create($data);

        return redirect()->route('jobs.index')->with('success', 'Job listing created successfully.');
    }

    /**
     * Display the specified job listing.
     */
    public function show(JobListing $jobListing)
    {
        // Only authorize if the job listing is not published (for unpublished jobs, only authorized users can view)
        if ($jobListing->job_status !== 'published') {
            $this->authorize('view', $jobListing);
        }

        return view('jobs.show', compact('jobListing'));
    }

    /**
     * Show the form for editing the specified job listing.
     */
    public function edit(JobListing $jobListing)
    {
        $this->authorize('update', $jobListing);
        return view('jobs.edit', compact('jobListing'));
    }

    /**
     * Update the specified job listing in storage.
     */
    public function update(Request $request, JobListing $jobListing)
    {
        $this->authorize('update', $jobListing);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'responsibilities' => 'required|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'job_type' => 'required|in:full_time,part_time,contract,internship,remote',
            'experience_level' => 'required|in:entry,mid,senior,executive',
            'location' => 'required|string|max:255',
            'application_deadline' => 'nullable|date|after:now',
            'job_status' => 'required|in:draft,published,closed,cancelled',
            'is_premium' => 'boolean',
        ]);

        $data = $request->except(['posted_by']); // Don't allow changing posted_by
        $jobListing->update($data);

        return redirect()->route('jobs.index')->with('success', 'Job listing updated successfully.');
    }

    /**
     * Remove the specified job listing from storage.
     */
    public function destroy(JobListing $jobListing)
    {
        $this->authorize('delete', $jobListing);

        $jobListing->delete();

        return redirect()->route('jobs.index')->with('success', 'Job listing deleted successfully.');
    }

    /**
     * Display job listings posted by the authenticated user.
     */
    public function myJobs()
    {
        $jobListings = JobListing::where('posted_by', Auth::id())->paginate(10);
        return view('jobs.my-jobs', compact('jobListings'));
    }

    /**
     * Show the application form for a job.
     */
    public function showApplicationForm(JobListing $jobListing)
    {
        // Check if user has already applied
        $existingApplication = JobApplication::where('job_id', $jobListing->id)
            ->where('applicant_id', Auth::id())
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this position.');
        }

        // Check if application deadline has passed
        if ($jobListing->application_deadline && $jobListing->application_deadline < now()) {
            return redirect()->back()->with('error', 'Application deadline has passed for this job.');
        }

        return view('jobs.apply', compact('jobListing'));
    }

    /**
     * Apply for a job (process the submission).
     */
    public function apply(JobListing $jobListing, Request $request)
    {
        // Check if user has already applied
        $existingApplication = JobApplication::where('job_id', $jobListing->id)
            ->where('applicant_id', Auth::id())
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this position.');
        }

        // Check if application deadline has passed
        if ($jobListing->application_deadline && $jobListing->application_deadline < now()) {
            return redirect()->back()->with('error', 'Application deadline has passed for this job.');
        }

        $request->validate([
            'cover_letter' => 'required|string',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
        ]);

        $resumePath = $request->file('resume')->store('resumes', 'public');

        JobApplication::create([
            'job_id' => $jobListing->id,
            'applicant_id' => Auth::id(),
            'cover_letter' => $request->cover_letter,
            'resume_path' => $resumePath,
            'applied_position' => $jobListing->title,
        ]);

        return redirect()->back()->with('success', 'Successfully applied for the job.');
    }

    /**
     * Display user's job applications.
     */
    public function myApplications()
    {
        $applications = JobApplication::where('applicant_id', Auth::id())
            ->with('job')
            ->paginate(10);

        return view('jobs.my-applications', compact('applications'));
    }

    /**
     * Manage job applications for a specific job listing.
     */
    public function manageApplications(JobListing $jobListing)
    {
        $this->authorize('update', $jobListing);

        $applications = JobApplication::where('job_id', $jobListing->id)
            ->with('applicant')
            ->paginate(10);

        return view('jobs.manage-applications', compact('jobListing', 'applications'));
    }

    /**
     * Display all job listings for admin management.
     */
    public function adminIndex()
    {
        $jobListings = JobListing::with(['poster', 'jobApplications'])->paginate(15);
        return view('admin.job-listings.index', compact('jobListings'));
    }

    /**
     * Display all job applications for admin management.
     */
    public function adminApplications()
    {
        $applications = JobApplication::with(['job', 'applicant'])->paginate(15);
        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Display the specified application for admin.
     */
    public function showApplication(JobApplication $application)
    {
        $this->authorize('view', $application);
        return view('admin.applications.show', compact('application'));
    }

    /**
     * Update the status of a job application.
     */
    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        // Ensure the user is authorized to update this application
        $jobListing = $application->job;
        $this->authorize('update', $jobListing);

        $request->validate([
            'application_status' => 'required|in:pending,reviewed,shortlisted,rejected,hired',
        ]);

        $application->update([
            'application_status' => $request->application_status,
        ]);

        return redirect()->back()->with('success', 'Application status updated successfully.');
    }
}
