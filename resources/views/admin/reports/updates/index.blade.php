@extends('layouts.admin')

@section('title', 'Platform updates report')
@section('page-title', 'Platform updates report')

@section('content')
@php
    $q = array_filter([
        'date_from' => $filters['date_from'] ?? $from->toDateString(),
        'date_to' => $filters['date_to'] ?? $to->toDateString(),
        'company_id' => $filters['company_id'] ?? null,
    ], fn ($v) => $v !== null && $v !== '');
@endphp
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Activity across portals</h2>
            <p class="text-sm text-gray-500 mt-1">Companies, applications, contracts, invoices, maintenance, and payments in the selected window.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.reports.updates.export.csv', $q) }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-md hover:bg-gray-800">Export CSV</a>
            <a href="{{ route('admin.reports.updates.export.pdf', $q) }}" class="px-4 py-2 bg-gmo-gold text-black text-sm font-semibold rounded-md hover:bg-opacity-90">Export PDF</a>
        </div>
    </div>

    <form method="get" action="{{ route('admin.reports.updates.index') }}" class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="date_from" class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">From</label>
                <input type="date" name="date_from" id="date_from" value="{{ old('date_from', $from->toDateString()) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold text-sm">
            </div>
            <div>
                <label for="date_to" class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">To</label>
                <input type="date" name="date_to" id="date_to" value="{{ old('date_to', $to->toDateString()) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold text-sm">
            </div>
            <div class="md:col-span-2">
                <label for="company_id" class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Company (optional)</label>
                <select name="company_id" id="company_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold text-sm">
                    <option value="">All companies</option>
                    @foreach ($companies as $c)
                        <option value="{{ $c->id }}" @selected((string) old('company_id', $filters['company_id'] ?? '') === (string) $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button type="submit" class="px-4 py-2 bg-gmo-gold text-black text-sm font-semibold rounded-md hover:bg-opacity-90">Apply filters</button>
            <a href="{{ route('admin.reports.updates.index') }}" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50">Reset</a>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 text-sm text-gray-600">
            {{ $rows->count() }} row(s) · {{ $from->format('M j, Y') }} — {{ $to->format('M j, Y') }}
        </div>
        @if ($rows->isEmpty())
            <div class="p-12 text-center text-gray-500">No activity in this range.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">When</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Summary</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($rows as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                    {{ $row['at'] instanceof \Carbon\Carbon ? $row['at']->format('Y-m-d H:i') : '—' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $row['company'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $row['entity'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $row['summary'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
