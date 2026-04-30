@extends('layouts.company')

@section('title', $announcement->title)
@section('page-title', $announcement->title)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('company.announcements.index', $company) }}" class="text-sm text-gmo-gold hover:underline">&larr; All announcements</a>
        <div class="flex flex-wrap gap-2">
            @can('edit_announcements')
                @if(auth()->user()->isCompanyAdmin() || (int) $announcement->created_by === (int) auth()->id())
                    <a href="{{ route('company.announcements.edit', [$company, $announcement]) }}" class="px-3 py-1.5 text-sm font-medium border border-gray-300 rounded-md hover:bg-gray-50">Edit</a>
                @endif
            @endcan
            @if($announcement->is_published)
                <form method="post" action="{{ route('company.announcements.read', [$company, $announcement]) }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 text-sm font-medium bg-gray-900 text-white rounded-md hover:bg-gray-800">Mark as read</button>
                </form>
            @endif
        </div>
    </div>

    <article class="bg-white rounded-lg shadow p-6 sm:p-8 space-y-4">
        <div class="flex flex-wrap gap-2 text-xs">
            @if($announcement->is_published)
                <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-800 font-medium">Published</span>
            @else
                <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 font-medium">Draft</span>
            @endif
            @if($announcement->building)
                <span class="text-gray-500">{{ $announcement->building->name }}</span>
            @else
                <span class="text-gray-500">All buildings</span>
            @endif
            @if($announcement->published_at)
                <span class="text-gray-500">{{ $announcement->published_at->format('M j, Y H:i') }}</span>
            @endif
        </div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $announcement->title }}</h1>
        <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-wrap">{{ $announcement->body }}</div>
        @if($announcement->creator)
            <p class="text-xs text-gray-400 pt-4 border-t border-gray-100">Created by {{ $announcement->creator->name }}</p>
        @endif
    </article>
</div>
@endsection
