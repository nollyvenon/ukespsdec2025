@extends('layouts.public')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Frequently Asked Questions</h1>

                @if($faqs->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-question-circle text-5xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">No FAQs available</h3>
                        <p class="text-gray-500">Check back later for frequently asked questions.</p>
                    </div>
                @else
                    @foreach($faqs as $faq)
                        @if($faq->is_active)
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $faq->question }}</h3>
                                <div class="ml-4 text-gray-700">
                                    <p>{!! nl2br(e($faq->answer)) !!}</p>
                                    @if($faq->category !== 'general')
                                        <span class="inline-block mt-2 px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded-full">{{ $faq->category }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection