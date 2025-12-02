<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\AdCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ads = Ad::with('campaign')->latest()->paginate(15);
        return view('ads.index', compact('ads'));
    }


    /**
     * Display ads for admin management with positions.
     */
    public function adminIndex()
    {
        $ads = Ad::with('campaign')->paginate(15);
        $positions = ['top', 'below_header', 'above_footer', 'left_sidebar', 'right_sidebar', 'top_slider'];
        return view('admin.ads.index', compact('ads', 'positions'));
    }

    /**
     * Display slider ads management.
     */
    public function sliderManagement()
    {
        $sliderAds = Ad::where('is_slider_featured', true)
            ->orderBy('slider_order', 'asc')
            ->paginate(15);
        return view('admin.ads.slider-management', compact('sliderAds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Ad::class);
        $campaigns = AdCampaign::all();
        return view('ads.create', compact('campaigns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Ad::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'required|url',
            'ad_type' => 'required|in:image,text,banner,video',
            'target_audience' => 'required|string|max:255',
            'position' => 'required|string|max:100',
            'status' => 'required|in:active,pending,inactive,expired',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'daily_budget' => 'required|numeric|min:0',
            'impressions' => 'nullable|integer|min:0',
            'clicks' => 'nullable|integer|min:0',
        ]);

        // Handle image upload if applicable
        $data = $request->all();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ads', 'public');
            $data['image_url'] = $path;
        }

        Ad::create($data);

        return redirect()->route('ads.index')->with('success', 'Ad created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ad $ad)
    {
        $this->authorize('view', $ad);
        return view('ads.show', compact('ad'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ad $ad)
    {
        $this->authorize('update', $ad);
        $campaigns = AdCampaign::all();
        return view('ads.edit', compact('ad', 'campaigns'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ad $ad)
    {
        $this->authorize('update', $ad);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'required|url',
            'ad_type' => 'required|in:image,text,banner,video',
            'target_audience' => 'required|string|max:255',
            'position' => 'required|string|max:100',
            'status' => 'required|in:active,pending,inactive,expired',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'daily_budget' => 'required|numeric|min:0',
            'impressions' => 'nullable|integer|min:0',
            'clicks' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ads', 'public');
            $data['image_url'] = $path;
        }

        $ad->update($data);

        return redirect()->route('ads.index')->with('success', 'Ad updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ad $ad)
    {
        $this->authorize('delete', $ad);

        $ad->delete();

        return redirect()->route('ads.index')->with('success', 'Ad deleted successfully.');
    }

    /**
     * Toggle the status of an ad.
     */
    public function toggleStatus(Ad $ad)
    {
        $this->authorize('update', $ad);

        $ad->status = $ad->status === 'active' ? 'inactive' : 'active';
        $ad->save();

        return redirect()->back()->with('success', 'Ad status updated successfully.');
    }

    /**
     * Get ads for a specific position and page.
     */
    public function getAdsForPosition(Request $request, $position, $page = null)
    {
        $ads = Ad::where('position', $position)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($ads);
    }

}