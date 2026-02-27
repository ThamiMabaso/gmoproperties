@extends('layouts.company')

@section('title', $message->subject ?? 'Message')
@section('page-title', 'Message')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold">{{ $message->subject ?? '(No Subject)' }}</h3>
                    <div class="mt-2 text-sm text-gray-500">
                        <p>
                            <span class="font-medium">From:</span> {{ $message->sender->name }}
                            <span class="mx-2">•</span>
                            <span class="font-medium">To:</span> {{ $message->recipient->name }}
                        </p>
                        <p class="mt-1">{{ $message->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    @if(!$message->is_read && $message->recipient_id === auth()->id())
                        <form action="{{ route('company.messages.read', [$company, $message]) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200">
                                Mark as Read
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('company.messages.destroy', [$company, $message]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-md hover:bg-red-200">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="prose max-w-none">
                <p class="whitespace-pre-wrap text-gray-900">{{ $message->body }}</p>
            </div>

            @if($message->relatedEntity)
                <div class="mt-6 pt-6 border-t">
                    <p class="text-sm text-gray-500 mb-2">Related to:</p>
                    <a href="#" class="text-gmo-gold hover:underline">
                        {{ class_basename($message->relatedEntity) }} #{{ $message->relatedEntity->id }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('company.messages.index', $company) }}" class="text-gmo-gold hover:underline">
            ← Back to Messages
        </a>
    </div>
</div>
@endsection
