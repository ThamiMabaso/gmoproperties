@extends('layouts.company')

@section('title', 'Maintenance Tickets')
@section('page-title', 'Maintenance Tickets')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Maintenance Tickets</h2>

        <!-- Filters -->
        <form method="GET" action="{{ route('company.maintenance.index', $company) }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select name="priority" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Priorities</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                    Filter
                </button>
            </div>
        </form>

        <!-- Tickets Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ticket #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($tickets as $ticket)
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $ticket->tenant->name }}</td>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $ticket->assignedUser ? $ticket->assignedUser->name : 'Unassigned' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('company.maintenance.show', [$company, $ticket]) }}" class="text-gmo-gold hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No maintenance tickets found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection
