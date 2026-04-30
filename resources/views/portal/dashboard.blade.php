@extends('layouts.app')

@section('title', 'Portal')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Announcements</h1>
    @if ($announcements->isEmpty())
        <p class="text-gray-600">No active announcements.</p>
    @else
        <ul class="space-y-4">
            @foreach ($announcements as $item)
                <li class="border border-gray-200 rounded-lg p-4 bg-white shadow-sm">
                    <p class="font-semibold text-gray-900">{{ $item->title }}</p>
                    <p class="text-sm text-gray-600 mt-2 whitespace-pre-wrap">{{ $item->body }}</p>
                    @if($item->published_at)
                        <p class="text-xs text-gray-400 mt-3">{{ $item->published_at->format('M j, Y H:i') }}</p>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
