@extends('layouts.tenant')

@section('title', 'Create Maintenance Ticket')
@section('page-title', 'Create Maintenance Ticket')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Create Maintenance Ticket</h2>

        <!-- Unit Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="font-semibold mb-2">Unit Information</h3>
            <p class="text-sm text-gray-600">{{ $unit->building->name }} - {{ $unit->unit_number }}</p>
            <p class="text-sm text-gray-600">{{ $unit->building->address }}</p>
        </div>

        <form method="POST" action="{{ route('tenant.maintenance.store') }}">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Brief description of the issue">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="6" required class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Provide detailed information about the maintenance issue...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                <select name="priority" required class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low - Can wait</option>
                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium - Should be fixed soon</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High - Needs urgent attention</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent - Safety hazard or emergency</option>
                </select>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('tenant.maintenance.index') }}" class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                    Submit Ticket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
