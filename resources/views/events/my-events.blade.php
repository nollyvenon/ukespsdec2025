<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">My Events</h1>
                        <a href="{{ route('events.create') }}" 
                           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            Create Event
                        </a>
                    </div>

                    @if($events->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-calendar-plus text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No events created</h3>
                            <p class="text-gray-500 mb-6">You haven't created any events yet.</p>
                            <a href="{{ route('events.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                Create Event
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($events as $event)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        {{ $event->title }}
                                    </h3>
                                    
                                    <p class="text-gray-600 text-sm mb-4">
                                        {{ Str::limit($event->description, 100) }}
                                    </p>
                                    
                                    <div class="space-y-2 text-sm text-gray-500 mb-4">
                                        <div><i class="fas fa-calendar mr-2"></i>{{ $event->start_date->format('M d, Y') }}</div>
                                        <div><i class="fas fa-clock mr-2"></i>{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</div>
                                        <div><i class="fas fa-map-marker-alt mr-2"></i>{{ $event->location }}</div>
                                        <div>
                                            <i class="fas fa-users mr-2"></i>
                                            {{ $event->eventRegistrations->count() }} attendees
                                        </div>
                                    </div>
                                    
                                    <div class="text-sm mb-4">
                                        <span class="inline-block px-2 py-1 rounded-full text-xs 
                                            @switch($event->event_status)
                                                @case('published')
                                                    {{ 'bg-green-100 text-green-800' }}
                                                    @break
                                                @case('draft')
                                                    {{ 'bg-yellow-100 text-yellow-800' }}
                                                    @break
                                                @case('cancelled')
                                                    {{ 'bg-red-100 text-red-800' }}
                                                    @break
                                                @default
                                                    {{ 'bg-gray-100 text-gray-800' }}
                                            @endswitch">
                                            {{ ucfirst($event->event_status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex space-x-3">
                                        <a href="{{ route('events.show', $event) }}" 
                                           class="flex-1 text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                                            View Event
                                        </a>
                                        <a href="{{ route('events.edit', $event) }}" 
                                           class="flex-1 text-center bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 text-sm">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>