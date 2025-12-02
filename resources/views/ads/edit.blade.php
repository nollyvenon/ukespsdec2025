<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Edit Advertisement</h1>

                    <form method="POST" action="{{ route('ads.update', $ad) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" name="title" id="title" 
                                       value="{{ old('title', $ad->title) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="ad_type" class="block text-sm font-medium text-gray-700">Ad Type</label>
                                <select name="ad_type" id="ad_type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required>
                                    <option value="image" {{ old('ad_type', $ad->ad_type) === 'image' ? 'selected' : '' }}>Image</option>
                                    <option value="text" {{ old('ad_type', $ad->ad_type) === 'text' ? 'selected' : '' }}>Text</option>
                                    <option value="banner" {{ old('ad_type', $ad->ad_type) === 'banner' ? 'selected' : '' }}>Banner</option>
                                    <option value="video" {{ old('ad_type', $ad->ad_type) === 'video' ? 'selected' : '' }}>Video</option>
                                </select>
                                @error('ad_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                      required>{{ old('description', $ad->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                            <input type="url" name="url" id="url" 
                                   value="{{ old('url', $ad->url) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   required>
                            @error('url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                                <select name="position" id="position"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required>
                                    <option value="top" {{ old('position', $ad->position) === 'top' ? 'selected' : '' }}>Top Banner</option>
                                    <option value="below_header" {{ old('position', $ad->position) === 'below_header' ? 'selected' : '' }}>Below Header</option>
                                    <option value="above_footer" {{ old('position', $ad->position) === 'above_footer' ? 'selected' : '' }}>Above Footer</option>
                                    <option value="left_sidebar" {{ old('position', $ad->position) === 'left_sidebar' ? 'selected' : '' }}>Left Sidebar</option>
                                    <option value="right_sidebar" {{ old('position', $ad->position) === 'right_sidebar' ? 'selected' : '' }}>Right Sidebar</option>
                                    <option value="top_slider" {{ old('position', $ad->position) === 'top_slider' ? 'selected' : '' }}>Homepage Slider</option>
                                </select>
                                @error('position')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required>
                                    <option value="active" {{ old('status', $ad->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="pending" {{ old('status', $ad->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="inactive" {{ old('status', $ad->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date" 
                                       value="{{ old('start_date', $ad->start_date) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       required>
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date" 
                                       value="{{ old('end_date', $ad->end_date) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       required>
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="daily_budget" class="block text-sm font-medium text-gray-700">Daily Budget ($)</label>
                            <input type="number" step="0.01" name="daily_budget" id="daily_budget" 
                                   value="{{ old('daily_budget', $ad->daily_budget) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('daily_budget')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                            @if($ad->image_url)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $ad->image_url) }}" alt="Current Image" class="h-32 object-contain">
                                </div>
                            @endif
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                            <span>Change file</span>
                                            <input id="image" name="image" type="file" class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 10MB
                                    </p>
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slider Specific Fields -->
                        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Slider Settings</h3>
                            
                            <div class="mt-4">
                                <label for="slider_title" class="block text-sm font-medium text-gray-700">Slider Title (optional)</label>
                                <input type="text" name="slider_title" id="slider_title" 
                                       value="{{ old('slider_title', $ad->slider_title) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('slider_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="slider_description" class="block text-sm font-medium text-gray-700">Slider Description (optional)</label>
                                <textarea name="slider_description" id="slider_description" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('slider_description', $ad->slider_description) }}</textarea>
                                @error('slider_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="video_url" class="block text-sm font-medium text-gray-700">Video URL (optional)</label>
                                <input type="url" name="video_url" id="video_url" 
                                       value="{{ old('video_url', $ad->video_url) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('video_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="is_slider_featured" class="flex items-center">
                                    <input type="checkbox" name="is_slider_featured" id="is_slider_featured" 
                                           value="1" {{ old('is_slider_featured', $ad->is_slider_featured) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Include in Homepage Slider</span>
                                </label>
                            </div>

                            <div class="mt-4">
                                <label for="slider_order" class="block text-sm font-medium text-gray-700">Slider Order</label>
                                <input type="number" name="slider_order" id="slider_order" 
                                       value="{{ old('slider_order', $ad->slider_order) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('slider_order')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('admin.ads.index') }}" 
                               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                Update Ad
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>