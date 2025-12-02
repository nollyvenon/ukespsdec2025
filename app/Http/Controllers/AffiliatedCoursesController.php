<?php

namespace App\Http\Controllers;

use App\Models\AffiliatedCourse;
use App\Models\AffiliatedCourseEnrollment;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliatedCoursesController extends Controller
{
    /**
     * Display a listing of affiliated courses.
     */
    public function index()
    {
        $courses = AffiliatedCourse::where('status', 'published')->paginate(10);
        return view('affiliated-courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new affiliated course.
     */
    public function create()
    {
        $this->authorize('create', AffiliatedCourse::class);
        return view('affiliated-courses.create');
    }

    /**
     * Store a newly created affiliated course in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', AffiliatedCourse::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'university_name' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced,all_levels',
            'duration' => 'required|integer|min:1',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'university_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'course_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fee' => 'nullable|numeric|min:0',
            'prerequisites' => 'nullable|string',
            'syllabus' => 'nullable|string',
            'skills_covered' => 'nullable|array',
            'career_outcomes' => 'nullable|array',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
            'max_enrollment' => 'nullable|integer|min:1',
        ]);

        $data = $request->all();
        $data['skills_covered'] = $request->skills_covered ? json_encode($request->skills_covered) : null;
        $data['career_outcomes'] = $request->career_outcomes ? json_encode($request->career_outcomes) : null;

        if ($request->hasFile('university_logo')) {
            $path = $request->file('university_logo')->store('university_logos', 'public');
            $data['university_logo'] = $path;
        }

        if ($request->hasFile('course_image')) {
            $path = $request->file('course_image')->store('course_images', 'public');
            $data['course_image'] = $path;
        }

        AffiliatedCourse::create($data);

        return redirect()->route('affiliated-courses.index')->with('success', 'Affiliated course created successfully.');
    }

    /**
     * Display the specified affiliated course.
     */
    public function show(AffiliatedCourse $affiliatedCourse)
    {
        return view('affiliated-courses.show', compact('affiliatedCourse'));
    }

    /**
     * Show the form for editing the specified affiliated course.
     */
    public function edit(AffiliatedCourse $affiliatedCourse)
    {
        $this->authorize('update', $affiliatedCourse);
        return view('affiliated-courses.edit', compact('affiliatedCourse'));
    }

    /**
     * Update the specified affiliated course in storage.
     */
    public function update(Request $request, AffiliatedCourse $affiliatedCourse)
    {
        $this->authorize('update', $affiliatedCourse);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'university_name' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced,all_levels',
            'duration' => 'required|integer|min:1',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'university_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'course_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fee' => 'nullable|numeric|min:0',
            'prerequisites' => 'nullable|string',
            'syllabus' => 'nullable|string',
            'skills_covered' => 'nullable|array',
            'career_outcomes' => 'nullable|array',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
            'max_enrollment' => 'nullable|integer|min:1',
        ]);

        $data = $request->all();
        $data['skills_covered'] = $request->skills_covered ? json_encode($request->skills_covered) : null;
        $data['career_outcomes'] = $request->career_outcomes ? json_encode($request->career_outcomes) : null;

        if ($request->hasFile('university_logo')) {
            $path = $request->file('university_logo')->store('university_logos', 'public');
            $data['university_logo'] = $path;
        }

        if ($request->hasFile('course_image')) {
            $path = $request->file('course_image')->store('course_images', 'public');
            $data['course_image'] = $path;
        }

        $affiliatedCourse->update($data);

        return redirect()->route('affiliated-courses.index')->with('success', 'Affiliated course updated successfully.');
    }

    /**
     * Remove the specified affiliated course from storage.
     */
    public function destroy(AffiliatedCourse $affiliatedCourse)
    {
        $this->authorize('delete', $affiliatedCourse);

        $affiliatedCourse->delete();

        return redirect()->route('affiliated-courses.index')->with('success', 'Affiliated course deleted successfully.');
    }

    /**
     * Enroll in an affiliated course.
     */
    public function enroll(AffiliatedCourse $affiliatedCourse)
    {
        // Check if user is already enrolled
        $existingEnrollment = AffiliatedCourseEnrollment::where('affiliated_course_id', $affiliatedCourse->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        // Check if the course has reached max enrollment
        if ($affiliatedCourse->max_enrollment && $affiliatedCourse->affiliatedCourseEnrollments()->count() >= $affiliatedCourse->max_enrollment) {
            return redirect()->back()->with('error', 'This course has reached maximum enrollment.');
        }

        // Check if course has already started
        if ($affiliatedCourse->start_date < now()->subDay()) {
            return redirect()->back()->with('error', 'Cannot enroll in a course that has already started.');
        }

        AffiliatedCourseEnrollment::create([
            'affiliated_course_id' => $affiliatedCourse->id,
            'user_id' => Auth::id(),
            'enrollment_status' => 'enrolled',
            'completion_percentage' => 0,
        ]);

        return redirect()->back()->with('success', 'Successfully enrolled in the affiliated course.');
    }

    /**
     * Display user's affiliated course enrollments.
     */
    public function myEnrollments()
    {
        $enrollments = AffiliatedCourseEnrollment::where('user_id', Auth::id())
            ->with('affiliatedCourse')
            ->paginate(10);

        return view('affiliated-courses.my-enrollments', compact('enrollments'));
    }

    /**
     * Display courses for admin management.
     */
    public function adminIndex()
    {
        $courses = AffiliatedCourse::with('affiliatedCourseEnrollments')->paginate(15);
        return view('admin.affiliated-courses', compact('courses'));
    }
}
