@extends('layouts.public')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Advanced Search Form -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Search Jobs</h2>
                    <form method="GET" action="{{ route('jobs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Keyword</label>
                            <input type="text" name="search" id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Title, description, location..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="job_type" class="block text-sm font-medium text-gray-700 mb-1">Job Type</label>
                            <select name="job_type" id="job_type"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All Types</option>
                                @foreach(['full_time', 'part_time', 'contract', 'internship', 'remote'] as $type)
                                    <option value="{{ $type }}" {{ request('job_type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="experience_level" class="block text-sm font-medium text-gray-700 mb-1">Experience</label>
                            <select name="experience_level" id="experience_level"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All Levels</option>
                                @foreach(['entry', 'mid', 'senior', 'executive'] as $level)
                                    <option value="{{ $level }}" {{ request('experience_level') == $level ? 'selected' : '' }}>
                                        {{ ucfirst($level) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" name="location" id="location"
                                   value="{{ request('location') }}"
                                   placeholder="City, state..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="salary_min" class="block text-sm font-medium text-gray-700 mb-1">Min Salary</label>
                            <input type="number" name="salary_min" id="salary_min"
                                   value="{{ request('salary_min') }}"
                                   placeholder="e.g. 50000"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="date_posted" class="block text-sm font-medium text-gray-700 mb-1">Date Posted</label>
                            <select name="date_posted" id="date_posted"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Any Time</option>
                                <option value="today" {{ request('date_posted') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ request('date_posted') == 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ request('date_posted') == 'month' ? 'selected' : '' }}>This Month</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded">
                                Search
                            </button>
                        </div>

                        <div class="flex items-end">
                            <a href="{{ route('jobs.index') }}"
                               class="w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Search Results Summary -->
                @if(request()->has('search') || request()->hasAny(['job_type', 'experience_level', 'location', 'salary_min', 'date_posted']))
                    <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-medium text-blue-800">Search Results</h3>
                        <p class="text-sm text-blue-600">Found {{ $jobListings->total() }} job{{ $jobListings->total() != 1 ? 's' : '' }}
                        @if(request('search')) for "{{ request('search') }}" @endif
                        @if(request('job_type')) with type "{{ ucfirst(str_replace('_', ' ', request('job_type'))) }}" @endif
                        @if(request('experience_level')) at "{{ ucfirst(request('experience_level')) }}" level @endif
                        @if(request('location')) in "{{ request('location') }}" @endif
                        </p>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">All Job Listings</h1>
                    @auth
                        <a href="{{ route('jobs.create') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Post Job
                        </a>
                    @endauth
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($jobListings as $job)
                        <div class="border rounded-lg p-4 shadow-md">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-bold mb-1">{{ $job->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-1">{{ $job->job_type }} • {{ $job->experience_level }} Level</p>
                                    <p class="text-gray-600 text-sm mb-2">{{ $job->location }}</p>
                                </div>
                                <span class="inline-block bg-{{ $job->job_status === 'published' ? 'green' : ($job->job_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $job->job_status === 'published' ? 'green' : ($job->job_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                    {{ ucfirst($job->job_status) }}
                                </span>
                            </div>

                            <div class="mb-3">
                                @if($job->salary_min && $job->salary_max)
                                    <p class="text-sm font-medium">${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}</p>
                                @elseif($job->salary_min)
                                    <p class="text-sm font-medium">Min: ${{ number_format($job->salary_min) }}</p>
                                @else
                                    <p class="text-sm font-medium">Salary: Negotiable</p>
                                @endif
                            </div>

                            <p class="text-gray-700 mb-4">{{ Str::limit($job->description, 120) }}</p>

                            <div class="flex justify-between items-center">
                                @if($job->application_deadline)
                                    <p class="text-sm text-gray-500">Deadline: {{ \Carbon\Carbon::parse($job->application_deadline)->format('M j, Y') }}</p>
                                @endif
                                <a href="{{ route('jobs.show', $job) }}" class="text-purple-600 hover:underline">View Details</a>
                            </div>

                            @if($job->job_status === 'published' && (!$job->application_deadline || $job->application_deadline > now()))
                                <div class="mt-3">
                                    <a href="{{ route('jobs.apply', $job) }}" class="w-full bg-purple-500 hover:bg-purple-700 text-white py-2 px-4 rounded text-center inline-block">Apply Now</a>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-500">No job listings available at the moment.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $jobListings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection