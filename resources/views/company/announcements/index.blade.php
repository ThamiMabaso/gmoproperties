@extends('layouts.company')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Announcements</h2>
            <p class="text-sm text-gray-500 mt-1">Published items for your company; drafts you created are visible only to you until published.</p>
        </div>
        @can('create_announcements')
            <a href="{{ route('company.announcements.create', $company) }}" class="inline-flex justify-center px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors text-sm">
                New announcement
            </a>
        @endcan
    </div>

    @if ($announcements->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Scope</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($announcements as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('company.announcements.show', [$company, $item]) }}" class="font-medium text-gray-900 hover:text-gmo-gold">{{ $item->title }}</a>
                                @if($item->priority > 0)
                                    <span class="ml-2 text-xs text-amber-700">P{{ $item->priority }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $item->building_id ? ($item->building?->name ?? 'Building') : 'All buildings' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($item->is_published)
                                    <span class="text-green-700">Published</span>
                                    @if($item->published_at)
                                        <span class="block text-xs text-gray-500">{{ $item->published_at->format('M j, Y H:i') }}</span>
                                    @endif
                                @elseif($item->publish_at && $item->publish_at->isFuture())
                                    <span class="text-amber-700">Scheduled</span>
                                    <span class="block text-xs text-gray-500">{{ $item->publish_at->format('M j, Y H:i') }}</span>
                                @else
                                    <span class="text-gray-600">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->updated_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('company.announcements.show', [$company, $item]) }}" class="text-gmo-gold hover:underline font-medium">View</a>
                                @can('edit_announcements')
                                    @if(auth()->user()->isCompanyAdmin() || (int) $item->created_by === (int) auth()->id())
                                        <a href="{{ route('company.announcements.edit', [$company, $item]) }}" class="ml-3 text-gray-700 hover:text-gmo-gold font-medium">Edit</a>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $announcements->links() }}</div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-500">
            <p class="mb-4">No announcements yet.</p>
            @can('create_announcements')
                <a href="{{ route('company.announcements.create', $company) }}" class="inline-block px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90">Create the first announcement</a>
            @endcan
        </div>
    @endif
</div>
@endsection
