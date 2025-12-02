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
                    <!-- Header Section -->
                    <section class="text-center py-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl mb-12">
                        <h1 class="text-4xl font-bold mb-4">Explore University Courses</h1>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">Find the perfect course to advance your career and achieve your academic goals</p>
                        
                        <!-- Quick Search -->
                        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-2 flex">
                            <input type="text" placeholder="Search for courses, universities, or fields..." class="flex-grow px-4 py-2 rounded-l-lg border-0 focus:outline-none">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-r-lg">Search</button>
                        </div>
                    </section>

                    <!-- Choose Your Sector of Preference -->
                    <section class="mb-16">
                        <h2 class="text-3xl font-bold mb-8 text-center">Choose Your Sector of Preference</h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            @foreach($popularSectors as $sector)
                                <div class="bg-gray-50 p-4 rounded-lg text-center shadow-sm hover:shadow-md transition cursor-pointer">
                                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <h3 class="font-bold">{{ ucfirst($sector) }}</h3>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <!-- Our Services -->
                    <section class="mb-16">
                        <h2 class="text-3xl font-bold mb-8 text-center">Our Services</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                                <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold mb-2">Wide Course Selection</h3>
                                <p class="text-gray-600">Access thousands of courses from top universities worldwide across various disciplines and levels.</p>
                            </div>
                            
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002-2h2a2 2 0 002 2" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold mb-2">Verified Certificates</h3>
                                <p class="text-gray-600">Earn recognized certificates that boost your CV and increase your employability.</p>
                            </div>
                            
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                                <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold mb-2">Expert Instructors</h3>
                                <p class="text-gray-600">Learn from industry experts and experienced professors with practical insights.</p>
                            </div>
                        </div>
                    </section>

                    <!-- Course List -->
                    <section class="mb-16">
                        <div class="flex justify-between items-center mb-8">
                            <h2 class="text-3xl font-bold">Featured Courses</h2>
                            <a href="{{ route('universities.search-courses') }}" class="text-blue-600 hover:underline font-medium">View All Courses</a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($featuredCourses as $course)
                                <div class="border rounded-lg overflow-hidden shadow-md">
                                    @if($course->course_image)
                                        <img src="{{ Storage::url($course->course_image) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="bg-gray-200 w-full h-48 flex items-center justify-center">
                                            <span class="text-gray-500">No Image</span>
                                        </div>
                                    @endif
                                    
                                    <div class="p-4">
                                        @if($course->university)
                                            <p class="text-sm text-gray-600 mb-1">{{ $course->university->name }}</p>
                                        @endif
                                        
                                        <h3 class="text-lg font-bold mb-2">{{ $course->title }}</h3>
                                        <p class="text-gray-600 text-sm mb-2">Level: {{ ucfirst($course->level) }}</p>
                                        <p class="text-gray-700 text-sm mb-4">{{ Str::limit($course->description, 100) }}</p>
                                        
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                                {{ $course->duration }} weeks
                                            </span>
                                            @if($course->fee)
                                                <span class="font-bold text-green-600">${{ number_format($course->fee, 2) }}</span>
                                            @else
                                                <span class="font-bold text-green-600">Free</span>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-4">
                                            <a href="{{ route('affiliated-courses.show', $course) }}" class="w-full bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded text-center inline-block">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-12">
                                    <p class="text-gray-500">No featured courses available at the moment.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <!-- About Us -->
                    <section class="mb-16 bg-gray-50 p-8 rounded-xl">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
                                <h2 class="text-3xl font-bold mb-4">About Our Courses Program</h2>
                                <p class="text-gray-700 mb-4">We partner with leading universities worldwide to bring you the best educational opportunities. Our platform connects students with top-quality courses across various disciplines and levels.</p>
                                <p class="text-gray-700 mb-6">From foundational courses to advanced master's programs, we have something for every learning goal. All our courses come with verified certificates that are recognized by employers globally.</p>
                                <a href="{{ route('pages.show', 'about-us') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">Learn More</a>
                            </div>
                            <div class="md:w-1/2 flex justify-center">
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-64 flex items-center justify-center">
                                    <span class="text-gray-500">About Us Image</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Testimonials -->
                    <section class="mb-16">
                        <h2 class="text-3xl font-bold mb-8 text-center">What Our Students Say</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            @forelse($testimonials as $testimonial)
                                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                                    <div class="flex items-center mb-4">
                                        @if($testimonial->avatar)
                                            <img src="{{ Storage::url($testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="w-12 h-12 rounded-full mr-4">
                                        @else
                                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 mr-4" />
                                        @endif
                                        <div>
                                            <h4 class="font-bold">{{ $testimonial->name }}</h4>
                                            @if($testimonial->position)
                                                <p class="text-sm text-gray-600">{{ $testimonial->position }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-gray-700 italic mb-4">"{{ $testimonial->testimonial }}"</p>
                                    @if($testimonial->rating)
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $testimonial->rating)
                                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 24 24">
                                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="col-span-full text-center py-8">
                                    <p class="text-gray-500">No testimonials available yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <!-- Learn More From Video -->
                    <section class="mb-16">
                        <h2 class="text-3xl font-bold mb-8 text-center">Learn More From Video</h2>
                        
                        <div class="flex justify-center">
                            <div class="w-full max-w-4xl aspect-w-16 aspect-h-9 bg-gray-200 rounded-xl overflow-hidden">
                                <div class="w-full h-96 bg-gray-300 rounded-xl flex items-center justify-center">
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="mt-4 text-gray-600">Video Content Area</p>
                                        <p class="text-sm text-gray-500">Videos can be uploaded by admin</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Email Subscription -->
                    <section class="bg-blue-50 p-8 rounded-xl">
                        <div class="text-center">
                            <h2 class="text-2xl font-bold mb-2">Stay Updated on New Courses</h2>
                            <p class="text-gray-600 mb-6">Subscribe to our newsletter to receive notifications about new courses and opportunities</p>
                            
                            <div class="max-w-xl mx-auto flex">
                                <input type="email" placeholder="Enter your email address" class="flex-grow px-4 py-3 rounded-l-lg border-0 focus:outline-none">
                                <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-r-lg">Subscribe</button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>