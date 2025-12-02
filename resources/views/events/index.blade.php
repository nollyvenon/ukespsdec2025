@extends('layouts.public')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Advanced Search Form -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Search Events</h2>
                    <form method="GET" action="{{ route('events.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Keyword</label>
                            <input type="text" name="search" id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Title, description, location..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="event_type" class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                            <select name="event_type" id="event_type"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All Types</option>
                                @foreach(['workshop', 'seminar', 'conference', 'training', 'webinar', 'course', 'meetup', 'social'] as $type)
                                    <option value="{{ $type }}" {{ request('event_type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" id="category"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All Categories</option>
                                @foreach(['education', 'technology', 'business', 'health', 'arts', 'sports', 'networking', 'career'] as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" name="location" id="location"
                                   value="{{ request('location') }}"
                                   placeholder="City, state..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">Min Price</label>
                            <input type="number" name="min_price" id="min_price"
                                   value="{{ request('min_price') }}"
                                   placeholder="0"
                                   step="0.01"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Max Price</label>
                            <input type="number" name="max_price" id="max_price"
                                   value="{{ request('max_price') }}"
                                   placeholder="1000"
                                   step="0.01"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" name="date_from" id="date_from"
                                   value="{{ request('date_from') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" name="date_to" id="date_to"
                                   value="{{ request('date_to') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded">
                                Search
                            </button>
                        </div>

                        <div class="flex items-end">
                            <a href="{{ route('events.index') }}"
                               class="w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Search Results Summary -->
                @if(request()->has('search') || request()->hasAny(['event_type', 'category', 'location', 'min_price', 'max_price', 'date_from', 'date_to']))
                    <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-medium text-blue-800">Search Results</h3>
                        <p class="text-sm text-blue-600">Found {{ $events->total() }} event{{ $events->total() != 1 ? 's' : '' }}
                        @if(request('search')) for "{{ request('search') }}" @endif
                        @if(request('event_type')) with type "{{ ucfirst(request('event_type')) }}" @endif
                        @if(request('category')) in "{{ ucfirst(request('category')) }}" category @endif
                        @if(request('location')) in "{{ request('location') }}" @endif
                        </p>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">All Events</h1>
                    @auth
                        <a href="{{ route('events.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Event
                        </a>
                    @endauth
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($events as $event)
                        <div class="border rounded-lg overflow-hidden shadow-md">
                            @if($event->event_image)
                                <img src="{{ Storage::url($event->event_image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="bg-gray-200 w-full h-48 flex items-center justify-center">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="text-xl font-bold mb-2">{{ $event->title }}</h3>
                                <p class="text-gray-600 text-sm mb-1">{{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y g:i A') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('F j, Y g:i A') }}</p>
                                <p class="text-gray-600 text-sm mb-2">Location: {{ $event->location }}</p>
                                <p class="text-gray-700 mb-4">{{ Str::limit($event->description, 100) }}</p>

                                <div class="flex justify-between items-center">
                                    <span class="inline-block bg-{{ $event->event_status === 'published' ? 'green' : ($event->event_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $event->event_status === 'published' ? 'green' : ($event->event_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                        {{ ucfirst($event->event_status) }}
                                    </span>
                                    <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:underline">View Details</a>
                                </div>

                                @if($event->event_status === 'published')
                                    <div class="mt-3">
                                        <a href="{{ route('events.register', $event) }}" class="w-full bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded text-center inline-block">Register</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-500">No events available at the moment.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection