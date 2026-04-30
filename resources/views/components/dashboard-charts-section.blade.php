@props([
    'chartData' => ['charts' => []],
    'gridClass' => 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6',
])

@if(!empty($chartData['charts']))
    <script type="application/json" id="dashboard-charts-data">@json($chartData)</script>
    <div class="space-y-3">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Trends &amp; analytics</h2>
            <p class="text-sm text-gray-500 mt-0.5">Visual summaries from your live data.</p>
        </div>
        <div class="{{ $gridClass }}">
            @foreach($chartData['charts'] as $key => $chart)
                @if(!is_array($chart) || empty($chart['title']))
                    @continue
                @endif
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden flex flex-col min-h-0">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-4 py-3 sm:px-6 sm:py-4">
                        <h3 class="text-base font-semibold text-gray-900">{{ $chart['title'] }}</h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="h-56 sm:h-64 w-full">
                            <canvas data-chart-key="{{ $key }}"></canvas>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
