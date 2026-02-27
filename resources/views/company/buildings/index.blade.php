@extends('layouts.company')

@section('title', 'Buildings')
@section('page-title', 'Buildings')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Buildings</h2>
        <a href="{{ route('company.buildings.create', $company) }}" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
            Add New Building
        </a>
    </div>

    @if ($buildings->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($buildings as $building)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('company.buildings.show', [$company, $building]) }}" class="text-gmo-gold hover:underline font-medium">
                                    {{ $building->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $building->code ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $building->address }}, {{ $building->city }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ ucfirst(str_replace('_', ' ', $building->property_type)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $building->units_count ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($building->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('company.buildings.edit', [$company, $building]) }}" class="text-gmo-gold hover:text-gmo-gold-dark mr-4">Edit</a>
                                <form action="{{ route('company.buildings.destroy', [$company, $building]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this building?');">
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
            {{ $buildings->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500 mb-4">No buildings found.</p>
            <a href="{{ route('company.buildings.create', $company) }}" class="inline-block px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
                Add Your First Building
            </a>
        </div>
    @endif
</div>
@endsection
