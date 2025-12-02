<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\JobListingsController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AffiliatedCoursesController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\MatchingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Test route for debugging
Route::get('/test-create', function () {
    return 'Test route works!';
})->middleware(['auth'])->name('test.create');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public routes
Route::get('/events', [EventsController::class, 'index'])->name('events.index');

// Events routes - specific routes first, then generic
Route::middleware(['auth'])->group(function () {
    Route::post('/events', [EventsController::class, 'store'])->name('events.store');
    Route::get('/events/create', [EventsController::class, 'create'])->name('events.create');
    Route::get('/events/{event}/edit', [EventsController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventsController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventsController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/{event}/register', [EventsController::class, 'register'])->name('events.register');
});
// Public show route goes last to avoid conflict
Route::get('/events/{event}', [EventsController::class, 'show'])->name('events.show');

// For courses and jobs, we define specific routes first, then the generic show route to avoid conflicts
Route::get('/courses', [CoursesController::class, 'index'])->name('courses.index');
// Authenticated course routes (defined first to avoid conflict with /courses/{course})
Route::middleware(['auth'])->group(function () {
    Route::post('/courses', [CoursesController::class, 'store'])->name('courses.store');
    Route::get('/courses/create', [CoursesController::class, 'create'])->name('courses.create');
    Route::get('/courses/{course}/edit', [CoursesController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CoursesController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CoursesController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{course}/enroll', [CoursesController::class, 'enroll'])->name('courses.enroll');
});
// Public show route goes last to avoid conflict
Route::get('/courses/{course}', [CoursesController::class, 'show'])->name('courses.show');

// Jobs routes - specific routes first, then generic
Route::get('/jobs', [JobListingsController::class, 'index'])->name('jobs.index');
// Authenticated job routes (defined first to avoid conflict with /jobs/{jobListing})
Route::middleware(['auth'])->group(function () {
    Route::post('/jobs', [JobListingsController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/create', [JobListingsController::class, 'create'])->name('jobs.create');
    Route::get('/jobs/{jobListing}/edit', [JobListingsController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{jobListing}', [JobListingsController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{jobListing}', [JobListingsController::class, 'destroy'])->name('jobs.destroy');
    Route::post('/jobs/{jobListing}/apply', [JobListingsController::class, 'apply'])->name('jobs.apply');
});
// Public show route goes last to avoid conflict
Route::get('/jobs/{jobListing}', [JobListingsController::class, 'show'])->name('jobs.show');

// Affiliated courses routes - specific routes first, then generic
Route::get('/affiliated-courses', [AffiliatedCoursesController::class, 'index'])->name('affiliated-courses.index');
Route::middleware(['auth'])->group(function () {
    Route::post('/affiliated-courses', [AffiliatedCoursesController::class, 'store'])->name('affiliated-courses.store');
    Route::get('/affiliated-courses/create', [AffiliatedCoursesController::class, 'create'])->name('affiliated-courses.create');
    Route::get('/affiliated-courses/{affiliatedCourse}/edit', [AffiliatedCoursesController::class, 'edit'])->name('affiliated-courses.edit');
    Route::put('/affiliated-courses/{affiliatedCourse}', [AffiliatedCoursesController::class, 'update'])->name('affiliated-courses.update');
    Route::delete('/affiliated-courses/{affiliatedCourse}', [AffiliatedCoursesController::class, 'destroy'])->name('affiliated-courses.destroy');
    Route::post('/affiliated-courses/{affiliatedCourse}/enroll', [AffiliatedCoursesController::class, 'enroll'])->name('affiliated-courses.enroll');
});
Route::get('/affiliated-courses/{affiliatedCourse}', [AffiliatedCoursesController::class, 'show'])->name('affiliated-courses.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.send');
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
Route::get('/matching', [App\Http\Controllers\MatchingController::class, 'index'])->name('matching.index');
Route::get('/api/matching', [App\Http\Controllers\MatchingController::class, 'getMatches'])->name('matching.get');
Route::get('/api/ads/{position}/{page?}', [AdController::class, 'getAdsForPosition'])->name('ads.get');
Route::get('/portal/events', [DashboardController::class, 'eventsPortal'])->name('portal.events');
Route::get('/portal/courses', [DashboardController::class, 'coursesPortal'])->name('portal.courses');
Route::get('/portal/jobs', [DashboardController::class, 'jobsPortal'])->name('portal.jobs');

// Authenticated routes
//Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    // Events management routes - keeping index and show public but others restricted
    Route::get('/my-events', [EventsController::class, 'myEvents'])->name('events.my');
    Route::get('/my-registrations', [EventsController::class, 'myRegistrations'])->name('events.registrations');

    Route::get('/my-courses', [CoursesController::class, 'myCourses'])->name('courses.my');
    Route::get('/my-enrollments', [CoursesController::class, 'myEnrollments'])->name('courses.enrollments');
    Route::middleware(['auth'])->group(function () {
        Route::post('/courses/{course}/progress', [CoursesController::class, 'updateProgress'])->name('courses.progress');
    });
    Route::get('/courses-portal', [CoursesController::class, 'portalLanding'])->name('courses.portal-landing');

    Route::get('/my-jobs', [JobListingsController::class, 'myJobs'])->name('jobs.my');
    Route::get('/my-applications', [JobListingsController::class, 'myApplications'])->name('jobs.applications');
    Route::get('/jobs/{jobListing}/manage-applications', [JobListingsController::class, 'manageApplications'])->name('jobs.manage-applications');
    Route::patch('/job-applications/{application}', [JobListingsController::class, 'updateApplicationStatus'])->name('job-applications.update-status');

    Route::get('/my-affiliated-enrollments', [AffiliatedCoursesController::class, 'myEnrollments'])->name('affiliated-courses.my-enrollments');

    // Blog routes
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
    Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');

    // Support routes
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::get('/support/create', [SupportController::class, 'create'])->name('support.create');
    Route::post('/support', [SupportController::class, 'store'])->name('support.store');
    Route::get('/support/{id}', [SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{id}/reply', [SupportController::class, 'addReply'])->name('support.add-reply');

    // Matching routes
    Route::get('/matching', [App\Http\Controllers\MatchingController::class, 'index'])->name('matching.index');
    Route::get('/api/matching', [App\Http\Controllers\MatchingController::class, 'getMatches'])->name('matching.get');

    // Page routes
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/services', [PageController::class, 'services'])->name('services');

    // Contact routes
    Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.send');

    // FAQ routes
    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');

    // Ad routes
    Route::get('/api/ads/{position}/{page?}', [AdController::class, 'getAdsForPosition'])->name('ads.get');
    Route::get('/ads', [AdController::class, 'index'])->name('ads.index');

    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
        Route::get('/events', [App\Http\Controllers\AdminController::class, 'events'])->name('events');
        Route::get('/courses', [App\Http\Controllers\AdminController::class, 'courses'])->name('courses');
        Route::get('/jobs', [App\Http\Controllers\AdminController::class, 'jobs'])->name('jobs');

        // Settings route
        Route::get('/settings', [App\Http\Controllers\AdminController::class, 'generalSettings'])->name('settings');
        Route::put('/settings', [App\Http\Controllers\AdminController::class, 'updateGeneralSettings'])->name('settings.update');

        // Admin routes for affiliated courses
        Route::get('/affiliated-courses', [App\Http\Controllers\AffiliatedCoursesController::class, 'adminIndex'])->name('affiliated-courses.index');

        // Blog routes for authenticated users
        Route::middleware(['auth'])->group(function () {
            Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
            Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
            Route::get('/blog/{slug}/edit', [BlogController::class, 'edit'])->name('blog.edit');
            Route::put('/blog/{slug}', [BlogController::class, 'update'])->name('blog.update');
            Route::delete('/blog/{slug}', [BlogController::class, 'destroy'])->name('blog.destroy');
        });

        // Admin routes for FAQs
        Route::resource('faqs', App\Http\Controllers\FaqController::class);

        // Admin routes for contact messages
        Route::get('/contact-messages', [App\Http\Controllers\ContactController::class, 'adminIndex'])->name('contact.index');
        Route::get('/contact-messages/{message}', [App\Http\Controllers\ContactController::class, 'show'])->name('contact.show');
        Route::patch('/contact-messages/{message}/status', [App\Http\Controllers\ContactController::class, 'updateStatus'])->name('contact.update-status');
        Route::post('/contact-messages/{message}/mark-read', [App\Http\Controllers\ContactController::class, 'markAsRead'])->name('contact.mark-read');

        // Admin routes for job listings
        Route::get('/job-listings', [App\Http\Controllers\JobListingsController::class, 'adminIndex'])->name('job-listings.index');

        // Admin routes for users
        Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
        Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.destroy');

        // Admin routes for creating users
        Route::get('/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');

        // Admin routes for ads
        Route::get('/ads', [App\Http\Controllers\AdController::class, 'adminIndex'])->name('ads.index');
        Route::get('/ads/slider', [App\Http\Controllers\AdController::class, 'sliderManagement'])->name('ads.slider');
        Route::get('/ads/create', [App\Http\Controllers\AdController::class, 'create'])->name('ads.create');
        Route::post('/ads', [App\Http\Controllers\AdController::class, 'store'])->name('ads.store');
        Route::get('/ads/{ad}', [App\Http\Controllers\AdController::class, 'show'])->name('ads.show');
        Route::get('/ads/{ad}/edit', [App\Http\Controllers\AdController::class, 'edit'])->name('ads.edit');
        Route::put('/ads/{ad}', [App\Http\Controllers\AdController::class, 'update'])->name('ads.update');
        Route::delete('/ads/{ad}', [App\Http\Controllers\AdController::class, 'destroy'])->name('ads.destroy');
        Route::post('/ads/{ad}/toggle-status', [App\Http\Controllers\AdController::class, 'toggleStatus'])->name('ads.toggle-status');
        Route::get('/ads/api/positions/{position}/{page?}', [App\Http\Controllers\AdController::class, 'getAdsForPosition'])->name('ads.get-ads-for-position');

        // Admin routes for hero content (explicitly named to ensure admin prefix)
        Route::get('/hero-contents', [App\Http\Controllers\HeroContentController::class, 'index'])->name('hero-contents.index');
        Route::get('/hero-contents/create', [App\Http\Controllers\HeroContentController::class, 'create'])->name('hero-contents.create');
        Route::post('/hero-contents', [App\Http\Controllers\HeroContentController::class, 'store'])->name('hero-contents.store');
        Route::get('/hero-contents/{heroContent}', [App\Http\Controllers\HeroContentController::class, 'show'])->name('hero-contents.show');
        Route::get('/hero-contents/{heroContent}/edit', [App\Http\Controllers\HeroContentController::class, 'edit'])->name('hero-contents.edit');
        Route::put('/hero-contents/{heroContent}', [App\Http\Controllers\HeroContentController::class, 'update'])->name('hero-contents.update');
        Route::delete('/hero-contents/{heroContent}', [App\Http\Controllers\HeroContentController::class, 'destroy'])->name('hero-contents.destroy');

        // Admin routes for applications
        Route::get('/applications', [App\Http\Controllers\JobListingsController::class, 'adminApplications'])->name('applications.index');
        Route::get('/applications/{application}', [App\Http\Controllers\JobListingsController::class, 'showApplication'])->name('applications.show');
        Route::patch('/applications/{application}/status', [App\Http\Controllers\JobListingsController::class, 'updateApplicationStatus'])->name('applications.update-status');
    });
//}); // Close the auth middleware group

// University and Course Search Routes (commented out as controller doesn't exist)
// Route::prefix('universities')->name('universities.')->group(function () {
//     Route::get('/search-courses', [UniversityController::class, 'searchCourses'])->name('search-courses');
//     Route::get('/search-results', [UniversityController::class, 'searchResults'])->name('search-results');
//     Route::get('/courses/{universityId}', [UniversityController::class, 'coursesByUniversity'])->name('courses');
// });

require __DIR__.'/auth.php';
