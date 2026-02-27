@extends('layouts.company')

@section('title', 'Invoices')
@section('page-title', 'Invoices')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Invoices</h2>
            <div class="flex space-x-4">
                <form method="POST" action="{{ route('company.invoices.generate-rent', $company) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Generate Rent Invoices
                    </button>
                </form>
                <a href="{{ route('company.invoices.create', $company) }}" class="px-4 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                    Create Invoice
                </a>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('company.invoices.index', $company) }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Statuses</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Types</option>
                    <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>Rent</option>
                    <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                    <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="penalty" {{ request('type') == 'penalty' ? 'selected' : '' }}>Penalty</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                    Filter
                </button>
            </div>
        </form>

        <!-- Invoices Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('company.invoices.show', [$company, $invoice]) }}" class="text-gmo-gold hover:underline">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->tenant->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $invoice->unit->building->name }} - {{ $invoice->unit->unit_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst($invoice->type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">R {{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $invoice->due_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($invoice->status === 'paid')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                @elseif($invoice->isOverdue())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Overdue</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('company.invoices.show', [$company, $invoice]) }}" class="text-gmo-gold hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No invoices found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endsection
