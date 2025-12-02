<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-8">
                        <h1 class="text-3xl font-bold">Events Management Portal</h1>
                        <a href="{{ route('events.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create New Event
                        </a>
                    </div>
                    
                    <div class="mb-6">
                        <a href="{{ route('events.my') }}" class="text-blue-600 hover:underline">My Events</a>
                        <span class="mx-2">|</span>
                        <a href="{{ route('events.registrations') }}" class="text-blue-600 hover:underline">My Registrations</a>
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
                                <a href="{{ route('events.create') }}" class="text-blue-600 hover:underline mt-4 inline-block">Create your first event</a>
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
</x-app-layout>