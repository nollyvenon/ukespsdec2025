@extends('layouts.public')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Advanced Search Form -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Search Courses</h2>
                    <form method="GET" action="{{ route('courses.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Keyword</label>
                            <input type="text" name="search" id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Title, description..."
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
                            <label for="duration_min" class="block text-sm font-medium text-gray-700 mb-1">Min Duration (weeks)</label>
                            <input type="number" name="duration_min" id="duration_min"
                                   value="{{ request('duration_min') }}"
                                   placeholder="1"
                                   min="1"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="duration_max" class="block text-sm font-medium text-gray-700 mb-1">Max Duration (weeks)</label>
                            <input type="number" name="duration_max" id="duration_max"
                                   value="{{ request('duration_max') }}"
                                   placeholder="52"
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
                                Search
                            </button>
                        </div>

                        <div class="flex items-end">
                            <a href="{{ route('courses.index') }}"
                               class="w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Search Results Summary -->
                @if(request()->has('search') || request()->hasAny(['level', 'duration_min', 'duration_max', 'start_date_from', 'start_date_to', 'location', 'instructor']))
                    <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-medium text-blue-800">Search Results</h3>
                        <p class="text-sm text-blue-600">Found {{ $courses->total() }} course{{ $courses->total() != 1 ? 's' : '' }}
                        @if(request('search')) for "{{ request('search') }}" @endif
                        @if(request('level')) at "{{ ucfirst(str_replace('_', ' ', request('level'))) }}" level @endif
                        @if(request('location')) in "{{ request('location') }}" @endif
                        @if(request('instructor')) by "{{ request('instructor') }}" @endif
                        </p>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">All Courses</h1>
                    @auth
                        <a href="{{ route('courses.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Create Course
                        </a>
                    @endauth
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($courses as $course)
                        <div class="border rounded-lg overflow-hidden shadow-md {{ $course->is_premium ? 'border-yellow-400 border-2' : '' }}">
                            @if($course->course_image)
                                <img src="{{ Storage::url($course->course_image) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="bg-gray-200 w-full h-48 flex items-center justify-center">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="text-xl font-bold mb-2 {{ $course->is_premium ? 'text-yellow-600' : '' }}">
                                    {{ $course->is_premium ? '⭐ ' : '' }}{{ $course->title }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-1">Level: {{ ucfirst($course->level) }}</p>
                                <p class="text-gray-600 text-sm mb-1">Duration: {{ $course->duration }} weeks</p>
                                <p class="text-gray-700 mb-4">{{ Str::limit($course->description, 100) }}</p>

                                <div class="flex justify-between items-center">
                                    <div class="flex flex-col">
                                        @if($course->is_premium)
                                            <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full mb-1">
                                                Premium
                                            </span>
                                        @endif
                                        <span class="inline-block bg-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'green' : ($course->course_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'green' : ($course->course_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                            {{ ucfirst($course->course_status) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('courses.show', $course) }}" class="text-green-600 hover:underline">View Details</a>
                                </div>

                                @if($course->course_status === 'published' && $course->start_date > now())
                                    <div class="mt-3">
                                        <a href="{{ route('courses.enroll', $course) }}" class="w-full bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded text-center inline-block">Enroll Now</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-500">No courses available at the moment.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $courses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection