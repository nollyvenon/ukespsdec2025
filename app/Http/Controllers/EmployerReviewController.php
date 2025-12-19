<?php

namespace App\Http\Controllers;

use App\Models\EmployerReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EmployerReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EmployerReview::approved()->with('user');

        // Apply filters
        if ($request->filled('company')) {
            $query->where('company_name', 'LIKE', '%' . $request->company . '%');
        }

        if ($request->filled('min_rating')) {
            $query->where('overall_rating', '>=', $request->min_rating);
        }

        if ($request->filled('location')) {
            $query->where('company_location', 'LIKE', '%' . $request->location . '%');
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'highest_rated':
                    $query->orderBy('overall_rating', 'desc');
                    break;
                case 'lowest_rated':
                    $query->orderBy('overall_rating', 'asc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $reviews = $query->paginate(15);

        return view('employer-reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employer-reviews.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_location' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'review_headline' => 'required|string|max:500',
            'review_text' => 'required|string|min:50',
            'overall_rating' => 'required|numeric|min:1|max:5',
            'work_life_balance_rating' => 'nullable|numeric|min:1|max:5',
            'salary_benefits_rating' => 'nullable|numeric|min:1|max:5',
            'job_security_rating' => 'nullable|numeric|min:1|max:5',
            'management_rating' => 'nullable|numeric|min:1|max:5',
            'culture_rating' => 'nullable|numeric|min:1|max:5',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'is_anonymous' => 'boolean',
            'is_current_employee' => 'boolean',
            'employment_start_date' => 'nullable|date',
            'employment_end_date' => 'nullable|date|after_or_equal:employment_start_date',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_approved'] = true; // Auto-approve for now
        $validated['review_date'] = now();

        $review = EmployerReview::create($validated);

        return redirect()->route('employer-reviews.show', $review->id)
                         ->with('success', 'Review submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployerReview $review)
    {
        // Check if user can view this review
        if (!$review->is_approved && !Auth::user()?->hasRole('admin')) {
            abort(404);
        }

        return view('employer-reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployerReview $review)
    {
        // Check if user can edit this review
        if ($review->user_id !== Auth::id() && !Auth::user()?->hasRole('admin')) {
            abort(403);
        }

        return view('employer-reviews.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployerReview $review)
    {
        // Check if user can update this review
        if ($review->user_id !== Auth::id() && !Auth::user()?->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'company_location' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'review_headline' => 'sometimes|string|max:500',
            'review_text' => 'sometimes|string|min:50',
            'overall_rating' => 'sometimes|numeric|min:1|max:5',
            'work_life_balance_rating' => 'nullable|numeric|min:1|max:5',
            'salary_benefits_rating' => 'nullable|numeric|min:1|max:5',
            'job_security_rating' => 'nullable|numeric|min:1|max:5',
            'management_rating' => 'nullable|numeric|min:1|max:5',
            'culture_rating' => 'nullable|numeric|min:1|max:5',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'is_anonymous' => 'boolean',
            'is_current_employee' => 'boolean',
            'employment_start_date' => 'nullable|date',
            'employment_end_date' => 'nullable|date|after_or_equal:employment_start_date',
        ]);

        $review->update($validated);

        return redirect()->route('employer-reviews.show', $review->id)
                         ->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployerReview $review)
    {
        // Check if user can delete this review
        if ($review->user_id !== Auth::id() && !Auth::user()?->hasRole('admin')) {
            abort(403);
        }

        $review->delete();

        return redirect()->route('employer-reviews.index')
                         ->with('success', 'Review deleted successfully!');
    }

    /**
     * Get average ratings for a company
     */
    public function getCompanyRatings($companyName)
    {
        $reviews = EmployerReview::where('company_name', 'LIKE', '%' . $companyName . '%')
                                 ->approved()
                                 ->get();

        if ($reviews->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No reviews found for this company',
            ], 404);
        }

        $averageRatings = [
            'overall' => $reviews->avg('overall_rating'),
            'work_life_balance' => $reviews->avg('work_life_balance_rating'),
            'salary_benefits' => $reviews->avg('salary_benefits_rating'),
            'job_security' => $reviews->avg('job_security_rating'),
            'management' => $reviews->avg('management_rating'),
            'culture' => $reviews->avg('culture_rating'),
            'review_count' => $reviews->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $averageRatings,
        ]);
    }

    /**
     * Toggle review approval status (admin only)
     */
    public function toggleApproval(EmployerReview $review)
    {
        if (!Auth::user()?->hasRole('admin')) {
            abort(403);
        }

        $review->update([
            'is_approved' => !$review->is_approved,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review approval status updated',
            'is_approved' => $review->is_approved,
        ]);
    }

    /**
     * Vote on a review
     */
    public function vote(Request $request, EmployerReview $review)
    {
        $request->validate([
            'vote' => 'required|in:up,down',
        ]);

        // For simplicity, we'll just increment upvotes/downvotes
        // In a real system, you'd want to track individual user votes
        if ($request->vote === 'up') {
            $review->increment('upvotes');
        } else {
            $review->increment('downvotes');
        }

        return response()->json([
            'success' => true,
            'upvotes' => $review->fresh()->upvotes,
            'downvotes' => $review->fresh()->downvotes,
        ]);
    }
}
