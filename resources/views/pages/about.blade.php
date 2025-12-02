@extends('layouts.public')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="max-w-4xl mx-auto">
                    <h1 class="text-4xl font-bold text-gray-900 mb-8 text-center">About Our Platform</h1>

                    <div class="prose prose-lg max-w-none">
                        <div class="bg-gray-50 rounded-xl p-8 mb-8">
                            <p class="text-gray-700 leading-relaxed">
                                {!! nl2br(e($aboutContent)) !!}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-lightbulb text-indigo-600 text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold mb-2">Our Mission</h3>
                                <p class="text-gray-600">Connecting learners with the best opportunities for their career growth.</p>
                            </div>

                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-green-600 text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold mb-2">Community</h3>
                                <p class="text-gray-600">Building a community of learners and industry professionals.</p>
                            </div>

                            <div class="text-center">
                                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-graduation-cap text-purple-600 text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold mb-2">Learning</h3>
                                <p class="text-gray-600">Providing access to quality education and professional development.</p>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                            <h2 class="text-2xl font-bold mb-4 text-gray-800">Our Story</h2>
                            <p class="text-gray-700 mb-4">
                                Founded with the vision of making quality education accessible to all, our platform has grown to become a trusted destination for learners and professionals worldwide.
                            </p>
                            <p class="text-gray-700">
                                We believe that everyone deserves the opportunity to advance their career and achieve their goals, regardless of their background or location.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection