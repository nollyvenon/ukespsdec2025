@extends('layouts.public')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6">
                <!-- Advanced Search Form -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Find Your Perfect Course</h2>
                    <form method="GET" action="{{ route('courses.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
                            <input type="text" name="search" id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Course title, skills..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                            <select name="level" id="level"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All Levels</option>
                                @foreach(['beginner', 'intermediate', 'advanced', 'all_levels'] as $level)
                                    <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $level)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="duration_min" class="block text-sm font-medium text-gray-700 mb-1">Min Duration</label>
                            <input type="number" name="duration_min" id="duration_min"
                                   value="{{ request('duration_min') }}"
                                   placeholder="1 week"
                                   min="1"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="duration_max" class="block text-sm font-medium text-gray-700 mb-1">Max Duration</label>
                            <input type="number" name="duration_max" id="duration_max"
                                   value="{{ request('duration_max') }}"
                                   placeholder="52 weeks"
                                   min="1"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="start_date_from" class="block text-sm font-medium text-gray-700 mb-1">Start From</label>
                            <input type="date" name="start_date_from" id="start_date_from"
                                   value="{{ request('start_date_from') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="start_date_to" class="block text-sm font-medium text-gray-700 mb-1">Start To</label>
                            <input type="date" name="start_date_to" id="start_date_to"
                                   value="{{ request('start_date_to') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" name="location" id="location"
                                   value="{{ request('location') }}"
                                   placeholder="City, state..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="instructor" class="block text-sm font-medium text-gray-700 mb-1">Instructor</label>
                            <input type="text" name="instructor" id="instructor"
                                   value="{{ request('instructor') }}"
                                   placeholder="Instructor name..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded">
                                Search Courses
                            </button>
                        </div>

                        <div class="flex items-end">
                            <a href="{{ route('courses.index') }}"
                               class="w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Search Results Summary -->
                @if(request()->has('search') || request()->hasAny(['level', 'duration_min', 'duration_max', 'start_date_from', 'start_date_to', 'location', 'instructor']))
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-medium text-blue-800">Search Results</h3>
                        <p class="text-sm text-blue-600">Displaying {{ $courses->count() }} of {{ $courses->total() }} course{{ $courses->total() != 1 ? 's' : '' }}
                        @if(request('search')) matching "{{ request('search') }}" @endif
                        @if(request('level')) for "{{ ucfirst(str_replace('_', ' ', request('level'))) }}" level @endif
                        @if(request('location')) in "{{ request('location') }}" @endif
                        @if(request('instructor')) by "{{ request('instructor') }}" @endif
                        </p>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Course Catalog</h1>
                        <p class="text-gray-600">{{ $courses->total() }} courses available</p>
                    </div>
                    @auth
                        <a href="{{ route('courses.create') }}" class="mt-4 sm:mt-0 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                            <i class="fas fa-plus-circle mr-2"></i> Create New Course
                        </a>
                    @endauth
                </div>

                @if($courses->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @foreach($courses as $course)
                            <div class="border border-gray-200 rounded-lg p-6 hover:border-indigo-300 transition duration-300 {{ $course->is_premium ? 'ring-2 ring-yellow-400 ring-opacity-50' : '' }}">
                                <div class="flex">
                                    <div class="flex-shrink-0 mr-6">
                                        @if($course->course_image)
                                            <img src="{{ Storage::url($course->course_image) }}" alt="{{ $course->title }}" class="w-32 h-24 object-cover rounded">
                                        @else
                                            <div class="bg-gray-200 w-32 h-24 flex items-center justify-center rounded">
                                                <i class="fas fa-landmark text-gray-500 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-900 {{ $course->is_premium ? 'text-yellow-600' : '' }}">
                                                    {{ $course->is_premium ? '⭐ ' : '' }}
                                                    <a href="{{ route('courses.show', $course) }}" class="hover:text-indigo-600">
                                                        {{ $course->title }}
                                                    </a>
                                                </h3>
                                                <p class="text-gray-600 mt-1">By {{ $course->instructor->name }}</p>
                                            </div>
                                            @if($course->is_premium)
                                                <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-medium">
                                                    Premium
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-3 flex flex-wrap gap-2 text-sm">
                                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                <i class="fas fa-chart-line mr-1"></i> {{ ucfirst($course->level) }}
                                            </span>
                                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                <i class="fas fa-clock mr-1"></i> {{ $course->duration }} weeks
                                            </span>
                                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                <i class="fas fa-calendar mr-1"></i> Starts {{ \Carbon\Carbon::parse($course->start_date)->format('M j, Y') }}
                                            </span>
                                        </div>

                                        <p class="text-gray-700 mt-3">{{ Str::limit($course->description, 150) }}</p>

                                        <div class="mt-4 flex justify-between items-center">
                                            <span class="inline-block bg-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'green' : ($course->course_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'green' : ($course->course_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                                {{ ucfirst($course->course_status) }}
                                            </span>

                                            <div>
                                                @if($course->course_status === 'published' && $course->start_date > now())
                                                    <a href="{{ route('courses.enroll', $course) }}" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm font-medium">
                                                        Enroll Now
                                                    </a>
                                                @else
                                                    <a href="{{ route('courses.show', $course) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg text-sm font-medium">
                                                        View Details
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $courses->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-graduation-cap text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">No courses found</h3>
                        <p class="text-gray-500 mb-6">Try adjusting your search criteria or browse all courses</p>
                        <a href="{{ route('courses.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Browse All Courses
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection