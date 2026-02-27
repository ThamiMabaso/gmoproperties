@extends('layouts.company')

@section('title', 'Financial Reports')
@section('page-title', 'Financial Reports')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-gmo-gold">R {{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Monthly Revenue</p>
            <p class="text-2xl font-bold text-green-600">R {{ number_format($monthlyRevenue, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Outstanding Invoices</p>
            <p class="text-2xl font-bold text-yellow-600">R {{ number_format($outstandingInvoices, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Total Expenses</p>
            <p class="text-2xl font-bold text-red-600">R {{ number_format($totalExpenses, 2) }}</p>
        </div>
    </div>

    <!-- Profit/Loss -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Total Profit/Loss</h3>
            <p class="text-3xl font-bold {{ $totalProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                R {{ number_format($totalProfit, 2) }}
            </p>
            <p class="text-sm text-gray-600 mt-2">Revenue: R {{ number_format($totalRevenue, 2) }} - Expenses: R {{ number_format($totalExpenses, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Monthly Profit/Loss</h3>
            <p class="text-3xl font-bold {{ $monthlyProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                R {{ number_format($monthlyProfit, 2) }}
            </p>
            <p class="text-sm text-gray-600 mt-2">Revenue: R {{ number_format($monthlyRevenue, 2) }} - Expenses: R {{ number_format($monthlyExpenses, 2) }}</p>
        </div>
    </div>

    <!-- Revenue by Month Chart -->
    @if($revenueByMonth->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Revenue by Month (Last 12 Months)</h3>
        <div class="space-y-2">
            @foreach($revenueByMonth as $month)
                <div class="flex items-center">
                    <div class="w-32 text-sm text-gray-600">
                        {{ \Carbon\Carbon::create($month->year, $month->month, 1)->format('M Y') }}
                    </div>
                    <div class="flex-1 bg-gray-200 rounded-full h-6 relative">
                        <div class="bg-gmo-gold h-6 rounded-full" style="width: {{ ($month->revenue / $revenueByMonth->max('revenue')) * 100 }}%"></div>
                        <span class="absolute left-2 top-0.5 text-xs font-semibold text-black">R {{ number_format($month->revenue, 2) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Expenses by Category -->
    @if($expensesByCategory->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Expenses by Category</h3>
        <div class="space-y-4">
            @foreach($expensesByCategory as $category)
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $category->category)) }}</span>
                        <span class="text-sm font-semibold">R {{ number_format($category->total, 2) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-600 h-2 rounded-full" style="width: {{ ($category->total / $expensesByCategory->sum('total')) * 100 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Top Revenue Units -->
    @if($topRevenueUnits->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Top Revenue Units</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Building</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($topRevenueUnits as $unit)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $unit->unit->unit_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $unit->unit->building->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gmo-gold">R {{ number_format($unit->revenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
