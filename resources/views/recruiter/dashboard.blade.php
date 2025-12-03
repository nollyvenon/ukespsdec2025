<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Recruiter Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-blue-800">Jobs Posted</h3>
                            <p class="text-3xl font-bold mt-2">{{ $totalJobsPosted }}</p>
                        </div>
                        <div class="bg-green-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-green-800">Total Applications</h3>
                            <p class="text-3xl font-bold mt-2">{{ $totalApplications }}</p>
                        </div>
                        <div class="bg-yellow-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-yellow-800">Pending Applications</h3>
                            <p class="text-3xl font-bold mt-2">{{ $pendingApplications }}</p>
                        </div>
                    </div>

                    <!-- Recent Jobs and Applications -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Recent Jobs -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-bold mb-4">Recent Jobs Posted</h3>
                            @if($recentJobs->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($recentJobs as $job)
                                        <li class="border-b pb-3">
                                            <a href="{{ route('jobs.show', $job) }}" class="text-blue-600 hover:underline">
                                                <h4 class="font-semibold">{{ $job->title }}</h4>
                                            </a>
                                            <p class="text-sm text-gray-600">{{ $job->location }} • {{ $job->job_type }}</p>
                                            <p class="text-xs text-gray-500">{{ $job->created_at->diffForHumans() }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-600">No jobs posted yet.</p>
                            @endif
                        </div>

                        <!-- Recent Applications -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-bold mb-4">Recent Applications</h3>
                            @if($recentApplications->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($recentApplications as $application)
                                        <li class="border-b pb-3">
                                            <h4 class="font-semibold">
                                                <a href="{{ route('jobs.show', $application->job) }}" class="text-blue-600 hover:underline">
                                                    {{ $application->job->title }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-600">Applicant: {{ $application->applicant->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }} • Status: 
                                                <span class="px-2 py-1 rounded text-xs 
                                                    @if($application->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($application->status == 'accepted') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-600">No applications received yet.</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('jobs.create') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Post New Job
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>