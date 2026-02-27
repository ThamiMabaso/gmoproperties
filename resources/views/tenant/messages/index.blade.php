@extends('layouts.tenant')

@section('title', 'Messages')
@section('page-title', 'Messages')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Messages</h2>
            @if($unreadCount > 0)
                <p class="text-sm text-gray-500 mt-1">{{ $unreadCount }} unread message(s)</p>
            @endif
        </div>
        <a href="{{ route('tenant.messages.create') }}" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
            New Message
        </a>
    </div>

    @if ($messages->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From/To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($messages as $message)
                        <tr class="hover:bg-gray-50 {{ !$message->is_read && $message->recipient_id === auth()->id() ? 'bg-blue-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($message->sender_id === auth()->id())
                                        <span class="text-sm text-gray-500">To:</span>
                                        <span class="ml-2 text-sm font-medium text-gray-900">{{ $message->recipient->name }}</span>
                                    @else
                                        <span class="text-sm text-gray-500">From:</span>
                                        <span class="ml-2 text-sm font-medium text-gray-900">{{ $message->sender->name }}</span>
                                        @if(!$message->is_read)
                                            <span class="ml-2 w-2 h-2 bg-blue-600 rounded-full"></span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('tenant.messages.show', $message) }}" class="text-sm text-gray-900 hover:text-gmo-gold">
                                    {{ $message->subject ?? '(No Subject)' }}
                                </a>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ Str::limit($message->body, 60) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $message->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('tenant.messages.show', $message) }}" class="text-gmo-gold hover:text-gmo-gold-dark mr-4">View</a>
                                <form action="{{ route('tenant.messages.destroy', $message) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $messages->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500 mb-4">No messages found.</p>
            <a href="{{ route('tenant.messages.create') }}" class="inline-block px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
                Send Your First Message
            </a>
        </div>
    @endif
</div>
@endsection
