@extends('layouts.company')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Notifications</h2>
            <p class="text-sm text-gray-500 mt-1">In-app alerts for your account.</p>
        </div>
        @if($notifications->count() > 0)
            <form method="post" action="{{ route('company.notifications.read-all', $company) }}">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm font-semibold border border-gray-300 rounded-md hover:bg-gray-50">Mark all read</button>
            </form>
        @endif
    </div>

    @if ($notifications->count() > 0)
        <ul class="bg-white rounded-lg shadow divide-y divide-gray-200">
            @foreach ($notifications as $n)
                @php
                    $data = $n->data ?? [];
                    $title = $data['title'] ?? class_basename($n->type);
                    $body = $data['body'] ?? null;
                    $actionUrl = $data['action_url'] ?? null;
                    $actionLabel = $data['action_label'] ?? 'Open';
                @endphp
                <li class="px-6 py-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 {{ $n->read_at ? '' : 'bg-blue-50/50' }}">
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-gray-900">{{ $title }}</p>
                        @if($body)
                            <p class="text-sm text-gray-600 mt-1">{{ $body }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-2">{{ $n->created_at->format('M j, Y H:i') }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                        @if($actionUrl)
                            <a href="{{ $actionUrl }}" class="text-sm font-medium text-gmo-gold hover:underline">{{ $actionLabel }}</a>
                        @endif
                        @if(! $n->read_at)
                            <form method="post" action="{{ route('company.notifications.read', [$company, $n->id]) }}">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gmo-gold">Mark read</button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-4">{{ $notifications->links() }}</div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-500">You have no notifications yet.</div>
    @endif
</div>
@endsection
