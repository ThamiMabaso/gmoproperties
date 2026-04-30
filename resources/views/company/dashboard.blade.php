@extends('layouts.company')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php
    $roleLabel = Auth::user()->isCompanyAdmin() ? 'Company administrator' : 'Property manager';
@endphp

<div class="space-y-8 max-w-[1600px]">
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-gmo-gold">{{ $roleLabel }}</p>
            <h1 class="mt-1 text-2xl font-bold text-gray-900 tracking-tight">{{ $company->name }}</h1>
            <p class="mt-1 text-sm text-gray-600 max-w-2xl">
                Manage buildings, units, tenant applications, contracts, billing, maintenance, and reporting for your portfolio.
            </p>
        </div>
        <div class="flex flex-wrap gap-2 shrink-0">
            @if(Auth::user()->isCompanyAdmin())
                <a href="{{ route('company.users.index', $company) }}" class="inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Team &amp; users</a>
            @endif
            <a href="{{ route('company.applications.index', $company) }}" class="inline-flex rounded-lg bg-gmo-gold px-4 py-2.5 text-sm font-semibold text-black shadow-sm hover:bg-opacity-90">Applications</a>
            <a href="{{ route('company.invoices.index', $company) }}" class="inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Invoices</a>
            <a href="{{ route('company.financial.index', $company) }}" class="inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Financial</a>
            <a href="{{ route('home') }}" class="inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Website</a>
        </div>
    </div>

    {{-- Primary KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Units</p>
            <p class="mt-1 text-3xl font-bold text-gmo-gold tabular-nums">{{ $stats['total_units'] }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ $stats['occupied_units'] }} occupied · {{ $stats['available_units'] }} available</p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Active contracts</p>
            <p class="mt-1 text-3xl font-bold text-emerald-700 tabular-nums">{{ $stats['active_contracts'] }}</p>
            <p class="mt-1 text-xs text-gray-500">Across {{ $stats['total_buildings'] }} {{ $stats['total_buildings'] === 1 ? 'building' : 'buildings' }}</p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Pending applications</p>
            <p class="mt-1 text-3xl font-bold text-amber-700 tabular-nums">{{ $stats['pending_applications'] }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ $stats['total_tenants'] }} registered {{ $stats['total_tenants'] === 1 ? 'tenant' : 'tenants' }}</p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Revenue (this month)</p>
            <p class="mt-1 text-3xl font-bold text-gmo-gold tabular-nums">R {{ number_format($stats['monthly_revenue'], 2) }}</p>
            <p class="mt-1 text-xs text-gray-500">From paid invoices</p>
        </div>
    </div>

    {{-- Secondary KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
        <div class="rounded-lg border border-gray-200 bg-gray-50/80 px-4 py-3">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Buildings</p>
            <p class="text-xl font-semibold text-gray-900 tabular-nums">{{ $stats['total_buildings'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-gray-50/80 px-4 py-3">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Open tickets</p>
            <p class="text-xl font-semibold text-gray-900 tabular-nums">{{ $stats['open_tickets'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-gray-50/80 px-4 py-3">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Overdue invoices</p>
            <p class="text-xl font-semibold text-red-700 tabular-nums">{{ $stats['overdue_invoices'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-gray-50/80 px-4 py-3">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Plan</p>
            <p class="text-sm font-semibold text-gray-900 capitalize">{{ $company->subscription_plan ?? '—' }}</p>
        </div>
    </div>

    <x-dashboard-charts-section :chartData="$chartData" />

    {{-- Quick links --}}
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Quick access</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            <a href="{{ route('company.buildings.index', $company) }}" class="rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-800 hover:border-gmo-gold hover:bg-gmo-gold/5 transition-colors">Buildings</a>
            <a href="{{ route('company.units.index', $company) }}" class="rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-800 hover:border-gmo-gold hover:bg-gmo-gold/5 transition-colors">Units</a>
            <a href="{{ route('company.contracts.index', $company) }}" class="rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-800 hover:border-gmo-gold hover:bg-gmo-gold/5 transition-colors">Contracts</a>
            <a href="{{ route('company.maintenance.index', $company) }}" class="rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-800 hover:border-gmo-gold hover:bg-gmo-gold/5 transition-colors">Maintenance</a>
            <a href="{{ route('company.messages.index', $company) }}" class="rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-800 hover:border-gmo-gold hover:bg-gmo-gold/5 transition-colors">Messages</a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        {{-- Applications --}}
        <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent applications</h2>
                <a href="{{ route('company.applications.index', $company) }}" class="text-sm font-medium text-gmo-gold hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Applicant</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentApplications as $application)
                            <tr class="hover:bg-gray-50/80">
                                <td class="whitespace-nowrap px-4 py-3">
                                    <a href="{{ route('company.applications.show', [$company, $application]) }}" class="font-medium text-gmo-gold hover:underline">{{ $application->first_name }} {{ $application->last_name }}</a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $application->unit->building->name }} — {{ $application->unit->unit_number }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    @if($application->status === 'pending')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">Pending</span>
                                    @elseif($application->status === 'approved')
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Approved</span>
                                    @elseif($application->status === 'rejected')
                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Rejected</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">{{ ucfirst($application->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">No applications yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent invoices --}}
        <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent invoices</h2>
                <a href="{{ route('company.invoices.index', $company) }}" class="text-sm font-medium text-gmo-gold hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Invoice</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentInvoices as $invoice)
                            <tr class="hover:bg-gray-50/80">
                                <td class="whitespace-nowrap px-4 py-3">
                                    <a href="{{ route('company.invoices.show', [$company, $invoice]) }}" class="font-medium text-gmo-gold hover:underline">{{ $invoice->invoice_number }}</a>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm tabular-nums">R {{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium capitalize text-gray-800">{{ $invoice->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">No invoices yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Maintenance --}}
    <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Recent maintenance</h2>
            <a href="{{ route('company.maintenance.index', $company) }}" class="text-sm font-medium text-gmo-gold hover:underline">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Ticket</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentTickets as $ticket)
                        <tr class="hover:bg-gray-50/80">
                            <td class="whitespace-nowrap px-6 py-4">
                                <a href="{{ route('company.maintenance.show', [$company, $ticket]) }}" class="font-medium text-gmo-gold hover:underline">{{ $ticket->ticket_number }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $ticket->title }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $ticket->unit->building->name }} — {{ $ticket->unit->unit_number }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm capitalize">{{ $ticket->priority }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm capitalize">{{ str_replace('_', ' ', $ticket->status) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No maintenance tickets.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($upcomingRenewals->count() > 0)
        <div class="rounded-xl border border-amber-200 bg-amber-50/40 shadow-sm overflow-hidden">
            <div class="border-b border-amber-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-amber-900">Contract renewals (30 days)</h2>
            </div>
            <div class="overflow-x-auto bg-white">
                <table class="min-w-full divide-y divide-amber-100">
                    <thead class="bg-amber-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-amber-900/80">Contract</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-amber-900/80">Tenant</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-amber-900/80">Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-amber-900/80">Ends</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-50">
                        @foreach($upcomingRenewals as $contract)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <a href="{{ route('company.contracts.show', [$company, $contract]) }}" class="font-medium text-gmo-gold hover:underline">{{ $contract->contract_number }}</a>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $contract->tenant->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $contract->unit->building->name }} — {{ $contract->unit->unit_number }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $contract->end_date->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
