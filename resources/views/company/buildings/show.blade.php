@extends('layouts.company')

@section('title', $building->name)
@section('page-title', $building->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap justify-between items-start gap-4">
        <div>
            <a href="{{ route('company.buildings.index', $company) }}" class="text-sm text-gmo-gold hover:underline">&larr; Back to buildings</a>
            <h2 class="text-2xl font-bold text-gray-900 mt-2">{{ $building->name }}</h2>
            <p class="text-gray-600 mt-1">{{ $building->address }}, {{ $building->city }}{{ $building->postal_code ? ', ' . $building->postal_code : '' }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if ($building->is_active)
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
            @else
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
            @endif
            <a href="{{ route('company.buildings.edit', [$company, $building]) }}" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
                Edit building
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Details</h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Code</dt>
                <dd class="font-medium text-gray-900">{{ $building->code ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Property type</dt>
                <dd class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $building->property_type)) }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Province</dt>
                <dd class="font-medium text-gray-900">{{ $building->province }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Units</dt>
                <dd class="font-medium text-gray-900">
                    @if ($building->total_units !== null)
                        {{ $building->occupied_units ?? 0 }} / {{ $building->total_units }} (occupied / total)
                    @else
                        {{ $units->total() }} listed
                    @endif
                </dd>
            </div>
        </dl>
        @if ($building->description)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <dt class="text-sm text-gray-500 mb-1">Description</dt>
                <dd class="text-gray-800 whitespace-pre-wrap">{{ $building->description }}</dd>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Units</h3>
        </div>
        @if ($units->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($units as $unit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $unit->unit_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $unit->unit_type)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst(str_replace('_', ' ', $unit->status)) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if ($unit->monthly_rent !== null)
                                    R {{ number_format((float) $unit->monthly_rent, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $units->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                No units in this building yet.
            </div>
        @endif
    </div>
</div>
@endsection
