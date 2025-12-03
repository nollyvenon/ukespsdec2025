<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events Manager Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-blue-800">Events Created</h3>
                            <p class="text-3xl font-bold mt-2">{{ $totalEventsCreated }}</p>
                        </div>
                        <div class="bg-green-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-green-800">Total Registrations</h3>
                            <p class="text-3xl font-bold mt-2">{{ $totalEventRegistrations }}</p>
                        </div>
                        <div class="bg-purple-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-purple-800">Active Events</h3>
                            <p class="text-3xl font-bold mt-2">
                                {{ $recentEvents->where('event_status', 'published')->count() }}
                            </p>
                        </div>
                    </div>

                    <!-- Recent Events and Registrations -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Recent Events -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-bold mb-4">Recent Events</h3>
                            @if($recentEvents->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($recentEvents as $event)
                                        <li class="border-b pb-3">
                                            <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:underline">
                                                <h4 class="font-semibold">{{ $event->title }}</h4>
                                            </a>
                                            <p class="text-sm text-gray-600">{{ $event->location }}</p>
                                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($event->start_date)->format('M j, Y') }} • 
                                                <span class="px-2 py-1 rounded text-xs 
                                                    @if($event->event_status == 'published') bg-green-100 text-green-800
                                                    @elseif($event->event_status == 'draft') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($event->event_status) }}
                                                </span>
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-600">No events created yet.</p>
                            @endif
                        </div>

                        <!-- Recent Registrations -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-bold mb-4">Recent Registrations</h3>
                            @if($recentRegistrations->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($recentRegistrations as $registration)
                                        <li class="border-b pb-3">
                                            <h4 class="font-semibold">
                                                <a href="{{ route('events.show', $registration->event) }}" class="text-blue-600 hover:underline">
                                                    {{ $registration->event->title }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-600">Attendee: {{ $registration->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $registration->created_at->diffForHumans() }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-600">No registrations received yet.</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('events.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create New Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>