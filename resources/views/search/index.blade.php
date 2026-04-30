@extends('layouts.admin')

@section('title', 'Search')
@section('page-title', 'Search')

@section('content')
<div class="max-w-5xl space-y-6">
    <form method="GET" action="{{ route('search') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[12rem]">
            <label for="query" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="search" name="query" id="query" value="{{ $query }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gmo-gold focus:ring-gmo-gold"
                   placeholder="Keywords…">
        </div>
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type" id="type" class="rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                <option value="companies" @selected($type === 'companies')>Companies</option>
                <option value="buildings" @selected($type === 'buildings')>Buildings</option>
                <option value="units" @selected($type === 'units')>Units</option>
                <option value="tenants" @selected($type === 'tenants')>Tenants</option>
            </select>
        </div>
        <button type="submit" class="rounded-lg bg-gmo-gold px-4 py-2 text-sm font-semibold text-black hover:bg-opacity-90">
            Search
        </button>
    </form>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-4 py-3 bg-gray-50">
            <p class="text-sm text-gray-600">
                @if($query !== '')
                    Results for &ldquo;{{ $query }}&rdquo; ({{ $type }})
                @else
                    Showing {{ $type }} (enter a search term to filter)
                @endif
            </p>
        </div>
        <div class="p-4">
            @if($type === 'companies')
                <ul class="divide-y divide-gray-100">
                    @forelse($results as $company)
                        <li class="py-3 flex flex-wrap justify-between gap-2">
                            <div>
                                <a href="{{ route('admin.companies.show', $company) }}" class="font-medium text-gmo-gold hover:underline">{{ $company->name }}</a>
                                <p class="text-xs text-gray-500">{{ $company->email }} · {{ $company->slug }}</p>
                            </div>
                            <span class="text-xs capitalize text-gray-600">{{ $company->subscription_plan }}</span>
                        </li>
                    @empty
                        <li class="py-8 text-center text-gray-500 text-sm">No companies found.</li>
                    @endforelse
                </ul>
            @elseif($type === 'buildings')
                <ul class="divide-y divide-gray-100">
                    @forelse($results as $building)
                        <li class="py-3">
                            <p class="font-medium text-gray-900">{{ $building->name }}</p>
                            <p class="text-xs text-gray-500">{{ $building->company?->name }} · {{ $building->code }} · {{ $building->city }}</p>
                        </li>
                    @empty
                        <li class="py-8 text-center text-gray-500 text-sm">No buildings found.</li>
                    @endforelse
                </ul>
            @elseif($type === 'units')
                <ul class="divide-y divide-gray-100">
                    @forelse($results as $unit)
                        <li class="py-3">
                            <p class="font-medium text-gray-900">Unit {{ $unit->unit_number }}</p>
                            <p class="text-xs text-gray-500">{{ $unit->building?->name }} · {{ $unit->company?->name }}</p>
                        </li>
                    @empty
                        <li class="py-8 text-center text-gray-500 text-sm">No units found.</li>
                    @endforelse
                </ul>
            @else
                <ul class="divide-y divide-gray-100">
                    @forelse($results as $tenant)
                        <li class="py-3">
                            <p class="font-medium text-gray-900">{{ $tenant->name }}</p>
                            <p class="text-xs text-gray-500">{{ $tenant->email }} · {{ $tenant->company?->name ?? '—' }}</p>
                        </li>
                    @empty
                        <li class="py-8 text-center text-gray-500 text-sm">No tenants found.</li>
                    @endforelse
                </ul>
            @endif

            @if($results->hasPages())
                <div class="mt-4 border-t border-gray-100 pt-4">
                    {{ $results->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
