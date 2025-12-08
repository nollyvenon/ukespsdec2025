@extends('layouts.app')

@section('title', $university->name . ' - Courses')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- University Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center">
            @if($university->logo)
                @if(filter_var($university->logo, FILTER_VALIDATE_URL))
                    <img src="{{ $university->logo }}" alt="{{ $university->name }}" class="w-16 h-16 object-contain mr-4">
                @else
                    <img src="{{ asset('storage/' . $university->logo) }}" alt="{{ $university->name }}" class="w-16 h-16 object-contain mr-4">
                @endif
            @endif
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $university->name }}</h1>
                <p class="text-gray-600">{{ $university->location }}</p>
                @if($university->country)
                    <p class="text-gray-600">{{ $university->country->name }}</p>
                @endif
            </div>
        </div>
        
        @if($university->description)
            <div class="mt-4">
                <p class="text-gray-700">{{ $university->description }}</p>
            </div>
        @endif
        
        <div class="mt-4 flex space-x-4">
            @if($university->website)
                <a href="{{ $university->website }}" target="_blank" class="text-blue-600 hover:underline">
                    <i class="fas fa-globe mr-1"></i> Website
                </a>
            @endif
            @if($university->email)
                <a href="mailto:{{ $university->email }}" class="text-blue-600 hover:underline">
                    <i class="fas fa-envelope mr-1"></i> {{ $university->email }}
                </a>
            @endif
        </div>
    </div>

    <!-- University Courses -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Available Courses</h2>
        
        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        @if($course->course_image)
                            @if(filter_var($course->course_image, FILTER_VALIDATE_URL))
                                <img src="{{ $course->course_image }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                            @else
                                <img src="{{ asset('storage/' . $course->course_image) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                            @endif
                        @else
                            <div class="bg-gray-200 w-full h-48 flex items-center justify-center">
                                <span class="text-gray-500">No Image</span>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $course->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($course->description, 100) }}</p>
                            
                            <div class="space-y-1 text-sm text-gray-600 mb-4">
                                <div><strong>Level:</strong> {{ ucfirst($course->level) }}</div>
                                <div><strong>Duration:</strong> {{ $course->duration }} weeks</div>
                                <div><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($course->start_date)->format('M d, Y') }}</div>
                                @if($course->fee)
                                    <div><strong>Fee:</strong> {{ number_format($course->fee, 2) }}</div>
                                @endif
                            </div>
                            
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if($course->skills_covered && is_array($course->skills_covered))
                                    @foreach(array_slice($course->skills_covered, 0, 3) as $skill)
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $skill }}</span>
                                    @endforeach
                                    @if(count($course->skills_covered) > 3)
                                        <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">+{{ count($course->skills_covered) - 3 }}</span>
                                    @endif
                                @endif
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('affiliated-courses.show', $course->id) }}" 
                                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded transition-colors">
                                    View Details
                                </a>
                                
                                @auth
                                    <form action="{{ route('affiliated-courses.enroll', $course->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded transition-colors">
                                            Apply
                                        </button>
                                    </form>
                                @endauth
                                
                                @guest
                                    <a href="{{ route('login') }}" 
                                       class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded transition-colors">
                                        Apply
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $courses->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <h3 class="text-xl font-semibold text-gray-700">No courses available</h3>
                <p class="text-gray-600 mt-2">This university currently has no courses listed.</p>
            </div>
        @endif
    </div>
    
    <!-- Contact University Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Contact University</h2>
        <p class="text-gray-600 mb-4">Get in touch with {{ $university->name }} for more information about their programs.</p>
        
        <div class="flex flex-col sm:flex-row gap-4">
            @if($university->email)
                <a href="mailto:{{ $university->email }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded text-center transition-colors">
                    <i class="fas fa-envelope mr-2"></i> Send Email
                </a>
            @endif
            @if($university->website)
                <a href="{{ $university->website }}" 
                   target="_blank" 
                   class="bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded text-center transition-colors">
                    <i class="fas fa-globe mr-2"></i> Visit Website
                </a>
            @endif
            @if($university->phone)
                <a href="tel:{{ $university->phone }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-6 rounded text-center transition-colors">
                    <i class="fas fa-phone mr-2"></i> Call
                </a>
            @endif
        </div>
    </div>
</div>
@endsection