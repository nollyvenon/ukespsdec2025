<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8 text-center">Course Search Results</h1>
                    
                    <div class="bg-blue-50 p-6 rounded-xl mb-12">
                        <form method="GET" action="{{ route('universities.search-results') }}" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div>
                                    <label for="level" class="block text-sm font-medium text-gray-700">Course Level</label>
                                    <select name="level" id="level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">All Levels</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->name }}" {{ request('level') == $level->name ? 'selected' : '' }}>{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                    <select name="country" id="country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">All Countries</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ request('country') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="university" class="block text-sm font-medium text-gray-700">University</label>
                                    <select name="university" id="university" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">All Universities</option>
                                        @foreach($universities as $university)
                                            <option value="{{ $university->id }}" {{ request('university') == $university->id ? 'selected' : '' }}>{{ $university->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="keywords" class="block text-sm font-medium text-gray-700">Keywords</label>
                                    <input 
                                        type="text" 
                                        name="keywords" 
                                        id="keywords" 
                                        placeholder="Search by title, description..." 
                                        value="{{ request('keywords') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    >
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg">
                                    Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="mb-8">
                        <h2 class="text-xl font-bold mb-4">Found {{ $courses->total() }} courses</h2>
                    </div>
                    
                    @if($courses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($courses as $course)
                                <div class="border rounded-lg overflow-hidden shadow-md">
                                    @if($course->course_image)
                                        <img src="{{ Storage::url($course->course_image) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="bg-gray-200 w-full h-48 flex items-center justify-center">
                                            <span class="text-gray-500">No Image</span>
                                        </div>
                                    @endif
                                    
                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="text-lg font-bold">{{ $course->title }}</h3>
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                                {{ $course->level }}
                                            </span>
                                        </div>
                                        
                                        @if($course->university)
                                            <div class="mb-2">
                                                <p class="text-sm text-gray-600">{{ $course->university->name }}</p>
                                                @if($course->university->country)
                                                    <p class="text-sm text-gray-600">{{ $course->university->country->name }}</p>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <p class="text-gray-700 text-sm mb-4">{{ Str::limit($course->description, 100) }}</p>
                                        
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                                {{ $course->duration }} weeks
                                            </span>
                                            <a href="{{ route('affiliated-courses.show', $course) }}" class="text-blue-600 hover:underline text-sm">Details</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $courses->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-xl font-medium text-gray-900">No courses found</h3>
                            <p class="mt-1 text-gray-500">Try adjusting your search criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>