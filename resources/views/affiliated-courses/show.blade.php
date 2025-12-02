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
                            <h1 class="text-3xl font-bold mb-2">{{ $affiliatedCourse->title }}</h1>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span>{{ $affiliatedCourse->university_name }}</span>
                                <span>•</span>
                                <span>Level: {{ ucfirst($affiliatedCourse->level) }}</span>
                                <span>•</span>
                                <span class="inline-block bg-{{ $affiliatedCourse->status === 'published' || $affiliatedCourse->status === 'ongoing' ? 'green' : ($affiliatedCourse->status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $affiliatedCourse->status === 'published' || $affiliatedCourse->status === 'ongoing' ? 'green' : ($affiliatedCourse->status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                    {{ ucfirst($affiliatedCourse->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($affiliatedCourse->course_image)
                        <div class="mb-6">
                            <img src="{{ Storage::url($affiliatedCourse->course_image) }}" alt="{{ $affiliatedCourse->title }}" class="w-full h-64 object-cover rounded">
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="font-bold text-lg mb-2">Course Details</h3>
                            <p><span class="font-medium">University:</span> {{ $affiliatedCourse->university_name }}</p>
                            <p><span class="font-medium">Level:</span> {{ ucfirst($affiliatedCourse->level) }}</p>
                            <p><span class="font-medium">Duration:</span> {{ $affiliatedCourse->duration }} weeks</p>
                            <p><span class="font-medium">Starts:</span> {{ \Carbon\Carbon::parse($affiliatedCourse->start_date)->format('F j, Y') }}</p>
                            <p><span class="font-medium">Ends:</span> {{ \Carbon\Carbon::parse($affiliatedCourse->end_date)->format('F j, Y') }}</p>
                            @if($affiliatedCourse->max_enrollment)
                                <p><span class="font-medium">Max Enrollment:</span> {{ $affiliatedCourse->max_enrollment }}</p>
                            @endif
                            @if($affiliatedCourse->fee)
                                <p class="font-bold text-green-600 mt-2">Fee: ${{ number_format($affiliatedCourse->fee, 2) }}</p>
                            @else
                                <p class="font-bold text-green-600 mt-2">Fee: Free</p>
                            @endif
                        </div>
                        
                        <div class="md:col-span-2">
                            <h3 class="font-bold text-lg mb-2">Description</h3>
                            <p class="whitespace-pre-line">{{ $affiliatedCourse->description }}</p>
                            
                            @if($affiliatedCourse->prerequisites)
                                <h4 class="font-bold mt-4">Prerequisites</h4>
                                <p class="whitespace-pre-line">{{ $affiliatedCourse->prerequisites }}</p>
                            @endif
                            
                            @if($affiliatedCourse->syllabus)
                                <h4 class="font-bold mt-4">Syllabus</h4>
                                <p class="whitespace-pre-line">{{ $affiliatedCourse->syllabus }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($affiliatedCourse->skills_covered)
                        <div class="mb-8">
                            <h3 class="font-bold text-lg mb-2">Skills Covered</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($affiliatedCourse->skills_covered as $skill)
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @if($affiliatedCourse->career_outcomes)
                        <div class="mb-8">
                            <h3 class="font-bold text-lg mb-2">Career Outcomes</h3>
                            <ul class="list-disc pl-5">
                                @foreach($affiliatedCourse->career_outcomes as $outcome)
                                    <li>{{ $outcome }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="mt-8">
                        @if($affiliatedCourse->status === 'published' && $affiliatedCourse->start_date > now() && (!$affiliatedCourse->max_enrollment || $affiliatedCourse->affiliatedCourseEnrollments()->count() < $affiliatedCourse->max_enrollment))
                            <form action="{{ route('affiliated-courses.enroll', $affiliatedCourse) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                                    Enroll in Course
                                </button>
                            </form>
                        @elseif($affiliatedCourse->status !== 'published')
                            <p class="text-gray-500">This course is not currently open for enrollment.</p>
                        @elseif($affiliatedCourse->start_date <= now())
                            <p class="text-gray-500">This course has already started.</p>
                        @elseif($affiliatedCourse->max_enrollment && $affiliatedCourse->affiliatedCourseEnrollments()->count() >= $affiliatedCourse->max_enrollment)
                            <p class="text-gray-500">This course has reached maximum enrollment.</p>
                        @else
                            <p class="text-gray-500">Enrollment is not available at this time.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>