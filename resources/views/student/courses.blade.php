<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Courses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">My Courses</h1>

                    @if($courses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($courses as $course)
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
                                        <p class="text-gray-600 text-sm mb-1">Instructor: {{ $course->instructor->name }}</p>
                                        <p class="text-gray-600 text-sm mb-1">Level: {{ ucfirst($course->level) }}</p>
                                        <p class="text-gray-600 text-sm mb-1">Duration: {{ $course->duration }} weeks</p>
                                        <a href="{{ route('courses.show', $course) }}" class="text-green-600 hover:underline mt-2 inline-block">View Details</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $courses->links() }}
                        </div>
                    @else
                        <p class="text-gray-600">You haven't enrolled in any courses yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>