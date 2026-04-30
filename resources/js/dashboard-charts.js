import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const GOLD = 'rgba(212, 175, 55, 0.9)';
const GOLD_FILL = 'rgba(212, 175, 55, 0.15)';
const SLATE = 'rgba(17, 24, 39, 0.85)';
const MUTED = 'rgba(107, 114, 128, 0.85)';

const DOUGHNUT_COLORS = [
    'rgba(212, 175, 55, 0.85)',
    'rgba(17, 24, 39, 0.75)',
    'rgba(59, 130, 246, 0.75)',
    'rgba(16, 185, 129, 0.75)',
    'rgba(245, 158, 11, 0.85)',
    'rgba(139, 92, 246, 0.75)',
];

function readPayload() {
    const el = document.getElementById('dashboard-charts-data');

    if (!el) {
        return null;
    }

    try {
        return JSON.parse(el.textContent || '{}');
    } catch {
        return null;
    }
}

function buildChartConfig(spec) {
    if (!spec || !spec.type) {
        return null;
    }

    const type = spec.type;

    if (type === 'doughnut') {
        const values = spec.values || [];
        const labels = spec.labels || [];

        return {
            type: 'doughnut',
            data: {
                labels,
                datasets: [
                    {
                        data: values,
                        backgroundColor: labels.map((_, i) => DOUGHNUT_COLORS[i % DOUGHNUT_COLORS.length]),
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: MUTED, boxWidth: 12 },
                    },
                },
            },
        };
    }

    const labels = spec.labels || [];
    const values = spec.values || [];
    const datasetLabel = spec.datasetLabel || 'Value';

    if (type === 'line') {
        return {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: datasetLabel,
                        data: values,
                        borderColor: GOLD,
                        backgroundColor: GOLD_FILL,
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: { color: MUTED, maxRotation: 45, minRotation: 0 },
                        grid: { color: 'rgba(0,0,0,0.06)' },
                    },
                    y: {
                        ticks: { color: MUTED },
                        grid: { color: 'rgba(0,0,0,0.06)' },
                        beginAtZero: true,
                    },
                },
                plugins: {
                    legend: {
                        labels: { color: SLATE },
                    },
                },
            },
        };
    }

    if (type === 'bar') {
        return {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: datasetLabel,
                        data: values,
                        backgroundColor: labels.map((_, i) =>
                            i % 2 === 0 ? GOLD : 'rgba(17, 24, 39, 0.55)'
                        ),
                        borderRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: { color: MUTED, maxRotation: 45, minRotation: 0 },
                        grid: { display: false },
                    },
                    y: {
                        ticks: { color: MUTED },
                        grid: { color: 'rgba(0,0,0,0.06)' },
                        beginAtZero: true,
                    },
                },
                plugins: {
                    legend: {
                        display: labels.length <= 8,
                        labels: { color: SLATE },
                    },
                },
            },
        };
    }

    return null;
}

function mountCharts() {
    const payload = readPayload();

    if (!payload || !payload.charts) {
        return;
    }

    const charts = payload.charts;

    Object.keys(charts).forEach((key) => {
        const canvas = document.querySelector(`canvas[data-chart-key="${key}"]`);

        if (!canvas) {
            return;
        }

        const spec = charts[key];
        const config = buildChartConfig(spec);

        if (!config) {
            return;
        }

        const ctx = canvas.getContext('2d');

        if (!ctx) {
            return;
        }

        new Chart(ctx, config);
    });
}

document.addEventListener('DOMContentLoaded', mountCharts);
