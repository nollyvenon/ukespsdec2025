<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Job Details') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Job Header -->
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-8">
                        <div class="flex-1 mb-6 md:mb-0">
                            <div class="flex items-center mb-4">
                                @if($job->is_premium)
                                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full mr-3">
                                        Premium
                                    </span>
                                    <span class="text-yellow-600 mr-2">⭐</span>
                                @endif
                                <h1 class="text-3xl font-bold text-gray-900">{{ $job->title }}</h1>
                            </div>
                            <div class="flex flex-wrap items-center text-sm text-gray-600 mb-4">
                                <span class="mr-4">Employer: {{ $job->poster->name }}</span>
                                <span class="mr-4">Type: {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}</span>
                                <span class="mr-4">Level: {{ ucfirst($job->experience_level) }}</span>
                                <span class="inline-block bg-{{ $job->job_status === 'published' ? 'green' : ($job->job_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $job->job_status === 'published' ? 'green' : ($job->job_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                    {{ ucfirst($job->job_status) }}
                                </span>
                            </div>
                        </div>
                        @if(Auth::check() && Auth::id() === $job->posted_by)
                            <div class="flex space-x-2 mt-4 md:mt-0">
                                <a href="{{ route('jobs.edit', $job) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded text-sm">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm" onclick="return confirm('Are you sure you want to delete this job?')">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Main Content -->
                        <div class="lg:col-span-2">
                            <!-- Salary and Location -->
                            <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Salary</h3>
                                        @if($job->salary_min && $job->salary_max)
                                            <p class="text-2xl font-bold text-green-600">${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}</p>
                                        @elseif($job->salary_min)
                                            <p class="text-2xl font-bold text-green-600">Min: ${{ number_format($job->salary_min) }}+</p>
                                        @else
                                            <p class="text-2xl font-bold text-green-600">Salary: Negotiable</p>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Location</h3>
                                        <p class="text-xl font-medium">{{ $job->location }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Description -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b">Job Overview</h2>
                                <div class="text-gray-700 whitespace-pre-line">
                                    {{ $job->description }}
                                </div>
                            </div>

                            <!-- Responsibilities -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b">Key Responsibilities</h2>
                                <div class="text-gray-700 whitespace-pre-line">
                                    {{ $job->responsibilities }}
                                </div>
                            </div>

                            <!-- Requirements -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b">Requirements</h2>
                                <div class="text-gray-700 whitespace-pre-line">
                                    {{ $job->requirements }}
                                </div>
                            </div>

                            @if($job->application_deadline)
                                <div class="mb-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Application Deadline</h3>
                                    <p class="text-gray-700">This job listing closes on {{ \Carbon\Carbon::parse($job->application_deadline)->format('F j, Y') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Sidebar - Company Info & Apply -->
                        <div class="lg:col-span-1">
                            <!-- Job Details Card -->
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <h3 class="font-bold text-lg text-gray-900 mb-4">Job Details</h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Job type:</span>
                                        <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $job->job_type)) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Experience level:</span>
                                        <span class="font-medium">{{ ucfirst($job->experience_level) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Location:</span>
                                        <span class="font-medium">{{ $job->location }}</span>
                                    </div>
                                    @if($job->salary_min && $job->salary_max)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Salary:</span>
                                            <span class="font-medium">${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}</span>
                                        </div>
                                    @elseif($job->salary_min)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Min Salary:</span>
                                            <span class="font-medium">${{ number_format($job->salary_min) }}+</span>
                                        </div>
                                    @else
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Salary:</span>
                                            <span class="font-medium">Negotiable</span>
                                        </div>
                                    @endif
                                    @if($job->application_deadline)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Closing date:</span>
                                            <span class="font-medium">{{ \Carbon\Carbon::parse($job->application_deadline)->format('F j, Y') }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Posted:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($job->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="inline-block bg-{{ $job->job_status === 'published' ? 'green' : ($job->job_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $job->job_status === 'published' ? 'green' : ($job->job_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                            {{ ucfirst($job->job_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Apply Now Card -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                                <div class="text-center mb-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Ready to Apply?</h3>
                                    <p class="text-gray-600">Take the next step in your career</p>
                                </div>

                                @if($job->job_status === 'published' && (!$job->application_deadline || $job->application_deadline > now()))
                                    <a href="{{ route('jobs.apply', $job) }}" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg text-center inline-block transition duration-300">
                                        Apply Now
                                    </a>

                                    <div class="mt-4">
                                        <a href="#" class="text-gray-600 hover:text-gray-900 text-sm inline-flex items-center">
                                            <i class="fas fa-save mr-2"></i> Save job for later
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center">
                                        @if($job->job_status !== 'published')
                                            <p class="text-gray-500">This job listing is not currently accepting applications.</p>
                                        @elseif($job->application_deadline && $job->application_deadline <= now())
                                            <p class="text-gray-500">Application deadline has passed.</p>
                                        @else
                                            <p class="text-gray-500">Applications are not available at this time.</p>
                                        @endif
                                    </div>
                                @endif

                                <div class="mt-6 text-center text-sm text-gray-500">
                                    Report this job <a href="#" class="text-purple-600 hover:underline">to staff</a>
                                </div>
                            </div>

                            <!-- Company Info -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="font-bold text-lg text-gray-900 mb-4">Employer</h3>
                                <div class="flex items-center">
                                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex items-center justify-center">
                                        <i class="fas fa-building text-gray-500 text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-medium text-gray-900">{{ $job->poster->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $job->poster->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>