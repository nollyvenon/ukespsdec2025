@extends('layouts.public')

@section('title', $university->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- University Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center">
            @if($university->logo)
                @if(filter_var($university->logo, FILTER_VALIDATE_URL))
                    <img src="{{ $university->logo }}" alt="{{ $university->name }}" class="w-24 h-24 object-contain mr-6 mb-4 md:mb-0">
                @else
                    <img src="{{ asset('storage/' . $university->logo) }}" alt="{{ $university->name }}" class="w-24 h-24 object-contain mr-6 mb-4 md:mb-0">
                @endif
            @else
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-24 h-24 flex items-center justify-center mr-6 mb-4 md:mb-0">
                    <i class="fas fa-university text-gray-500 text-2xl"></i>
                </div>
            @endif
            
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-800">{{ $university->name }}</h1>
                @if($university->acronym)
                    <p class="text-xl text-gray-600">{{ $university->acronym }}</p>
                @endif
                <p class="text-gray-600 mt-1">{{ $university->location }}</p>
                @if($university->country)
                    <p class="text-gray-600">{{ $university->country->name }}</p>
                @endif
            </div>
            
            <div class="mt-4 md:mt-0">
                <div class="flex flex-wrap gap-2">
                    @if($university->website)
                        <a href="{{ $university->website }}" target="_blank" 
                           class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition-colors flex items-center">
                            <i class="fas fa-globe mr-2"></i> Visit Website
                        </a>
                    @endif
                    @if($university->email)
                        <a href="mailto:{{ $university->email }}" 
                           class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded transition-colors flex items-center">
                            <i class="fas fa-envelope mr-2"></i> Email
                        </a>
                    @endif
                    @if($university->phone)
                        <a href="tel:{{ $university->phone }}" 
                           class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded transition-colors flex items-center">
                            <i class="fas fa-phone mr-2"></i> Call
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if($university->description)
            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">About</h2>
                <p class="text-gray-700">{{ $university->description }}</p>
            </div>
        @endif
    </div>

    <!-- University Stats -->
    @if($university->established_year || $university->rating || $courses->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        @if($university->established_year)
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $university->established_year }}</div>
            <div class="text-gray-600">Established</div>
        </div>
        @endif
        @if($university->rating)
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <div class="text-2xl font-bold text-yellow-500 flex items-center justify-center">
                <i class="fas fa-star mr-1"></i> {{ number_format($university->rating, 1) }}
            </div>
            <div class="text-gray-600">Rating</div>
        </div>
        @endif
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $courses->count() }}</div>
            <div class="text-gray-600">Courses</div>
        </div>
    </div>
    @endif

    <!-- University Programs/Specialties -->
    @if($university->programs && is_array($university->programs) && !empty($university->programs))
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Programs & Specialties</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($university->programs as $program)
                <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">{{ $program }}</span>
            @endforeach
        </div>
    </div>
    @endif

    <!-- University Courses -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Available Courses</h2>

        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="border rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
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

                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $course->title }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ Str::limit($course->description, 100) }}</p>

                            <div class="space-y-1 text-sm text-gray-600 mb-3">
                                <div><strong>Level:</strong> {{ ucfirst($course->level) }}</div>
                                <div><strong>Duration:</strong> {{ $course->duration }} weeks</div>
                                @if($course->start_date)
                                    <div><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($course->start_date)->format('M d, Y') }}</div>
                                @endif
                                @if($course->fee > 0)
                                    <div><strong>Fee:</strong> ${{ number_format($course->fee, 2) }}</div>
                                @else
                                    <div><strong>Fee:</strong> Free</div>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-2 mb-3">
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
                                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded transition-colors text-sm">
                                    View Details
                                </a>

                                @auth
                                    <form action="{{ route('affiliated-courses.enroll', $course->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit"
                                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded transition-colors text-sm">
                                            Apply
                                        </button>
                                    </form>
                                @endauth

                                @guest
                                    <a href="{{ route('login') }}"
                                       class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded transition-colors text-sm">
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
            <div class="text-center py-8">
                <i class="fas fa-book-open text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700">No courses available</h3>
                <p class="text-gray-600 mt-2">This university currently has no published courses.</p>
            </div>
        @endif
    </div>
</div>
@endsection