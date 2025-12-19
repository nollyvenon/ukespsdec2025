<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationTrackingController extends Controller
{
    /**
     * Display the application dashboard for the current user
     */
    public function dashboard()
    {
        $applications = JobApplication::where('applicant_id', Auth::id())
                                      ->with(['job:id,title,company,location,job_type,salary_min,salary_max'])
                                      ->orderBy('created_at', 'desc')
                                      ->paginate(10);

        // Get application statistics for the dashboard
        $stats = [
            'total' => $applications->total(),
            'submitted' => JobApplication::where('applicant_id', Auth::id())
                                         ->where('application_status', 'submitted')
                                         ->count(),
            'reviewed' => JobApplication::where('applicant_id', Auth::id())
                                         ->where('application_stage', 'reviewed')
                                         ->count(),
            'shortlisted' => JobApplication::where('applicant_id', Auth::id())
                                            ->where('application_stage', 'shortlisted')
                                            ->count(),
            'interview' => JobApplication::where('applicant_id', Auth::id())
                                          ->where('application_stage', 'interview')
                                          ->count(),
            'rejected' => JobApplication::where('applicant_id', Auth::id())
                                         ->where('application_stage', 'rejected')
                                         ->count(),
            'offered' => JobApplication::where('applicant_id', Auth::id())
                                        ->where('application_stage', 'offered')
                                        ->count(),
            'accepted' => JobApplication::where('applicant_id', Auth::id())
                                         ->where('application_stage', 'accepted')
                                         ->count(),
        ];

        return view('application-dashboard.index', compact('applications', 'stats'));
    }

    /**
     * Get application statistics for the dashboard
     */
    public function getStatistics()
    {
        $stats = [
            'total_applications' => JobApplication::where('applicant_id', Auth::id())->count(),
            'active_applications' => JobApplication::where('applicant_id', Auth::id())
                                                      ->where('is_active', true)
                                                      ->count(),
            'awaiting_response' => JobApplication::where('applicant_id', Auth::id())
                                                   ->where('application_stage', 'submitted')
                                                   ->count(),
            'in_interview_process' => JobApplication::where('applicant_id', Auth::id())
                                                     ->whereIn('application_stage', ['reviewed', 'shortlisted', 'interview'])
                                                     ->count(),
            'successful' => JobApplication::where('applicant_id', Auth::id())
                                           ->whereIn('application_stage', ['offered', 'accepted'])
                                           ->count(),
            'unsuccessful' => JobApplication::where('applicant_id', Auth::id())
                                             ->whereIn('application_stage', ['rejected', 'withdrawn'])
                                             ->count(),
        ];

        $timelineData = JobApplication::where('applicant_id', Auth::id())
                                       ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                       ->groupBy('date')
                                       ->orderBy('date', 'desc')
                                       ->limit(30)
                                       ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'timeline' => $timelineData,
            ],
        ]);
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus(Request $request, $id)
    {
        $application = JobApplication::where('applicant_id', Auth::id())
                                     ->findOrFail($id);

        $request->validate([
            'application_stage' => 'required|in:submitted,reviewed,shortlisted,interview,rejected,offered,accepted,withdrawn',
            'status_updated_at' => 'nullable|date',
            'follow_up_notes' => 'nullable|string',
        ]);

        $oldStage = $application->application_stage;
        $application->update([
            'application_stage' => $request->application_stage,
            'status_updated_at' => $request->status_updated_at ?? now(),
            'follow_up_notes' => $request->follow_up_notes,
        ]);

        // Add to application timeline
        $timeline = $application->application_timeline ?: [];
        $timeline[] = [
            'event' => 'stage_change',
            'from_stage' => $oldStage,
            'to_stage' => $request->application_stage,
            'updated_at' => now()->toISOString(),
            'notes' => $request->follow_up_notes,
            'updated_by' => Auth::id(),
        ];

        $application->update([
            'application_timeline' => $timeline,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully',
            'data' => [
                'application' => $application->load('job'),
            ],
        ]);
    }

    /**
     * Add follow-up note to application
     */
    public function addFollowUpNote(Request $request, $id)
    {
        $application = JobApplication::where('applicant_id', Auth::id())
                                     ->findOrFail($id);

        $request->validate([
            'follow_up_notes' => 'required|string|max:1000',
        ]);

        // Add to application timeline
        $timeline = $application->application_timeline ?: [];
        $timeline[] = [
            'event' => 'follow_up_note',
            'note' => $request->follow_up_notes,
            'added_at' => now()->toISOString(),
            'added_by' => Auth::id(),
        ];

        $application->update([
            'follow_up_notes' => $request->follow_up_notes,
            'application_timeline' => $timeline,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Follow-up note added successfully',
            'data' => [
                'application' => $application->load('job'),
            ],
        ]);
    }

    /**
     * Schedule next action for an application
     */
    public function scheduleAction(Request $request, $id)
    {
        $application = JobApplication::where('applicant_id', Auth::id())
                                     ->findOrFail($id);

        $request->validate([
            'next_action_date' => 'required|date|after_or_equal:today',
            'action_type' => 'required|in:interview,call,meeting,follow_up,response,evaluation',
            'action_description' => 'nullable|string|max:500',
        ]);

        $application->update([
            'next_action_date' => $request->next_action_date,
            'application_timeline' => array_merge($application->application_timeline ?: [], [[
                'event' => 'scheduled_action',
                'action_type' => $request->action_type,
                'description' => $request->action_description,
                'scheduled_for' => $request->next_action_date,
                'scheduled_at' => now()->toISOString(),
            ]])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Action scheduled successfully',
            'data' => [
                'application' => $application->load('job'),
            ],
        ]);
    }

    /**
     * Get upcoming actions for applications
     */
    public function getUpcomingActions(Request $request)
    {
        $days = $request->get('days', 7); // Default to next 7 days

        $upcomingActions = JobApplication::where('applicant_id', Auth::id())
                                          ->whereDate('next_action_date', '<=', now()->addDays($days))
                                          ->whereNotNull('next_action_date')
                                          ->with(['job:id,title,company'])
                                          ->orderBy('next_action_date')
                                          ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'upcoming_actions' => $upcomingActions,
                'days_ahead' => $days,
            ],
        ]);
    }

    /**
     * Add interview details to an application
     */
    public function addInterviewDetails(Request $request, $id)
    {
        $application = JobApplication::where('applicant_id', Auth::id())
                                     ->findOrFail($id);

        $request->validate([
            'interview_date' => 'required|date',
            'interview_type' => 'required|in:phone,video,in_person,panel,assessment',
            'interview_notes' => 'nullable|string',
            'interview_outcome' => 'nullable|in:scheduled,completed,passed,failed,waiting',
        ]);

        // Update the application with interview details
        $interviewDetails = [
            'date' => $request->interview_date,
            'type' => $request->interview_type,
            'notes' => $request->interview_notes,
            'outcome' => $request->interview_outcome ?? 'scheduled',
            'recorded_at' => now()->toISOString(),
        ];

        $application->update([
            'interview_details' => array_merge($application->interview_details ?: [], [$interviewDetails]),
            'application_stage' => 'interview',
            'application_timeline' => array_merge($application->application_timeline ?: [], [[
                'event' => 'interview_scheduled',
                'details' => $interviewDetails,
                'recorded_at' => now()->toISOString(),
            ]])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Interview details added successfully',
            'data' => [
                'application' => $application->load('job'),
            ],
        ]);
    }

    /**
     * Add offer details to an application
     */
    public function addOfferDetails(Request $request, $id)
    {
        $application = JobApplication::where('applicant_id', Auth::id())
                                     ->findOrFail($id);

        $request->validate([
            'offer_date' => 'required|date',
            'salary_offered' => 'nullable|numeric|min:0',
            'benefits' => 'nullable|string',
            'start_date' => 'nullable|date',
            'offer_status' => 'nullable|in:received,accepted,declined,pending,negotiating',
        ]);

        // Update the application with offer details
        $offerDetails = [
            'date' => $request->offer_date,
            'salary' => $request->salary_offered,
            'benefits' => $request->benefits,
            'start_date' => $request->start_date,
            'status' => $request->offer_status ?? 'pending',
            'recorded_at' => now()->toISOString(),
        ];

        $application->update([
            'offer_details' => array_merge($application->offer_details ?: [], [$offerDetails]),
            'application_stage' => $request->offer_status === 'accepted' ? 'accepted' : 'offered',
            'application_timeline' => array_merge($application->application_timeline ?: [], [[
                'event' => 'offer_received',
                'details' => $offerDetails,
                'recorded_at' => now()->toISOString(),
            ]])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Offer details added successfully',
            'data' => [
                'application' => $application->load('job'),
            ],
        ]);
    }

    /**
     * Mark an application as inactive
     */
    public function markInactive($id)
    {
        $application = JobApplication::where('applicant_id', Auth::id())
                                     ->findOrFail($id);

        $application->update([
            'is_active' => false,
            'application_timeline' => array_merge($application->application_timeline ?: [], [[
                'event' => 'marked_inactive',
                'reason' => 'user_request',
                'action_taken_at' => now()->toISOString(),
            ]])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application marked as inactive',
            'data' => [
                'application' => $application->load('job'),
            ],
        ]);
    }

    /**
     * Mark an application as active again
     */
    public function markActive($id)
    {
        $application = JobApplication::where('applicant_id', Auth::id())
                                     ->findOrFail($id);

        $application->update([
            'is_active' => true,
            'application_timeline' => array_merge($application->application_timeline ?: [], [[
                'event' => 'marked_active',
                'reason' => 'user_request',
                'action_taken_at' => now()->toISOString(),
            ]])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application marked as active',
            'data' => [
                'application' => $application->load('job'),
            ],
        ]);
    }

    /**
     * Get application timeline
     */
    public function getTimeline($id)
    {
        $application = JobApplication::where('applicant_id', Auth::id())
                                     ->findOrFail($id);

        $timeline = $application->application_timeline ?: [];

        // Sort timeline by date (most recent first)
        usort($timeline, function ($a, $b) {
            $dateA = isset($a['updated_at']) ? $a['updated_at'] :
                    (isset($a['added_at']) ? $a['added_at'] :
                    (isset($a['scheduled_at']) ? $a['scheduled_at'] :
                    (isset($a['recorded_at']) ? $a['recorded_at'] : now()->toISOString())));

            $dateB = isset($b['updated_at']) ? $b['updated_at'] :
                    (isset($b['added_at']) ? $b['added_at'] :
                    (isset($b['scheduled_at']) ? $b['scheduled_at'] :
                    (isset($b['recorded_at']) ? $b['recorded_at'] : now()->toISOString())));

            return strcmp($dateB, $dateA);
        });

        return response()->json([
            'success' => true,
            'data' => [
                'application' => $application->load('job'),
                'timeline' => $timeline,
            ],
        ]);
    }

    /**
     * Send reminder to employer about the application
     */
    public function sendReminder($id)
    {
        $application = JobApplication::where('applicant_id', Auth::id())
                                     ->findOrFail($id);

        // Check if enough time has passed since the last reminder
        if ($application->last_reminder_sent &&
            $application->last_reminder_sent->diffInDays(now()) < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Please wait at least 3 days between reminders',
            ], 422);
        }

        // Update last reminder sent timestamp
        $application->update([
            'last_reminder_sent' => now(),
            'application_timeline' => array_merge($application->application_timeline ?: [], [[
                'event' => 'reminder_sent',
                'sent_at' => now()->toISOString(),
            ]])
        ]);

        // In a real application, you'd send an email here
        // For now, just simulate the action

        return response()->json([
            'success' => true,
            'message' => 'Reminder sent successfully',
            'data' => [
                'application' => $application->load('job'),
            ],
        ]);
    }

    /**
     * Export application tracking data as CSV
     */
    public function exportCsv()
    {
        $applications = JobApplication::where('applicant_id', Auth::id())
                                      ->with(['job:id,title,company,location,job_type,salary_min,salary_max'])
                                      ->orderBy('created_at', 'desc')
                                      ->get();

        $csvData = [];
        $csvData[] = [
            'Job Title',
            'Company',
            'Location',
            'Application Stage',
            'Applied Date',
            'Status Updated',
            'Next Action Date',
            'Current Status',
        ];

        foreach ($applications as $application) {
            $csvData[] = [
                $application->job->title ?? 'N/A',
                $application->job->company ?? 'N/A',
                $application->job->location ?? 'N/A',
                $application->application_stage ?? 'Submitted',
                $application->created_at->format('Y-m-d'),
                $application->status_updated_at ? $application->status_updated_at->format('Y-m-d') : 'N/A',
                $application->next_action_date ? $application->next_action_date->format('Y-m-d') : 'N/A',
                $application->is_active ? 'Active' : 'Inactive',
            ];
        }

        $csv = '';
        foreach ($csvData as $row) {
            $csv .= '"' . implode('","', $row) . '"' . "\n";
        }

        $filename = 'job_applications_' . date('Y-m-d') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }
}
