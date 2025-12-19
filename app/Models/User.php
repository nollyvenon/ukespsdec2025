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
            'cv_search_credits' => 'integer',
            'cv_search_credits_used' => 'integer',
            'cv_search_subscription_active' => 'boolean',
            'cv_search_subscription_expiry' => 'datetime',
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
     * Get the job alerts created by the user.
     */
    public function jobAlerts()
    {
        return $this->hasMany(\App\Models\JobAlert::class);
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

    /**
     * Determine if the user can upload a CV.
     */
    public function canUploadCv(): bool
    {
        return $this->hasRole('student', 'job_seeker', 'user');
    }

    /**
     * Determine if the user can search CVs (recruiters only).
     */
    public function canSearchCvs(): bool
    {
        return $this->hasRole('recruiter', 'employer', 'admin');
    }

    /**
     * Determine if the user can download a CV.
     */
    public function canDownloadCv(CvUpload $cv): bool
    {
        // User can download their own CV
        if ($this->id === $cv->user_id) {
            return true;
        }

        // Recruiter can download if CV is public
        if ($this->hasRole('recruiter', 'employer') && $cv->is_public) {
            return true;
        }

        return $this->hasRole('admin');
    }

    /**
     * Determine if the user can pay to access a private CV.
     */
    public function canAccessPrivateCv(CvUpload $cv): bool
    {
        return $this->hasRole('recruiter', 'employer', 'admin');
    }


    /**
     * Get the user's active subscription.
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->where('end_date', '>', now());
    }

    /**
     * Get all subscriptions for the user.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Check if user has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    /**
     * Check if user's subscription is active and not expired.
     */
    public function isSubscribed(): bool
    {
        return $this->hasActiveSubscription();
    }

    /**
     * Get the CV search logs for this user.
     */
    public function cvSearchLogs()
    {
        return $this->hasMany(\App\Models\CvSearchLog::class, 'user_id');
    }

    /**
     * Check if the user has an active CV search subscription.
     */
    public function hasActiveCvSearchSubscription(): bool
    {
        return $this->cv_search_subscription_active &&
               (!$this->cv_search_subscription_expiry || $this->cv_search_subscription_expiry->isFuture());
    }

    /**
     * Check if the user has sufficient CV search credits.
     */
    public function hasCvSearchCredits(int $requiredCredits = 1): bool
    {
        return $this->cv_search_credits >= $requiredCredits;
    }

    /**
     * Check if the user can perform CV search (has credits or subscription).
     */
    public function canPerformCvSearch(): bool
    {
        return $this->hasActiveCvSearchSubscription() || $this->hasCvSearchCredits(1);
    }

    /**
     * Deduct CV search credits.
     */
    public function deductCvSearchCredits(int $amount = 1): void
    {
        if ($this->cv_search_credits >= $amount) {
            $this->decrement('cv_search_credits', $amount);
            $this->increment('cv_search_credits_used', $amount);
        }
    }


    /**
     * Add CV search credits.
     */
    public function addCvSearchCredits(int $amount): void
    {
        $this->increment('cv_search_credits', $amount);
    }

    /**
     * Check if user can perform CV search (either has credits or subscription).
     */
    public function canPerformCvSearch(): bool
    {
        return $this->hasActiveCvSearchSubscription() || $this->hasCvSearchCredits(1);
    }
}
