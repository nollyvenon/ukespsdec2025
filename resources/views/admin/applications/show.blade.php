<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin - Application Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Application Details</h1>
                        <a href="{{ route('admin.applications.index') }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                            Back to Applications
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-xl font-semibold mb-4">Job Information</h2>
                            <div class="space-y-3">
                                <div><strong>Title:</strong> {{ $application->job->title ?? 'N/A' }}</div>
                                <div><strong>Company:</strong> {{ $application->job->company ?? 'N/A' }}</div>
                                <div><strong>Location:</strong> {{ $application->job->location ?? 'N/A' }}</div>
                                <div><strong>Job Type:</strong> {{ ucfirst(str_replace('_', ' ', $application->job->job_type ?? 'N/A')) }}</div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-xl font-semibold mb-4">Applicant Information</h2>
                            <div class="space-y-3">
                                <div><strong>Name:</strong> {{ $application->applicant->name ?? 'N/A' }}</div>
                                <div><strong>Email:</strong> {{ $application->applicant->email ?? 'N/A' }}</div>
                                <div><strong>Phone:</strong> {{ $application->applicant->profile->phone ?? 'N/A' }}</div>
                                <div><strong>Location:</strong> {{ $application->applicant->profile->location ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                        <h2 class="text-xl font-semibold mb-4">Cover Letter</h2>
                        <div class="prose max-w-none">
                            <p>{{ $application->cover_letter }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h2 class="text-xl font-semibold mb-4">Application Status</h2>
                            <form method="POST" action="{{ route('admin.applications.update-status', $application) }}">
                                @csrf
                                @method('PATCH')
                                <div class="mb-4">
                                    <label for="application_status" class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                                    <select name="application_status" id="application_status"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="pending" {{ $application->application_status == 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="reviewed" {{ $application->application_status == 'reviewed' ? 'selected' : '' }}>
                                            Reviewed
                                        </option>
                                        <option value="shortlisted" {{ $application->application_status == 'shortlisted' ? 'selected' : '' }}>
                                            Shortlisted
                                        </option>
                                        <option value="rejected" {{ $application->application_status == 'rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>
                                        <option value="hired" {{ $application->application_status == 'hired' ? 'selected' : '' }}>
                                            Hired
                                        </option>
                                    </select>
                                </div>
                                <button type="submit" 
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                    Update Status
                                </button>
                            </form>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h2 class="text-xl font-semibold mb-4">Resume</h2>
                            @if($application->resume_path)
                                <div class="space-y-3">
                                    <p class="text-gray-600">Resume Attached: {{ basename($application->resume_path) }}</p>
                                    <a href="{{ asset('storage/' . $application->resume_path) }}" 
                                       target="_blank"
                                       class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                        Download Resume
                                    </a>
                                </div>
                            @else
                                <p class="text-gray-500">No resume attached</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-xl font-semibold mb-4">Application History</h2>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar-check text-indigo-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium">Applied on {{ $application->created_at->format('M d, Y \a\t g:i A') }}</p>
                                    <p class="text-sm text-gray-600">Application submitted by {{ $application->applicant->name }}</p>
                                </div>
                            </div>
                            
                            @if($application->application_status !== 'pending')
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-check text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-medium">Status updated to "{{ ucfirst($application->application_status) }}"</p>
                                        <p class="text-sm text-gray-600">Updated on {{ $application->updated_at->format('M d, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>