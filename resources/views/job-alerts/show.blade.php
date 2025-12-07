<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Job Alert Details') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-6">
                        <div>
                            <div class="flex items-center mb-3">
                                @if($jobAlert->is_active)
                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full mr-3">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-block bg-gray-100 text-gray-800 text-xs px-3 py-1 rounded-full mr-3">
                                        Inactive
                                    </span>
                                @endif
                                <h1 class="text-2xl font-bold text-gray-900">{{ $jobAlert->name }}</h1>
                            </div>
                            <p class="text-gray-600">Created {{ $jobAlert->created_at->diffForHumans() }} • Updated {{ $jobAlert->updated_at->diffForHumans() }}</p>
                        </div>
                        
                        @auth
                            @if(Auth::id() === $jobAlert->user_id)
                                <div class="flex space-x-3 mt-4 md:mt-0">
                                    <a href="{{ route('job-alerts.edit', $jobAlert) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
                                        <i class="fas fa-edit mr-2"></i> Edit
                                    </a>
                                    <form action="{{ route('job-alerts.destroy', $jobAlert) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this job alert?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
                                            <i class="fas fa-trash mr-2"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>

                    @if($jobAlert->description)
                        <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-900 mb-2">Description</h2>
                            <p class="text-gray-700">{{ $jobAlert->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                                <i class="fas fa-cog mr-2"></i> Alert Settings
                            </h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Frequency:</span>
                                    <span class="font-medium">{{ ucfirst($jobAlert->frequency) }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-medium">{{ $jobAlert->is_active ? 'Active' : 'Inactive' }}</span>
                                </div>
                                
                                @if($jobAlert->last_run_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Last Checked:</span>
                                        <span class="font-medium">{{ $jobAlert->last_run_at->diffForHumans() }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-bold text-green-800 mb-4 flex items-center">
                                <i class="fas fa-search mr-2"></i> Search Criteria
                            </h3>
                            
                            <div class="space-y-2">
                                @if(!empty($jobAlert->criteria['keywords']))
                                    <div class="flex">
                                        <span class="text-gray-600 w-32">Keywords:</span>
                                        <span class="font-medium">{{ implode(', ', $jobAlert->criteria['keywords']) }}</span>
                                    </div>
                                @endif
                                
                                @if(!empty($jobAlert->criteria['locations']))
                                    <div class="flex">
                                        <span class="text-gray-600 w-32">Locations:</span>
                                        <span class="font-medium">{{ implode(', ', $jobAlert->criteria['locations']) }}</span>
                                    </div>
                                @endif
                                
                                @if(!empty($jobAlert->criteria['job_types']))
                                    <div class="flex">
                                        <span class="text-gray-600 w-32">Job Types:</span>
                                        <span class="font-medium">{{ implode(', ', array_map(function($type) { return ucfirst(str_replace('_', ' ', $type)); }, $jobAlert->criteria['job_types'])) }}</span>
                                    </div>
                                @endif
                                
                                @if(!empty($jobAlert->criteria['experience_level']))
                                    <div class="flex">
                                        <span class="text-gray-600 w-32">Experience:</span>
                                        <span class="font-medium">{{ ucfirst($jobAlert->criteria['experience_level']) }}</span>
                                    </div>
                                @endif
                                
                                @if(!empty($jobAlert->criteria['salary_min']) || !empty($jobAlert->criteria['salary_max']))
                                    <div class="flex">
                                        <span class="text-gray-600 w-32">Salary Range:</span>
                                        <span class="font-medium">
                                            @if($jobAlert->criteria['salary_min'])
                                                Min: ${{ number_format($jobAlert->criteria['salary_min']) }}
                                            @endif
                                            @if($jobAlert->criteria['salary_min'] && $jobAlert->criteria['salary_max'])
                                                -
                                            @endif
                                            @if($jobAlert->criteria['salary_max'])
                                                Max: ${{ number_format($jobAlert->criteria['salary_max']) }}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        @if(!$jobAlert->is_active)
                            <form action="{{ route('job-alerts.toggle-status', $jobAlert) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                                    <i class="fas fa-play mr-2"></i> Enable Alert
                                </button>
                            </form>
                        @else
                            <form action="{{ route('job-alerts.toggle-status', $jobAlert) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                                    <i class="fas fa-power-off mr-2"></i> Disable Alert
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('job-alerts.find-matching-jobs', $jobAlert) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                            <i class="fas fa-search mr-2"></i> Find Matching Jobs
                        </a>
                        
                        <a href="{{ route('job-alerts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Alerts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>