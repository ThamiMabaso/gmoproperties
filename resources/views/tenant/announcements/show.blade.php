@extends('layouts.tenant')

@section('title', $announcement->title)
@section('page-title', $announcement->title)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('tenant.announcements.index') }}" class="text-sm text-gmo-gold hover:underline">&larr; All announcements</a>
        <form method="post" action="{{ route('tenant.announcements.read', $announcement) }}">
            @csrf
            <button type="submit" class="px-3 py-1.5 text-sm font-medium bg-gray-900 text-white rounded-md hover:bg-gray-800">Mark as read</button>
        </form>
    </div>

    <article class="bg-white rounded-lg shadow p-6 sm:p-8 space-y-4">
        @if($announcement->building)
            <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $announcement->building->name }}</p>
        @endif
        <h1 class="text-2xl font-bold text-gray-900">{{ $announcement->title }}</h1>
        <div class="text-gray-700 whitespace-pre-wrap">{{ $announcement->body }}</div>
    </article>
</div>
@endsection
