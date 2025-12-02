<?php

namespace App\Http\Controllers;

use App\Models\HeroContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', HeroContent::class);

        $heroContents = HeroContent::orderBy('order', 'asc')->paginate(10);

        return view('admin.hero-contents.index', compact('heroContents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', HeroContent::class);

        return view('admin.hero-contents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', HeroContent::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'content_type' => 'required|in:image,video,youtube',
            'content_url' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:102400', // 100MB max
            'youtube_url' => 'nullable|url',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|url',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'subtitle', 'content_type', 'youtube_url', 'button_text', 'button_url', 'order']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('content_url')) {
            $path = $request->file('content_url')->store('hero-contents', 'public');
            $data['content_url'] = $path;
        }

        HeroContent::create($data);

        return redirect()->route('admin.hero-contents.index')->with('success', 'Hero content created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HeroContent $heroContent)
    {
        $this->authorize('view', $heroContent);

        return view('admin.hero-contents.show', compact('heroContent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HeroContent $heroContent)
    {
        $this->authorize('update', $heroContent);

        return view('admin.hero-contents.edit', compact('heroContent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HeroContent $heroContent)
    {
        $this->authorize('update', $heroContent);

        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'content_type' => 'required|in:image,video,youtube',
            'content_url' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:102400', // 100MB max
            'youtube_url' => 'nullable|url',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|url',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'subtitle', 'content_type', 'youtube_url', 'button_text', 'button_url', 'order']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('content_url')) {
            // Delete old file if exists
            if ($heroContent->content_url) {
                Storage::disk('public')->delete($heroContent->content_url);
            }

            $path = $request->file('content_url')->store('hero-contents', 'public');
            $data['content_url'] = $path;
        }

        $heroContent->update($data);

        return redirect()->route('admin.hero-contents.index')->with('success', 'Hero content updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeroContent $heroContent)
    {
        $this->authorize('delete', $heroContent);

        // Delete file if exists
        if ($heroContent->content_url) {
            Storage::disk('public')->delete($heroContent->content_url);
        }

        $heroContent->delete();

        return redirect()->route('admin.hero-contents.index')->with('success', 'Hero content deleted successfully.');
    }
}
