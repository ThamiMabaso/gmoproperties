@extends('layouts.company')

@section('title', 'Units')
@section('page-title', 'Units')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap justify-between items-center gap-3">
        <h2 class="text-2xl font-bold text-gray-900">Units</h2>
        <a href="{{ route('company.units.create', $company) }}" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
            Add New Unit
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('company.units.index', $company) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="building_id" class="block text-sm font-medium text-gray-700 mb-1">Building</label>
                <select id="building_id" name="building_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-gmo-gold focus:border-gmo-gold">
                    <option value="">All buildings</option>
                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}" @selected((string) request('building_id') === (string) $building->id)>
                            {{ $building->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-gmo-gold focus:border-gmo-gold">
                    <option value="">Any status</option>
                    @foreach (['available', 'occupied', 'maintenance', 'reserved'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="unit_type" class="block text-sm font-medium text-gray-700 mb-1">Unit type</label>
                <select id="unit_type" name="unit_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-gmo-gold focus:border-gmo-gold">
                    <option value="">Any type</option>
                    @foreach (['studio', 'one_bedroom', 'two_bedroom', 'three_bedroom', 'four_bedroom', 'shared'] as $type)
                        <option value="{{ $type }}" @selected(request('unit_type') === $type)>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-black text-white text-sm font-medium rounded-md hover:bg-gray-800 transition-colors">
                    Filter
                </button>
                <a href="{{ route('company.units.index', $company) }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-black">
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if ($units->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Building</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($units as $unit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('company.units.show', [$company, $unit]) }}" class="text-gmo-gold hover:underline font-medium">
                                    {{ $unit->unit_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $unit->building?->name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $unit->unit_type)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst(str_replace('_', ' ', $unit->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if ($unit->monthly_rent !== null)
                                    R {{ number_format((float) $unit->monthly_rent, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $unit->activeContract?->tenant?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('company.units.edit', [$company, $unit]) }}" class="text-gmo-gold hover:text-gmo-gold-dark mr-4">Edit</a>
                                <form action="{{ route('company.units.destroy', [$company, $unit]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this unit?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $units->appends(request()->query())->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500 mb-4">No units found for the selected filters.</p>
            <a href="{{ route('company.units.create', $company) }}" class="inline-block px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
                Add Your First Unit
            </a>
        </div>
    @endif
</div>
@endsection
