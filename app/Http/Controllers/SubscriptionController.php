<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    /**
     * Display all available subscription packages.
     */
    public function index()
    {
        $packages = SubscriptionPackage::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        // Get user's current subscription if any
        $currentSubscription = Auth::user()->activeSubscription()->first();

        return view('subscriptions.packages', compact('packages', 'currentSubscription'));
    }

    /**
     * Display the user's subscription history.
     */
    public function history()
    {
        $subscriptions = Auth::user()->subscriptions()->orderBy('created_at', 'desc')->paginate(10);

        return view('subscriptions.history', compact('subscriptions'));
    }

    /**
     * Subscribe to a package.
     */
    public function subscribe(Request $request, $packageId)
    {
        $package = SubscriptionPackage::findOrFail($packageId);

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if user already has an active subscription
        $activeSubscription = Auth::user()->activeSubscription()->first();
        if ($activeSubscription) {
            return redirect()->back()->with('error', 'You already have an active subscription.');
        }

        // Calculate end date based on subscription type
        $startDate = now();
        $endDate = null;

        switch ($package->type) {
            case 'one_time':
                $endDate = $startDate->copy()->addDays($package->duration_days ?? 30);
                break;
            case 'monthly':
                $endDate = $startDate->copy()->addMonth();
                break;
            case 'yearly':
                $endDate = $startDate->copy()->addYear();
                break;
        }

        // Create the subscription
        $subscription = Subscription::create([
            'user_id' => Auth::id(),
            'type' => $package->type,
            'package_name' => $package->name,
            'role_type' => $package->role_type,
            'amount' => $package->price,
            'currency' => 'USD',
            'payment_gateway' => $request->payment_method,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'features' => $package->features,
            'is_recurring' => $package->type !== 'one_time',
        ]);

        // Update user role if different from current
        $user = Auth::user();
        if ($user->role !== $package->role_type) {
            $user->role = $package->role_type;
            $user->save();
        }

        return redirect()->route('dashboard')->with('success', 'You have successfully subscribed to the ' . $package->name . ' package!');
    }

    /**
     * Update user's role (for role switching).
     */
    public function switchRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_role' => 'required|in:student,job_seeker,recruiter,university_manager,event_hoster,admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        // Check if user has a subscription for the requested role
        $subscription = $user->activeSubscription()->where('role_type', $request->new_role)->first();

        if ($request->new_role !== 'admin') { // Allow admin role only for actual admins
            if (!$subscription && $user->role !== $request->new_role) {
                return redirect()->back()->with('error', 'You need an active subscription for the selected role.');
            }
        }

        $user->role = $request->new_role;
        $user->save();

        return redirect()->back()->with('success', 'Role successfully updated to ' . ucfirst(str_replace('_', ' ', $request->new_role)));
    }
}
