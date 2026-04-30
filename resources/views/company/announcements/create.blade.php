@extends('layouts.company')

@section('title', 'New announcement')
@section('page-title', 'New announcement')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('company.announcements.index', $company) }}" class="text-sm text-gmo-gold hover:underline">&larr; Back to announcements</a>

    <form method="post" action="{{ route('company.announcements.store', $company) }}" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required maxlength="255"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
        </div>
        <div>
            <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Body</label>
            <textarea name="body" id="body" rows="10" required maxlength="20000"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">{{ old('body') }}</textarea>
        </div>

        @if(Auth::user()->isCompanyAdmin())
            <div>
                <label for="building_id" class="block text-sm font-medium text-gray-700 mb-1">Building (optional)</label>
                <select name="building_id" id="building_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    <option value="">All buildings</option>
                    @foreach ($buildings as $b)
                        <option value="{{ $b->id }}" @selected((string) old('building_id') === (string) $b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Leave empty to target every building in the company.</p>
            </div>
        @else
            <p class="text-sm text-gray-600 border border-gray-200 rounded-md p-3 bg-gray-50">
                This announcement will be scoped to your assigned building.
            </p>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority (0–5)</label>
                <input type="number" name="priority" id="priority" min="0" max="5" value="{{ old('priority', 0) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
            </div>
            <div>
                <label for="publish_at" class="block text-sm font-medium text-gray-700 mb-1">Publish at (optional)</label>
                <input type="datetime-local" name="publish_at" id="publish_at" value="{{ old('publish_at') }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                <p class="text-xs text-gray-500 mt-1">Leave empty to control publishing manually below.</p>
            </div>
        </div>
        <div>
            <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Expires at (optional)</label>
            <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at') }}"
                class="w-full max-w-md rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
        </div>

        <div class="flex items-start gap-2">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" id="is_published" value="1" class="mt-1 rounded border-gray-300 text-gmo-gold focus:ring-gmo-gold" @checked(old('is_published'))>
            <label for="is_published" class="text-sm text-gray-700">
                Publish now (or on save if publish time is blank/past). If you set a future <em>Publish at</em>, leave this unchecked; the scheduler will publish it.
            </label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90">Save</button>
            <a href="{{ route('company.announcements.index', $company) }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
@endsection
