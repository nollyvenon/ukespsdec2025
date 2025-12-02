<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Affiliated Course Enrollments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">My Affiliated Course Enrollments</h1>

                    @if($enrollments->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-graduation-cap text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No affiliated course enrollments yet</h3>
                            <p class="text-gray-500 mb-6">You haven't enrolled in any affiliated courses yet.</p>
                            <a href="{{ route('affiliated-courses.index') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                Browse Affiliated Courses
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($enrollments as $enrollment)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        {{ $enrollment->affiliatedCourse->title }}
                                    </h3>
                                    
                                    <p class="text-gray-600 text-sm mb-2">
                                        University: {{ $enrollment->affiliatedCourse->university->name ?? 'N/A' }}
                                    </p>
                                    
                                    <p class="text-gray-600 text-sm mb-4">
                                        {{ Str::limit($enrollment->affiliatedCourse->description, 100) }}
                                    </p>
                                    
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span>Start Date: {{ $enrollment->affiliatedCourse->start_date->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span>End Date: {{ $enrollment->affiliatedCourse->end_date->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="text-sm text-gray-500 mb-4">
                                        <p>Enrolled: {{ $enrollment->created_at->format('M d, Y') }}</p>
                                        <p>Level: {{ ucfirst($enrollment->affiliatedCourse->level ?? 'Unknown') }}</p>
                                    </div>
                                    
                                    <div class="flex space-x-3">
                                        <a href="{{ route('affiliated-courses.show', $enrollment->affiliatedCourse) }}" 
                                           class="flex-1 text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                                            View Course
                                        </a>
                                        
                                        <a href="#" 
                                           class="flex-1 text-center bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 text-sm">
                                            Certificate
                                        </a>
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