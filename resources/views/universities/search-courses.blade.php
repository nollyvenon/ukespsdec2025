<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search University Courses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8 text-center">Find Your Perfect University Course</h1>
                    
                    <div class="bg-blue-50 p-8 rounded-xl mb-12">
                        <form method="GET" action="{{ route('universities.search-results') }}" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div>
                                    <label for="level" class="block text-sm font-medium text-gray-700">Course Level</label>
                                    <select name="level" id="level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">All Levels</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->name }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                    <select name="country" id="country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">All Countries</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="university" class="block text-sm font-medium text-gray-700">University</label>
                                    <select name="university" id="university" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">All Universities</option>
                                        @foreach($universities as $university)
                                            <option value="{{ $university->id }}">{{ $university->name }}</option>
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
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    >
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg">
                                    Find Courses
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold mb-6">Featured Universities</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($universities->take(6) as $university)
                                <div class="border rounded-lg overflow-hidden shadow-md">
                                    <div class="p-4">
                                        <h3 class="text-xl font-bold mb-2">{{ $university->name }}</h3>
                                        <p class="text-gray-600 text-sm mb-2">{{ $university->location }}</p>
                                        <p class="text-gray-600 text-sm mb-4">{{ $university->country->name }}</p>
                                        
                                        @if($university->description)
                                            <p class="text-gray-700 text-sm mb-4">{{ Str::limit($university->description, 100) }}</p>
                                        @endif
                                        
                                        <a href="{{ route('universities.courses', $university->id) }}" class="text-blue-600 hover:underline">View Courses</a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-8">
                                    <p class="text-gray-500">No universities available at the moment.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>