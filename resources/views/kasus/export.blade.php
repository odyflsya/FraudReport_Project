@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold">Export File - Laporan Kasus Fraud</h1>
            <div class="flex gap-3">
                <a href="{{ route('kasus.index') }}"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    ← Kembali ke Daftar Kasus
                </a>
                <button onclick="window.print()"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                    🖨️ Cetak
                </button>
                <button onclick="exportToCSV()"
                    class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                    📥 Download CSV
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">Filter Laporan</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Dari Tanggal</label>
                    <input type="date" id="filterFromDate" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Sampai Tanggal</label>
                    <input type="date" id="filterToDate"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Status</label>
                    <select id="filterStatus"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="dalam_proses">Dalam Proses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Jenis Laporan</label>
                    <select id="filterJenisLaporan"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Jenis</option>
                        <option value="semester">Semester</option>
                        <option value="signifikan">Signifikan</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button onclick="applyFilters()"
                        class="flex-1 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Filter
                    </button>
                    <button onclick="resetFilters()"
                        class="flex-1 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full" id="exportTable">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium">No</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Tanggal</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Kode Komponen</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Divisi/Unit</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Jenis Fraud</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Pelaku</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Status Penanganan</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Jenis Laporan</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Tindak Lanjut LJK</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">LJK Rill</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">LJK Potensial</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">LJK Recovery</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Konsumen Rill</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Konsumen Potensial</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Konsumen Recovery</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Pihak Lain Rill</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Pihak Lain Potensial</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Pihak Lain Recovery</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kasus as $index => $k)
                        <tr class="hover:bg-gray-50" data-jenis-laporan="{{ $k->jenis_laporan ?? 'semester' }}" data-status="{{ $k->status_penanganan ?? '' }}">
                            <td class="px-6 py-3 text-sm">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 text-sm">{{ $k->created_at ? $k->created_at->format('d-m-Y') : '-' }}</td>
                            <td class="px-6 py-3 text-sm font-medium">{{ $k->kode_komponen ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm">{{ $k->divisi_unit ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm">
                                @if($k->jenisFraud->count())
                                    {{ $k->jenisFraud->pluck('nama')->join(', ') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-sm">
                                @if($k->pelakuFrauds->count())
                                    {{ $k->pelakuFrauds->pluck('nama')->join(', ') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $k->status_penanganan === 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($k->status_penanganan ?? 'Dalam Proses') }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm">{{ ucfirst($k->jenis_laporan ?? 'semester') }}</td>
                            <td class="px-6 py-3 text-sm">{{ $k->tindak_lanjut_ljk ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm">{{ $k->jenis_laporan === 'signifikan' ? '-' : number_format($k->kerugianFraud->ljk_rill ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm">{{ number_format($k->kerugianFraud->ljk_potensial ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm">{{ $k->jenis_laporan === 'signifikan' ? '-' : number_format($k->kerugianFraud->ljk_recovery ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm">{{ $k->jenis_laporan === 'signifikan' ? '-' : number_format($k->kerugianFraud->konsumen_rill ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm">{{ number_format($k->kerugianFraud->konsumen_potensial ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm">{{ $k->jenis_laporan === 'signifikan' ? '-' : number_format($k->kerugianFraud->konsumen_recovery ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm">{{ $k->jenis_laporan === 'signifikan' ? '-' : number_format($k->kerugianFraud->pihak_lain_rill ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm">{{ number_format($k->kerugianFraud->pihak_lain_potensial ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm">{{ $k->jenis_laporan === 'signifikan' ? '-' : number_format($k->kerugianFraud->pihak_lain_recovery ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm">
                                <a href="{{ route('kasus.show', $k->id) }}" 
                                    class="text-blue-500 hover:text-blue-700 font-medium">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="19" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data kasus
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
            <div class="bg-blue-50 p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-600 mb-2">Total Kasus</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $kasus->count() }}</p>
            </div>
            <div class="bg-green-50 p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-600 mb-2">Selesai</h3>
                <p class="text-3xl font-bold text-green-600">{{ $kasus->whereIn('status_penanganan', ['002', '004'])->count() }}</p>
            </div>
            <div class="bg-yellow-50 p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-600 mb-2">Dalam Proses</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $kasus->whereNotIn('status_penanganan', ['002', '004'])->count() }}</p>
            </div>
            <div class="bg-purple-50 p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-600 mb-2">Total Pelaku</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $kasus->sum(fn($k) => $k->pelakuFrauds->count()) }}</p>
            </div>
        </div>
    </div>
</div>

<script>
function applyFilters() {
    const fromDate = document.getElementById('filterFromDate').value;
    const toDate = document.getElementById('filterToDate').value;
    const status = document.getElementById('filterStatus').value;
    const jenisLaporan = document.getElementById('filterJenisLaporan').value;
    
    const rows = document.querySelectorAll('#exportTable tbody tr');
    rows.forEach(row => {
        const rowDateText = row.querySelector('td:nth-child(2)')?.textContent.trim();
        const rowDate = rowDateText ? new Date(rowDateText.split('-').reverse().join('-')) : null;
        const rowStatus = row.dataset.status || '';
        const rowJenisLaporan = row.dataset.jenisLaporan || '';

        let show = true;

        if (fromDate && rowDate) {
            show = show && rowDate >= new Date(fromDate);
        }
        if (toDate && rowDate) {
            show = show && rowDate <= new Date(toDate);
        }
        if (status) {
            if (status === 'dalam_proses') {
                show = show && !['002', '004'].includes(rowStatus);
            } else if (status === 'selesai') {
                show = show && ['002', '004'].includes(rowStatus);
            }
        }
        if (jenisLaporan) {
            show = show && rowJenisLaporan === jenisLaporan;
        }

        row.style.display = show ? 'table-row' : 'none';
    });
}

function resetFilters() {
    document.getElementById('filterFromDate').value = '';
    document.getElementById('filterToDate').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterJenisLaporan').value = '';
    
    const rows = document.querySelectorAll('#exportTable tbody tr');
    rows.forEach(row => {
        row.style.display = 'table-row';
    });
}

function exportToCSV() {
    const table = document.getElementById('exportTable');
    let csv = [];
    
    // Headers
    const headers = [];
    table.querySelectorAll('thead th').forEach(th => {
        headers.push(th.textContent.trim());
    });
    csv.push(headers.join(','));
    
    // Data rows
    table.querySelectorAll('tbody tr').forEach(row => {
        if(row.style.display !== 'none') {
            const cols = [];
            row.querySelectorAll('td').forEach((td, idx) => {
                if(idx < headers.length - 1) { // Exclude action column
                    cols.push('"' + td.textContent.trim().replace(/"/g, '""') + '"');
                }
            });
            csv.push(cols.join(','));
        }
    });
    
    // Download
    const csvContent = 'data:text/csv;charset=utf-8,' + csv.join('\n');
    const link = document.createElement('a');
    link.href = encodeURI(csvContent);
    link.download = 'kasus_fraud_' + new Date().toISOString().split('T')[0] + '.csv';
    link.click();
}
</script>

@endsection
