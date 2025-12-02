<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8 text-center">Welcome to the Events, Courses & Jobs Management Portal</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <!-- Events Card -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg p-6 shadow-lg">
                            <div class="flex items-center">
                                <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">Events</h3>
                                    <p class="text-lg">{{ $eventsCount }} Active Events</p>
                                </div>
                            </div>
                            <a href="{{ route('portal.events') }}" class="mt-4 inline-block bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition">Browse Events</a>
                        </div>
                        
                        <!-- Courses Card -->
                        <div class="bg-gradient-to-r from-green-500 to-green-700 text-white rounded-lg p-6 shadow-lg">
                            <div class="flex items-center">
                                <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">Courses</h3>
                                    <p class="text-lg">{{ $coursesCount }} Active Courses</p>
                                </div>
                            </div>
                            <a href="{{ route('portal.courses') }}" class="mt-4 inline-block bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition">Browse Courses</a>
                        </div>
                        
                        <!-- Jobs Card -->
                        <div class="bg-gradient-to-r from-purple-500 to-purple-700 text-white rounded-lg p-6 shadow-lg">
                            <div class="flex items-center">
                                <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">Jobs</h3>
                                    <p class="text-lg">{{ $jobsCount }} Active Jobs</p>
                                </div>
                            </div>
                            <a href="{{ route('portal.jobs') }}" class="mt-4 inline-block bg-white text-purple-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition">Browse Jobs</a>
                        </div>
                    </div>
                    
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold mb-6">Latest Events</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($latestEvents as $event)
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <h3 class="font-bold text-lg mb-2">{{ $event->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }} at {{ $event->location }}</p>
                                    <p class="text-gray-700 text-sm mb-3">{{ Str::limit($event->description, 100) }}</p>
                                    <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:underline text-sm">View Details</a>
                                </div>
                            @empty
                                <p class="col-span-3 text-center">No events available.</p>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold mb-6">Latest Courses</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($latestCourses as $course)
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <h3 class="font-bold text-lg mb-2">{{ $course->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">Level: {{ ucfirst($course->level) }}</p>
                                    <p class="text-gray-700 text-sm mb-3">{{ Str::limit($course->description, 100) }}</p>
                                    <a href="{{ route('courses.show', $course) }}" class="text-green-600 hover:underline text-sm">View Details</a>
                                </div>
                            @empty
                                <p class="col-span-3 text-center">No courses available.</p>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold mb-6">Latest Job Listings</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($latestJobs as $job)
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <h3 class="font-bold text-lg mb-2">{{ $job->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $job->job_type }} • {{ $job->location }}</p>
                                    <p class="text-gray-700 text-sm mb-3">{{ Str::limit($job->description, 100) }}</p>
                                    <a href="{{ route('jobs.show', $job) }}" class="text-purple-600 hover:underline text-sm">View Details</a>
                                </div>
                            @empty
                                <p class="col-span-3 text-center">No job listings available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>