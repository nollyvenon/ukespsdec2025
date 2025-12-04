<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Job Alerts') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Job Alerts</h1>
                            <p class="text-gray-600">Get notified when new jobs match your criteria</p>
                        </div>
                        <a href="{{ route('job-alerts.create') }}" class="mt-4 sm:mt-0 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                            <i class="fas fa-bell-plus mr-2"></i> Create Alert
                        </a>
                    </div>

                    @if($jobAlerts->count() > 0)
                        <div class="space-y-6">
                            @foreach($jobAlerts as $alert)
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-indigo-300 transition duration-300">
                                    <div class="flex justify-between">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                                <i class="fas fa-bell text-indigo-500 mr-2"></i> {{ $alert->name }}
                                            </h3>
                                            
                                            <div class="mt-2 flex flex-wrap gap-2 text-sm">
                                                @if(!empty($alert->criteria['keywords']))
                                                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                        <i class="fas fa-keyboard mr-1"></i> Keywords: {{ implode(', ', $alert->criteria['keywords']) }}
                                                    </span>
                                                @endif
                                                
                                                @if(!empty($alert->criteria['locations']))
                                                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded">
                                                        <i class="fas fa-map-marker-alt mr-1"></i> {{ implode(', ', $alert->criteria['locations']) }}
                                                    </span>
                                                @endif
                                                
                                                @if(!empty($alert->criteria['job_types']))
                                                    <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                                        <i class="fas fa-briefcase mr-1"></i> {{ implode(', ', array_map(function($type) { return ucfirst(str_replace('_', ' ', $type)); }, $alert->criteria['job_types'])) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col items-end">
                                            <div class="flex items-center mb-2">
                                                <span class="mr-3 inline-block bg-{{ $alert->is_active ? 'green' : 'red' }}-100 text-{{ $alert->is_active ? 'green' : 'red' }}-800 text-xs px-2 py-1 rounded-full">
                                                    {{ $alert->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">
                                                    {{ ucfirst($alert->frequency) }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex space-x-2">
                                                <a href="{{ route('job-alerts.find-matching-jobs', $alert) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                    <i class="fas fa-search mr-1"></i> Find Matching
                                                </a>
                                                <a href="{{ route('job-alerts.edit', $alert) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                                <form action="{{ route('job-alerts.toggle-status', $alert) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-{{ $alert->is_active ? 'red' : 'green' }}-600 hover:text-{{ $alert->is_active ? 'red' : 'green' }}-900 text-sm font-medium">
                                                        <i class="fas fa-{{ $alert->is_active ? 'power-off' : 'play' }} mr-1"></i> 
                                                        {{ $alert->is_active ? 'Disable' : 'Enable' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('job-alerts.destroy', $alert) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this job alert?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                        <i class="fas fa-trash mr-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($alert->description)
                                        <p class="text-gray-700 mt-3">{{ $alert->description }}</p>
                                    @endif>
                                    
                                    <div class="mt-4 flex justify-between items-center">
                                        <div>
                                            @if($alert->last_run_at)
                                                <p class="text-sm text-gray-600">Last checked: {{ $alert->last_run_at->diffForHumans() }}</p>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('job-alerts.show', $alert) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-1 px-4 rounded text-sm">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $jobAlerts->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-bell-slash text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No job alerts created</h3>
                            <p class="text-gray-500 mb-6">Create your first job alert to get notified of matching opportunities</p>
                            <a href="{{ route('job-alerts.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg">
                                Create Job Alert
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>