<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('University Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-blue-800">Affiliated Courses</h3>
                            <p class="text-3xl font-bold mt-2">{{ $totalAffiliatedCourses }}</p>
                        </div>
                        <div class="bg-green-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-green-800">Students Enrolled</h3>
                            <p class="text-3xl font-bold mt-2">{{ $totalStudents }}</p>
                        </div>
                        <div class="bg-purple-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-purple-800">Courses Active</h3>
                            <p class="text-3xl font-bold mt-2">
                                {{ $affiliatedCourses->where('status', 'published')->count() }}
                            </p>
                        </div>
                    </div>

                    <!-- Recent Courses -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Recent Courses</h3>
                            <a href="{{ route('university.courses.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Create Course
                            </a>
                        </div>
                        @if($affiliatedCourses->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Title</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($affiliatedCourses as $course)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                                    <div class="text-sm text-gray-500">{{ Str::limit($course->description, 50) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ ucfirst($course->level) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $course->duration }} weeks
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 py-1 rounded text-xs 
                                                        @if($course->status == 'published') bg-green-100 text-green-800
                                                        @elseif($course->status == 'draft') bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800 @endif">
                                                        {{ ucfirst($course->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:underline">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-600">No courses created yet.</p>
                        @endif
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('university.courses') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            View All Courses
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>