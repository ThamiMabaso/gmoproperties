@extends('layouts.tenant')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-8 max-w-[1600px]">
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Welcome back, {{ $user->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">
                View your lease, pay invoices, track maintenance, and message your property manager.
            </p>
        </div>
        <div class="flex flex-wrap gap-2 shrink-0">
            <a href="{{ route('tenant.applications.index') }}" class="inline-flex rounded-lg bg-gmo-gold px-4 py-2.5 text-sm font-semibold text-black shadow-sm hover:bg-opacity-90">Applications</a>
            <a href="{{ route('tenant.invoices.index') }}" class="inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Invoices</a>
            <a href="{{ route('tenant.messages.index') }}" class="inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Messages</a>
            <a href="{{ route('home') }}" class="inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Website</a>
        </div>
    </div>

    @if($activeContract && $unit)
        <div class="rounded-xl border border-amber-200 bg-gradient-to-br from-amber-50/80 to-white p-6 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-amber-900/80">Current lease</h2>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500">Unit</p>
                    <p class="mt-0.5 text-lg font-semibold text-gray-900">{{ $unit->building->name }} — {{ $unit->unit_number }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Monthly rent</p>
                    <p class="mt-0.5 text-lg font-semibold text-gmo-gold tabular-nums">R {{ number_format($activeContract->monthly_rent, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Lease end</p>
                    <p class="mt-0.5 text-lg font-semibold text-gray-900">{{ $activeContract->end_date->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('tenant.contracts.show', $activeContract) }}" class="text-sm font-medium text-gmo-gold hover:underline">View contract</a>
                <a href="{{ route('tenant.invoices.index') }}" class="text-sm font-medium text-gmo-gold hover:underline">Pay invoices</a>
            </div>
        </div>
    @else
        <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
            <h2 class="text-lg font-semibold text-gray-900">No active lease on file</h2>
            <p class="mt-2 text-sm text-gray-600 max-w-md mx-auto">
                Apply for a unit or wait for your property company to link your account to an active contract.
            </p>
            <a href="{{ route('tenant.applications.index') }}" class="mt-4 inline-flex rounded-lg bg-gmo-gold px-5 py-2.5 text-sm font-semibold text-black hover:bg-opacity-90">Browse &amp; apply</a>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Outstanding invoices</p>
            <p class="mt-1 text-3xl font-bold text-amber-700 tabular-nums">{{ $stats['pending_invoices'] }}</p>
            <a href="{{ route('tenant.invoices.index') }}" class="mt-2 inline-block text-xs font-medium text-gmo-gold hover:underline">View invoices →</a>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Amount due</p>
            <p class="mt-1 text-3xl font-bold text-red-700 tabular-nums">R {{ number_format($stats['total_due'], 2) }}</p>
            <p class="mt-1 text-xs text-gray-500">Unpaid balance</p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Open maintenance</p>
            <p class="mt-1 text-3xl font-bold text-blue-700 tabular-nums">{{ $stats['open_tickets'] }}</p>
            <a href="{{ route('tenant.maintenance.index') }}" class="mt-2 inline-block text-xs font-medium text-gmo-gold hover:underline">Track requests →</a>
        </div>
    </div>

    <x-dashboard-charts-section
        :chartData="$chartData"
        grid-class="grid grid-cols-1 lg:grid-cols-2 gap-6"
    />

    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Tenant portal</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('tenant.applications.index') }}" class="rounded-lg border border-gray-200 px-3 py-3 text-center text-sm font-medium text-gray-800 hover:border-gmo-gold hover:bg-gmo-gold/5">Applications</a>
            <a href="{{ route('tenant.contracts.index') }}" class="rounded-lg border border-gray-200 px-3 py-3 text-center text-sm font-medium text-gray-800 hover:border-gmo-gold hover:bg-gmo-gold/5">Contracts</a>
            <a href="{{ route('tenant.invoices.index') }}" class="rounded-lg border border-gray-200 px-3 py-3 text-center text-sm font-medium text-gray-800 hover:border-gmo-gold hover:bg-gmo-gold/5">Invoices</a>
            <a href="{{ route('tenant.maintenance.index') }}" class="rounded-lg border border-gray-200 px-3 py-3 text-center text-sm font-medium text-gray-800 hover:border-gmo-gold hover:bg-gmo-gold/5">Maintenance</a>
            <a href="{{ route('tenant.messages.index') }}" class="rounded-lg border border-gray-200 px-3 py-3 text-center text-sm font-medium text-gray-800 hover:border-gmo-gold hover:bg-gmo-gold/5">Messages</a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-1 rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent invoices</h2>
                <a href="{{ route('tenant.invoices.index') }}" class="text-sm font-medium text-gmo-gold hover:underline">All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">#</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentInvoices as $invoice)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <a href="{{ route('tenant.invoices.show', $invoice) }}" class="text-gmo-gold font-medium hover:underline">{{ $invoice->invoice_number }}</a>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm tabular-nums">R {{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-xs capitalize">{{ $invoice->status }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">No invoices.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="xl:col-span-1 rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent payments</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Reference</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentPayments as $payment)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">{{ $payment->payment_date?->format('M d, Y') ?? '—' }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm font-medium tabular-nums">R {{ number_format($payment->amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 truncate max-w-[8rem]" title="{{ $payment->payment_reference }}">{{ $payment->payment_reference ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">No payments recorded.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="xl:col-span-1 rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Maintenance</h2>
                <a href="{{ route('tenant.maintenance.index') }}" class="text-sm font-medium text-gmo-gold hover:underline">All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Ticket</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentTickets as $ticket)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <a href="{{ route('tenant.maintenance.show', $ticket) }}" class="text-gmo-gold font-medium hover:underline">{{ $ticket->ticket_number }}</a>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-xs capitalize">{{ str_replace('_', ' ', $ticket->status) }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">{{ $ticket->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">No tickets.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
