<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search CVs') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Form -->
            <div class="bg-white shadow rounded-lg mb-8 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Search CVs</h3>
                <form method="GET" action="{{ route('cv.search') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
                        <input type="text" name="search" id="search"
                               value="{{ request('search') }}"
                               placeholder="Skills, experience..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="location" id="location"
                               value="{{ request('location') }}"
                               placeholder="City, State..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Skills</label>
                        <input type="text" name="skills" id="skills"
                               value="{{ request('skills') }}"
                               placeholder="JavaScript, Python..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded">
                            Search CVs
                        </button>
                    </div>
                </form>
            </div>

            <!-- Search Results -->
            @if($cvs->count() > 0)
                <div class="mb-8">
                    <p class="text-gray-600 mb-4">Found {{ $cvs->total() }} CV{{ $cvs->total() != 1 ? 's' : '' }} 
                        @if(request('search')) for "{{ request('search') }}" @endif
                        @if(request('location')) in "{{ request('location') }}" @endif
                    </p>

                    <div class="space-y-6">
                        @foreach($cvs as $cv)
                            <div class="bg-white shadow rounded-lg p-6 border-l-4 {{ $cv->is_featured ? 'border-yellow-500' : 'border-indigo-500' }}">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 {{ $cv->is_featured ? 'text-yellow-600' : '' }}">
                                            {{ $cv->is_featured ? '⭐ ' : '' }}{{ $cv->original_name }}
                                        </h3>
                                        <p class="text-gray-600 mt-1">By {{ $cv->user->name }}</p>
                                        @if($cv->location)
                                            <p class="text-gray-600 text-sm mt-1"><i class="fas fa-map-marker-alt mr-1"></i> {{ $cv->location }}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end">
                                        @if($cv->is_featured)
                                            <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full mb-2">
                                                Featured
                                            </span>
                                        @endif
                                        <span class="inline-block bg-{{ $cv->status === 'active' ? 'green' : ($cv->status === 'archived' ? 'yellow' : 'red') }}-100 text-{{ $cv->status === 'active' ? 'green' : ($cv->status === 'archived' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                            {{ ucfirst($cv->status) }}
                                        </span>
                                    </div>
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
                                </div>

                                @if($cv->summary)
                                    <p class="text-gray-700 mt-3">{{ Str::limit(strip_tags($cv->summary), 200) }}</p>
                                @endif

                                <div class="mt-4 flex justify-between items-center">
                                    <div>
                                        @if($cv->extracted_skills && count($cv->extracted_skills) > 0)
                                            <div class="mt-2">
                                                <span class="text-sm font-medium text-gray-700">Skills:</span>
                                                <div class="flex flex-wrap gap-1 mt-1">
                                                    @foreach(array_slice($cv->extracted_skills, 0, 5) as $skill)
                                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $skill }}</span>
                                                    @endforeach
                                                    @if(count($cv->extracted_skills) > 5)
                                                        <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">+{{ count($cv->extracted_skills) - 5 }} more</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex space-x-2">
                                        <a href="{{ route('cv.download', $cv) }}" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm font-medium">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                        <button class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg text-sm font-medium">
                                            <i class="fas fa-envelope mr-1"></i> Contact
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $cvs->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-file-alt text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No CVs found</h3>
                    <p class="text-gray-500 mb-6">Try adjusting your search criteria</p>
                    <a href="{{ route('cv.search') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Clear Search
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>