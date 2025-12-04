<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Course;
use App\Models\JobListing;
use App\Models\Ad;
use App\Models\CvUpload;
use App\Policies\EventPolicy;
use App\Policies\CoursePolicy;
use App\Policies\JobListingPolicy;
use App\Policies\AdPolicy;
use App\Policies\CvUploadPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Event::class => EventPolicy::class,
        Course::class => CoursePolicy::class,
        JobListing::class => JobListingPolicy::class,
        Ad::class => AdPolicy::class,
        CvUpload::class => CvUploadPolicy::class,
        User::class => UserRolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
