@extends('layouts.public')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold">University Courses</h1>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($courses as $course)
                        <div class="border rounded-lg overflow-hidden shadow-md">
                            @if($course->course_image)
                                <img src="{{ Storage::url($course->course_image) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="bg-gray-200 w-full h-48 flex items-center justify-center">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                            @endif

                            @if($course->university_logo)
                                <div class="p-2 bg-gray-100 border-b">
                                    <img src="{{ Storage::url($course->university_logo) }}" alt="{{ $course->university_name }}" class="h-10 object-contain">
                                </div>
                            @endif

                            <div class="p-4">
                                <h3 class="text-xl font-bold mb-2">{{ $course->title }}</h3>
                                <p class="text-gray-600 text-sm mb-1">{{ $course->university_name }}</p>
                                <p class="text-gray-600 text-sm mb-1">Level: {{ ucfirst($course->level) }}</p>
                                <p class="text-gray-600 text-sm mb-1">Duration: {{ $course->duration }} weeks</p>

                                @if($course->fee)
                                    <p class="text-green-600 font-bold mb-2">Fee: ${{ number_format($course->fee, 2) }}</p>
                                @else
                                    <p class="text-green-600 font-bold mb-2">Fee: Free</p>
                                @endif

                                <p class="text-gray-700 mb-4">{{ Str::limit($course->description, 100) }}</p>

                                <div class="flex justify-between items-center">
                                    <span class="inline-block bg-{{ $course->status === 'published' || $course->status === 'ongoing' ? 'green' : ($course->status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $course->status === 'published' || $course->status === 'ongoing' ? 'green' : ($course->status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                    <a href="{{ route('affiliated-courses.show', $course) }}" class="text-blue-600 hover:underline">View Details</a>
                                </div>

                                @if($course->status === 'published' && $course->start_date > now() && (!$course->max_enrollment || $course->affiliatedCourseEnrollments()->count() < $course->max_enrollment))
                                    <div class="mt-3">
                                        <a href="{{ route('affiliated-courses.enroll', $course) }}" class="w-full bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded text-center inline-block">Enroll Now</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-500">No affiliated courses available at the moment.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $courses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection