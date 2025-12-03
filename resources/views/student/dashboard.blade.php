<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-blue-800">Courses Enrolled</h3>
                            <p class="text-3xl font-bold mt-2">{{ $totalCoursesEnrolled }}</p>
                        </div>
                        <div class="bg-green-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-green-800">Events Registered</h3>
                            <p class="text-3xl font-bold mt-2">{{ $totalEventsRegistered }}</p>
                        </div>
                        <div class="bg-purple-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-purple-800">Job Applications</h3>
                            <p class="text-3xl font-bold mt-2">{{ $totalJobApplications }}</p>
                        </div>
                    </div>

                    <!-- Recent Items -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Enrolled Courses -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-bold mb-4">Enrolled Courses</h3>
                            @if($enrolledCourses->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($enrolledCourses as $course)
                                        <li class="border-b pb-3">
                                            <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:underline">
                                                <h4 class="font-semibold">{{ $course->title }}</h4>
                                            </a>
                                            <p class="text-sm text-gray-600">Instructor: {{ $course->instructor->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $course->duration }} weeks • {{ $course->level }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-600">No courses enrolled yet.</p>
                            @endif
                        </div>

                        <!-- Registered Events -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-bold mb-4">Registered Events</h3>
                            @if($registeredEvents->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($registeredEvents as $event)
                                        <li class="border-b pb-3">
                                            <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:underline">
                                                <h4 class="font-semibold">{{ $event->title }}</h4>
                                            </a>
                                            <p class="text-sm text-gray-600">{{ $event->location }}</p>
                                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($event->start_date)->format('M j, Y') }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-600">No events registered yet.</p>
                            @endif
                        </div>

                        <!-- Applied Jobs -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-bold mb-4">Applied Jobs</h3>
                            @if($appliedJobs->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($appliedJobs as $job)
                                        <li class="border-b pb-3">
                                            <a href="{{ route('jobs.show', $job) }}" class="text-blue-600 hover:underline">
                                                <h4 class="font-semibold">{{ $job->title }}</h4>
                                            </a>
                                            <p class="text-sm text-gray-600">{{ $job->location }} • {{ $job->job_type }}</p>
                                            <p class="text-xs text-gray-500">Applied {{ $job->pivot->created_at->diffForHumans() }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-600">No job applications submitted yet.</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <a href="{{ route('courses.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Browse Courses
                        </a>
                        <a href="{{ route('events.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Browse Events
                        </a>
                        <a href="{{ route('jobs.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Find Jobs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>