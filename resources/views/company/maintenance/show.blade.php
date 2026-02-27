@extends('layouts.company')

@section('title', 'Maintenance Ticket Details')
@section('page-title', 'Maintenance Ticket Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold">Maintenance Ticket</h2>
                <p class="text-gray-600">Ticket #{{ $ticket->ticket_number }}</p>
            </div>
            <div>
                @if($ticket->priority === 'urgent')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800">Urgent</span>
                @elseif($ticket->priority === 'high')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-orange-100 text-orange-800">High</span>
                @elseif($ticket->priority === 'medium')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Medium</span>
                @else
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Low</span>
                @endif
            </div>
        </div>

        <!-- Ticket Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold mb-2">Ticket Information</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Title:</span> {{ $ticket->title }}</p>
                    <p><span class="text-gray-600">Status:</span> 
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst($ticket->status) }}</span>
                    </p>
                    <p><span class="text-gray-600">Created:</span> {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                    @if($ticket->assigned_at)
                        <p><span class="text-gray-600">Assigned:</span> {{ $ticket->assigned_at->format('M d, Y H:i') }}</p>
                    @endif
                    @if($ticket->completed_at)
                        <p><span class="text-gray-600">Completed:</span> {{ $ticket->completed_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Unit & Tenant Information</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Building:</span> {{ $ticket->unit->building->name }}</p>
                    <p><span class="text-gray-600">Unit:</span> {{ $ticket->unit->unit_number }}</p>
                    <p><span class="text-gray-600">Tenant:</span> {{ $ticket->tenant->name }}</p>
                    <p><span class="text-gray-600">Tenant Email:</span> {{ $ticket->tenant->email }}</p>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <h3 class="font-semibold mb-2">Description</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
            </div>
        </div>

        <!-- Assignment -->
        <div class="mb-6">
            <h3 class="font-semibold mb-4">Assignment</h3>
            @if(!$ticket->assigned_to)
                <form method="POST" action="{{ route('company.maintenance.assign', [$company, $ticket]) }}" class="flex items-end space-x-4">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                        <select name="assigned_to" required class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Select a user...</option>
                            @foreach($availableAssignees as $assignee)
                                <option value="{{ $assignee->id }}">{{ $assignee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                        Assign
                    </button>
                </form>
            @else
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm"><span class="text-gray-600">Assigned To:</span> <span class="font-semibold">{{ $ticket->assignedUser->name }}</span></p>
                    @if($ticket->assigned_at)
                        <p class="text-sm text-gray-600 mt-1">Assigned on {{ $ticket->assigned_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Status Update -->
        <div class="mb-6">
            <h3 class="font-semibold mb-4">Update Status</h3>
            <form method="POST" action="{{ route('company.maintenance.update-status', [$company, $ticket]) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" required class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="assigned" {{ $ticket->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $ticket->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $ticket->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Actual Cost (R)</label>
                        <input type="number" name="actual_cost" value="{{ old('actual_cost', $ticket->actual_cost) }}" step="0.01" min="0" class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Resolution Notes</label>
                    <textarea name="resolution_notes" rows="4" class="w-full border-gray-300 rounded-md shadow-sm">{{ old('resolution_notes', $ticket->resolution_notes) }}</textarea>
                </div>
                <button type="submit" class="px-6 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                    Update Status
                </button>
            </form>
        </div>

        <!-- Cost Information -->
        @if($ticket->estimated_cost || $ticket->actual_cost)
        <div class="mb-6">
            <h3 class="font-semibold mb-2">Cost Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($ticket->estimated_cost)
                    <div>
                        <p class="text-sm text-gray-600">Estimated Cost</p>
                        <p class="text-lg font-semibold">R {{ number_format($ticket->estimated_cost, 2) }}</p>
                    </div>
                @endif
                @if($ticket->actual_cost)
                    <div>
                        <p class="text-sm text-gray-600">Actual Cost</p>
                        <p class="text-lg font-semibold text-gmo-gold">R {{ number_format($ticket->actual_cost, 2) }}</p>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('company.maintenance.index', $company) }}" class="text-gmo-gold hover:underline">
                ← Back to Tickets
            </a>
        </div>
    </div>
</div>
@endsection
