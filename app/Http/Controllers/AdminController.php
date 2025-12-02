<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Course;
use App\Models\JobListing;
use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // For now, we'll allow any authenticated user to access admin functions
        // In a real application, you'd want to check for admin roles/permissions

        $totalUsers = User::count();
        $totalEvents = Event::count();
        $publishedEvents = Event::where('event_status', 'published')->count();
        $totalCourses = Course::count();
        $publishedCourses = Course::where('course_status', 'published')->count();
        $totalJobs = JobListing::count();
        $publishedJobs = JobListing::where('job_status', 'published')->count();

        // Get recent activities
        $recentEvents = Event::latest()->limit(5)->get();
        $recentCourses = Course::latest()->limit(5)->get();
        $recentJobs = JobListing::latest()->limit(5)->get();
        $recentUsers = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalEvents', 'publishedEvents',
            'totalCourses', 'publishedCourses',
            'totalJobs', 'publishedJobs',
            'recentEvents', 'recentCourses', 'recentJobs', 'recentUsers'
        ));
    }

    /**
     * Display the admin settings page.
     */
    public function settings()
    {
        $siteLogo = SiteSetting::get('site_logo');

        return view('admin.settings', compact('siteLogo'));
    }

    /**
     * Update the admin settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('logos', 'public');

            // Update or create logo setting
            SiteSetting::updateOrCreate(
                ['key' => 'site_logo'],
                ['value' => $logoPath]
            );
        }

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully!');
    }

    /**
     * Display all events for admin review.
     */
    public function events()
    {
        $events = Event::with('creator')->paginate(15);
        return view('admin.events', compact('events'));
    }

    /**
     * Display all courses for admin review.
     */
    public function courses()
    {
        $courses = Course::with('instructor')->paginate(15);
        return view('admin.courses', compact('courses'));
    }

    /**
     * Display all job listings for admin review.
     */
    public function jobs()
    {
        $jobListings = JobListing::with('poster')->paginate(15);
        return view('admin.jobs', compact('jobListings'));
    }

    /**
     * Display all users for admin review.
     */
    public function users()
    {
        $users = User::with('profile')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the general site settings page.
     */
    public function generalSettings()
    {
        $settings = SiteSetting::orderBy('sort_order')->get();
        $siteLogo = SiteSetting::get('site_logo');

        return view('admin.settings', compact('settings', 'siteLogo'));
    }

    /**
     * Update the general site settings.
     */
    public function updateGeneralSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'sometimes|string|max:255',
            'site_description' => 'sometimes|string',
            'about_us' => 'sometimes|string',
            'services_info' => 'sometimes|string',
            'contact_content' => 'sometimes|string',
            'contact_email' => 'sometimes|email',
            'contact_phone' => 'sometimes|string',
            'contact_address' => 'sometimes|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle logo upload if provided
        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('logos', 'public');
            SiteSetting::set('site_logo', $logoPath);
        }

        // Update other settings
        if ($request->filled('site_name')) {
            SiteSetting::set('site_name', $request->site_name);
        }

        if ($request->filled('site_description')) {
            SiteSetting::set('site_description', $request->site_description);
        }

        if ($request->filled('about_us')) {
            SiteSetting::set('about_us', $request->about_us);
        }

        if ($request->filled('services_info')) {
            SiteSetting::set('services_info', $request->services_info);
        }

        if ($request->filled('contact_content')) {
            SiteSetting::set('contact_content', $request->contact_content);
        }

        if ($request->filled('contact_email')) {
            SiteSetting::set('contact_email', $request->contact_email);
        }

        if ($request->filled('contact_phone')) {
            SiteSetting::set('contact_phone', $request->contact_phone);
        }

        if ($request->filled('contact_address')) {
            SiteSetting::set('contact_address', $request->contact_address);
        }

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully!');
    }

    /**
     * Show the form for editing a user.
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,instructor,admin,moderator',
            'is_admin' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_admin' => $request->is_admin ?? false,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}