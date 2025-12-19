<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_id',
        'applicant_id',
        'cover_letter',
        'resume_path',
        'application_status',
        'application_stage',
        'application_notes',
        'application_timeline',
        'is_notified',
        'last_notification_sent',
        'application_stage',
        'applied_position',
        'reviewed_at',
        'interview_scheduled_at',
        'decision_made_at',
        'status_updated_at',
        'next_action_date',
        'follow_up_notes',
        'interview_details',
        'offer_details',
        'internal_notes',
        'is_active',
        'last_reminder_sent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'application_date' => 'datetime',
        'reviewed_at' => 'datetime',
        'interview_scheduled_at' => 'datetime',
        'decision_made_at' => 'datetime',
        'application_timeline' => 'array',
        'is_notified' => 'boolean',
        'last_notification_sent' => 'datetime',
        'application_stage' => 'string',
        'status_updated_at' => 'datetime',
        'next_action_date' => 'date',
        'follow_up_notes' => 'string',
        'interview_details' => 'array',
        'offer_details' => 'array',
        'internal_notes' => 'string',
        'is_active' => 'boolean',
        'last_reminder_sent' => 'datetime',
    ];

    /**
     * Get the job listing for this application.
     */
    public function job()
    {
        return $this->belongsTo(JobListing::class);
    }

    /**
     * Get the applicant who submitted this application.
     */
    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }
}
