<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin - Job Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Job Applications Management</h1>
                    </div>

                    @if($applications->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-envelope-open-text text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No job applications</h3>
                            <p class="text-gray-500">No job applications have been submitted yet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Applied For</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($applications as $application)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $application->applicant->name ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $application->applicant->email ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $application->job->title ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $application->job->company ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $application->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form method="POST" action="{{ route('admin.applications.update-status', $application) }}" 
                                                      class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="application_status" 
                                                            onchange="this.form.submit()"
                                                            class="text-sm rounded border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.applications.show', $application) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                                @if($application->resume_path)
                                                    <a href="{{ asset('storage/' . $application->resume_path) }}" 
                                                       target="_blank"
                                                       class="text-green-600 hover:text-green-900">Download Resume</a>
                                                @endif
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