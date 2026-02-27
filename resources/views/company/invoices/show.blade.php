@extends('layouts.company')

@section('title', 'Invoice Details')
@section('page-title', 'Invoice Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold">Invoice</h2>
                <p class="text-gray-600">Invoice #{{ $invoice->invoice_number }}</p>
            </div>
            <div>
                @if($invoice->status === 'paid')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                @elseif($invoice->isOverdue())
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800">Overdue</span>
                @else
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Payment</span>
                @endif
            </div>
        </div>

        <!-- Invoice Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold mb-2">Billing Information</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Invoice Number:</span> {{ $invoice->invoice_number }}</p>
                    <p><span class="text-gray-600">Type:</span> {{ ucfirst($invoice->type) }}</p>
                    <p><span class="text-gray-600">Issue Date:</span> {{ $invoice->issue_date->format('M d, Y') }}</p>
                    <p><span class="text-gray-600">Due Date:</span> {{ $invoice->due_date->format('M d, Y') }}</p>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Tenant & Unit Information</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Tenant:</span> {{ $invoice->tenant->name }}</p>
                    <p><span class="text-gray-600">Email:</span> {{ $invoice->tenant->email }}</p>
                    <p><span class="text-gray-600">Building:</span> {{ $invoice->unit->building->name }}</p>
                    <p><span class="text-gray-600">Unit:</span> {{ $invoice->unit->unit_number }}</p>
                </div>
            </div>
        </div>

        <!-- Invoice Amounts -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold">R {{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                @if($invoice->tax_amount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">Tax:</span>
                    <span class="font-semibold">R {{ number_format($invoice->tax_amount, 2) }}</span>
                </div>
                @endif
                <div class="border-t pt-2 mt-2">
                    <div class="flex justify-between">
                        <span class="text-lg font-semibold">Total Amount:</span>
                        <span class="text-lg font-semibold text-gmo-gold">R {{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                </div>
                <div class="flex justify-between mt-2">
                    <span class="text-gray-600">Paid Amount:</span>
                    <span class="font-semibold text-green-600">R {{ number_format($invoice->paid_amount, 2) }}</span>
                </div>
                @if($invoice->remaining_balance > 0)
                <div class="flex justify-between mt-2">
                    <span class="text-gray-600">Remaining Balance:</span>
                    <span class="font-semibold text-red-600">R {{ number_format($invoice->remaining_balance, 2) }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment History -->
        @if($invoice->payments->count() > 0)
        <div class="mb-6">
            <h3 class="font-semibold mb-4">Payment History</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Payment Reference</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($invoice->payments as $payment)
                            <tr>
                                <td class="px-4 py-2 text-sm">{{ $payment->payment_reference }}</td>
                                <td class="px-4 py-2 text-sm">{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td class="px-4 py-2 text-sm">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td class="px-4 py-2 text-sm font-semibold">R {{ number_format($payment->amount, 2) }}</td>
                                <td class="px-4 py-2 text-sm">
                                    @if($payment->status === 'completed')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('company.invoices.index', $company) }}" class="text-gmo-gold hover:underline">
                ← Back to Invoices
            </a>
        </div>
    </div>
</div>
@endsection
