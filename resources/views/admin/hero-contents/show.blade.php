<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Hero Content') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Hero Content Details</h1>
                        <div>
                            <a href="{{ route('admin.hero-contents.edit', $heroContent) }}"
                               class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 mr-2">
                                Edit
                            </a>
                            <a href="{{ route('admin.hero-contents.index') }}"
                               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Title</h3>
                            <p class="mt-1 text-gray-600">{{ $heroContent->title }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Subtitle</h3>
                            <p class="mt-1 text-gray-600">{{ $heroContent->subtitle ?: 'N/A' }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Content Type</h3>
                            <p class="mt-1 text-gray-600">{{ Str::title($heroContent->content_type) }}</p>
                        </div>

                        @if($heroContent->content_type === 'youtube')
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">YouTube URL</h3>
                                <p class="mt-1 text-gray-600">
                                    <a href="{{ $heroContent->youtube_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $heroContent->youtube_url }}
                                    </a>
                                </p>
                            </div>
                        @elseif($heroContent->content_url)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Content Preview</h3>
                                <div class="mt-2">
                                    @if($heroContent->content_type === 'image')
                                        <img src="{{ asset('storage/' . $heroContent->content_url) }}" alt="{{ $heroContent->title }}" class="max-h-64 object-contain">
                                    @elseif($heroContent->content_type === 'video')
                                        <video controls class="max-h-64">
                                            <source src="{{ asset('storage/' . $heroContent->content_url) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Button Text</h3>
                            <p class="mt-1 text-gray-600">{{ $heroContent->button_text ?: 'N/A' }}</p>
                        </div>

                        @if($heroContent->button_url)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Button URL</h3>
                                <p class="mt-1 text-gray-600">
                                    <a href="{{ $heroContent->button_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $heroContent->button_url }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Order</h3>
                                <p class="mt-1 text-gray-600">{{ $heroContent->order }}</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Status</h3>
                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full 
                                    {{ $heroContent->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $heroContent->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Created At</h3>
                            <p class="mt-1 text-gray-600">{{ $heroContent->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Updated At</h3>
                            <p class="mt-1 text-gray-600">{{ $heroContent->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>