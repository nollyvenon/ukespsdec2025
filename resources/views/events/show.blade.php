<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-3xl font-bold mb-2 {{ $event->is_premium ? 'text-yellow-600' : '' }}">
                                {{ $event->is_premium ? '⭐ ' : '' }}{{ $event->title }}
                            </h1>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                @if($event->is_premium)
                                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                        Premium
                                    </span>
                                    <span>•</span>
                                @endif
                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y g:i A') }}</span>
                                <span>•</span>
                                <span>{{ $event->location }}</span>
                                <span>•</span>
                                <span class="inline-block bg-{{ $event->event_status === 'published' ? 'green' : ($event->event_status === 'draft' ? 'yellow' : 'red') }}-100 text-{{ $event->event_status === 'published' ? 'green' : ($event->event_status === 'draft' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                    {{ ucfirst($event->event_status) }}
                                </span>
                            </div>
                        </div>
                        @if(Auth::id() === $event->created_by)
                            <div class="flex space-x-2">
                                <a href="{{ route('events.edit', $event) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-3 rounded text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded text-sm" onclick="return confirm('Are you sure you want to delete this event?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    
                    @if($event->event_image)
                        <div class="mb-6">
                            <img src="{{ Storage::url($event->event_image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover rounded">
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="font-bold text-lg mb-2">Event Details</h3>
                            <p><span class="font-medium">Starts:</span> {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y g:i A') }}</p>
                            <p><span class="font-medium">Ends:</span> {{ \Carbon\Carbon::parse($event->end_date)->format('F j, Y g:i A') }}</p>
                            <p><span class="font-medium">Location:</span> {{ $event->location }}</p>
                            @if($event->max_participants)
                                <p><span class="font-medium">Max Participants:</span> {{ $event->max_participants }}</p>
                            @endif
                            @if($event->registration_deadline)
                                <p><span class="font-medium">Registration Deadline:</span> {{ \Carbon\Carbon::parse($event->registration_deadline)->format('F j, Y g:i A') }}</p>
                            @endif
                        </div>
                        
                        <div class="md:col-span-2">
                            <h3 class="font-bold text-lg mb-2">Description</h3>
                            <p class="whitespace-pre-line">{{ $event->description }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        @if($event->event_status === 'published' && (!$event->registration_deadline || $event->registration_deadline > now()))
                            <form action="{{ route('events.register', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                                    Register for Event
                                </button>
                            </form>
                        @elseif($event->event_status !== 'published')
                            <p class="text-gray-500">This event is not currently open for registration.</p>
                        @elseif($event->registration_deadline && $event->registration_deadline <= now())
                            <p class="text-gray-500">Registration deadline has passed.</p>
                        @else
                            <p class="text-gray-500">Registration is not available at this time.</p>
                        @endif
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="font-bold text-lg mb-4">Event Creator</h3>
                        <div class="flex items-center">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16" />
                            <div class="ml-4">
                                <p class="font-medium">{{ $event->creator->name }}</p>
                                <p class="text-sm text-gray-600">{{ $event->creator->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>