<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course Details') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Course Header Section -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-6">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                @if($course->is_premium)
                                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full mr-3">
                                        Premium
                                    </span>
                                    <span class="text-yellow-600 mr-2">⭐</span>
                                @endif
                                <h1 class="text-3xl font-bold text-gray-900">{{ $course->title }}</h1>
                            </div>
                            <div class="flex flex-wrap items-center text-sm text-gray-600 mb-4">
                                <span class="mr-4">Level: {{ ucfirst($course->level) }}</span>
                                <span class="mr-4">Duration: {{ $course->duration }} weeks</span>
                                <span class="mr-4">Starts: {{ \Carbon\Carbon::parse($course->start_date)->format('F j, Y') }}</span>
                                <span class="inline-block bg-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'green' : ($course->course_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'green' : ($course->course_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                    {{ ucfirst($course->course_status) }}
                                </span>
                            </div>
                        </div>
                        @if(Auth::check() && Auth::id() === $course->instructor_id)
                            <div class="flex space-x-2 mt-4 md:mt-0">
                                <a href="{{ route('courses.edit', $course) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white py-2 px-4 rounded text-sm">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('courses.destroy', $course) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded text-sm" onclick="return confirm('Are you sure you want to delete this course?')">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left Column -->
                        <div class="lg:col-span-2">
                            <!-- Course Image -->
                            @if($course->course_image)
                                <div class="mb-6">
                                    <img src="{{ Storage::url($course->course_image) }}" alt="{{ $course->title }}" class="w-full h-64 object-cover rounded-lg">
                                </div>
                            @endif

                            <!-- Course Description -->
                            <div class="mb-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">Course Description</h2>
                                <div class="text-gray-700 prose">
                                    <p class="whitespace-pre-line">{!! nl2br(e($course->description)) !!}</p>
                                </div>
                            </div>

                            <!-- Syllabus -->
                            @if($course->syllabus)
                                <div class="mb-6">
                                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">Syllabus</h2>
                                    <div class="text-gray-700 whitespace-pre-line">
                                        {{ $course->syllabus }}
                                    </div>
                                </div>
                            @endif

                            <!-- Prerequisites -->
                            @if($course->prerequisites)
                                <div class="mb-6">
                                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">Prerequisites</h2>
                                    <div class="text-gray-700 whitespace-pre-line">
                                        {{ $course->prerequisites }}
                                    </div>
                                </div>
                            @endif

                            <!-- What You'll Learn -->
                            <div class="mb-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">What You'll Learn</h2>
                                <ul class="space-y-2">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                        <span>Essential skills and techniques in {{ $course->title }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                        <span>Industry-standard practices and methodologies</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                        <span>Real-world applications and case studies</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                        <span>Portfolio development and presentation skills</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Right Column - Course Info & Actions -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <h3 class="font-bold text-lg text-gray-900 mb-4">Course Information</h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Level:</span>
                                        <span class="font-medium">{{ ucfirst($course->level) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Duration:</span>
                                        <span class="font-medium">{{ $course->duration }} weeks</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Start Date:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($course->start_date)->format('F j, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">End Date:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($course->end_date)->format('F j, Y') }}</span>
                                    </div>
                                    @if($course->max_enrollment)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Max Enrollment:</span>
                                            <span class="font-medium">{{ $course->max_enrollment }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="inline-block bg-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'green' : ($course->course_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'green' : ($course->course_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                            {{ ucfirst($course->course_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Enrollment Action Card -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                                <div class="text-center mb-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Ready to Enroll?</h3>
                                    <p class="text-gray-600">Take the next step in your education</p>
                                </div>

                                @if($course->course_status === 'published' && $course->start_date > now() && (!$course->max_enrollment || $course->enrollments()->count() < $course->max_enrollment))
                                    <form action="{{ route('courses.enroll', $course) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                                            Enroll Now
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center">
                                        @if($course->course_status !== 'published')
                                            <p class="text-gray-500">This course is not currently open for enrollment.</p>
                                        @elseif($course->start_date <= now())
                                            <p class="text-gray-500">This course has already started.</p>
                                        @elseif($course->max_enrollment && $course->enrollments()->count() >= $course->max_enrollment)
                                            <p class="text-gray-500">This course has reached maximum enrollment.</p>
                                        @else
                                            <p class="text-gray-500">Enrollment is not available at this time.</p>
                                        @endif
                                    </div>
                                @endif

                                <div class="mt-4 text-center text-sm text-gray-500">
                                    Questions? <a href="{{ route('contact.form') }}" class="text-green-600 hover:underline">Contact us</a>
                                </div>
                            </div>

                            <!-- Instructor Info -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="font-bold text-lg text-gray-900 mb-4">Instructor</h3>
                                <div class="flex items-center">
                                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500 text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-medium text-gray-900">{{ $course->instructor->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $course->instructor->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>