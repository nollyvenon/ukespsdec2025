@extends('layouts.public')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">University Courses</h1>
                        <p class="text-gray-600">{{ $courses->total() }} courses available</p>
                    </div>
                </div>

                @if($courses->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @foreach($courses as $course)
                            <div class="border border-gray-200 rounded-lg p-6 hover:border-indigo-300 transition duration-300">
                                <div class="flex">
                                    <div class="flex-shrink-0 mr-6">
                                        @if($course->course_image)
                                            <img src="{{ Storage::url($course->course_image) }}" alt="{{ $course->title }}" class="w-32 h-24 object-cover rounded">
                                        @else
                                            <div class="bg-gray-200 w-32 h-24 flex items-center justify-center rounded">
                                                <i class="fas fa-graduation-cap text-gray-500 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-900">
                                                    <a href="{{ route('affiliated-courses.show', $course) }}" class="hover:text-indigo-600">
                                                        {{ $course->title }}
                                                    </a>
                                                </h3>
                                                <p class="text-gray-600 mt-1">From {{ $course->university_name }}</p>
                                            </div>
                                            <span class="inline-block bg-{{ $course->status === 'published' || $course->status === 'ongoing' ? 'green' : ($course->status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $course->status === 'published' || $course->status === 'ongoing' ? 'green' : ($course->status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                                {{ ucfirst($course->status) }}
                                            </span>
                                        </div>

                                        <div class="mt-3 flex flex-wrap gap-2 text-sm">
                                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                <i class="fas fa-chart-line mr-1"></i> {{ ucfirst($course->level) }}
                                            </span>
                                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                <i class="fas fa-clock mr-1"></i> {{ $course->duration }} weeks
                                            </span>
                                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                <i class="fas fa-coins mr-1"></i> ${{ number_format($course->fee, 2) }}
                                            </span>
                                        </div>

                                        <p class="text-gray-700 mt-3">{{ Str::limit($course->description, 150) }}</p>

                                        <div class="mt-4 flex justify-between items-center">
                                            <div>
                                                @if($course->fee > 0)
                                                    <p class="text-lg font-bold text-green-600">${{ number_format($course->fee, 2) }}</p>
                                                @else
                                                    <p class="text-lg font-bold text-green-600">Free</p>
                                                @endif
                                            </div>

                                            <div>
                                                @if($course->status === 'published' && $course->start_date > now() && (!$course->max_enrollment || $course->affiliatedCourseEnrollments()->count() < $course->max_enrollment))
                                                    <a href="{{ route('affiliated-courses.enroll', $course) }}" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm font-medium">
                                                        Enroll Now
                                                    </a>
                                                @endif
                                                <a href="{{ route('affiliated-courses.show', $course) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg text-sm font-medium ml-2">
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
                        {{ $courses->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-graduation-cap text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">No university courses found</h3>
                        <p class="text-gray-500 mb-6">Try browsing other courses or check back later</p>
                        <a href="{{ route('courses.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Browse All Courses
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection