<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\AffiliatedCourse;
use App\Models\Testimonial;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursesController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(Request $request)
    {
        $query = Course::where('course_status', 'published');

        // Advanced search criteria
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prerequisites', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('syllabus', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }

        if ($request->filled('duration_min')) {
            $query->where('duration', '>=', $request->input('duration_min'));
        }

        if ($request->filled('duration_max')) {
            $query->where('duration', '<=', $request->input('duration_max'));
        }

        if ($request->filled('start_date_from')) {
            $query->whereDate('start_date', '>=', $request->input('start_date_from'));
        }

        if ($request->filled('start_date_to')) {
            $query->whereDate('start_date', '<=', $request->input('start_date_to'));
        }

        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->input('location') . '%');
        }

        if ($request->filled('instructor')) {
            $query->whereHas('instructor', function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->input('instructor') . '%');
            });
        }

        $courses = $query->with('instructor')
            ->orderBy('is_premium', 'desc')  // Premium courses first
            ->orderBy('created_at', 'desc')  // Then by creation date
            ->paginate(10);

        // Get filter options for the view
        $levels = ['beginner', 'intermediate', 'advanced', 'all_levels'];

        return view('courses.index', compact('courses', 'levels'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        \Log::info('CoursesController create method called');

        try {
            \Log::info('Attempting to authorize course creation');
            $this->authorize('create', Course::class);
            \Log::info('Authorization passed');
        } catch (\Exception $e) {
            \Log::error('Authorization failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            throw $e; // Re-throw to see the actual error
        }

        \Log::info('About to return courses.create view');

        try {
            return view('courses.create');
        } catch (\Exception $e) {
            \Log::error('Error returning view: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            throw $e; // Re-throw to see the actual error
        }
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Course::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'level' => 'required|in:beginner,intermediate,advanced,all_levels',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'course_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'course_status' => 'required|in:draft,published,ongoing,completed,cancelled',
            'max_enrollment' => 'nullable|integer|min:1',
            'prerequisites' => 'nullable|string',
            'syllabus' => 'nullable|string',
            'is_premium' => 'boolean',
            'premium_fee' => 'nullable|numeric|min:0',
        ]);

        $data = $request->except(['instructor_id']);
        $data['instructor_id'] = Auth::id();

        // Handle premium course settings
        if ($request->is_premium) {
            if ($request->premium_fee) {
                $data['is_premium'] = true;
                $data['premium_fee'] = $request->premium_fee;
                $data['premium_expires_at'] = now()->addDays(30); // Default to 30 days
            } else {
                $data['is_premium'] = false; // Don't allow premium without specifying fee
            }
        } else {
            $data['is_premium'] = false;
            $data['premium_fee'] = null;
            $data['premium_expires_at'] = null;
        }

        if ($request->hasFile('course_image')) {
            $path = $request->file('course_image')->store('courses', 'public');
            $data['course_image'] = $path;
        }

        Course::create($data);

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        // Only authorize if the course is not published (for unpublished courses, only authorized users can view)
        if ($course->course_status !== 'published') {
            $this->authorize('view', $course);
        }

        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'level' => 'required|in:beginner,intermediate,advanced,all_levels',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'course_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'course_status' => 'required|in:draft,published,ongoing,completed,cancelled',
            'max_enrollment' => 'nullable|integer|min:1',
            'prerequisites' => 'nullable|string',
            'syllabus' => 'nullable|string',
            'is_premium' => 'boolean',
        ]);

        $data = $request->except(['instructor_id']); // Don't allow changing instructor_id

        if ($request->hasFile('course_image')) {
            $path = $request->file('course_image')->store('courses', 'public');
            $data['course_image'] = $path;
        }

        $course->update($data);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }

    /**
     * Display courses taught by the authenticated user.
     */
    public function myCourses()
    {
        $courses = Course::where('instructor_id', Auth::id())->paginate(10);
        return view('courses.my-courses', compact('courses'));
    }

    /**
     * Enroll in a course.
     */
    public function enroll(Course $course)
    {
        // Check if user is already enrolled
        $existingEnrollment = CourseEnrollment::where('course_id', $course->id)
            ->where('student_id', Auth::id())
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        // Check if the course has reached max enrollment
        if ($course->max_enrollment && $course->enrollments()->count() >= $course->max_enrollment) {
            return redirect()->back()->with('error', 'This course has reached maximum enrollment.');
        }

        // Check if course has already started
        if ($course->start_date < now()->subDay()) {
            return redirect()->back()->with('error', 'Cannot enroll in a course that has already started.');
        }

        CourseEnrollment::create([
            'course_id' => $course->id,
            'student_id' => Auth::id(),
            'enrollment_status' => 'enrolled',
            'completion_percentage' => 0,
        ]);

        return redirect()->back()->with('success', 'Successfully enrolled in the course.');
    }

    /**
     * Display user's course enrollments.
     */
    public function myEnrollments()
    {
        $enrollments = CourseEnrollment::where('student_id', Auth::id())
            ->with('course')
            ->paginate(10);

        return view('courses.my-enrollments', compact('enrollments'));
    }

    /**
     * Display the courses portal landing page.
     */
    public function portalLanding()
    {
        // Get featured courses
        $featuredCourses = AffiliatedCourse::where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get recent courses
        $recentCourses = AffiliatedCourse::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get popular sectors/fields
        $popularSectors = AffiliatedCourse::where('status', 'published')
            ->selectRaw('level, COUNT(*) as count')
            ->groupBy('level')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->pluck('level');

        // Get testimonials for the courses section
        $testimonials = Testimonial::where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Get countries for search
        $countries = Country::where('is_active', true)->get();

        return view('courses.portal-landing', compact(
            'featuredCourses',
            'recentCourses',
            'popularSectors',
            'testimonials',
            'countries'
        ));
    }

    /**
     * Update course progress.
     */
    public function updateProgress(Request $request, Course $course)
    {
        $enrollment = CourseEnrollment::where('course_id', $course->id)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'completion_percentage' => 'required|integer|min:0|max:100',
        ]);

        $enrollment->update([
            'completion_percentage' => $request->completion_percentage,
        ]);

        return response()->json(['message' => 'Progress updated successfully']);
    }
}
