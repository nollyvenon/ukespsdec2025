<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the profile associated with the user.
     */
    public function profile()
    {
        return $this->hasOne(\App\Models\UserProfile::class);
    }

    /**
     * Get the events created by the user.
     */
    public function createdEvents()
    {
        return $this->hasMany(\App\Models\Event::class, 'created_by');
    }

    /**
     * Get the events the user is registered for.
     */
    public function eventRegistrations()
    {
        return $this->hasMany(\App\Models\EventRegistration::class);
    }

    /**
     * Get the courses the user teaches.
     */
    public function taughtCourses()
    {
        return $this->hasMany(\App\Models\Course::class, 'instructor_id');
    }

    /**
     * Get the courses the user is enrolled in.
     */
    public function courseEnrollments()
    {
        return $this->hasMany(\App\Models\CourseEnrollment::class, 'student_id');
    }

    /**
     * Get the job listings posted by the user.
     */
    public function postedJobs()
    {
        return $this->hasMany(\App\Models\JobListing::class, 'posted_by');
    }

    /**
     * Get the job applications submitted by the user.
     */
    public function jobApplications()
    {
        return $this->hasMany(\App\Models\JobApplication::class, 'applicant_id');
    }

    /**
     * Get the affiliated course enrollments for the user.
     */
    public function affiliatedCourseEnrollments()
    {
        return $this->hasMany(\App\Models\AffiliatedCourseEnrollment::class, 'user_id');
    }

    /**
     * Get the contact messages sent by the user.
     */
    public function contactMessages()
    {
        return $this->hasMany(\App\Models\ContactMessage::class);
    }

    /**
     * Get the assessments created by the user.
     */
    public function createdAssessments()
    {
        return $this->hasMany(\App\Models\Assessment::class, 'created_by');
    }

    /**
     * Get the assessment attempts by the user.
     */
    public function assessmentAttempts()
    {
        return $this->hasMany(\App\Models\AssessmentAttempt::class);
    }

    /**
     * Get the certificates earned by the user.
     */
    public function certificates()
    {
        return $this->hasMany(\App\Models\Certificate::class);
    }

    /**
     * Get the transactions made by the user.
     */
    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }

    /**
     * Get the ad campaigns created by the user.
     */
    public function adCampaigns()
    {
        return $this->hasMany(\App\Models\AdCampaign::class);
    }

    /**
     * Get the admin status attribute.
     */
    public function getIsAdminAttribute()
    {
        return $this->role === 'admin' || ($this->attributes['is_admin'] ?? false);
    }

    /**
     * Check if user is a student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is a job seeker.
     */
    public function isJobSeeker(): bool
    {
        return $this->role === 'job_seeker';
    }

    /**
     * Check if user is a recruiter.
     */
    public function isRecruiter(): bool
    {
        return $this->role === 'recruiter';
    }

    /**
     * Check if user is a university manager.
     */
    public function isUniversityManager(): bool
    {
        return $this->role === 'university_manager';
    }

    /**
     * Check if user is an event hoster.
     */
    public function isEventHoster(): bool
    {
        return $this->role === 'event_hoster';
    }

    /**
     * Check if the user has any of the specified roles.
     */
    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles);
    }
}
