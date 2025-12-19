<?php

namespace App\Http\Controllers;

use App\Models\CvUpload;
use App\Models\CvSearchLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\CvAnalysisService;
use App\Services\JobRecommendationService;

class CvSearchController extends Controller
{
    protected $cvAnalysisService;
    protected $jobRecommendationService;

    public function __construct(
        CvAnalysisService $cvAnalysisService,
        JobRecommendationService $jobRecommendationService
    ) {
        $this->cvAnalysisService = $cvAnalysisService;
        $this->jobRecommendationService = $jobRecommendationService;
    }
    /**
     * Show the CV search dashboard for recruiters
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user is authorized to search CVs
        if (!$user->hasRole('recruiter') && !$user->hasRole('employer') && !$user->can('administrate')) {
            abort(403, 'Unauthorized to search CVs');
        }

        $remainingCredits = $user->cv_search_credits;
        $hasSubscription = $user->cv_search_subscription_active &&
                           (!$user->cv_search_subscription_expiry || $user->cv_search_subscription_expiry->isFuture());
        $searchHistory = CvSearchLog::where('user_id', $user->id)
                                  ->orderBy('created_at', 'desc')
                                  ->limit(10)
                                  ->get();

        return view('cv.search.dashboard', compact(
            'remainingCredits',
            'hasSubscription',
            'searchHistory'
        ));
    }

    /**
     * Search for CVs with payment check
     */
    public function search(Request $request)
    {
        $user = Auth::user();

        // Check if user is authorized to search CVs
        if (!$user->hasRole('recruiter') && !$user->hasRole('employer') && !$user->can('administrate')) {
            abort(403, 'Unauthorized to search CVs');
        }

        // Check if user has sufficient credits or an active subscription
        $hasSubscription = $user->hasActiveCvSearchSubscription();
        $hasSufficientCredits = $user->cv_search_credits >= 1; // Assuming 1 credit per search

        if (!$hasSubscription && !$hasSufficientCredits) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient credits for CV search. Please purchase credits or subscribe.',
                'requires_payment' => true,
            ], 402); // Payment Required status
        }

        // Validate search parameters
        $request->validate([
            'search' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'skills' => 'nullable|string',
            'experience_level' => 'nullable|string|in:entry,intermediate,senior,executive',
            'education_level' => 'nullable|string',
            'job_type' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'results_per_page' => 'nullable|integer|min:1|max:50',
        ]);

        // Build the search query
        $query = CvUpload::where('is_public', true)
                         ->where('status', 'active')
                         ->with('user'); // Include user data for contact info

        // Apply search filters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('summary', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('location', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('extracted_skills', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('work_experience', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('education', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        if ($request->filled('skills')) {
            $skills = explode(',', $request->skills);
            foreach ($skills as $skill) {
                $skill = trim($skill);
                if (!empty($skill)) {
                    $query->where(function($q) use ($skill) {
                        $q->whereJsonContains('extracted_skills', $skill)
                          ->orWhereRaw("JSON_SEARCH(JSON_EXTRACT(extracted_skills, '$[*]'), 'all', ?) IS NOT NULL", [$skill]);
                    });
                }
            }
        }

        if ($request->filled('experience_level')) {
            $query->where('experience_level', $request->experience_level);
        }

        if ($request->filled('education_level')) {
            $query->where('education_level', $request->education_level);
        }

        if ($request->filled('job_type')) {
            $query->where('desired_job_type', $request->job_type); // assuming a field exists for this
        }

        if ($request->filled('salary_min')) {
            $query->where('desired_salary_min', '>=', $request->salary_min);
        }

        $resultsPerPage = $request->get('results_per_page', 10);
        $cvs = $query->orderBy('is_featured', 'desc')  // Show featured CVs first
                     ->orderBy('view_count', 'desc')   // Then by popularity
                     ->orderBy('updated_at', 'desc')   // Then by recency
                     ->paginate($resultsPerPage);

        // Calculate credits used for this search
        $cvsAccessed = $cvs->count();
        $creditsUsed = $hasSubscription ? 0 : $cvsAccessed; // If subscription, no credits charged

        // Only charge credits if no active subscription
        $cvsAccessed = $cvs->count();
        $creditsUsed = $hasSubscription ? 0 : $cvsAccessed; // If subscription, no credits charged

        // Only deduct credits if no active subscription
        if (!$hasSubscription && $creditsUsed > 0) {
            $user->decrement('cv_search_credits', $creditsUsed);
            $user->increment('cv_search_credits_used', $creditsUsed);
        }

        // Log the search activity
        CvSearchLog::create([
            'user_id' => $user->id,
            'search_query' => $request->search,
            'search_filters' => json_encode($request->except(['page', 'results_per_page'])),
            'cvs_accessed' => $cvsAccessed,
            'credits_used' => $creditsUsed,
            'search_type' => $hasSubscription ? 'subscription_based' : 'credit_based',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'cvs' => $cvs,
                'search_query' => $request->search,
                'total_results' => $cvs->total(),
                'current_page' => $cvs->currentPage(),
                'last_page' => $cvs->lastPage(),
                'has_credits' => $user->cv_search_credits >= 1,
                'remaining_credits' => $user->cv_search_credits,
                'has_subscription' => $user->hasActiveCvSearchSubscription(),
            ],
        ]);
    }

    /**
     * Purchase CV search credits
     */
    public function purchaseCredits(Request $request)
    {
        $user = Auth::user();

        // Check if user is authorized
        if (!$user->hasRole('recruiter') && !$user->hasRole('employer') && !$user->can('administrate')) {
            abort(403, 'Unauthorized to purchase credits');
        }

        $request->validate([
            'credit_package' => 'required|integer|in:5,10,20,50,100', // Common credit packages
            'payment_method' => 'required|in:paypal,stripe,card,bank_transfer',
        ]);

        // In a real system, you would process payment here
        // For this implementation, we'll just add credits after simulating payment

        // Credit prices could be defined as:
        $creditPrices = [
            5 => 10.00,   // £10 for 5 credits = £2 per credit
            10 => 18.00,  // £18 for 10 credits = £1.80 per credit
            20 => 32.00,  // £32 for 20 credits = £1.60 per credit
            50 => 70.00,  // £70 for 50 credits = £1.40 per credit
            100 => 120.00, // £120 for 100 credits = £1.20 per credit
        ];

        $creditAmount = $request->credit_package;
        $price = $creditPrices[$creditAmount] ?? 0;

        // If it were a real payment system, we'd process payment here
        // For now, let's just add the credits to the user's account
        $user->addCvSearchCredits($creditAmount);

        // Log the purchase
        CvSearchLog::create([
            'user_id' => $user->id,
            'search_query' => 'Credit Purchase: ' . $creditAmount . ' credits',
            'cvs_accessed' => 0,
            'credits_used' => 0,
            'search_type' => 'credit_purchase',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Credits purchased successfully',
            'data' => [
                'new_credits' => $creditAmount,
                'total_credits' => $user->refresh()->cv_search_credits,
            ],
        ]);
    }

    /**
     * Get user's search history
     */
    public function searchHistory()
    {
        $user = Auth::user();

        // Check if user is authorized
        if (!$user->hasRole('recruiter') && !$user->hasRole('employer') && !$user->can('administrate')) {
            abort(403, 'Unauthorized to view search history');
        }

        $searchHistory = CvSearchLog::where('user_id', $user->id)
                                   ->orderBy('searched_at', 'desc')
                                   ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'search_history' => $searchHistory,
                'total_credits' => $user->cv_search_credits,
                'credits_used' => $user->cv_search_credits_used,
                'has_subscription' => $user->hasActiveCvSearchSubscription(),
            ],
        ]);
    }

    /**
     * Subscribe to CV search service
     */
    public function subscribe(Request $request)
    {
        $user = Auth::user();

        // Check if user is authorized
        if (!$user->hasRole('recruiter') && !$user->hasRole('employer') && !$user->can('administrate')) {
            abort(403, 'Unauthorized to subscribe');
        }

        $request->validate([
            'subscription_plan' => 'required|in:monthly,quarterly,yearly',
            'payment_method' => 'required|in:paypal,stripe,card,bank_transfer',
        ]);

        // Define subscription plans and prices
        $subscriptionPlans = [
            'monthly' => [
                'price' => 49.99,
                'duration_days' => 30,
                'access_unlimited' => true,
            ],
            'quarterly' => [
                'price' => 129.99,
                'duration_days' => 90,
                'access_unlimited' => true,
            ],
            'yearly' => [
                'price' => 399.99,
                'duration_days' => 365,
                'access_unlimited' => true,
            ],
        ];

        $plan = $subscriptionPlans[$request->subscription_plan];

        // Again, in a real system you would process payment here
        // For now, just activate the subscription

        $user->update([
            'cv_search_subscription_active' => true,
            'cv_search_subscription_expiry' => now()->addDays($plan['duration_days']),
        ]);

        // Log the subscription
        CvSearchLog::create([
            'user_id' => $user->id,
            'search_query' => 'Subscription Purchase: ' . $request->subscription_plan,
            'cvs_accessed' => 0,
            'credits_used' => 0,
            'search_type' => 'subscription_purchase',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription activated successfully',
            'data' => [
                'subscription_type' => $request->subscription_plan,
                'expires_at' => $user->cv_search_subscription_expiry,
                'access_unlimited' => $plan['access_unlimited'],
            ],
        ]);
    }

    /**
     * Show CV details to recruiters (with payment/permission check)
     */
    public function showCv($cvId)
    {
        $user = Auth::user();
        $cv = CvUpload::findOrFail($cvId);

        // Check if user is authorized
        if (!$user->hasRole('recruiter') && !$user->hasRole('employer') && !$user->can('administrate')) {
            abort(403, 'Unauthorized to view CV');
        }

        // Check if user has sufficient credits or subscription
        $hasSubscription = $user->hasActiveCvSearchSubscription();
        $hasSufficientCredits = $user->cv_search_credits >= 1; // 1 credit per CV view

        if (!$hasSubscription && !$hasSufficientCredits && $cv->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient credits to view this CV. Please purchase credits or subscribe.',
                'requires_payment' => true,
            ], 402);
        }

        // Only charge a credit if no subscription AND it's not the user's own CV
        if (!$hasSubscription && $cv->user_id !== $user->id) {
            $user->decrement('cv_search_credits', 1);
            $user->increment('cv_search_credits_used', 1);
        }

        // Log the CV access
        CvSearchLog::create([
            'user_id' => $user->id,
            'search_query' => 'CV View: ' . $cv->original_name,
            'cvs_accessed' => 1,
            'credits_used' => (!$hasSubscription && $cv->user_id !== $user->id) ? 1 : 0,
            'search_type' => $hasSubscription ? 'subscription_based' : 'credit_based',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'cv' => $cv,
                'cv_owner' => $cv->user,
                'remaining_credits' => $user->cv_search_credits,
                'has_subscription' => $user->hasActiveCvSearchSubscription(),
            ],
        ]);
    }

    /**
     * Download CV with payment/permission check
     */
    public function downloadCv($cvId)
    {
        $user = Auth::user();
        $cv = CvUpload::findOrFail($cvId);

        // Check if user is authorized
        if (!$user->hasRole('recruiter') && !$user->hasRole('employer') && !$user->can('administrate')) {
            abort(403, 'Unauthorized to download CV');
        }

        // Check if user has sufficient credits or subscription (unless it's their own CV)
        $hasSubscription = $user->hasActiveCvSearchSubscription();
        $hasSufficientCredits = $user->cv_search_credits >= 1; // 1 credit per download

        if (!$hasSubscription && !$hasSufficientCredits && $cv->user_id !== $user->id) {
            return redirect()->route('cv.search.index')
                             ->with('error', 'Insufficient credits to download CV. Please purchase credits or subscribe.');
        }

        // Only charge if no subscription and not the user's own CV
        if (!$hasSubscription && $cv->user_id !== $user->id) {
            $user->decrement('cv_search_credits', 1);
            $user->increment('cv_search_credits_used', 1);
        }

        // Log the download
        CvSearchLog::create([
            'user_id' => $user->id,
            'search_query' => 'CV Download: ' . $cv->original_name,
            'cvs_accessed' => 1,
            'credits_used' => (!$hasSubscription && $cv->user_id !== $user->id) ? 1 : 0,
            'search_type' => $hasSubscription ? 'subscription_based' : 'credit_based',
        ]);

        // Increment view/download count
        $cv->increment('view_count');

        return response()->download(storage_path('app/public/' . $cv->file_path), $cv->original_name);
    }
}
