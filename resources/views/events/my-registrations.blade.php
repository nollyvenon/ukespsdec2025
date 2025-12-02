<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Event Registrations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">My Event Registrations</h1>
                        <a href="{{ route('events.index') }}" 
                           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            Browse Events
                        </a>
                    </div>

                    @if($registrations->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-calendar-check text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No event registrations</h3>
                            <p class="text-gray-500 mb-6">You haven't registered for any events yet.</p>
                            <a href="{{ route('events.index') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                Find Events
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($registrations as $registration)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        {{ $registration->event->title }}
                                    </h3>
                                    
                                    <p class="text-gray-600 text-sm mb-4">
                                        {{ Str::limit($registration->event->description, 100) }}
                                    </p>
                                    
                                    <div class="space-y-2 text-sm text-gray-500 mb-4">
                                        <div><i class="fas fa-calendar mr-2"></i>{{ $registration->event->start_date->format('M d, Y') }}</div>
                                        <div><i class="fas fa-clock mr-2"></i>{{ $registration->event->start_date->format('g:i A') }} - {{ $registration->event->end_date->format('g:i A') }}</div>
                                        <div><i class="fas fa-map-marker-alt mr-2"></i>{{ $registration->event->location }}</div>
                                    </div>
                                    
                                    <div class="flex space-x-3">
                                        <a href="{{ route('events.show', $registration->event) }}" 
                                           class="flex-1 text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                                            View Event
                                        </a>
                                        
                                        <form method="POST" action="{{ route('events.unregister', $registration->event) }}" 
                                              class="flex-1" 
                                              onsubmit="return confirm('Are you sure you want to unregister from this event?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full text-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm">
                                                Unregister
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $registrations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>