<?php

namespace App\Http\Controllers;

use App\Models\University;
use App\Models\AffiliatedCourse;
use App\Models\Country;
use Illuminate\Http\Request;

class PublicUniversitiesController extends Controller
{
    /**
     * Display a listing of universities.
     */
    public function index(Request $request)
    {
        $query = University::withCount('courses as courses_count')
                          ->where('is_active', true);

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('acronym', 'LIKE', '%' . $request->search . '%');
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->where('country_id', $request->country);
        }

        $universities = $query->orderBy('name', 'asc')->paginate(12);
        $countries = Country::where('is_active', true)->get();

        return view('universities.index', compact('universities', 'countries'));
    }

    /**
     * Display the specified university and its courses.
     */
    public function show($id)
    {
        $university = University::with('country')->findOrFail($id);
        
        // Check if university is active
        if (!$university->is_active) {
            abort(404, 'University not found');
        }
        
        $courses = AffiliatedCourse::where('university_id', $id)
                                   ->where('status', 'published')
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(12);

        return view('universities.courses-by-university', compact('university', 'courses'));
    }
}