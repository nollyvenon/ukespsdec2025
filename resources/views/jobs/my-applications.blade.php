<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Job Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">My Job Applications</h1>

                    @if($applications->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-file-alt text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No applications yet</h3>
                            <p class="text-gray-500 mb-6">You haven't applied to any jobs yet.</p>
                            <a href="{{ route('jobs.index') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                Browse Jobs
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($applications as $application)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $application->job->title }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($application->job->description, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $application->job->company ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @switch($application->application_status)
                                                        @case('pending')
                                                            {{ 'bg-yellow-100 text-yellow-800' }}
                                                            @break
                                                        @case('reviewed')
                                                            {{ 'bg-blue-100 text-blue-800' }}
                                                            @break
                                                        @case('shortlisted')
                                                            {{ 'bg-purple-100 text-purple-800' }}
                                                            @break
                                                        @case('rejected')
                                                            {{ 'bg-red-100 text-red-800' }}
                                                            @break
                                                        @case('hired')
                                                            {{ 'bg-green-100 text-green-800' }}
                                                            @break
                                                        @default
                                                            {{ 'bg-gray-100 text-gray-800' }}
                                                    @endswitch">
                                                    {{ ucfirst(str_replace('_', ' ', $application->application_status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $application->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('jobs.show', $application->job) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3">View Job</a>
                                                <a href="{{ $application->resume_path ? asset('storage/' . $application->resume_path) : '#' }}" 
                                                   target="_blank"
                                                   class="text-green-600 hover:text-green-900 {{ !$application->resume_path ? 'opacity-50 pointer-events-none' : '' }}">
                                                    View Resume
                                                </a>
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