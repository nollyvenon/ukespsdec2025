<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
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
        'duration',
        'level',
        'instructor_id',
        'start_date',
        'end_date',
        'course_image',
        'course_status',
        'max_enrollment',
        'prerequisites',
        'syllabus',
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
        'start_date' => 'date',
        'end_date' => 'date',
        'is_premium' => 'boolean',
        'premium_fee' => 'decimal:2',
        'premium_expires_at' => 'datetime',
    ];

    /**
     * Get the instructor who teaches this course.
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the enrollments for this course.
     */
    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }

    /**
     * Scope a query to only include premium courses.
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    /**
     * Scope a query to only include non-premium courses.
     */
    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    /**
     * Check if the course is currently premium and valid.
     */
    public function isCurrentlyPremium(): bool
    {
        return $this->is_premium && (!$this->premium_expires_at || $this->premium_expires_at->isFuture());
    }
}
