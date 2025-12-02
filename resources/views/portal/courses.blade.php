<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Courses Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-8">
                        <h1 class="text-3xl font-bold">Courses Management Portal</h1>
                        <a href="{{ route('courses.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Create New Course
                        </a>
                    </div>
                    
                    <div class="mb-6">
                        <a href="{{ route('courses.my') }}" class="text-green-600 hover:underline">My Courses</a>
                        <span class="mx-2">|</span>
                        <a href="{{ route('courses.enrollments') }}" class="text-green-600 hover:underline">My Enrollments</a>
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
                                <div class="p-4">
                                    <h3 class="text-xl font-bold mb-2">{{ $course->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-1">Level: {{ ucfirst($course->level) }}</p>
                                    <p class="text-gray-600 text-sm mb-1">Duration: {{ $course->duration }} weeks</p>
                                    <p class="text-gray-700 mb-4">{{ Str::limit($course->description, 100) }}</p>
                                    
                                    <div class="flex justify-between items-center">
                                        <span class="inline-block bg-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'green' : ($course->course_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'green' : ($course->course_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                            {{ ucfirst($course->course_status) }}
                                        </span>
                                        <a href="{{ route('courses.show', $course) }}" class="text-green-600 hover:underline">View Details</a>
                                    </div>
                                    
                                    @if($course->course_status === 'published' && $course->start_date > now())
                                        <div class="mt-3">
                                            <a href="{{ route('courses.enroll', $course) }}" class="w-full bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded text-center inline-block">Enroll Now</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8">
                                <p class="text-gray-500">No courses available at the moment.</p>
                                <a href="{{ route('courses.create') }}" class="text-green-600 hover:underline mt-4 inline-block">Create your first course</a>
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
</x-app-layout>