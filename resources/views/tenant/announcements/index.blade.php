@extends('layouts.tenant')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Company announcements</h2>
        <p class="text-sm text-gray-500 mt-1">Updates from your property manager.</p>
    </div>

    @if ($announcements->count() > 0)
        <div class="bg-white rounded-lg shadow divide-y divide-gray-200">
            @foreach ($announcements as $item)
                <a href="{{ route('tenant.announcements.show', $item) }}" class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between gap-4">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $item->title }}</p>
                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ Str::limit(strip_tags($item->body), 140) }}</p>
                            @if($item->building)
                                <p class="text-xs text-gray-400 mt-2">{{ $item->building->name }}</p>
                            @endif
                        </div>
                        <div class="text-right shrink-0 text-xs text-gray-500 whitespace-nowrap">
                            @if($item->published_at)
                                {{ $item->published_at->format('M j, Y') }}
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-4">{{ $announcements->links() }}</div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-500">No announcements at the moment.</div>
    @endif
</div>
@endsection
