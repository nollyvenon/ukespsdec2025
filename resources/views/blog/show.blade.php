<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <article>
                        <header class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $post->title }}</h1>
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
                                <div class="mb-6">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                         alt="{{ $post->title }}" 
                                         class="w-full h-64 object-cover rounded">
                                </div>
                            @endif
                        </header>

                        <div class="prose max-w-none">
                            {{ Str::markdown($post->content) }}
                        </div>
                    </article>

                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Related Posts</h3>
                        @if($relatedPosts->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($relatedPosts as $relatedPost)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h4 class="font-semibold mb-2">
                                            <a href="{{ route('blog.show', $relatedPost->slug) }}" 
                                               class="hover:text-indigo-600">
                                                {{ Str::limit($relatedPost->title, 40) }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-600 mb-2">
                                            {{ Str::limit(strip_tags($relatedPost->excerpt ?? $relatedPost->content), 100) }}
                                        </p>
                                        <div class="text-xs text-gray-500">
                                            {{ $relatedPost->published_at->format('M j, Y') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600">No related posts found.</p>
                        @endif
                    </div>

                    <!-- Comments section would go here -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>