<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course Details') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Course Header -->
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-8">
                        <div class="flex-1 mb-6 md:mb-0">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $affiliatedCourse->title }}</h1>
                            <div class="flex flex-wrap items-center text-sm text-gray-600 mb-4">
                                <span class="mr-4">From: {{ $affiliatedCourse->university_name }}</span>
                                <span class="mr-4">Level: {{ ucfirst($affiliatedCourse->level) }}</span>
                                <span class="mr-4">Duration: {{ $affiliatedCourse->duration }} weeks</span>
                                <span class="inline-block bg-{{ $affiliatedCourse->status === 'published' || $affiliatedCourse->status === 'ongoing' ? 'green' : ($affiliatedCourse->status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $affiliatedCourse->status === 'published' || $affiliatedCourse->status === 'ongoing' ? 'green' : ($affiliatedCourse->status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                    {{ ucfirst($affiliatedCourse->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Main Content -->
                        <div class="lg:col-span-2">
                            <!-- Course Image -->
                            @if($affiliatedCourse->course_image)
                                <div class="mb-6">
                                    <img src="{{ Storage::url($affiliatedCourse->course_image) }}" alt="{{ $affiliatedCourse->title }}" class="w-full h-64 object-cover rounded-lg">
                                </div>
                            @endif

                            <!-- Course Information Card -->
                            <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Institution</h3>
                                        <p class="text-xl font-bold text-gray-900">{{ $affiliatedCourse->university_name }}</p>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Fee</h3>
                                        @if($affiliatedCourse->fee > 0)
                                            <p class="text-2xl font-bold text-green-600">${{ number_format($affiliatedCourse->fee, 2) }}</p>
                                        @else
                                            <p class="text-2xl font-bold text-green-600">Free</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Course Description -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b">Course Overview</h2>
                                <div class="text-gray-700 whitespace-pre-line">
                                    {{ $affiliatedCourse->description }}
                                </div>
                            </div>

                            <!-- Skills Covered -->
                            @if($affiliatedCourse->skills_covered && count($affiliatedCourse->skills_covered) > 0)
                                <div class="mb-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b">Skills You'll Gain</h2>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($affiliatedCourse->skills_covered as $skill)
                                            <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Syllabus -->
                            @if($affiliatedCourse->syllabus)
                                <div class="mb-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b">Syllabus</h2>
                                    <div class="text-gray-700 whitespace-pre-line">
                                        {{ $affiliatedCourse->syllabus }}
                                    </div>
                                </div>
                            @endif

                            <!-- Prerequisites -->
                            @if($affiliatedCourse->prerequisites)
                                <div class="mb-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b">Prerequisites</h2>
                                    <div class="text-gray-700 whitespace-pre-line">
                                        {{ $affiliatedCourse->prerequisites }}
                                    </div>
                                </div>
                            @endif

                            <!-- Career Outcomes -->
                            @if($affiliatedCourse->career_outcomes && count($affiliatedCourse->career_outcomes) > 0)
                                <div class="mb-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b">Career Outcomes</h2>
                                    <ul class="list-disc pl-5 space-y-2">
                                        @foreach($affiliatedCourse->career_outcomes as $outcome)
                                            <li class="text-gray-700">{{ $outcome }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- What You'll Learn -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b">What You'll Learn</h2>
                                <ul class="space-y-2">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                        <span>Essential skills and techniques in {{ $affiliatedCourse->title }}</span>
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

                        <!-- Sidebar - Course Info & Actions -->
                        <div class="lg:col-span-1">
                            <!-- Course Details Card -->
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <h3 class="font-bold text-lg text-gray-900 mb-4">Course Information</h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">University:</span>
                                        <span class="font-medium">{{ $affiliatedCourse->university_name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Level:</span>
                                        <span class="font-medium">{{ ucfirst($affiliatedCourse->level) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Duration:</span>
                                        <span class="font-medium">{{ $affiliatedCourse->duration }} weeks</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Start Date:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($affiliatedCourse->start_date)->format('F j, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">End Date:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($affiliatedCourse->end_date)->format('F j, Y') }}</span>
                                    </div>
                                    @if($affiliatedCourse->max_enrollment)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Max Enrollment:</span>
                                            <span class="font-medium">{{ $affiliatedCourse->max_enrollment }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Fee:</span>
                                        @if($affiliatedCourse->fee > 0)
                                            <span class="font-medium text-green-600 font-bold">${{ number_format($affiliatedCourse->fee, 2) }}</span>
                                        @else
                                            <span class="font-medium text-green-600 font-bold">Free</span>
                                        @endif
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="inline-block bg-{{ $affiliatedCourse->status === 'published' || $affiliatedCourse->status === 'ongoing' ? 'green' : ($affiliatedCourse->status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $affiliatedCourse->status === 'published' || $affiliatedCourse->status === 'ongoing' ? 'green' : ($affiliatedCourse->status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                            {{ ucfirst($affiliatedCourse->status) }}
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

                                @if($affiliatedCourse->status === 'published' && $affiliatedCourse->start_date > now() && (!$affiliatedCourse->max_enrollment || $affiliatedCourse->affiliatedCourseEnrollments()->count() < $affiliatedCourse->max_enrollment))
                                    <form action="{{ route('affiliated-courses.enroll', $affiliatedCourse) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                                            Enroll Now
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center">
                                        @if($affiliatedCourse->status !== 'published')
                                            <p class="text-gray-500">This course is not currently open for enrollment.</p>
                                        @elseif($affiliatedCourse->start_date <= now())
                                            <p class="text-gray-500">This course has already started.</p>
                                        @elseif($affiliatedCourse->max_enrollment && $affiliatedCourse->affiliatedCourseEnrollments()->count() >= $affiliatedCourse->max_enrollment)
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

                            <!-- Institution Info -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="font-bold text-lg text-gray-900 mb-4">Institution</h3>
                                <div class="flex items-center">
                                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex items-center justify-center">
                                        <i class="fas fa-university text-gray-500 text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-medium text-gray-900">{{ $affiliatedCourse->university_name }}</h4>
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