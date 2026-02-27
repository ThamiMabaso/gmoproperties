@extends('layouts.tenant')

@section('title', 'My Invoices')
@section('page-title', 'My Invoices')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">My Invoices</h2>

        <!-- Filters -->
        <form method="GET" action="{{ route('tenant.invoices.index') }}" class="mb-6">
            <div class="flex space-x-4">
                <select name="status" class="border-gray-300 rounded-md shadow-sm">
                    <option value="">All Statuses</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('tenant.invoices.show', $invoice) }}" class="text-gmo-gold hover:underline">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst($invoice->type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $invoice->issue_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $invoice->due_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">R {{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">R {{ number_format($invoice->paid_amount, 2) }}</td>
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
                                <a href="{{ route('tenant.invoices.show', $invoice) }}" class="text-gmo-gold hover:underline">View</a>
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
