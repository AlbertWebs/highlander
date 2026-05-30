@extends('layouts.admin')

@section('title', __('Dashboard'))
@section('heading', __('Dashboard'))
@section('breadcrumb')
    <span>{{ __('Home') }}</span>
@endsection

@section('content')
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    @foreach([
        ['key' => 'totalBookings', 'label' => __('Total Bookings'), 'value' => $totalBookings, 'class' => 'text-primary'],
        ['key' => 'totalVisitors', 'label' => __('Total Visitors'), 'value' => number_format($totalVisitors), 'class' => 'text-secondary'],
        ['key' => 'totalTours', 'label' => __('Total Tours'), 'value' => $totalTours, 'class' => 'text-accent'],
        ['key' => 'pendingInquiries', 'label' => __('Pending Inquiries'), 'value' => $pendingInquiries, 'class' => 'text-primary'],
        ['key' => 'articlesCount', 'label' => __('Articles'), 'value' => $articlesCount, 'class' => 'text-secondary'],
        ['key' => 'galleryCount', 'label' => __('Gallery Images'), 'value' => $galleryCount, 'class' => 'text-accent'],
    ] as $card)
        <div class="rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft">
            <p class="text-sm font-medium text-ink/60">{{ $card['label'] }}</p>
            <p class="mt-2 text-3xl font-bold {{ $card['class'] }}" data-kpi="{{ $card['key'] }}">{{ $card['value'] }}</p>
        </div>
    @endforeach
</div>

<div class="mt-8 grid gap-8 lg:grid-cols-2">
    <div class="rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft">
        <h2 class="text-lg font-semibold text-primary">{{ __('Bookings per month') }}</h2>
        <canvas id="chartBookings" height="220"></canvas>
    </div>
    <div class="rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft">
        <h2 class="text-lg font-semibold text-primary">{{ __('Traffic trends') }}</h2>
        <canvas id="chartTraffic" height="220"></canvas>
    </div>
</div>

<div class="mt-8 grid gap-8 lg:grid-cols-2">
    <div class="rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft">
        <h2 class="text-lg font-semibold text-primary">{{ __('Popular destinations') }}</h2>
        <ul class="mt-4 space-y-2">
            @forelse($popularDestinations as $d)
                <li class="flex justify-between rounded-lg bg-secondary/30 px-3 py-2 text-sm">
                    <span>{{ $d->name }}</span>
                    <span class="text-ink/50">#{{ $d->sort_order }}</span>
                </li>
            @empty
                <li class="text-sm text-ink/60">{{ __('No data yet.') }}</li>
            @endforelse
        </ul>
    </div>
    <div class="rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft">
        <h2 class="text-lg font-semibold text-primary">{{ __('Recent activity') }}</h2>
        <ul class="mt-4 max-h-80 space-y-3 overflow-y-auto text-sm">
            @forelse($recentActivity as $log)
                <li class="border-b border-secondary/30 pb-2">
                    <span class="font-medium text-ink">{{ $log->action }}</span>
                    @if($log->description)<span class="text-ink/70"> — {{ $log->description }}</span>@endif
                    <span class="block text-xs text-ink/50">{{ $log->created_at->diffForHumans() }} @if($log->user) · {{ $log->user->name }} @endif</span>
                </li>
            @empty
                <li class="text-ink/60">{{ __('No activity yet.') }}</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const primary = '#4CAF50';
    const secondary = '#A7C7C7';
    const labels = @json($bookingsChartLabels);
    const chartBookings = new Chart(document.getElementById('chartBookings'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: @json(__('Bookings')),
                data: @json($bookingsChartData),
                borderColor: primary,
                backgroundColor: 'rgba(76, 175, 80, 0.12)',
                fill: true,
                tension: 0.35,
            }],
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } },
    });
    const chartTraffic = new Chart(document.getElementById('chartTraffic'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: @json(__('Traffic')),
                data: @json($trafficChartData),
                backgroundColor: secondary,
                borderRadius: 8,
            }],
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } },
    });

    const formatKpi = (key, value) => {
        if (key === 'totalVisitors') {
            return Number(value || 0).toLocaleString();
        }

        return String(value ?? 0);
    };

    const applyDashboardData = (payload) => {
        const bookingsLabels = payload?.bookingsChartLabels ?? [];
        const bookingsData = payload?.bookingsChartData ?? [];
        const trafficData = payload?.trafficChartData ?? [];

        chartBookings.data.labels = bookingsLabels;
        chartBookings.data.datasets[0].data = bookingsData;
        chartBookings.update('none');

        chartTraffic.data.labels = bookingsLabels;
        chartTraffic.data.datasets[0].data = trafficData;
        chartTraffic.update('none');

        document.querySelectorAll('[data-kpi]').forEach((el) => {
            const key = el.getAttribute('data-kpi');
            if (key && Object.prototype.hasOwnProperty.call(payload, key)) {
                el.textContent = formatKpi(key, payload[key]);
            }
        });
    };

    const refreshDashboardData = async () => {
        try {
            const response = await fetch(@json(route('admin.dashboard.data')), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!response.ok) return;
            const payload = await response.json();
            applyDashboardData(payload);
        } catch (error) {
            // Keep current chart state if polling fails.
        }
    };

    refreshDashboardData();
    setInterval(refreshDashboardData, 15000);
});
</script>
@endpush
