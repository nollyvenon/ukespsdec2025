<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobAlert extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'criteria',
        'description',
        'frequency',
        'is_active',
        'last_run_at',
        'last_notification_at',
        'matched_jobs_count',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'criteria' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'frequency' => 'string',
        'last_run_at' => 'datetime',
        'last_notification_at' => 'datetime',
        'matched_jobs_count' => 'integer',
    ];

    /**
     * Get the user that owns the job alert.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the alert is due to run based on frequency.
     */
    public function isDueToRun(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->last_run_at) {
            return true; // Run immediately if never run before
        }

        switch ($this->frequency) {
            case 'immediate':
                return true; // Always run for immediate alerts
            case 'daily':
                return $this->last_run_at->addDay()->isPast();
            case 'weekly':
                return $this->last_run_at->addWeek()->isPast();
            default:
                return false;
        }
    }
}
