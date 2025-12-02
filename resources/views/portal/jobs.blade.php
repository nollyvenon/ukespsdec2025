<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jobs Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-8">
                        <h1 class="text-3xl font-bold">Jobs Management Portal</h1>
                        <a href="{{ route('jobs.create') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Post New Job
                        </a>
                    </div>
                    
                    <div class="mb-6">
                        <a href="{{ route('jobs.my') }}" class="text-purple-600 hover:underline">My Job Listings</a>
                        <span class="mx-2">|</span>
                        <a href="{{ route('jobs.applications') }}" class="text-purple-600 hover:underline">My Applications</a>
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
                                <a href="{{ route('jobs.create') }}" class="text-purple-600 hover:underline mt-4 inline-block">Post your first job</a>
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
</x-app-layout>