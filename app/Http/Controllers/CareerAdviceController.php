<?php

namespace App\Http\Controllers;

use App\Models\CareerAdvice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CareerAdviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CareerAdvice::query();

        // Apply filters if provided
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        if ($request->has('level') && $request->level) {
            $query->byLevel($request->level);
        }

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('content', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'LIKE', "%{$searchTerm}%");
            });
        }

        $articles = $query->published()->orderBy('published_at', 'desc')->paginate(12);

        return view('career-advice.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', CareerAdvice::class);

        return view('career-advice.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', CareerAdvice::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'author' => 'nullable|string|max:255',
            'topic_category' => 'required|string|in:general,resume,interview,career-change,promotion,skills,networking,salary',
            'career_level' => 'required|string|in:all,entry-level,mid-level,senior,executive',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'action_steps' => 'nullable|array',
            'action_steps.*' => 'string|max:500',
            'estimated_reading_time' => 'nullable|string|max:20',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|array',
            'meta_keywords.*' => 'string|max:50',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['author'] = $validated['author'] ?? Auth::user()->name ?? 'Admin';
        $validated['published_at'] = $validated['is_published'] ? now() : null;

        $article = CareerAdvice::create($validated);

        return redirect()->route('career-advice.show', $article->slug)
                         ->with('success', 'Career advice article created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $article = CareerAdvice::where('slug', $slug)->firstOrFail();

        // Increment view count
        $article->incrementViews();

        // Get related articles
        $relatedArticles = CareerAdvice::where('topic_category', $article->topic_category)
                                       ->where('id', '!=', $article->id)
                                       ->published()
                                       ->take(3)
                                       ->get();

        return view('career-advice.show', compact('article', 'relatedArticles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CareerAdvice $careerAdvice)
    {
        $this->authorize('update', $careerAdvice);

        return view('career-advice.edit', compact('careerAdvice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CareerAdvice $careerAdvice)
    {
        $this->authorize('update', $careerAdvice);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'author' => 'nullable|string|max:255',
            'topic_category' => 'required|string|in:general,resume,interview,career-change,promotion,skills,networking,salary',
            'career_level' => 'required|string|in:all,entry-level,mid-level,senior,executive',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'action_steps' => 'nullable|array',
            'action_steps.*' => 'string|max:500',
            'estimated_reading_time' => 'nullable|string|max:20',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|array',
            'meta_keywords.*' => 'string|max:50',
        ]);

        $validated['published_at'] = $validated['is_published'] ? now() : null;

        $careerAdvice->update($validated);

        return redirect()->route('career-advice.show', $careerAdvice->slug)
                         ->with('success', 'Career advice article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CareerAdvice $careerAdvice)
    {
        $this->authorize('delete', $careerAdvice);

        $careerAdvice->delete();

        return redirect()->route('career-advice.index')
                         ->with('success', 'Career advice article deleted successfully.');
    }

    /**
     * Get featured articles
     */
    public function featured()
    {
        $featuredArticles = CareerAdvice::featured()->published()->orderBy('published_at', 'desc')->get();

        return view('career-advice.featured', compact('featuredArticles'));
    }

    /**
     * Get articles by category
     */
    public function byCategory($category)
    {
        $articles = CareerAdvice::byCategory($category)->published()->orderBy('published_at', 'desc')->paginate(12);

        return view('career-advice.category', compact('articles', 'category'));
    }

    /**
     * Get articles by career level
     */
    public function byLevel($level)
    {
        $articles = CareerAdvice::byLevel($level)->published()->orderBy('published_at', 'desc')->paginate(12);

        return view('career-advice.level', compact('articles', 'level'));
    }
}
