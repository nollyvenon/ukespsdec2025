<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Matching Jobs for ') . $jobAlert->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Jobs Matching "{{ $jobAlert->name }}"</h1>
                            <p class="text-gray-600">Found {{ $matchingJobs->count() }} jobs matching your criteria</p>
                        </div>
                        <a href="{{ route('job-alerts.index') }}" class="mt-4 sm:mt-0 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Alerts
                        </a>
                    </div>

                    @if($matchingJobs->count() > 0)
                        <div class="space-y-8">
                            @foreach($matchingJobs as $job)
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-indigo-300 transition duration-300">
                                    <div class="flex">
                                        <div class="flex-shrink-0 mr-6">
                                            <div class="bg-gray-200 w-16 h-16 flex items-center justify-center rounded">
                                                <i class="fas fa-building text-gray-500 text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <div>
                                                    <h3 class="text-xl font-bold text-gray-900">
                                                        <a href="{{ route('jobs.show', $job) }}" class="hover:text-indigo-600">
                                                            {{ $job->title }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-gray-600 mt-1">By {{ $job->poster->name ?? 'Unknown' }}</p>
                                                </div>
                                                <span class="inline-block bg-{{ $job->job_status === 'published' ? 'green' : ($job->job_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $job->job_status === 'published' ? 'green' : ($job->job_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                                    {{ ucfirst($job->job_status) }}
                                                </span>
                                            </div>
                                            
                                            <div class="mt-3 flex flex-wrap gap-2 text-sm">
                                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                    <i class="fas fa-briefcase mr-1"></i> {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}
                                                </span>
                                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                    <i class="fas fa-chart-line mr-1"></i> {{ ucfirst($job->experience_level) }}
                                                </span>
                                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $job->location }}
                                                </span>
                                            </div>
                                            
                                            <p class="text-gray-700 mt-3">{{ Str::limit($job->description, 150) }}</p>
                                            
                                            <div class="mt-4 flex justify-between items-center">
                                                <div>
                                                    @if($job->salary_min && $job->salary_max)
                                                        <p class="text-sm font-medium text-green-600">${{ number_format($job->salary_min, 2) }} - ${{ number_format($job->salary_max, 2) }}</p>
                                                    @elseif($job->salary_min)
                                                        <p class="text-sm font-medium text-green-600">Min: ${{ number_format($job->salary_min, 2) }}+</p>
                                                    @else
                                                        <p class="text-sm font-medium text-green-600">Salary: Negotiable</p>
                                                    @endif
                                                    @if($job->application_deadline)
                                                        <p class="text-sm text-gray-600">Deadline: {{ \Carbon\Carbon::parse($job->application_deadline)->format('M j, Y') }}</p>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex space-x-3">
                                                    @if($job->job_status === 'published' && (!$job->application_deadline || $job->application_deadline > now()))
                                                        <a href="{{ route('jobs.apply', $job) }}" class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg text-sm font-medium">
                                                            Apply Now
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('jobs.show', $job) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg text-sm font-medium">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8 text-center">
                            <p class="text-gray-600">Refreshed on {{ now()->format('M j, Y g:i A') }}</p>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No matching jobs found</h3>
                            <p class="text-gray-500 mb-6">No jobs currently match your alert criteria. Try adjusting your search criteria or check back later.</p>
                            <div class="flex justify-center space-x-4">
                                <a href="{{ route('job-alerts.edit', $jobAlert) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Adjust Criteria
                                </a>
                                <a href="{{ route('job-alerts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    View All Alerts
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>