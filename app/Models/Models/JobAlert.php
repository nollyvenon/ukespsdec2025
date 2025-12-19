<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobAlert extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_alerts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'alert_name',
        'search_criteria',
        'keywords',
        'job_title_filter',
        'location_filter',
        'job_types',
        'min_salary',
        'max_salary',
        'industries',
        'experience_levels',
        'is_active',
        'frequency',
        'last_run',
        'next_run',
        'jobs_found_count',
        'recent_matches',
        'email_notification_enabled',
        'push_notification_enabled',
        'notification_preferences',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'search_criteria' => 'array',
        'keywords' => 'array',
        'job_types' => 'array',
        'min_salary' => 'decimal:2',
        'max_salary' => 'decimal:2',
        'industries' => 'array',
        'experience_levels' => 'array',
        'is_active' => 'boolean',
        'last_run' => 'datetime',
        'next_run' => 'datetime',
        'jobs_found_count' => 'integer',
        'recent_matches' => 'array',
        'email_notification_enabled' => 'boolean',
        'push_notification_enabled' => 'boolean',
        'notification_preferences' => 'array',
    ];

    /**
     * Get the user that owns the job alert.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only active job alerts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by frequency.
     */
    public function scopeByFrequency($query, $frequency)
    {
        return $query->where('frequency', $frequency);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if an alert is due for execution based on frequency
     */
    public function isDueForExecution(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->last_run) {
            return true; // Run if never executed before
        }

        switch ($this->frequency) {
            case 'daily':
                return $this->last_run->addDay()->isPast();
            case 'weekly':
                return $this->last_run->addWeek()->isPast();
            case 'monthly':
                return $this->last_run->addMonth()->isPast();
            default:
                return $this->last_run->addDay()->isPast(); // Default to daily
        }
    }

    /**
     * Update the last run time
     */
    public function updateLastRun(): void
    {
        $this->update([
            'last_run' => now(),
            'next_run' => $this->calculateNextRun(),
        ]);
    }

    /**
     * Calculate the next run time based on frequency
     */
    private function calculateNextRun(): \DateTime
    {
        $now = now();

        switch ($this->frequency) {
            case 'daily':
                return $now->addDay();
            case 'weekly':
                return $now->addWeek();
            case 'monthly':
                return $now->addMonth();
            default:
                return $now->addDay(); // Default to daily
        }
    }
}
