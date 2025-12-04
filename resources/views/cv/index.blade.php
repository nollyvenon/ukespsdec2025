<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My CVs') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">My Curriculum Vitae</h1>
                            <p class="text-gray-600">Manage your CVs and job applications</p>
                        </div>
                        <a href="{{ route('cv.create') }}" class="mt-4 sm:mt-0 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                            <i class="fas fa-upload mr-2"></i> Upload CV
                        </a>
                    </div>

                    @if($cvUploads->count() > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            @foreach($cvUploads as $cv)
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-indigo-300 transition duration-300">
                                    <div class="flex">
                                        <div class="flex-shrink-0 mr-6">
                                            <div class="bg-gray-200 w-16 h-16 flex items-center justify-center rounded">
                                                <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <div>
                                                    <h3 class="text-xl font-bold text-gray-900 {{ $cv->is_premium ? 'text-yellow-600' : '' }}">
                                                        {{ $cv->is_premium ? '⭐ ' : '' }}
                                                        <a href="{{ route('cv.show', $cv) }}" class="hover:text-indigo-600">
                                                            {{ $cv->original_name }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-gray-600 mt-1">Uploaded: {{ $cv->created_at->diffForHumans() }}</p>
                                                </div>
                                                @if($cv->is_premium)
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-medium">
                                                        Premium
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="mt-4 flex flex-wrap gap-2 text-sm">
                                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                    <i class="fas fa-file-alt mr-1"></i> {{ strtoupper($cv->file_type) }}
                                                </span>
                                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                    <i class="fas fa-weight mr-1"></i> {{ number_format($cv->file_size / 1024, 1) }} KB
                                                </span>
                                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                    <i class="fas fa-eye mr-1"></i> {{ $cv->view_count }} views
                                                </span>
                                                @if($cv->location)
                                                    <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $cv->location }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            @if($cv->summary)
                                                <p class="text-gray-700 mt-3 text-sm">{{ Str::limit(strip_tags($cv->summary), 150) }}</p>
                                            @endif>
                                            
                                            <div class="mt-4 flex justify-between items-center">
                                                <span class="inline-block bg-{{ $cv->status === 'active' ? 'green' : ($cv->status === 'archived' ? 'yellow' : 'red') }}-100 text-{{ $cv->status === 'active' ? 'green' : ($cv->status === 'archived' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                                    {{ ucfirst($cv->status) }}
                                                </span>
                                                
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('cv.download', $cv) }}" class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded text-sm">
                                                        <i class="fas fa-download mr-1"></i> Download
                                                    </a>
                                                    <a href="{{ route('cv.edit', $cv) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-1 px-3 rounded text-sm">
                                                        <i class="fas fa-edit mr-1"></i> Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $cvUploads->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-file-alt text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No CVs uploaded yet</h3>
                            <p class="text-gray-500 mb-6">Get started by uploading your first CV</p>
                            <a href="{{ route('cv.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                                <i class="fas fa-upload mr-2"></i> Upload CV Now
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>