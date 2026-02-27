@extends('layouts.company')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Units</p>
                    <p class="text-2xl font-bold text-gmo-gold">{{ $stats['total_units'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['occupied_units'] }} occupied / {{ $stats['available_units'] }} available</p>
                </div>
                <div class="w-12 h-12 bg-gmo-gold bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-gmo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Contracts</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active_contracts'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending Applications</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_applications'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-gmo-gold">R {{ number_format($stats['monthly_revenue'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-gmo-gold bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-gmo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold">Recent Applications</h3>
            <a href="{{ route('company.applications.index', $company) }}" class="text-sm text-gmo-gold hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applicant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentApplications as $application)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('company.applications.show', [$company, $application]) }}" class="text-gmo-gold hover:underline">
                                    {{ $application->first_name }} {{ $application->last_name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $application->unit->building->name }} - {{ $application->unit->unit_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ ucfirst(str_replace('_', ' ', $application->application_type)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($application->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($application->status === 'approved')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @elseif($application->status === 'rejected')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($application->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $application->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No applications found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Maintenance Tickets -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold">Recent Maintenance Tickets</h3>
            <a href="{{ route('company.maintenance.index', $company) }}" class="text-sm text-gmo-gold hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ticket #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentTickets as $ticket)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('company.maintenance.show', [$company, $ticket]) }}" class="text-gmo-gold hover:underline">
                                    {{ $ticket->ticket_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $ticket->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $ticket->unit->building->name }} - {{ $ticket->unit->unit_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ticket->priority === 'urgent')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Urgent</span>
                                @elseif($ticket->priority === 'high')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">High</span>
                                @elseif($ticket->priority === 'medium')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Medium</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Low</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst($ticket->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No maintenance tickets found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upcoming Renewals -->
    @if($upcomingRenewals->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Upcoming Contract Renewals (Next 30 Days)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contract #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($upcomingRenewals as $contract)
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $contract->end_date->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
