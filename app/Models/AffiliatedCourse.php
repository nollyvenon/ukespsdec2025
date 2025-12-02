<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AffiliatedCourse extends Model
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
        'university_name',
        'university_logo',
        'course_image',
        'level',
        'duration',
        'start_date',
        'end_date',
        'prerequisites',
        'syllabus',
        'fee',
        'skills_covered',
        'career_outcomes',
        'status',
        'max_enrollment',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'fee' => 'decimal:2',
        'skills_covered' => 'array',
        'career_outcomes' => 'array',
    ];

    /**
     * Get the enrollments for this affiliated course.
     */
    public function affiliatedCourseEnrollments()
    {
        return $this->hasMany(AffiliatedCourseEnrollment::class);
    }

    /**
     * Get the university offering this affiliated course.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the country of the university offering this course.
     */
    public function getCountryAttribute()
    {
        return $this->university->country;
    }
}
