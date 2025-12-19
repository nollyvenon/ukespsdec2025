<?php

namespace App\Http\Controllers;

use App\Models\InterviewPreparationTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InterviewPreparationToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = InterviewPreparationTool::published();

        // Apply filters
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('difficulty')) {
            $query->byDifficulty($request->difficulty);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%")
                  ->orWhere('summary', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'popular':
                    $query->orderBy('view_count', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'featured':
                    $query->orderBy('is_featured', 'desc')->latest();
                    break;
                case 'alphabetical':
                    $query->orderBy('title');
                    break;
                default:
                    $query->orderBy('view_count', 'desc');
                    break;
            }
        } else {
            $query->orderBy('view_count', 'desc');
        }

        $tools = $query->paginate(12);

        return view('interview-tools.index', compact('tools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()?->can('administrate')) {
            abort(403);
        }

        return view('interview-tools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()?->can('administrate')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category' => 'required|in:general,common-questions,industry-specific,behavioral,technical,phone-interview,video-interview,in-person-interview,assessment-test,case-study',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'related_positions' => 'nullable|array',
            'related_positions.*' => 'string|max:100',
            'question_examples' => 'nullable|array',
            'question_examples.*' => 'string|max:500',
            'answer_templates' => 'nullable|array',
            'answer_templates.*' => 'string|max:1000',
            'resources' => 'nullable|array',
            'resources.*' => 'string|max:500',
            'video_resources' => 'nullable|array',
            'video_resources.*' => 'string|max:500',
            'practice_scenarios' => 'nullable|array',
            'practice_scenarios.*' => 'string|max:1000',
            'skills_addressed' => 'nullable|array',
            'skills_addressed.*' => 'string|max:50',
            'interview_types' => 'nullable|array',
            'interview_types.*' => 'string|max:50',
            'estimated_preparation_time' => 'nullable|string|max:20',
            'author' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['author'] = $validated['author'] ?? Auth::user()->name;
        $validated['published_at'] = $validated['is_published'] ? now() : null;

        $tool = InterviewPreparationTool::create($validated);

        return redirect()->route('interview-tools.show', $tool->id)
                         ->with('success', 'Interview preparation tool created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(InterviewPreparationTool $interviewPreparationTool)
    {
        if (!$interviewPreparationTool->is_published && !Auth::user()?->can('administrate')) {
            abort(404);
        }

        // Increment view count
        $interviewPreparationTool->incrementViews();

        // Get related tools
        $relatedTools = $interviewPreparationTool->getRelatedTools(5);

        return view('interview-tools.show', compact('interviewPreparationTool', 'relatedTools'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InterviewPreparationTool $interviewPreparationTool)
    {
        if (!Auth::user()?->can('administrate')) {
            abort(403);
        }

        return view('interview-tools.edit', compact('interviewPreparationTool'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InterviewPreparationTool $interviewPreparationTool)
    {
        if (!Auth::user()?->can('administrate')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'summary' => 'nullable|string|max:500',
            'content' => 'sometimes|string',
            'category' => 'sometimes|in:general,common-questions,industry-specific,behavioral,technical,phone-interview,video-interview,in-person-interview,assessment-test,case-study',
            'difficulty_level' => 'sometimes|in:beginner,intermediate,advanced',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'related_positions' => 'nullable|array',
            'related_positions.*' => 'string|max:100',
            'question_examples' => 'nullable|array',
            'question_examples.*' => 'string|max:500',
            'answer_templates' => 'nullable|array',
            'answer_templates.*' => 'string|max:1000',
            'resources' => 'nullable|array',
            'resources.*' => 'string|max:500',
            'video_resources' => 'nullable|array',
            'video_resources.*' => 'string|max:500',
            'practice_scenarios' => 'nullable|array',
            'practice_scenarios.*' => 'string|max:1000',
            'skills_addressed' => 'nullable|array',
            'skills_addressed.*' => 'string|max:50',
            'interview_types' => 'nullable|array',
            'interview_types.*' => 'string|max:50',
            'estimated_preparation_time' => 'nullable|string|max:20',
            'author' => 'nullable|string|max:255',
        ]);

        if (isset($validated['is_published']) && $validated['is_published'] && !$interviewPreparationTool->published_at) {
            $validated['published_at'] = now();
        }

        $interviewPreparationTool->update($validated);

        return redirect()->route('interview-tools.show', $interviewPreparationTool->id)
                         ->with('success', 'Interview preparation tool updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InterviewPreparationTool $interviewPreparationTool)
    {
        if (!Auth::user()?->can('administrate')) {
            abort(403);
        }

        $interviewPreparationTool->delete();

        return redirect()->route('interview-tools.index')
                         ->with('success', 'Interview preparation tool deleted successfully!');
    }

    /**
     * Get tools by category
     */
    public function byCategory($category)
    {
        $tools = InterviewPreparationTool::byCategory($category)
                                         ->published()
                                         ->orderBy('view_count', 'desc')
                                         ->paginate(12);

        return view('interview-tools.by-category', compact('tools', 'category'));
    }

    /**
     * Get tools by difficulty level
     */
    public function byDifficulty($difficulty)
    {
        $tools = InterviewPreparationTool::byDifficulty($difficulty)
                                          ->published()
                                          ->orderBy('view_count', 'desc')
                                          ->paginate(12);

        return view('interview-tools.by-difficulty', compact('tools', 'difficulty'));
    }

    /**
     * Like a preparation tool
     */
    public function like(InterviewPreparationTool $tool)
    {
        $tool->incrementLikes();

        return response()->json([
            'success' => true,
            'like_count' => $tool->fresh()->like_count,
        ]);
    }

    /**
     * Get practice interview simulation
     */
    public function getPracticeInterview(InterviewPreparationTool $tool)
    {
        if (!$tool->is_published && !Auth::user()?->can('administrate')) {
            abort(404);
        }

        // Get practice scenarios for this tool
        $scenarios = $tool->practice_scenarios ?: [];
        $questions = $tool->question_examples ?: [];

        // Combine both if available
        $combinedScenarios = array_merge($scenarios, $questions);

        return response()->json([
            'success' => true,
            'data' => [
                'title' => $tool->title,
                'description' => $tool->summary ?: $tool->content,
                'scenarios' => $combinedScenarios,
                'estimated_duration' => $tool->estimated_reading_time_in_minutes,
            ],
        ]);
    }
}
