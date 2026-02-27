@extends('layouts.company')

@section('title', 'Contracts')
@section('page-title', 'Contracts')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Contracts</h2>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('company.contracts.index', $company) }}" class="mb-6">
            <div class="flex space-x-4">
                <select name="status" class="border-gray-300 rounded-md shadow-sm">
                    <option value="">All Statuses</option>
                    <option value="pending_signature" {{ request('status') == 'pending_signature' ? 'selected' : '' }}>Pending Signature</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                    Filter
                </button>
            </div>
        </form>

        <!-- Contracts Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contract #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monthly Rent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($contracts as $contract)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('company.contracts.show', [$company, $contract]) }}" class="text-gmo-gold hover:underline">
                                    {{ $contract->contract_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->tenant->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $contract->unit->building->name }} - {{ $contract->unit->unit_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $contract->start_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $contract->end_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">R {{ number_format($contract->monthly_rent, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($contract->status === 'active')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @elseif($contract->status === 'pending_signature')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Signature</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($contract->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('company.contracts.show', [$company, $contract]) }}" class="text-gmo-gold hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No contracts found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $contracts->links() }}
        </div>
    </div>
</div>
@endsection
