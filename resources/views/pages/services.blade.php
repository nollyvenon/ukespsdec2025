@extends('layouts.public')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="max-w-4xl mx-auto">
                    <h1 class="text-4xl font-bold text-gray-900 mb-8 text-center">Our Services</h1>

                    <div class="prose prose-lg max-w-none">
                        <div class="bg-gray-50 rounded-xl p-8 mb-8">
                            <p class="text-gray-700 leading-relaxed">
                                {!! nl2br(e($servicesContent)) !!}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-graduation-cap text-indigo-600"></i>
                                </div>
                                <h3 class="text-xl font-semibold mb-2">Online Courses</h3>
                                <p class="text-gray-600">
                                    Access comprehensive courses from top institutions and expert instructors. Learn at your own pace with our flexible learning options.
                                </p>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-briefcase text-green-600"></i>
                                </div>
                                <h3 class="text-xl font-semibold mb-2">Job Matching</h3>
                                <p class="text-gray-600">
                                    Find career opportunities tailored to your skills and interests. Connect with employers directly through our platform.
                                </p>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                </div>
                                <h3 class="text-xl font-semibold mb-2">Events & Workshops</h3>
                                <p class="text-gray-600">
                                    Attend industry events, workshops, and conferences to network and enhance your professional skills.
                                </p>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-user-check text-purple-600"></i>
                                </div>
                                <h3 class="text-xl font-semibold mb-2">Career Coaching</h3>
                                <p class="text-gray-600">
                                    Get personalized guidance from industry experts to accelerate your career growth and development.
                                </p>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm mb-8">
                            <h2 class="text-2xl font-bold mb-4 text-gray-800">Why Choose Our Platform?</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <i class="fas fa-check-circle text-3xl text-green-500 mb-2"></i>
                                    <h3 class="font-semibold">Quality Content</h3>
                                </div>
                                <div class="text-center">
                                    <i class="fas fa-check-circle text-3xl text-green-500 mb-2"></i>
                                    <h3 class="font-semibold">Expert Instructors</h3>
                                </div>
                                <div class="text-center">
                                    <i class="fas fa-check-circle text-3xl text-green-500 mb-2"></i>
                                    <h3 class="font-semibold">Flexible Schedule</h3>
                                </div>
                            </div>
                        </div>

                        <div class="bg-indigo-50 rounded-lg p-6">
                            <h3 class="text-xl font-semibold mb-4 text-indigo-800">Need Help Choosing?</h3>
                            <p class="text-indigo-700 mb-4">
                                Our team can help you find the right service that matches your career goals.
                            </p>
                            <a href="{{ route('contact.form') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection