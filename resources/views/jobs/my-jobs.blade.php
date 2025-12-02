<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Job Listings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">My Job Listings</h1>
                        <a href="{{ route('jobs.create') }}" 
                           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            Post New Job
                        </a>
                    </div>

                    @if($jobListings->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-briefcase text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No job listings found</h3>
                            <p class="text-gray-500 mb-6">You haven't posted any jobs yet.</p>
                            <a href="{{ route('jobs.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                Post a Job
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applications</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posted Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($jobListings as $job)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $job->title }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($job->description, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $job->company }}</div>
                                                <div class="text-sm text-gray-500">{{ $job->location }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @switch($job->job_status)
                                                        @case('draft')
                                                            {{ 'bg-gray-100 text-gray-800' }}
                                                            @break
                                                        @case('published')
                                                            {{ 'bg-green-100 text-green-800' }}
                                                            @break
                                                        @case('closed')
                                                            {{ 'bg-red-100 text-red-800' }}
                                                            @break
                                                        @case('cancelled')
                                                            {{ 'bg-yellow-100 text-yellow-800' }}
                                                            @break
                                                        @default
                                                            {{ 'bg-blue-100 text-blue-800' }}
                                                    @endswitch">
                                                    {{ ucfirst($job->job_status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $job->jobApplications->count() }} applications
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $job->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('jobs.show', $job) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                                <a href="{{ route('jobs.edit', $job) }}" 
                                                   class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                                                <a href="{{ route('jobs.manage-applications', $job) }}" 
                                                   class="text-blue-600 hover:text-blue-900 mr-3">Apps</a>
                                                <form method="POST" action="{{ route('jobs.destroy', $job) }}" class="inline-block" 
                                                      onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-8">
                            {{ $jobListings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>