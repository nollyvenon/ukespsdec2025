<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ticket #{{ $ticket->id }}: {{ $ticket->subject }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Ticket Header -->
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">{{ $ticket->subject }}</h1>
                                <div class="flex items-center mt-2 text-sm text-gray-500">
                                    <span>Ticket #{{ $ticket->id }}</span>
                                    <span class="mx-2">•</span>
                                    <span>Created {{ $ticket->created_at->format('M j, Y \a\t g:i A') }}</span>
                                    <span class="mx-2">•</span>
                                    <span>Category: {{ $ticket->category->name }}</span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($ticket->priority === 'low') bg-green-100 text-green-800
                                    @elseif($ticket->priority === 'medium') bg-blue-100 text-blue-800
                                    @elseif($ticket->priority === 'high') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($ticket->status === 'open') bg-blue-100 text-blue-800
                                    @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                                    @elseif($ticket->status === 'on_hold') bg-gray-100 text-gray-800
                                    @elseif($ticket->status === 'resolved') bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Original Ticket -->
                    <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <span class="font-medium">{{ $ticket->user->name ?? 'User' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $ticket->created_at->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $ticket->description }}</p>
                    </div>

                    <!-- Replies -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Replies ({{ $ticket->replies->count() }})</h3>
                        
                        @forelse($ticket->replies as $reply)
                            <div class="mb-6 p-6 bg-white rounded-lg border border-gray-200">
                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                    <span class="font-medium">{{ $reply->user->name ?? 'User' }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $reply->created_at->format('M j, Y \a\t g:i A') }}</span>
                                    @if($reply->is_internal_note)
                                        <span class="ml-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                            Internal Note
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-800 whitespace-pre-wrap">{{ $reply->content }}</p>
                            </div>
                        @empty
                            <p class="text-gray-600 mb-6">No replies yet.</p>
                        @endforelse
                    </div>

                    <!-- Add Reply Form -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Reply</h3>
                        <form method="POST" action="{{ route('support.add-reply', $ticket->id) }}">
                            @csrf

                            <div class="mb-4">
                                <textarea name="content" rows="4" required
                                          placeholder="Type your reply here..."
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Add Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>