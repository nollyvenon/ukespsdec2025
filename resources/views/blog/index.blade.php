<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($category) ? $category->name . ' - Blog' : 'Blog' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col lg:flex-row gap-8">
                        <div class="lg:w-3/4">
                            @if($posts->count() > 0)
                                @foreach($posts as $post)
                                    <article class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                                        <h2 class="text-2xl font-bold text-gray-800 mb-2">
                                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-indigo-600">
                                                {{ $post->title }}
                                            </a>
                                        </h2>
                                        <div class="flex items-center text-sm text-gray-500 mb-4">
                                            <span>By {{ $post->user->name ?? 'Anonymous' }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $post->published_at->format('F j, Y') }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $post->view_count }} views</span>
                                            @if($post->category)
                                                <span class="mx-2">•</span>
                                                <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs">
                                                    {{ $post->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($post->featured_image)
                                            <div class="mb-4">
                                                <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                                     alt="{{ $post->title }}" 
                                                     class="w-full h-48 object-cover rounded">
                                            </div>
                                        @endif
                                        <p class="text-gray-600 mb-4">
                                            {{ Str::limit(strip_tags($post->excerpt ?? $post->content), 200) }}
                                        </p>
                                        <a href="{{ route('blog.show', $post->slug) }}" 
                                           class="text-indigo-600 hover:text-indigo-800 font-medium">
                                            Read More →
                                        </a>
                                    </article>
                                @endforeach

                                <div class="mt-8">
                                    {{ $posts->links() }}
                                </div>
                            @else
                                <p class="text-gray-600">No blog posts found.</p>
                            @endif
                        </div>

                        <div class="lg:w-1/4">
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Search</h3>
                                <form method="GET" action="{{ route('blog.search') }}">
                                    <div class="flex">
                                        <input type="text" 
                                               name="q" 
                                               placeholder="Search posts..." 
                                               class="flex-1 rounded-l border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <button type="submit" 
                                                class="bg-indigo-600 text-white px-4 py-2 rounded-r hover:bg-indigo-700">
                                            Search
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="mt-6 bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Categories</h3>
                                <ul class="space-y-2">
                                    <li>
                                        <a href="{{ route('blog.index') }}" 
                                           class="block py-1 hover:text-indigo-600 {{ !isset($category) ? 'font-medium' : '' }}">
                                            All Posts
                                        </a>
                                    </li>
                                    @foreach($categories as $cat)
                                        <li>
                                            <a href="{{ route('blog.category', $cat->slug) }}" 
                                               class="block py-1 hover:text-indigo-600 {{ isset($category) && $category->id == $cat->id ? 'font-medium' : '' }}">
                                                {{ $cat->name }}
                                                <span class="text-xs text-gray-500">({{ $cat->blogPosts()->where('is_published', true)->count() }})</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            @can('create', App\Models\BlogPost::class)
                                <div class="mt-6 bg-gray-50 p-6 rounded-lg border border-gray-200">
                                    <a href="{{ route('blog.create') }}"
                                       class="block text-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                        Create New Post
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>