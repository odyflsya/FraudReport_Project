<section id="fraud-analytics-dashboard" class="mt-8">
    {{-- Filter --}}
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Analisis Kasus Fraud</h2>
                <p class="text-sm text-gray-500">filter tahun & bulan</p>
            </div>
            <p id="filter_summary" class="text-xs text-gray-500"></p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="filter_year" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select id="filter_year" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Tahun</option>
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ (string) $year === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filter_month" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select id="filter_month" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Bulan</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ (string) $month === (string) $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $m, 1)->locale('id')->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end gap-2 sm:col-span-2">
                <button type="button" id="btn_apply_filter" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium transition">
                    Terapkan Filter
                </button>
                <button type="button" id="btn_reset_filter" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <div id="analytics_loading" class="hidden mt-6 text-center text-sm text-gray-500 py-8">
        <span class="inline-block w-5 h-5 border-2 border-red-600 border-t-transparent rounded-full animate-spin mr-2 align-middle"></span>
        Memuat data analisis…
    </div>

    <div id="analytics_content" class="mt-6 space-y-8">
        {{-- BAGIAN 1: Executive Summary --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Executive Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-red-600">
                    <p class="text-xs font-medium text-gray-500">Total Kasus Fraud</p>
                    <p id="kpi_total_kasus" class="text-2xl font-bold text-gray-900 mt-2">-</p>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-orange-500">
                    <p class="text-xs font-medium text-gray-500">Total Kerugian</p>
                    <p id="kpi_total_kerugian" class="text-2xl font-bold text-gray-900 mt-2">-</p>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-emerald-500">
                    <p class="text-xs font-medium text-gray-500">Total Recovery</p>
                    <p id="kpi_total_recovery" class="text-2xl font-bold text-gray-900 mt-2">-</p>
                </div>

            </div>
        </div>

        {{-- BAGIAN 2: Tren Fraud --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Tren Fraud</h3>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <h4 class="text-sm font-semibold text-gray-800 mb-1">Tren Kasus dan Kerugian Fraud</h4>
                <p class="text-xs text-gray-500 mb-4">Perkembangan jumlah kasus dan dampak kerugian</p>
                <div class="relative h-72 sm:h-80">
                    <canvas id="chart_trend_combined"></canvas>
                </div>
            </div>
        </div>

        {{-- BAGIAN 3: Profil Pelaku --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Profil Pelaku</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-800 mb-1">Internal vs Eksternal</h4>
                    <p class="text-xs text-gray-500 mb-3">Klik segmen untuk detail kasus</p>
                    <div class="relative h-64 max-w-sm mx-auto">
                        <canvas id="chart_internal_external"></canvas>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-800 mb-1">Jabatan Pelaku yang Paling Dominan</h4>
                    <p class="text-xs text-gray-500 mb-3">Top 10 jabatan — klik bar untuk detail</p>
                    <div class="relative h-64">
                        <canvas id="chart_top_jabatan"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 4: Pola Fraud --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Pola Fraud</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-800 mb-1">Jenis Fraud yang Paling Sering Terjadi</h4>
                    <p class="text-xs text-gray-500 mb-3">Top 10 — klik bar untuk detail</p>
                    <div class="relative h-72">
                        <canvas id="chart_top_jenis_fraud"></canvas>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-800 mb-1">Divisi/Unit Kerja dengan Kerugian Fraud Terbesar</h4>
                    <p class="text-xs text-gray-500 mb-3">Berdasarkan total kerugian — klik bar untuk detail</p>
                    <div class="relative h-72">
                        <canvas id="chart_division_loss"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 5: Akar Penyebab --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Akar Penyebab Fraud</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-800 mb-1">Kelemahan Penyebab Fraud</h4>
                    <p class="text-xs text-gray-500 mb-3">Top 10 kelemahan — klik bar untuk detail</p>
                    <div class="relative h-72">
                        <canvas id="chart_top_kelemahan"></canvas>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-800 mb-1">Aktivitas Bisnis yang Rentan Fraud</h4>
                    <p class="text-xs text-gray-500 mb-3">Aktivitas terkait — klik bar untuk detail</p>
                    <div class="relative h-72">
                        <canvas id="chart_activity_related"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 6: Status Penanganan --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Status Penanganan</h3>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 max-w-xl">
                <h4 class="text-sm font-semibold text-gray-800 mb-1">Status Penanganan Kasus</h4>
                <p class="text-xs text-gray-500 mb-3">Klik segmen untuk detail kasus</p>
                <div class="relative h-64 max-w-sm mx-auto">
                    <canvas id="chart_handling_status"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Drawer Drill-down --}}
<div id="drilldown_overlay" class="fixed inset-0 z-40 bg-black/40 hidden" aria-hidden="true"></div>
<aside id="drilldown_drawer"
       class="fixed top-0 right-0 z-50 h-full w-full sm:w-[90%] md:w-[40%] lg:w-[38%] max-w-xl bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-out flex flex-col"
       aria-labelledby="drawer_title"
       role="dialog">
    <div class="flex items-start justify-between border-b border-gray-200 px-5 py-4 shrink-0">
        <div class="min-w-0 pr-4">
            <h3 id="drawer_title" class="text-lg font-semibold text-gray-900">Detail Kasus</h3>
            <p id="drawer_filter_label" class="text-sm text-gray-600 mt-1 truncate"></p>
            <p id="drawer_total" class="text-xs font-medium text-red-600 mt-1">Total Kasus: -</p>
        </div>
        <button type="button" id="close_drilldown_drawer" class="shrink-0 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
            Tutup
        </button>
    </div>
    <div class="flex-1 overflow-y-auto px-5 py-4">
        <div id="drawer_loading" class="hidden text-center text-sm text-gray-500 py-8">Memuat daftar kasus…</div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse min-w-[520px]">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="border border-gray-200 p-2 text-left font-semibold text-gray-700">ID Kasus</th>
                        <th class="border border-gray-200 p-2 text-left font-semibold text-gray-700">Jenis Fraud</th>
                        <th class="border border-gray-200 p-2 text-left font-semibold text-gray-700">Divisi</th>
                        <th class="border border-gray-200 p-2 text-left font-semibold text-gray-700">Status Penanganan</th>
                        <th class="border border-gray-200 p-2 text-right font-semibold text-gray-700">Total Kerugian</th>
                        <th class="border border-gray-200 p-2 text-center font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody id="drawer_cases_body" class="divide-y divide-gray-100"></tbody>
            </table>
        </div>
        <p id="drawer_empty" class="hidden text-sm text-gray-500 text-center py-8">Tidak ada kasus untuk filter ini.</p>
    </div>
</aside>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
(function () {
    const chartInstances = {};
    const drillMeta = { type: null, value: null, label: null };

    const FILTER_LABELS = {
        internal_external: 'Kategori Pelaku',
        jabatan: 'Jabatan Pelaku',
        jenis_fraud: 'Jenis Fraud',
        divisi: 'Divisi',
        kelemahan: 'Kelemahan',
        aktivitas: 'Aktivitas Terkait',
        status_penanganan: 'Status Penanganan',
    };

    function formatCurrency(value) {
        const n = Number(value) || 0;
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function formatPercent(value) {
        return (Number(value) || 0).toFixed(2) + '%';
    }

    function getFilterParams() {
        const params = new URLSearchParams();
        const year = document.getElementById('filter_year').value;
        const month = document.getElementById('filter_month').value;
        if (year) params.append('year', year);
        if (month && year) params.append('month', month);
        return params;
    }

    function updateFilterSummary() {
        const year = document.getElementById('filter_year');
        const month = document.getElementById('filter_month');
        const y = year.value;
        const m = month.value;
        let text = 'Menampilkan: Semua data';
        if (y && m) {
            text = 'Menampilkan: ' + month.options[month.selectedIndex].text + ' ' + y;
        } else if (y) {
            text = 'Menampilkan: Tahun ' + y;
        }
        document.getElementById('filter_summary').textContent = text;
    }

    function destroyChart(key) {
        if (chartInstances[key]) {
            chartInstances[key].destroy();
            delete chartInstances[key];
        }
    }

    function attachDrillClick(chart, type, labels, values) {
        chart.options.onClick = (evt, elements) => {
            if (!elements.length) return;
            const idx = elements[0].index;
            openDrilldownDrawer(type, values[idx], labels[idx]);
        };
        chart.canvas.style.cursor = 'pointer';
    }

    function renderDashboard(data) {
        const kpi = data.kpi;
        document.getElementById('kpi_total_kasus').textContent = (kpi.total_kasus ?? 0).toLocaleString('id-ID');
        document.getElementById('kpi_total_kerugian').textContent = formatCurrency(kpi.total_kerugian);
        document.getElementById('kpi_total_recovery').textContent = formatCurrency(kpi.total_recovery);

        const trend = data.trend;
        destroyChart('trend');
        chartInstances.trend = new Chart(document.getElementById('chart_trend_combined'), {
            type: 'line',
            data: {
                labels: trend.labels,
                datasets: [
                    {
                        label: 'Jumlah Kasus',
                        data: trend.cases,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        yAxisID: 'y',
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Total Kerugian',
                        data: trend.loss,
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.06)',
                        yAxisID: 'y1',
                        tension: 0.35,
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    y: { type: 'linear', position: 'left', beginAtZero: true, title: { display: true, text: 'Kasus' } },
                    y1: { type: 'linear', position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, ticks: { callback: v => formatCurrency(v) } },
                },
            },
        });

        const internal = data.internal_vs_external || [];
        destroyChart('internal');
        const internalLabels = internal.map(d => d.kategori);
        const internalValues = internal.map(d => d.kategori);
        chartInstances.internal = new Chart(document.getElementById('chart_internal_external'), {
            type: 'doughnut',
            data: {
                labels: internalLabels,
                datasets: [{ data: internal.map(d => d.count), backgroundColor: ['#dc2626', '#059669', '#6366f1', '#f59e0b'] }],
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } },
        });
        attachDrillClick(chartInstances.internal, 'internal_external', internalLabels, internalValues);

        const jabatan = data.top_jabatan || [];
        destroyChart('jabatan');
        const jabatanLabels = jabatan.map(d => d.nama);
        chartInstances.jabatan = new Chart(document.getElementById('chart_top_jabatan'), {
            type: 'bar',
            data: {
                labels: jabatanLabels,
                datasets: [{ data: jabatan.map(d => d.count), backgroundColor: '#f59e0b' }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } },
            },
        });
        attachDrillClick(chartInstances.jabatan, 'jabatan', jabatanLabels, jabatanLabels);

        const jenis = data.top_jenis_fraud || [];
        destroyChart('jenis');
        const jenisLabels = jenis.map(d => d.nama);
        chartInstances.jenis = new Chart(document.getElementById('chart_top_jenis_fraud'), {
            type: 'bar',
            data: {
                labels: jenisLabels,
                datasets: [{ data: jenis.map(d => d.count), backgroundColor: '#3b82f6' }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } },
            },
        });
        attachDrillClick(chartInstances.jenis, 'jenis_fraud', jenisLabels, jenisLabels);

        const divisi = data.division_loss || [];
        destroyChart('divisi');
        const divisiLabels = divisi.map(d => d.divisi);
        chartInstances.divisi = new Chart(document.getElementById('chart_division_loss'), {
            type: 'bar',
            data: {
                labels: divisiLabels,
                datasets: [{ label: 'Total Kerugian', data: divisi.map(d => d.total_loss), backgroundColor: '#06b6d4' }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => formatCurrency(ctx.raw) } } },
                scales: { y: { beginAtZero: true, ticks: { callback: v => formatCurrency(v) } } },
            },
        });
        attachDrillClick(chartInstances.divisi, 'divisi', divisiLabels, divisiLabels);

        const kelemahan = data.top_kelemahan || [];
        destroyChart('kelemahan');
        const kelemahanLabels = kelemahan.map(d => d.nama);
        chartInstances.kelemahan = new Chart(document.getElementById('chart_top_kelemahan'), {
            type: 'bar',
            data: {
                labels: kelemahanLabels,
                datasets: [{ data: kelemahan.map(d => d.count), backgroundColor: '#b91c1c' }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } },
            },
        });
        attachDrillClick(chartInstances.kelemahan, 'kelemahan', kelemahanLabels, kelemahanLabels);

        const aktivitas = data.activity_related || [];
        destroyChart('aktivitas');
        const aktivitasLabels = aktivitas.map(d => d.nama);
        chartInstances.aktivitas = new Chart(document.getElementById('chart_activity_related'), {
            type: 'bar',
            data: {
                labels: aktivitasLabels,
                datasets: [{ data: aktivitas.map(d => d.count), backgroundColor: '#8b5cf6' }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } },
            },
        });
        attachDrillClick(chartInstances.aktivitas, 'aktivitas', aktivitasLabels, aktivitasLabels);

        const handling = data.handling_status || [];
        destroyChart('handling');
        const handlingLabels = handling.map(d => d.code + ' — ' + d.status);
        const handlingValues = handling.map(d => d.code);
        chartInstances.handling = new Chart(document.getElementById('chart_handling_status'), {
            type: 'doughnut',
            data: {
                labels: handlingLabels,
                datasets: [{ data: handling.map(d => d.count), backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#6366f1'] }],
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } },
        });
        attachDrillClick(chartInstances.handling, 'status_penanganan', handlingLabels, handlingValues);
    }

    function openDrilldownDrawer(type, value, displayLabel) {
        drillMeta.type = type;
        drillMeta.value = value;
        drillMeta.label = displayLabel;

        const overlay = document.getElementById('drilldown_overlay');
        const drawer = document.getElementById('drilldown_drawer');
        overlay.classList.remove('hidden');
        drawer.classList.remove('translate-x-full');
        document.body.classList.add('overflow-hidden');

        const filterName = FILTER_LABELS[type] || 'Filter';
        document.getElementById('drawer_filter_label').textContent = filterName + ' = ' + displayLabel;
        document.getElementById('drawer_total').textContent = 'Total Kasus: …';
        document.getElementById('drawer_loading').classList.remove('hidden');
        document.getElementById('drawer_empty').classList.add('hidden');
        document.getElementById('drawer_cases_body').innerHTML = '';

        const params = getFilterParams();
        params.append('type', type);
        params.append('value', value);

        fetch(`{{ route('analytics.drilldown') }}?${params}`)
            .then(r => r.json())
            .then(res => {
                document.getElementById('drawer_loading').classList.add('hidden');
                document.getElementById('drawer_total').textContent = 'Total Kasus: ' + (res.total ?? 0);
                const tbody = document.getElementById('drawer_cases_body');
                tbody.innerHTML = '';

                if (!res.cases || !res.cases.length) {
                    document.getElementById('drawer_empty').classList.remove('hidden');
                    return;
                }

                res.cases.forEach(kasus => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50';
                    tr.innerHTML = `
                        <td class="border border-gray-200 p-2 font-medium text-gray-900">${kasus.kode_kejadian}</td>
                        <td class="border border-gray-200 p-2 text-gray-700">${kasus.jenis_fraud}</td>
                        <td class="border border-gray-200 p-2 text-gray-700">${kasus.divisi}</td>
                        <td class="border border-gray-200 p-2 text-gray-700">${kasus.status_label}</td>
                        <td class="border border-gray-200 p-2 text-right font-medium">${formatCurrency(kasus.total_kerugian)}</td>
                        <td class="border border-gray-200 p-2 text-center">
                            <a href="${kasus.show_url}" class="inline-flex items-center px-2.5 py-1 rounded-md bg-red-600 text-white text-xs font-medium hover:bg-red-700">Lihat Detail</a>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            })
            .catch(() => {
                document.getElementById('drawer_loading').classList.add('hidden');
                document.getElementById('drawer_empty').classList.remove('hidden');
                document.getElementById('drawer_empty').textContent = 'Gagal memuat data.';
            });
    }

    function closeDrilldownDrawer() {
        document.getElementById('drilldown_overlay').classList.add('hidden');
        document.getElementById('drilldown_drawer').classList.add('translate-x-full');
        document.body.classList.remove('overflow-hidden');
    }

    function loadDashboardData() {
        const loading = document.getElementById('analytics_loading');
        const content = document.getElementById('analytics_content');
        loading.classList.remove('hidden');
        content.classList.add('opacity-50', 'pointer-events-none');
        updateFilterSummary();

        const params = getFilterParams();
        const url = `{{ route('analytics.dashboard') }}?${params}`;
        const qs = params.toString();
        if (qs) {
            const u = new URL(window.location.href);
            u.searchParams.delete('year');
            u.searchParams.delete('month');
            params.forEach((v, k) => u.searchParams.set(k, v));
            window.history.replaceState({}, '', u);
        } else {
            window.history.replaceState({}, '', '{{ route('dashboard') }}');
        }

        fetch(url)
            .then(r => r.json())
            .then(data => {
                renderDashboard(data);
                loading.classList.add('hidden');
                content.classList.remove('opacity-50', 'pointer-events-none');
            })
            .catch(() => {
                loading.classList.add('hidden');
                content.classList.remove('opacity-50', 'pointer-events-none');
                alert('Gagal memuat dashboard analisis.');
            });
    }

    document.getElementById('btn_apply_filter').addEventListener('click', loadDashboardData);
    document.getElementById('btn_reset_filter').addEventListener('click', () => {
        document.getElementById('filter_year').value = '';
        document.getElementById('filter_month').value = '';
        loadDashboardData();
    });
    document.getElementById('close_drilldown_drawer').addEventListener('click', closeDrilldownDrawer);
    document.getElementById('drilldown_overlay').addEventListener('click', closeDrilldownDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrilldownDrawer(); });

    document.addEventListener('DOMContentLoaded', loadDashboardData);
})();
</script>
