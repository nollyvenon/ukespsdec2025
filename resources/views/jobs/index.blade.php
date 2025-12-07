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

                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Find Your Perfect Job</h1>
                        <p class="text-gray-600">{{ $jobListings->total() }} job{{ $jobListings->total() != 1 ? 's' : '' }} available</p>
                    </div>
                    @auth
                        <a href="{{ route('jobs.create') }}" class="mt-4 sm:mt-0 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                            <i class="fas fa-briefcase mr-2"></i> Post a Job
                        </a>
                    @endauth
                </div>

                @if($jobListings->count() > 0)
                    <div class="space-y-6">
                        @foreach($jobListings as $job)
                            <div class="border border-gray-200 rounded-lg p-6 hover:border-indigo-300 transition duration-300 {{ $job->is_premium ? 'border-2 border-yellow-400' : '' }}">
                                <div class="flex flex-col md:flex-row">
                                    <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                                        <div class="bg-gray-200 w-16 h-16 flex items-center justify-center rounded">
                                            <i class="fas fa-building text-gray-500 text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between flex-wrap gap-4">
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-900 {{ $job->is_premium ? 'text-yellow-600' : '' }}">
                                                    {{ $job->is_premium ? '⭐ ' : '' }}
                                                    <a href="{{ route('jobs.show', $job) }}" class="hover:text-indigo-600">
                                                        {{ $job->title }}
                                                    </a>
                                                </h3>
                                                <div class="flex flex-wrap items-center gap-4 mt-1 text-sm text-gray-600">
                                                    <span>By {{ $job->poster->name }}</span>
                                                    <span>•</span>
                                                    <span>{{ $job->location }}</span>
                                                    <span>•</span>
                                                    <span>{{ \Carbon\Carbon::parse($job->created_at)->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-end">
                                                @if($job->is_premium)
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-medium mb-1">
                                                        Premium
                                                    </span>
                                                @endif
                                                <span class="inline-block bg-{{ $job->job_status === 'published' ? 'green' : ($job->job_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $job->job_status === 'published' ? 'green' : ($job->job_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                                    {{ ucfirst($job->job_status) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-4 flex flex-wrap gap-2 text-sm">
                                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                <i class="fas fa-briefcase mr-1"></i> {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}
                                            </span>
                                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                <i class="fas fa-chart-line mr-1"></i> {{ ucfirst($job->experience_level) }} Level
                                            </span>
                                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $job->location }}
                                            </span>
                                            @if($job->salary_min && $job->salary_max)
                                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded">
                                                    <i class="fas fa-dollar-sign mr-1"></i> ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}
                                                </span>
                                            @elseif($job->salary_min)
                                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded">
                                                    <i class="fas fa-dollar-sign mr-1"></i> ${{ number_format($job->salary_min) }}+
                                                </span>
                                            @else
                                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded">
                                                    <i class="fas fa-dollar-sign mr-1"></i> Negotiable
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-gray-700 mt-4">{{ Str::limit($job->description, 200) }}</p>

                                        <div class="mt-6 flex flex-wrap justify-between items-center gap-4">
                                            <div>
                                                @if($job->application_deadline)
                                                    <p class="text-sm text-gray-600">
                                                        <i class="fas fa-clock mr-1"></i> Closes {{ \Carbon\Carbon::parse($job->application_deadline)->format('j M Y') }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex flex-wrap gap-3">
                                                @if($job->job_status === 'published' && (!$job->application_deadline || $job->application_deadline > now()))
                                                    <a href="{{ route('jobs.apply.form', $job) }}" class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg text-sm font-medium inline-flex items-center">
                                                        <i class="fas fa-paper-plane mr-1"></i> Apply Now
                                                    </a>
                                                @endif
                                                <a href="{{ route('jobs.show', $job) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg text-sm font-medium inline-flex items-center">
                                                    <i class="fas fa-info-circle mr-1"></i> View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $jobListings->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-briefcase text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">No jobs found</h3>
                        <p class="text-gray-500 mb-6">Try adjusting your search criteria or browse all jobs</p>
                        <a href="{{ route('jobs.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Browse All Jobs
                        </a>
                    </div>
                @endif

                <div class="mt-8">
                    {{ $jobListings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection