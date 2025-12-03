<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobListing extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'requirements',
        'responsibilities',
        'salary_min',
        'salary_max',
        'job_type',
        'experience_level',
        'location',
        'posted_by',
        'application_deadline',
        'job_status',
        'is_premium',
        'premium_fee',
        'premium_expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'application_deadline' => 'date',
        'is_premium' => 'boolean',
        'premium_fee' => 'decimal:2',
        'premium_expires_at' => 'datetime',
    ];

    /**
     * Get the user who posted the job listing.
     */
    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * Get the applications for this job listing.
     */
    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }

    /**
     * Scope a query to only include premium job listings.
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    /**
     * Scope a query to only include non-premium job listings.
     */
    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    /**
     * Check if the job listing is currently premium and valid.
     */
    public function isCurrentlyPremium(): bool
    {
        return $this->is_premium && (!$this->premium_expires_at || $this->premium_expires_at->isFuture());
    }
}
