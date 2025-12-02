<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Applications for ') . $jobListing->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold">{{ $jobListing->title }}</h1>
                        <p class="text-gray-600">{{ $jobListing->company }} • {{ $jobListing->location }}</p>
                    </div>

                    <div class="mb-6">
                        <a href="{{ route('jobs.edit', $jobListing) }}" 
                           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 mr-3">
                            Edit Job
                        </a>
                        <a href="{{ route('jobs.show', $jobListing) }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                            View Job
                        </a>
                    </div>

                    @if($applications->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-user-clock text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No applications yet</h3>
                            <p class="text-gray-500">No one has applied for this position yet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($applications as $application)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $application->applicant->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $application->applicant->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $application->created_at->format('M d, Y') }}</div>
                                                <div class="text-sm text-gray-500">{{ $application->created_at->format('h:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form method="POST" action="{{ route('job-applications.update-status', $application) }}" 
                                                      class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="application_status" 
                                                            onchange="this.form.submit()"
                                                            class="text-sm rounded border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        <option value="pending" {{ $application->application_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="reviewed" {{ $application->application_status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                                        <option value="shortlisted" {{ $application->application_status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                                        <option value="rejected" {{ $application->application_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                        <option value="hired" {{ $application->application_status == 'hired' ? 'selected' : '' }}>Hired</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('jobs.show', $application->job) }}" 
                                                   class="text-blue-600 hover:text-blue-900 mr-3">View Job</a>
                                                <a href="{{ asset('storage/' . $application->resume_path) }}" 
                                                   target="_blank"
                                                   class="text-green-600 hover:text-green-900">View Resume</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-8">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>