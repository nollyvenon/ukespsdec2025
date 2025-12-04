<?php

namespace App\Http\Controllers;

use App\Models\JobAlert;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobAlertController extends Controller
{
    /**
     * Display a listing of the job alerts for the authenticated user.
     */
    public function index()
    {
        $jobAlerts = Auth::user()->jobAlerts()->latest()->paginate(10);
        return view('job-alerts.index', compact('jobAlerts'));
    }

    /**
     * Show the form for creating a new job alert.
     */
    public function create()
    {
        return view('job-alerts.create');
    }

    /**
     * Store a newly created job alert in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'criteria' => 'required|array',
            'frequency' => 'required|in:immediate,daily,weekly',
            'description' => 'nullable|string',
        ]);

        $criteria = [
            'keywords' => $request->input('criteria.keywords', []),
            'locations' => $request->input('criteria.locations', []),
            'job_types' => $request->input('criteria.job_types', []),
            'salary_min' => $request->input('criteria.salary_min'),
            'salary_max' => $request->input('criteria.salary_max'),
            'experience_level' => $request->input('criteria.experience_level'),
        ];

        Auth::user()->jobAlerts()->create([
            'name' => $request->name,
            'criteria' => $criteria,
            'frequency' => $request->frequency,
            'description' => $request->description,
        ]);

        return redirect()->route('job-alerts.index')->with('success', 'Job alert created successfully!');
    }

    /**
     * Display the specified job alert.
     */
    public function show(JobAlert $jobAlert)
    {
        $this->authorize('view', $jobAlert);

        // Find matching jobs based on criteria
        $query = JobListing::where('job_status', 'published');

        $criteria = $jobAlert->criteria;

        if (!empty($criteria['keywords'])) {
            foreach ($criteria['keywords'] as $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('title', 'LIKE', "%{$keyword}%")
                      ->orWhere('description', 'LIKE', "%{$keyword}%")
                      ->orWhere('requirements', 'LIKE', "%{$keyword}%");
                });
            }
        }

        if (!empty($criteria['locations'])) {
            $query->whereIn('location', $criteria['locations']);
        }

        if (!empty($criteria['job_types'])) {
            $query->whereIn('job_type', $criteria['job_types']);
        }

        if (!empty($criteria['salary_min'])) {
            $query->where('salary_min', '>=', $criteria['salary_min']);
        }

        if (!empty($criteria['salary_max'])) {
            $query->where('salary_max', '<=', $criteria['salary_max']);
        }

        if (!empty($criteria['experience_level'])) {
            $query->where('experience_level', $criteria['experience_level']);
        }

        $matchingJobs = $query->with('poster')->take(10)->get();

        return view('job-alerts.show', compact('jobAlert', 'matchingJobs'));
    }

    /**
     * Show the form for editing the specified job alert.
     */
    public function edit(JobAlert $jobAlert)
    {
        $this->authorize('update', $jobAlert);
        return view('job-alerts.edit', compact('jobAlert'));
    }

    /**
     * Update the specified job alert in storage.
     */
    public function update(Request $request, JobAlert $jobAlert)
    {
        $this->authorize('update', $jobAlert);

        $request->validate([
            'name' => 'required|string|max:255',
            'criteria' => 'required|array',
            'frequency' => 'required|in:immediate,daily,weekly',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $criteria = [
            'keywords' => $request->input('criteria.keywords', []),
            'locations' => $request->input('criteria.locations', []),
            'job_types' => $request->input('criteria.job_types', []),
            'salary_min' => $request->input('criteria.salary_min'),
            'salary_max' => $request->input('criteria.salary_max'),
            'experience_level' => $request->input('criteria.experience_level'),
        ];

        $jobAlert->update([
            'name' => $request->name,
            'criteria' => $criteria,
            'frequency' => $request->frequency,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('job-alerts.index')->with('success', 'Job alert updated successfully!');
    }

    /**
     * Remove the specified job alert from storage.
     */
    public function destroy(JobAlert $jobAlert)
    {
        $this->authorize('delete', $jobAlert);

        $jobAlert->delete();

        return redirect()->route('job-alerts.index')->with('success', 'Job alert deleted successfully.');
    }

    /**
     * Toggle the active status of the job alert.
     */
    public function toggleStatus(JobAlert $jobAlert)
    {
        $this->authorize('update', $jobAlert);

        $jobAlert->update(['is_active' => !$jobAlert->is_active]);

        return redirect()->back()->with('success', 'Job alert status updated successfully.');
    }

    /**
     * Find latest jobs matching the alert criteria.
     */
    public function findMatchingJobs(JobAlert $jobAlert)
    {
        $this->authorize('view', $jobAlert);

        // Find matching jobs based on criteria
        $query = JobListing::where('job_status', 'published')
            ->where('created_at', '>', $jobAlert->last_run_at ?? now()->subDays(30)); // Only check recent jobs

        $criteria = $jobAlert->criteria;

        if (!empty($criteria['keywords'])) {
            foreach ($criteria['keywords'] as $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('title', 'LIKE', "%{$keyword}%")
                      ->orWhere('description', 'LIKE', "%{$keyword}%")
                      ->orWhere('requirements', 'LIKE', "%{$keyword}%");
                });
            }
        }

        if (!empty($criteria['locations'])) {
            $query->whereIn('location', $criteria['locations']);
        }

        if (!empty($criteria['job_types'])) {
            $query->whereIn('job_type', $criteria['job_types']);
        }

        if (!empty($criteria['salary_min'])) {
            $query->where('salary_min', '>=', $criteria['salary_min']);
        }

        if (!empty($criteria['salary_max'])) {
            $query->where('salary_max', '<=', $criteria['salary_max']);
        }

        if (!empty($criteria['experience_level'])) {
            $query->where('experience_level', $criteria['experience_level']);
        }

        $matchingJobs = $query->with('poster')->orderBy('created_at', 'desc')->take(20)->get();

        // Update the last run timestamp
        $jobAlert->update(['last_run_at' => now()]);

        return view('job-alerts.matching-jobs', compact('jobAlert', 'matchingJobs'));
    }
}
