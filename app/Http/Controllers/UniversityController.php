<?php

namespace App\Http\Controllers;

use App\Models\AffiliatedCourse;
use App\Models\University;
use App\Models\Country;
use App\Models\CourseLevel;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    /**
     * Display courses search page.
     */
    public function searchCourses()
    {
        $countries = Country::where('is_active', true)->get();
        $universities = University::where('is_active', true)->get();
        $levels = CourseLevel::where('is_active', true)->orderBy('order')->get();

        return view('universities.search-courses', compact('countries', 'universities', 'levels'));
    }

    /**
     * Search for courses based on filters.
     */
    public function searchResults(Request $request)
    {
        $query = AffiliatedCourse::with(['university.country']);

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->whereHas('university', function($q) use ($request) {
                $q->where('country_id', $request->country);
            });
        }

        // Filter by university
        if ($request->filled('university')) {
            $query->where('university_id', $request->university);
        }

        // Filter by keywords
        if ($request->filled('keywords')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->keywords . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->keywords . '%');
            });
        }

        $courses = $query->where('status', 'published')
                         ->orderBy('created_at', 'desc')
                         ->paginate(12);

        $countries = Country::where('is_active', true)->get();
        $universities = University::where('is_active', true)->get();
        $levels = CourseLevel::where('is_active', true)->orderBy('order')->get();

        return view('universities.search-results', compact('courses', 'countries', 'universities', 'levels'));
    }

    /**
     * Display course search results for a specific university.
     */
    public function coursesByUniversity($universityId)
    {
        $university = University::findOrFail($universityId);
        $courses = AffiliatedCourse::where('university_id', $universityId)
                                   ->where('status', 'published')
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(12);

        return view('universities.courses-by-university', compact('university', 'courses'));
    }
}
