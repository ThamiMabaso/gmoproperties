@extends('layouts.company')

@section('title', 'Edit announcement')
@section('page-title', 'Edit announcement')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('company.announcements.show', [$company, $announcement]) }}" class="text-sm text-gmo-gold hover:underline">&larr; Back to announcement</a>

    <form method="post" action="{{ route('company.announcements.update', [$company, $announcement]) }}" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" required maxlength="255"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
        </div>
        <div>
            <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Body</label>
            <textarea name="body" id="body" rows="10" required maxlength="20000"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">{{ old('body', $announcement->body) }}</textarea>
        </div>

        @if(Auth::user()->isCompanyAdmin())
            <div>
                <label for="building_id" class="block text-sm font-medium text-gray-700 mb-1">Building (optional)</label>
                <select name="building_id" id="building_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    <option value="">All buildings</option>
                    @foreach ($buildings as $b)
                        <option value="{{ $b->id }}" @selected((string) old('building_id', $announcement->building_id) === (string) $b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p class="text-sm text-gray-600 border border-gray-200 rounded-md p-3 bg-gray-50">
                Scoped to your assigned building.
            </p>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority (0–5)</label>
                <input type="number" name="priority" id="priority" min="0" max="5" value="{{ old('priority', $announcement->priority) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
            </div>
            <div>
                <label for="publish_at" class="block text-sm font-medium text-gray-700 mb-1">Publish at (optional)</label>
                <input type="datetime-local" name="publish_at" id="publish_at"
                    value="{{ old('publish_at', $announcement->publish_at?->format('Y-m-d\TH:i')) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
            </div>
        </div>
        <div>
            <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Expires at (optional)</label>
            <input type="datetime-local" name="expires_at" id="expires_at"
                value="{{ old('expires_at', $announcement->expires_at?->format('Y-m-d\TH:i')) }}"
                class="w-full max-w-md rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
        </div>

        @if(! $announcement->is_published)
            <div class="flex items-start gap-2">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" id="is_published" value="1" class="mt-1 rounded border-gray-300 text-gmo-gold focus:ring-gmo-gold" @checked(old('is_published'))>
                <label for="is_published" class="text-sm text-gray-700">Publish now if publish time is blank or in the past.</label>
            </div>
        @else
            <p class="text-sm text-green-800 bg-green-50 border border-green-200 rounded-md px-3 py-2">This announcement is already published.</p>
        @endif

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90">Update</button>
            <a href="{{ route('company.announcements.show', [$company, $announcement]) }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
@endsection
