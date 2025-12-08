@extends('layouts.public')

@section('title', 'Universities')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Universities</h1>
    
    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('universities.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Universities</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Search by name..." 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                    <select name="country" id="country" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">All Countries</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ request('country') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition-colors">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Universities Grid -->
    @if($universities->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($universities as $university)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    @if($university->logo)
                        @if(filter_var($university->logo, FILTER_VALIDATE_URL))
                            <img src="{{ $university->logo }}" alt="{{ $university->name }}" class="w-full h-48 object-contain bg-gray-100 p-4">
                        @else
                            <img src="{{ asset('storage/' . $university->logo) }}" alt="{{ $university->name }}" class="w-full h-48 object-contain bg-gray-100 p-4">
                        @endif
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $university->name }}</h3>
                        @if($university->acronym)
                            <p class="text-gray-600 mb-1">{{ $university->acronym }}</p>
                        @endif
                        <p class="text-gray-600 mb-2">{{ $university->location }}</p>
                        @if($university->country)
                            <p class="text-gray-600 text-sm mb-3">{{ $university->country->name }}</p>
                        @endif
                        
                        @if($university->description)
                            <p class="text-gray-700 mb-4">{{ Str::limit($university->description, 100) }}</p>
                        @endif
                        
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-600">
                                @if($university->rating)
                                    <i class="fas fa-star text-yellow-400"></i> {{ number_format($university->rating, 1) }}
                                @endif
                            </span>
                            
                            <!-- Course Count -->
                            <span class="text-sm text-gray-600">
                                {{ $university->courses_count }} courses
                            </span>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            @if($university->programs && is_array($university->programs) && !empty($university->programs))
                                @foreach(array_slice($university->programs, 0, 3) as $program)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1 mb-1">{{ $program }}</span>
                                @endforeach
                                @if(count($university->programs) > 3)
                                    <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded mr-1 mb-1">+{{ count($university->programs) - 3 }}</span>
                                @endif
                            @endif
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('universities.show', $university->id) }}" 
                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded transition-colors">
                                View Details
                            </a>
                            
                            @if($university->website)
                                <a href="{{ $university->website }}" 
                                   target="_blank" 
                                   class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded transition-colors">
                                    Visit Site
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $universities->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <h3 class="text-xl font-semibold text-gray-700">No universities found</h3>
            <p class="text-gray-600 mt-2">There are no universities matching your search criteria.</p>
        </div>
    @endif
</div>
@endsection