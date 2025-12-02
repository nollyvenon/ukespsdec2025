<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">{{ $course->title }}</h1>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span>Level: {{ ucfirst($course->level) }}</span>
                                <span>•</span>
                                <span>Duration: {{ $course->duration }} weeks</span>
                                <span>•</span>
                                <span class="inline-block bg-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'green' : ($course->course_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'green' : ($course->course_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                    {{ ucfirst($course->course_status) }}
                                </span>
                            </div>
                        </div>
                        @if(Auth::id() === $course->instructor_id)
                            <div class="flex space-x-2">
                                <a href="{{ route('courses.edit', $course) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-3 rounded text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('courses.destroy', $course) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded text-sm" onclick="return confirm('Are you sure you want to delete this course?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    
                    @if($course->course_image)
                        <div class="mb-6">
                            <img src="{{ Storage::url($course->course_image) }}" alt="{{ $course->title }}" class="w-full h-64 object-cover rounded">
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="font-bold text-lg mb-2">Course Details</h3>
                            <p><span class="font-medium">Level:</span> {{ ucfirst($course->level) }}</p>
                            <p><span class="font-medium">Duration:</span> {{ $course->duration }} weeks</p>
                            <p><span class="font-medium">Starts:</span> {{ \Carbon\Carbon::parse($course->start_date)->format('F j, Y') }}</p>
                            <p><span class="font-medium">Ends:</span> {{ \Carbon\Carbon::parse($course->end_date)->format('F j, Y') }}</p>
                            @if($course->max_enrollment)
                                <p><span class="font-medium">Max Enrollment:</span> {{ $course->max_enrollment }}</p>
                            @endif
                        </div>
                        
                        <div class="md:col-span-2">
                            <h3 class="font-bold text-lg mb-2">Description</h3>
                            <p class="whitespace-pre-line">{{ $course->description }}</p>
                            
                            @if($course->prerequisites)
                                <h4 class="font-bold mt-4">Prerequisites</h4>
                                <p class="whitespace-pre-line">{{ $course->prerequisites }}</p>
                            @endif
                            
                            @if($course->syllabus)
                                <h4 class="font-bold mt-4">Syllabus</h4>
                                <p class="whitespace-pre-line">{{ $course->syllabus }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        @if($course->course_status === 'published' && $course->start_date > now() && (!$course->max_enrollment || $course->enrollments()->count() < $course->max_enrollment))
                            <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                                    Enroll in Course
                                </button>
                            </form>
                        @elseif($course->course_status !== 'published')
                            <p class="text-gray-500">This course is not currently open for enrollment.</p>
                        @elseif($course->start_date <= now())
                            <p class="text-gray-500">This course has already started.</p>
                        @elseif($course->max_enrollment && $course->enrollments()->count() >= $course->max_enrollment)
                            <p class="text-gray-500">This course has reached maximum enrollment.</p>
                        @else
                            <p class="text-gray-500">Enrollment is not available at this time.</p>
                        @endif
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="font-bold text-lg mb-4">Instructor</h3>
                        <div class="flex items-center">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16" />
                            <div class="ml-4">
                                <p class="font-medium">{{ $course->instructor->name }}</p>
                                <p class="text-sm text-gray-600">{{ $course->instructor->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>