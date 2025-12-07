<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('CV Search') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">CV Search</h1>
                            <p class="text-gray-600">Find the right candidates for your job openings</p>
                        </div>
                        @auth
                            @if(auth()->user()->hasRole('recruiter', 'employer'))
                                <a href="{{ route('cv.create') }}" class="mt-4 md:mt-0 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                                    <i class="fas fa-upload mr-2"></i> Upload Your CV
                                </a>
                            @endif
                        @endauth
                    </div>

                    <!-- Search Form -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Find candidates</h2>
                        <form method="GET" action="{{ route('cv.search') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <div class="lg:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
                                <input type="text" name="search" id="search"
                                       value="{{ request('search') }}"
                                       placeholder="Enter job titles, skills, experience, or company names"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <input type="text" name="location" id="location"
                                       value="{{ request('location') }}"
                                       placeholder="Town or postcode"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Skills</label>
                                <input type="text" name="skills" id="skills"
                                       value="{{ request('skills') }}"
                                       placeholder="JavaScript, MySQL, etc."
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="experience_level" class="block text-sm font-medium text-gray-700 mb-1">Experience Level</label>
                                <select name="experience_level" id="experience_level"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">All Levels</option>
                                    <option value="entry" {{ request('experience_level') == 'entry' ? 'selected' : '' }}>Entry Level</option>
                                    <option value="junior" {{ request('experience_level') == 'junior' ? 'selected' : '' }}>Junior</option>
                                    <option value="mid" {{ request('experience_level') == 'mid' ? 'selected' : '' }}>Mid</option>
                                    <option value="senior" {{ request('experience_level') == 'senior' ? 'selected' : '' }}>Senior</option>
                                    <option value="executive" {{ request('experience_level') == 'executive' ? 'selected' : '' }}>Executive</option>
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button type="submit"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded">
                                    <i class="fas fa-search mr-2"></i> Search CVs
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Search Results Summary -->
                    @if(request()->hasAny(['search', 'location', 'skills', 'experience_level']))
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <h3 class="font-medium text-blue-800">Search results for "{{ request('search') ?: 'your search' }}"</h3>
                            <p class="text-sm text-blue-600">{{ $cvs->total() }} CV{{ $cvs->total() != 1 ? 's' : '' }} found
                                @if(request('location')) in "{{ request('location') }}" @endif
                                @if(request('experience_level')) with "{{ ucfirst(str_replace('_', ' ', request('experience_level'))) }}" level experience @endif
                            </p>
                        </div>
                    @endif

                    @if($cvs->count() > 0)
                        <div class="space-y-6">
                            @foreach($cvs as $cv)
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-indigo-300 transition duration-300 {{ $cv->is_featured ? 'border-2 border-yellow-400' : '' }}">
                                    <div class="flex">
                                        <div class="flex-shrink-0 mr-6">
                                            <div class="bg-gray-200 w-16 h-16 flex items-center justify-center rounded">
                                                <i class="fas fa-user text-gray-500 text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <div>
                                                    <h3 class="text-xl font-bold text-gray-900 {{ $cv->is_featured ? 'text-yellow-600' : '' }}">
                                                        {{ $cv->is_featured ? '⭐ ' : '' }}
                                                        <a href="{{ route('cv.show', $cv) }}" class="hover:text-indigo-600">
                                                            {{ $cv->original_name }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-gray-600 mt-1">By {{ $cv->user->name ?? 'User' }}</p>
                                                </div>
                                                <div class="flex flex-col items-end">
                                                    @if($cv->is_featured)
                                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-medium mb-1">
                                                            Featured
                                                        </span>
                                                    @endif
                                                    <span class="inline-block bg-{{ $cv->status === 'active' ? 'green' : ($cv->status === 'archived' ? 'yellow' : 'red') }}-100 text-{{ $cv->status === 'active' ? 'green' : ($cv->status === 'archived' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                                        {{ ucfirst($cv->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3 flex flex-wrap gap-2 text-sm">
                                                @if($cv->location)
                                                    <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $cv->location }}
                                                    </span>
                                                @endif
                                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                    <i class="fas fa-file mr-1"></i> {{ strtoupper($cv->file_type) }}
                                                </span>
                                                @if($cv->extracted_skills && count($cv->extracted_skills) > 0)
                                                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                        <i class="fas fa-code mr-1"></i> {{ count($cv->extracted_skills) }} skills
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-gray-700 mt-3">{{ Str::limit($cv->summary, 200) }}</p>
                                            
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="flex flex-wrap gap-2">
                                                    @if($cv->extracted_skills && count($cv->extracted_skills) > 0)
                                                        <div class="mt-2">
                                                            <span class="text-sm font-medium text-gray-700">Top Skills:</span>
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
                                                
                                                <div class="flex flex-wrap gap-3">
                                                    <a href="{{ route('cv.download', $cv) }}" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm font-medium inline-flex items-center">
                                                        <i class="fas fa-download mr-1"></i> Download
                                                    </a>
                                                    <a href="{{ route('cv.show', $cv) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg text-sm font-medium">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $cvs->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No CVs found</h3>
                            <p class="text-gray-500 mb-6">Try adjusting your search criteria or browse all CVs</p>
                            <a href="{{ route('cv.search') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded inline-flex items-center">
                                <i class="fas fa-sync-alt mr-2"></i> Reset Search
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>