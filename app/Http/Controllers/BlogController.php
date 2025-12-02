<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the blog posts.
     */
    public function index()
    {
        $posts = BlogPost::where('is_published', true)
            ->with('user', 'category')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        $categories = BlogCategory::where('is_active', true)->get();

        return view('blog.index', compact('posts', 'categories'));
    }

    /**
     * Display the specified blog post.
     */
    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->with('user', 'category', 'approvedComments.user')
            ->firstOrFail();

        // Increment the view count
        $post->increment('view_count');

        $relatedPosts = BlogPost::where('blog_category_id', $post->blog_category_id)
            ->where('id', '!=', $post->id)
            ->where('is_published', true)
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    /**
     * Display blog posts by category.
     */
    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $posts = BlogPost::where('blog_category_id', $category->id)
            ->where('is_published', true)
            ->with('user', 'category')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        $categories = BlogCategory::where('is_active', true)->get();

        return view('blog.index', compact('posts', 'categories', 'category'));
    }

    /**
     * Search blog posts.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $posts = BlogPost::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%");
            })
            ->with('user', 'category')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        $categories = BlogCategory::where('is_active', true)->get();

        return view('blog.search', compact('posts', 'categories', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', BlogPost::class);

        $categories = BlogCategory::where('is_active', true)->get();
        return view('blog.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', BlogPost::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_published' => 'boolean',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($request->title);
        $data['published_at'] = $request->is_published ? now() : null;

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('blog', 'public');
            $data['featured_image'] = $path;
        }

        $post = BlogPost::create($data);

        return redirect()->route('blog.show', $post->slug)->with('success', 'Blog post created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();
        $this->authorize('update', $post);

        $categories = BlogCategory::where('is_active', true)->get();
        return view('blog.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_published' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        if ($request->is_published && !$post->published_at) {
            $data['published_at'] = now();
        } elseif (!$request->is_published) {
            $data['published_at'] = null;
        }

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('blog', 'public');
            $data['featured_image'] = $path;
        }

        $post->update($data);

        return redirect()->route('blog.show', $post->slug)->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('blog.index')->with('success', 'Blog post deleted successfully.');
    }
}
