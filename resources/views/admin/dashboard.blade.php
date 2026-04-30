@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Platform overview')

@section('content')
<div class="space-y-8 max-w-[1600px]">
    {{-- Hero & quick actions --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Service provider console</h1>
            <p class="mt-1 text-sm text-gray-600 max-w-2xl">
                Manage property companies, subscriptions, and platform-wide analytics across the GMO Properties network.
            </p>
        </div>
        <div class="flex flex-wrap gap-3 shrink-0">
            <a href="{{ route('admin.companies.index') }}"
               class="inline-flex items-center justify-center rounded-lg bg-gmo-gold px-4 py-2.5 text-sm font-semibold text-black shadow-sm hover:bg-opacity-90 transition-colors">
                Manage companies
            </a>
            @if($stats['pending_companies'] > 0)
                <a href="{{ route('admin.companies.index') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-yellow-300 bg-yellow-50 px-4 py-2.5 text-sm font-medium text-yellow-900 hover:bg-yellow-100 transition-colors">
                    Pending approval ({{ $stats['pending_companies'] }})
                </a>
            @endif
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Public website
            </a>
        </div>
    </div>

    {{-- Primary KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-gray-500">Companies</p>
                    <p class="mt-1 text-3xl font-bold text-gmo-gold tabular-nums">{{ $stats['total_companies'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $stats['active_companies'] }} active · {{ $stats['pending_companies'] }} pending</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gmo-gold/15">
                    <svg class="h-6 w-6 text-gmo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-gray-500">Portfolio</p>
                    <p class="mt-1 text-3xl font-bold text-gmo-gold tabular-nums">{{ $stats['total_properties'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $stats['total_units'] }} units system-wide</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gmo-gold/15">
                    <svg class="h-6 w-6 text-gmo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-gray-500">Payments (this month)</p>
                    <p class="mt-1 text-3xl font-bold text-emerald-700 tabular-nums">R {{ number_format($financialOverview['monthly_revenue'], 2) }}</p>
                    <p class="mt-1 text-xs text-gray-500">All-time: R {{ number_format($financialOverview['total_revenue'], 2) }}</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                    <svg class="h-6 w-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-gray-500">Tenants & applications</p>
                    <p class="mt-1 text-3xl font-bold text-blue-700 tabular-nums">{{ $stats['total_tenants'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $stats['pending_applications'] }} pending of {{ $stats['total_applications'] }} applications</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Secondary platform metrics --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
        <div class="rounded-lg border border-gray-200 bg-gray-50/80 px-4 py-3">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Contracts</p>
            <p class="text-xl font-semibold text-gray-900 tabular-nums">{{ $platformWide['contracts'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-gray-50/80 px-4 py-3">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Invoices</p>
            <p class="text-xl font-semibold text-gray-900 tabular-nums">{{ $platformWide['invoices'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-gray-50/80 px-4 py-3">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Open maintenance</p>
            <p class="text-xl font-semibold text-gray-900 tabular-nums">{{ $platformWide['maintenance_open'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-gray-50/80 px-4 py-3">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Messages</p>
            <p class="text-xl font-semibold text-gray-900 tabular-nums">{{ $platformWide['messages'] }}</p>
        </div>
    </div>

    <x-dashboard-charts-section :chartData="$chartData" />

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Subscription MRR (estimated) --}}
        <div class="xl:col-span-2 rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Subscription revenue (estimated MRR)</h2>
                <p class="text-xs text-gray-500 mt-0.5">Per-plan rates are configured estimates for active companies — not necessarily tied to recorded billing.</p>
            </div>
            <div class="p-6 space-y-4">
                @foreach(['basic' => 'Basic', 'professional' => 'Professional', 'enterprise' => 'Enterprise'] as $key => $label)
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-50 pb-4 last:border-0 last:pb-0">
                        <div>
                            <p class="font-medium text-gray-900">{{ $label }}</p>
                            <p class="text-sm text-gray-500">{{ $subscriptionStats[$key] }} active {{ $subscriptionStats[$key] === 1 ? 'company' : 'companies' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gmo-gold tabular-nums">R {{ number_format($revenueByPlan[$key], 2) }}</p>
                            <p class="text-xs text-gray-500">/ month</p>
                        </div>
                    </div>
                @endforeach
                <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                    <span class="font-semibold text-gray-900">Total estimated MRR</span>
                    <span class="text-xl font-bold text-gmo-gold tabular-nums">R {{ number_format(array_sum($revenueByPlan), 2) }}</span>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Receivables snapshot</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Pending invoice total</span>
                        <span class="font-semibold text-amber-700 tabular-nums">R {{ number_format($financialOverview['pending_payments'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Overdue invoice total</span>
                        <span class="font-semibold text-red-700 tabular-nums">R {{ number_format($financialOverview['overdue_payments'], 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-100 pt-4 text-sm font-medium">
                        <span class="text-gray-900">Outstanding</span>
                        <span class="text-red-700 tabular-nums">R {{ number_format($financialOverview['pending_payments'] + $financialOverview['overdue_payments'], 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-5">
                <h3 class="text-sm font-semibold text-gray-900">Platform roadmap</h3>
                <p class="mt-2 text-xs text-gray-600 leading-relaxed">
                    Centralised <strong>audit logs</strong>, <strong>AI usage</strong> monitoring, and advanced analytics are planned extensions aligned with the service-provider admin capability set.
                </p>
            </div>
        </div>
    </div>

    @if($expiringSoon->count() > 0)
        <div class="rounded-xl border border-amber-200 bg-amber-50/50 shadow-sm overflow-hidden">
            <div class="border-b border-amber-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-amber-900">Subscriptions expiring (30 days)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-amber-100">
                    <thead class="bg-amber-50/80">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-amber-900/80">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-amber-900/80">Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-amber-900/80">Expires</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-amber-900/80">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-100 bg-white">
                        @foreach($expiringSoon as $company)
                            <tr class="hover:bg-amber-50/30">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <a href="{{ route('admin.companies.show', $company) }}" class="font-medium text-gmo-gold hover:underline">{{ $company->name }}</a>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">{{ ucfirst($company->subscription_plan) }}</span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                    {{ $company->subscription_expires_at->format('M d, Y') }}
                                    <span class="text-amber-700">({{ $company->subscription_expires_at->diffForHumans() }})</span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                    <a href="{{ route('admin.companies.edit', $company) }}" class="font-medium text-gmo-gold hover:underline">Renew / edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-6 py-4 flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-lg font-semibold text-gray-900">Recent companies</h2>
            <a href="{{ route('admin.companies.index') }}" class="text-sm font-medium text-gmo-gold hover:underline">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($recentCompanies as $company)
                        <tr class="hover:bg-gray-50/80">
                            <td class="whitespace-nowrap px-6 py-4">
                                <a href="{{ route('admin.companies.show', $company) }}" class="font-medium text-gmo-gold hover:underline">{{ $company->name }}</a>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $company->email }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($company->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Pending</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $company->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No companies yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($pendingCompanies->count() > 0)
        <div class="rounded-xl border border-yellow-200 bg-yellow-50/30 shadow-sm overflow-hidden">
            <div class="border-b border-yellow-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-yellow-900">Pending company approvals</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-yellow-100">
                    <thead class="bg-yellow-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-yellow-900/80">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-yellow-900/80">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-yellow-900/80">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-yellow-900/80">Review</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-yellow-100 bg-white">
                        @foreach($pendingCompanies as $company)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">{{ $company->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $company->email }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $company->created_at->format('M d, Y') }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <a href="{{ route('admin.companies.show', $company) }}" class="text-sm font-medium text-gmo-gold hover:underline">Open</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
