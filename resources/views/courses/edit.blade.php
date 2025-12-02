<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Course') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Edit Course: {{ $course->title }}</h1>
                    
                    <form method="POST" action="{{ route('courses.update', $course) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('description', $course->description) }}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700">Duration (in weeks)</label>
                                <input type="number" name="duration" id="duration" value="{{ old('duration', $course->duration) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            
                            <div>
                                <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                                <select name="level" id="level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="beginner" {{ old('level', $course->level) === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('level', $course->level) === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('level', $course->level) === 'advanced' ? 'selected' : '' }}>Advanced</option>
                                    <option value="all_levels" {{ old('level', $course->level) === 'all_levels' ? 'selected' : '' }}>All Levels</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ \Carbon\Carbon::parse(old('start_date', $course->start_date))->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ \Carbon\Carbon::parse(old('end_date', $course->end_date))->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="course_image" class="block text-sm font-medium text-gray-700">Course Image (Optional)</label>
                            <input type="file" name="course_image" id="course_image" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @if($course->course_image)
                                <p class="mt-1 text-sm text-gray-600">Current image: <a href="{{ Storage::url($course->course_image) }}" target="_blank" class="text-blue-600 hover:underline">View</a></p>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="max_enrollment" class="block text-sm font-medium text-gray-700">Max Enrollment (Optional)</label>
                                <input type="number" name="max_enrollment" id="max_enrollment" value="{{ old('max_enrollment', $course->max_enrollment) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            
                            <div>
                                <label for="course_status" class="block text-sm font-medium text-gray-700">Course Status</label>
                                <select name="course_status" id="course_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="draft" {{ old('course_status', $course->course_status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('course_status', $course->course_status) === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="ongoing" {{ old('course_status', $course->course_status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('course_status', $course->course_status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('course_status', $course->course_status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="prerequisites" class="block text-sm font-medium text-gray-700">Prerequisites (Optional)</label>
                            <textarea name="prerequisites" id="prerequisites" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('prerequisites', $course->prerequisites) }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="syllabus" class="block text-sm font-medium text-gray-700">Syllabus (Optional)</label>
                            <textarea name="syllabus" id="syllabus" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('syllabus', $course->syllabus) }}</textarea>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('courses.show', $course) }}" class="mr-4 px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Update Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>