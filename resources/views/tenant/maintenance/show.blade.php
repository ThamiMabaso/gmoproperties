@extends('layouts.tenant')

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
                <h3 class="font-semibold mb-2">Unit Information</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Building:</span> {{ $ticket->unit->building->name }}</p>
                    <p><span class="text-gray-600">Unit:</span> {{ $ticket->unit->unit_number }}</p>
                    <p><span class="text-gray-600">Address:</span> {{ $ticket->unit->building->address }}</p>
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

        <!-- Assigned To -->
        @if($ticket->assignedUser)
        <div class="mb-6">
            <h3 class="font-semibold mb-2">Assigned To</h3>
            <p class="text-sm text-gray-600">{{ $ticket->assignedUser->name }}</p>
        </div>
        @endif

        <!-- Resolution Notes -->
        @if($ticket->resolution_notes)
        <div class="mb-6">
            <h3 class="font-semibold mb-2">Resolution Notes</h3>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $ticket->resolution_notes }}</p>
            </div>
        </div>
        @endif

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

        <!-- Rating -->
        @if($ticket->isCompleted() && $ticket->rating)
        <div class="mb-6">
            <h3 class="font-semibold mb-2">Your Rating</h3>
            <div class="flex items-center">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-6 h-6 {{ $i <= $ticket->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
                <span class="ml-2 text-sm text-gray-600">{{ $ticket->rating }}/5</span>
            </div>
            @if($ticket->tenant_feedback)
                <p class="text-sm text-gray-600 mt-2">{{ $ticket->tenant_feedback }}</p>
            @endif
        </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('tenant.maintenance.index') }}" class="text-gmo-gold hover:underline">
                ← Back to Tickets
            </a>
        </div>
    </div>
</div>
@endsection
