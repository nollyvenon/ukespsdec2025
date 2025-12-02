<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Course Enrollments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">My Course Enrollments</h1>

                    @if($enrollments->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-graduation-cap text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No enrollments yet</h3>
                            <p class="text-gray-500 mb-6">You haven't enrolled in any courses yet.</p>
                            <a href="{{ route('courses.index') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                Browse Courses
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($enrollments as $enrollment)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        {{ $enrollment->course->title }}
                                    </h3>
                                    
                                    <p class="text-gray-600 text-sm mb-4">
                                        {{ Str::limit($enrollment->course->description, 100) }}
                                    </p>
                                    
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span>Completion: {{ $enrollment->completion_percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $enrollment->completion_percentage }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-sm text-gray-500 mb-4">
                                        <p>Enrolled: {{ $enrollment->created_at->format('M d, Y') }}</p>
                                        <p>Status: <span class="font-medium">{{ ucfirst($enrollment->enrollment_status) }}</span></p>
                                    </div>
                                    
                                    <div class="flex space-x-3">
                                        <a href="{{ route('courses.show', $enrollment->course) }}" 
                                           class="flex-1 text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                                            View Course
                                        </a>
                                        
                                        @if($enrollment->course->start_date >= now())
                                            <a href="{{ route('courses.show', $enrollment->course) }}"
                                               class="flex-1 text-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
                                                Track Progress
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $enrollments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>