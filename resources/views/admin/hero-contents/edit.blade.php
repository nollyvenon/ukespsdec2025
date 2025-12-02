<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Hero Content') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Edit Hero Content</h1>

                    <form method="POST" action="{{ route('admin.hero-contents.update', $heroContent) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 mb-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                                <input type="text" name="title" id="title" 
                                       value="{{ old('title', $heroContent->title) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('title') border-red-500 @enderror"
                                       required>
                                @error('title')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="subtitle" class="block text-sm font-medium text-gray-700">Subtitle</label>
                                <textarea name="subtitle" id="subtitle" rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('subtitle') border-red-500 @enderror">{{ old('subtitle', $heroContent->subtitle) }}</textarea>
                                @error('subtitle')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="content_type" class="block text-sm font-medium text-gray-700">Content Type *</label>
                                <select name="content_type" id="content_type" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('content_type') border-red-500 @enderror"
                                        required onchange="toggleContentTypeFields()">
                                    <option value="">Select Content Type</option>
                                    <option value="image" {{ old('content_type', $heroContent->content_type) == 'image' ? 'selected' : '' }}>Image</option>
                                    <option value="video" {{ old('content_type', $heroContent->content_type) == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="youtube" {{ old('content_type', $heroContent->content_type) == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                </select>
                                @error('content_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="content_url_field" class="{{ in_array(old('content_type', $heroContent->content_type), ['image', 'video']) ? '' : 'hidden' }}">
                                <label for="content_url" class="block text-sm font-medium text-gray-700">Content File</label>
                                <input type="file" name="content_url" id="content_url"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('content_url') border-red-500 @enderror"
                                       accept=".jpg,.jpeg,.png,.gif,.mp4,.mov,.avi">
                                <p class="mt-1 text-sm text-gray-500">Upload an image or video file (leave empty to keep current file)</p>
                                
                                @if($heroContent->content_url)
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-600">Current file:</p>
                                        @if(in_array($heroContent->content_type, ['image']))
                                            <img src="{{ asset('storage/' . $heroContent->content_url) }}" alt="Current Hero Image" class="mt-1 h-32 object-contain">
                                        @elseif(in_array($heroContent->content_type, ['video']))
                                            <video controls class="mt-1 h-32">
                                                <source src="{{ asset('storage/' . $heroContent->content_url) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @endif
                                    </div>
                                @endif
                                
                                @error('content_url')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="youtube_url_field" class="{{ old('content_type', $heroContent->content_type) == 'youtube' ? '' : 'hidden' }}">
                                <label for="youtube_url" class="block text-sm font-medium text-gray-700">YouTube URL</label>
                                <input type="url" name="youtube_url" id="youtube_url" 
                                       value="{{ old('youtube_url', $heroContent->youtube_url) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('youtube_url') border-red-500 @enderror">
                                <p class="mt-1 text-sm text-gray-500">Enter the full YouTube URL (e.g., https://www.youtube.com/watch?v=...)</p>
                                @error('youtube_url')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="button_text" class="block text-sm font-medium text-gray-700">Button Text</label>
                                <input type="text" name="button_text" id="button_text" 
                                       value="{{ old('button_text', $heroContent->button_text) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('button_text') border-red-500 @enderror">
                                @error('button_text')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="button_url" class="block text-sm font-medium text-gray-700">Button URL</label>
                                <input type="url" name="button_url" id="button_url" 
                                       value="{{ old('button_url', $heroContent->button_url) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('button_url') border-red-500 @enderror">
                                @error('button_url')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                                <input type="number" name="order" id="order" 
                                       value="{{ old('order', $heroContent->order) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('order') border-red-500 @enderror">
                                <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                                @error('order')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ old('is_active', $heroContent->is_active) ? 'checked' : '' }}>
                                    <span class="ml-2 block text-sm text-gray-900">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.hero-contents.index') }}" 
                               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                Update Hero Content
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleContentTypeFields() {
            const contentType = document.getElementById('content_type').value;
            const contentUrlField = document.getElementById('content_url_field');
            const youtubeUrlField = document.getElementById('youtube_url_field');

            // Hide all conditional fields first
            contentUrlField.classList.add('hidden');
            youtubeUrlField.classList.add('hidden');

            // Show the appropriate field
            if (contentType === 'image' || contentType === 'video') {
                contentUrlField.classList.remove('hidden');
            } else if (contentType === 'youtube') {
                youtubeUrlField.classList.remove('hidden');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleContentTypeFields();
        });
    </script>
</x-app-layout>