<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Job Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">{{ $job->title }}</h1>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span>{{ ucfirst($job->job_type) }}</span>
                                <span>•</span>
                                <span>{{ ucfirst($job->experience_level) }} Level</span>
                                <span>•</span>
                                <span class="inline-block bg-{{ $job->job_status === 'published' ? 'green' : ($job->job_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $job->job_status === 'published' ? 'green' : ($job->job_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                    {{ ucfirst($job->job_status) }}
                                </span>
                            </div>
                        </div>
                        @if(Auth::id() === $job->posted_by)
                            <div class="flex space-x-2">
                                <a href="{{ route('jobs.edit', $job) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-3 rounded text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded text-sm" onclick="return confirm('Are you sure you want to delete this job listing?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="font-bold text-lg mb-2">Job Details</h3>
                            <p><span class="font-medium">Type:</span> {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}</p>
                            <p><span class="font-medium">Level:</span> {{ ucfirst($job->experience_level) }}</p>
                            <p><span class="font-medium">Location:</span> {{ $job->location }}</p>
                            @if($job->salary_min && $job->salary_max)
                                <p><span class="font-medium">Salary:</span> ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}</p>
                            @elseif($job->salary_min)
                                <p><span class="font-medium">Salary:</span> ${{ number_format($job->salary_min) }}+ </p>
                            @else
                                <p><span class="font-medium">Salary:</span> Negotiable</p>
                            @endif
                            @if($job->application_deadline)
                                <p><span class="font-medium">Application Deadline:</span> {{ \Carbon\Carbon::parse($job->application_deadline)->format('F j, Y') }}</p>
                            @endif
                        </div>
                        
                        <div class="md:col-span-2">
                            <h3 class="font-bold text-lg mb-2">Description</h3>
                            <p class="whitespace-pre-line">{{ $job->description }}</p>
                            
                            <h4 class="font-bold mt-4">Requirements</h4>
                            <p class="whitespace-pre-line">{{ $job->requirements }}</p>
                            
                            <h4 class="font-bold mt-4">Responsibilities</h4>
                            <p class="whitespace-pre-line">{{ $job->responsibilities }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        @if($job->job_status === 'published' && (!$job->application_deadline || $job->application_deadline > now()))
                            <a href="{{ route('jobs.apply', $job) }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded">
                                Apply for Job
                            </a>
                        @elseif($job->job_status !== 'published')
                            <p class="text-gray-500">This job listing is not currently accepting applications.</p>
                        @elseif($job->application_deadline && $job->application_deadline <= now())
                            <p class="text-gray-500">Application deadline has passed.</p>
                        @else
                            <p class="text-gray-500">Applications are not available at this time.</p>
                        @endif
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="font-bold text-lg mb-4">Posted By</h3>
                        <div class="flex items-center">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16" />
                            <div class="ml-4">
                                <p class="font-medium">{{ $job->poster->name }}</p>
                                <p class="text-sm text-gray-600">{{ $job->poster->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>