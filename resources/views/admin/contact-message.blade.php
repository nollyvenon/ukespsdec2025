<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Contact Message Details</h1>
                        <a href="{{ route('admin.contact.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Messages
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p><span class="font-bold">Name:</span> {{ $message->name }}</p>
                            <p><span class="font-bold">Email:</span> {{ $message->email }}</p>
                            <p><span class="font-bold">Subject:</span> {{ $message->subject }}</p>
                        </div>
                        <div>
                            <p><span class="font-bold">Date:</span> {{ \Carbon\Carbon::parse($message->created_at)->format('F j, Y g:i A') }}</p>
                            <p><span class="font-bold">Status:</span> 
                                <span class="inline-block px-2 py-1 text-xs rounded-full 
                                    {{ $message->status === 'new' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($message->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                       ($message->status === 'resolved' ? 'bg-green-100 text-green-800' : 
                                       'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($message->status) }}
                                </span>
                            </p>
                            <p><span class="font-bold">Read:</span> {{ $message->read_at ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <h3 class="font-bold text-lg mb-2">Message:</h3>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="whitespace-pre-line">{{ $message->message }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <h3 class="font-bold text-lg mb-2">Update Status</h3>
                        <form method="POST" action="{{ route('admin.contact.update-status', $message) }}">
                            @csrf
                            @method('PATCH')
                            
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="new" {{ $message->status === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="in_progress" {{ $message->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $message->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $message->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Status
                            </button>
                        </form>
                    </div>
                    
                    @if($message->user)
                        <div class="mb-8">
                            <h3 class="font-bold text-lg mb-2">User Information</h3>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p><span class="font-bold">Name:</span> {{ $message->user->name }}</p>
                                <p><span class="font-bold">Email:</span> {{ $message->user->email }}</p>
                                <p><span class="font-bold">Joined:</span> {{ \Carbon\Carbon::parse($message->user->created_at)->format('F j, Y') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>