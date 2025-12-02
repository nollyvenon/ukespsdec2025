<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AffiliatedCourseEnrollment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'affiliated_course_id',
        'user_id',
        'enrollment_status',
        'completion_percentage',
        'grade',
        'certificate_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enrollment_date' => 'datetime',
    ];

    /**
     * Get the affiliated course for this enrollment.
     */
    public function affiliatedCourse()
    {
        return $this->belongsTo(AffiliatedCourse::class);
    }

    /**
     * Get the user who enrolled in the affiliated course.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
