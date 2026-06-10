@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-full mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold">Export Laporan Kasus Fraud</h1>
            <div class="flex gap-2">
                <a href="{{ route('kasus.index') }}"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    ← Kembali
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <form id="filterForm" method="GET" action="{{ route('kasus.export') }}" class="bg-white p-6 rounded-lg shadow mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" id="filterFromDate" 
                        value="{{ request('dari_tanggal') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Filter berdasarkan tanggal saat fraud diketahui.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" id="filterToDate"
                        value="{{ request('sampai_tanggal') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Status</label>
                    <select name="status_penanganan" id="filterStatus"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="001" {{ request('status_penanganan') === '001' ? 'selected' : '' }}>001 (Proses internal LJK)</option>
                        <option value="002" {{ request('status_penanganan') === '002' ? 'selected' : '' }}>002 (Selesai diproses internal LJK)</option>
                        <option value="003" {{ request('status_penanganan') === '003' ? 'selected' : '' }}>003 (Dalam proses penanganan aparat penegak hukum)</option>
                        <option value="004" {{ request('status_penanganan') === '004' ? 'selected' : '' }}>004 (Berkekuatan hukum tetap)</option>
                    </select>
                </div>
<div class="flex items-start pt-7">
                        <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </div>

            <!-- Export Buttons -->
<div class="flex flex-col md:flex-row justify-center gap-3 mt-4 pt-4 border-t">                <button type="button" onclick="resetFilters()"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset
                </button>
                <button type="button" onclick="exportToExcel()"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Excel
                </button>
            </div>
        </form>

        <!-- Active Filter Info -->
        @if(request('dari_tanggal') || request('sampai_tanggal') || request('status_penanganan'))
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                <p class="text-sm text-blue-700">
                    <strong>Filter Aktif:</strong>
                    @if(request('dari_tanggal') || request('sampai_tanggal'))
                        Waktu Fraud Diketahui:
                        @if(request('dari_tanggal'))
                            Dari {{ \Carbon\Carbon::createFromFormat('Y-m-d', request('dari_tanggal'))->format('d-m-Y') }}
                        @endif
                        @if(request('sampai_tanggal'))
                            sampai {{ \Carbon\Carbon::createFromFormat('Y-m-d', request('sampai_tanggal'))->format('d-m-Y') }}
                        @endif
                    @endif
                    @if(request('status_penanganan'))
                        @if(request('dari_tanggal') || request('sampai_tanggal'))
                            | 
                        @endif
                        Status: 
                        @switch(request('status_penanganan'))
                            @case('001')
                                001 (Proses internal LJK)
                                @break
                            @case('002')
                                002 (Selesai diproses internal LJK)
                                @break
                            @case('003')
                                003 (Dalam proses penanganan aparat penegak hukum)
                                @break
                            @case('004')
                                004 (Berkekuatan hukum tetap)
                                @break
                            @default
                                {{ request('status_penanganan') }}
                        @endswitch
                    @endif
                </p>
            </div>
        @endif

        <!-- Table Selector -->
<div class="bg-white p-4 rounded-xl shadow mb-6">
    <div class="flex justify-end">
        <select id="reportTypeSelector"
            class="px-7 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="semester" {{ request('jenis_laporan') === 'semester' ? 'selected' : '' }}>Laporan Semester</option>
                    <option value="signifikan" {{ request('jenis_laporan') === 'signifikan' ? 'selected' : '' }}>Laporan Signifikan</option>
                    <option value="non-signifikan" {{ request('jenis_laporan') === 'non-signifikan' ? 'selected' : '' }}>Laporan Non-Signifikan</option>
                </select>
            </div>
        </div>

        <!-- Column Selector -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Pilih Kolom untuk Export</h2>
                <div class="flex gap-2">
                    <button type="button" onclick="selectAllColumns()"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-sm">
                        Pilih Semua
                    </button>
                    <button type="button" onclick="deselectAllColumns()"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition text-sm">
                        Batalkan Semua
                    </button>
                </div>
            </div>

            <!-- Semester Columns -->
            <div id="semesterColumnsSelector" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <!-- Will be populated by JavaScript -->
            </div>

            <!-- Signifikan Columns -->
            <div id="signifikanColumnsSelector" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 hidden">
                <!-- Will be populated by JavaScript -->
            </div>

            <!-- Non-signifikan Columns -->
            <div id="nonSignifikanColumnsSelector" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 hidden">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>

        @php
            $statusLabels = [
                '001' => '001 (Proses internal LJK)',
                '002' => '002 (Selesai diproses internal LJK)',
                '003' => '003 (Dalam proses penanganan aparat penegak hukum)',
                '004' => '004 (Berkekuatan hukum tetap)',
            ];
            $formatCurrency = fn($value) => $value === null || $value === '' ? '' : number_format($value, 0, ',', '.');
            $formatRecovery = fn($value) => $value === null || $value === '' || $value === 0 ? '' : number_format($value, 0, ',', '.');
            $formatRefLabel = function ($ref) {
                return $ref
                    ? ($ref->kode ? $ref->kode . ' (' . $ref->nama . ')' : $ref->nama)
                    : '';
            };
        @endphp

        <!-- LAPORAN SEMESTER TABLE -->
        <div id="semesterTableContainer" class="bg-white rounded-lg shadow overflow-x-auto mb-8">
            <table class="min-w-[3500px] text-xs border-collapse" id="semesterTable">
                <colgroup id="semesterColGroup"></colgroup>
                <thead class="bg-[#FF0000] text-white">
                    <tr>
                    <th rowspan="3" class="border p-2" data-column="No">No</th>
                    <th rowspan="3" class="border p-2" data-column="Kode Komponen">Kode Komponen</th>
                    <th rowspan="3" class="border p-2" data-column="Kejadian Fraud Menurut Pelaku">Kejadian Fraud Menurut Pelaku</th>
                    <th rowspan="3" class="border p-2" data-column="ID Kejadian Fraud">ID Kejadian Fraud</th>

                    <th colspan="2" class="border p-2" data-group="group-jenis-fraud">Jenis Fraud</th>

                    <th rowspan="3" class="border p-2" data-column="Aktivitas Terkait Fraud">Aktivitas Terkait Fraud</th>
                    <th rowspan="3" class="border p-2" data-column="Deskripsi Fraud / Modus Operandi">Deskripsi Fraud / Modus Operandi</th>

                    <th colspan="2" class="border p-2" data-group="group-lokasi-fraud">Lokasi Fraud</th>

                    <th rowspan="3" class="border p-2" data-column="Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud">Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud</th>
                    <th rowspan="3" class="border p-2" data-column="Pihak Yang Dirugikan">Pihak Yang Dirugikan</th>

                    <th colspan="3" class="border p-2" data-group="group-waktu">Waktu</th>

                    <th colspan="9" class="border p-2" data-group="group-jumlah-kerugian">Jumlah Kerugian</th>

                    <th colspan="2" class="border p-2" data-group="group-kelemahan-penyebab-fraud">Kelemahan Penyebab Fraud</th>
                    <th colspan="2" class="border p-2" data-group="group-tindakan-penanganan">Tindakan untuk Penanganan Fraud</th>

                    <th colspan="4" class="border p-2" data-group="group-tindakan-perbaikan">Tindakan Perbaikan untuk Pencegahan Fraud</th>

                    <th colspan="16" class="border p-2" data-group="group-pelaku-fraud">Pelaku Fraud</th>

                    <th rowspan="3" class="border p-2" data-column="Status Penanganan">Status Penanganan</th>
                </tr>
                <tr>
                    <th rowspan="2" class="border p-2" data-column="Jenis Fraud" data-group="group-jenis-fraud">Jenis Fraud</th>
                    <th rowspan="2" class="border p-2" data-column="Keterangan Jenis Fraud" data-group="group-jenis-fraud">Keterangan Jenis Fraud</th>

                    <th rowspan="2" class="border p-2" data-column="Lokasi Fraud" data-group="group-lokasi-fraud">Lokasi Fraud</th>
                    <th rowspan="2" class="border p-2" data-column="Keterangan Lokasi Fraud" data-group="group-lokasi-fraud">Keterangan Lokasi Fraud</th>

                    <th colspan="2" class="border p-2" data-group="group-waktu group-waktu-terjadi">Waktu Terjadi</th>
                    <th rowspan="2" class="border p-2" data-column="Fraud Diketahui" data-group="group-waktu">Fraud Diketahui</th>

                    <th colspan="3" class="border p-2" data-group="group-jumlah-kerugian group-ljk">LJK</th>
                    <th colspan="3" class="border p-2" data-group="group-jumlah-kerugian group-konsumen">Konsumen</th>
                    <th colspan="3" class="border p-2" data-group="group-jumlah-kerugian group-pihak-lain">Pihak Lain</th>

                    <th rowspan="2" class="border p-2" data-column="Kelemahan Penyebab Fraud" data-group="group-kelemahan-penyebab-fraud">Kelemahan Penyebab Fraud</th>
                    <th rowspan="2" class="border p-2" data-column="Keterangan Kelemahan" data-group="group-kelemahan-penyebab-fraud">Keterangan</th>

                    <th rowspan="2" class="border p-2" data-column="Tindakan untuk Penanganan Fraud" data-group="group-tindakan-penanganan">Tindakan untuk Penanganan Fraud</th>
                    <th rowspan="2" class="border p-2" data-column="Keterangan Penanganan" data-group="group-tindakan-penanganan">Keterangan</th>

                    <th rowspan="2" class="border p-2" data-column="Tindakan Perbaikan untuk Pencegahan Fraud" data-group="group-tindakan-perbaikan">Tindakan Perbaikan untuk Pencegahan Fraud</th>
                    <th rowspan="2" class="border p-2" data-column="Keterangan Perbaikan" data-group="group-tindakan-perbaikan">Keterangan</th>
                    <th rowspan="2" class="border p-2" data-column="Target Waktu Pelaksanaan" data-group="group-tindakan-perbaikan">Target Waktu Pelaksanaan</th>
                    <th rowspan="2" class="border p-2" data-column="Realisasi Pelaksanaan" data-group="group-tindakan-perbaikan">Realisasi Pelaksanaan</th>

                    <th rowspan="2" class="border p-2" data-column="Internal/Eksternal" data-group="group-pelaku-fraud">Internal/Eksternal</th>
                    <th colspan="8" class="border p-2" data-group="group-pelaku-fraud group-identitas-pelaku">Identitas Pelaku</th>
                    <th rowspan="2" class="border p-2" data-column="Status Pelaku" data-group="group-pelaku-fraud">Status Pelaku</th>
                    <th colspan="4" class="border p-2" data-group="group-pelaku-fraud group-jabatan-pelaku">Jabatan Pelaku</th>
                    <th rowspan="2" class="border p-2" data-column="Keterangan Pelaku" data-group="group-pelaku-fraud">Keterangan Pelaku</th>
                    <th rowspan="2" class="border p-2" data-column="Pengenaan Sanksi" data-group="group-pelaku-fraud">Pengenaan Sanksi</th>
                </tr>
                <tr>
                    <th class="border p-2" data-column="Waktu Terjadi Awal" data-group="group-waktu group-waktu-terjadi">Awal</th>
                    <th class="border p-2" data-column="Waktu Terjadi Akhir" data-group="group-waktu group-waktu-terjadi">Akhir</th>

                    <th class="border p-2" data-column="LJK Rill" data-group="group-jumlah-kerugian group-ljk">Riil (incurred)</th>
                    <th class="border p-2" data-column="LJK Potensial" data-group="group-jumlah-kerugian group-ljk">Potensial (Potential)</th>
                    <th class="border p-2" data-column="LJK Recovery" data-group="group-jumlah-kerugian group-ljk">Setelah Pengembalian (Recovery)</th>

                    <th class="border p-2" data-column="Konsumen Rill" data-group="group-jumlah-kerugian group-konsumen">Riil (incurred)</th>
                    <th class="border p-2" data-column="Konsumen Potensial" data-group="group-jumlah-kerugian group-konsumen">Potensial (Potential)</th>
                    <th class="border p-2" data-column="Konsumen Recovery" data-group="group-jumlah-kerugian group-konsumen">Setelah Pengembalian (Recovery)</th>

                    <th class="border p-2" data-column="Pihak Lain Rill" data-group="group-jumlah-kerugian group-pihak-lain">Riil (incurred)</th>
                    <th class="border p-2" data-column="Pihak Lain Potensial" data-group="group-jumlah-kerugian group-pihak-lain">Potensial (Potential)</th>
                    <th class="border p-2" data-column="Pihak Lain Recovery" data-group="group-jumlah-kerugian group-pihak-lain">Setelah Pengembalian (Recovery)</th>

                    <th class="border p-2" data-column="Nama Pelaku" data-group="group-pelaku-fraud group-identitas-pelaku">Nama</th>
                    <th class="border p-2" data-column="Jenis Identitas" data-group="group-pelaku-fraud group-identitas-pelaku">Jenis Identitas</th>
                    <th class="border p-2" data-column="Nomor Identitas" data-group="group-pelaku-fraud group-identitas-pelaku">Nomor Identitas</th>
                    <th class="border p-2" data-column="Jenis Kelamin" data-group="group-pelaku-fraud group-identitas-pelaku">Jenis Kelamin</th>
                    <th class="border p-2" data-column="Tempat Lahir" data-group="group-pelaku-fraud group-identitas-pelaku">Tempat Lahir</th>
                    <th class="border p-2" data-column="Tanggal Lahir" data-group="group-pelaku-fraud group-identitas-pelaku">Tanggal Lahir</th>
                    <th class="border p-2" data-column="Alamat Identitas" data-group="group-pelaku-fraud group-identitas-pelaku">Alamat Identitas</th>
                    <th class="border p-2" data-column="Alamat Domisili" data-group="group-pelaku-fraud group-identitas-pelaku">Alamat Domisili</th>
                    <th class="border p-2" data-column="Pada Saat Fraud Terjadi" data-group="group-pelaku-fraud group-jabatan-pelaku">Pada Saat Fraud Terjadi</th>
                    <th class="border p-2" data-column="Keterangan Jabatan Terjadi" data-group="group-pelaku-fraud group-jabatan-pelaku">Keterangan Jabatan</th>
                    <th class="border p-2" data-column="Pada Saat Fraud Diketahui" data-group="group-pelaku-fraud group-jabatan-pelaku">Pada Saat Fraud Diketahui</th>
                    <th class="border p-2" data-column="Keterangan Jabatan Diketahui" data-group="group-pelaku-fraud group-jabatan-pelaku">Keterangan Jabatan</th>
                </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($semesterData['kasus'] as $k)
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="border p-2" data-column="No">{{ $loop->iteration }}</td>
                            <td class="border p-2" data-column="Kode Komponen">{{ $k->kode_komponen }}</td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Kejadian Fraud Menurut Pelaku">
                                @foreach($k->kejadianFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="ID Kejadian Fraud">
                                @foreach($k->kejadianFraud as $i) {{ $i->pivot->kode_kejadian ?? '' }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Jenis Fraud">
                                @foreach($k->jenisFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Jenis Fraud">
                                @foreach($k->jenisFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Aktivitas Terkait Fraud">
                                {{ $formatRefLabel($k->aktivitasTerkait) }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Deskripsi Fraud / Modus Operandi">{{ $k->deskripsi_fraud }}</td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Lokasi Fraud">
                                @foreach($k->lokasiFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Lokasi Fraud">
                                @foreach($k->lokasiFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud">{{ $k->divisi_unit }}</td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Pihak Yang Dirugikan">
                                {{ $formatRefLabel($k->pihakDirugikan) }}
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Waktu Terjadi Awal">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_awal ? \Carbon\Carbon::parse($k->waktuFraud->waktu_awal)->format('Y-m-d') : '' }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Waktu Terjadi Akhir">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_akhir ? \Carbon\Carbon::parse($k->waktuFraud->waktu_akhir)->format('Y-m-d') : '' }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Fraud Diketahui">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_diketahui ? \Carbon\Carbon::parse($k->waktuFraud->waktu_diketahui)->format('Y-m-d') : '' }}
                            </td>

<td class="border p-2" data-column="LJK Rill">
    @if($k->kerugianFraud && ($k->kerugianFraud->ljk_rill ?? 0) > 0)
        {{ $formatCurrency($k->kerugianFraud->ljk_rill) }}
    @endif
</td>

<td class="border p-2" data-column="LJK Potensial">
    @if($k->kerugianFraud && ($k->kerugianFraud->ljk_potensial ?? 0) > 0)
        {{ $formatCurrency($k->kerugianFraud->ljk_potensial) }}
    @endif
</td>

<td class="border p-2" data-column="LJK Recovery">
    @php
        $ljkOutstanding = (($k->kerugianFraud?->ljk_rill ?? 0)
            + ($k->kerugianFraud?->ljk_potensial ?? 0)
            - ($k->kerugianFraud?->recoveries?->where('kategori', 'ljk')->sum('amount') ?? 0));
    @endphp

    @if($ljkOutstanding > 0)
        {{ $formatCurrency($ljkOutstanding) }}
    @endif
</td>

<td class="border p-2" data-column="Konsumen Rill">
    @if($k->kerugianFraud && ($k->kerugianFraud->konsumen_rill ?? 0) > 0)
        {{ $formatCurrency($k->kerugianFraud->konsumen_rill) }}
    @endif
</td>

<td class="border p-2" data-column="Konsumen Potensial">
    @if($k->kerugianFraud && ($k->kerugianFraud->konsumen_potensial ?? 0) > 0)
        {{ $formatCurrency($k->kerugianFraud->konsumen_potensial) }}
    @endif
</td>

<td class="border p-2" data-column="Konsumen Recovery">
    @php
        $konsumenOutstanding = (($k->kerugianFraud?->konsumen_rill ?? 0)
            + ($k->kerugianFraud?->konsumen_potensial ?? 0)
            - ($k->kerugianFraud?->recoveries?->where('kategori', 'konsumen')->sum('amount') ?? 0));
    @endphp

    @if($konsumenOutstanding > 0)
        {{ $formatCurrency($konsumenOutstanding) }}
    @endif
</td>

<td class="border p-2" data-column="Pihak Lain Rill">
    @if($k->kerugianFraud && ($k->kerugianFraud->pihak_lain_rill ?? 0) > 0)
        {{ $formatCurrency($k->kerugianFraud->pihak_lain_rill) }}
    @endif
</td>

<td class="border p-2" data-column="Pihak Lain Potensial">
    @if($k->kerugianFraud && ($k->kerugianFraud->pihak_lain_potensial ?? 0) > 0)
        {{ $formatCurrency($k->kerugianFraud->pihak_lain_potensial) }}
    @endif
</td>

<td class="border p-2" data-column="Pihak Lain Recovery">
    @php
        $pihakLainOutstanding = (($k->kerugianFraud?->pihak_lain_rill ?? 0)
            + ($k->kerugianFraud?->pihak_lain_potensial ?? 0)
            - ($k->kerugianFraud?->recoveries?->where('kategori', 'pihak_lain')->sum('amount') ?? 0));
    @endphp

    @if($pihakLainOutstanding > 0)
        {{ $formatCurrency($pihakLainOutstanding) }}
    @endif
</td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Kelemahan Penyebab Fraud">
                                @foreach($k->kelemahanFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Kelemahan">
                                @foreach($k->kelemahanFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Tindakan untuk Penanganan Fraud">
                                @foreach($k->penangananFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Penanganan">
                                @foreach($k->penangananFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Tindakan Perbaikan untuk Pencegahan Fraud">
                                @foreach($k->pencegahanFraud as $i) {{ $i->refPencegahan ? ($i->refPencegahan->kode ? $i->refPencegahan->kode . ' (' . $i->refPencegahan->nama . ')' : $i->refPencegahan->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Perbaikan">
                                @foreach($k->pencegahanFraud as $i) {{ $i->keterangan }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Target Waktu Pelaksanaan">
                                @foreach($k->pencegahanFraud as $i) {{ $i->target_waktu ? \Carbon\Carbon::parse($i->target_waktu)->format('Y-m-d') : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Realisasi Pelaksanaan">
                                @foreach($k->pencegahanFraud as $i) {{ $i->realisasi ? \Carbon\Carbon::parse($i->realisasi)->format('Y-m-d') : '' }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Internal/Eksternal">
                                @foreach($k->pelakuFrauds as $p) {{ $p->kategori_label }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Nama Pelaku">
                                @foreach($k->pelakuFrauds as $p) {{ $p->nama }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Jenis Identitas">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jenisIdentitas ? ($p->jenisIdentitas->kode ? $p->jenisIdentitas->kode . ' (' . $p->jenisIdentitas->nama . ')' : $p->jenisIdentitas->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Nomor Identitas">
                                @foreach($k->pelakuFrauds as $p) {{ $p->nomor_identitas }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Jenis Kelamin">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jenis_kelamin_label }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Tempat Lahir">
                                @foreach($k->pelakuFrauds as $p) {{ $p->tempat_lahir }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Tanggal Lahir">
                                @foreach($k->pelakuFrauds as $p) {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('Y-m-d') : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Alamat Identitas">
                                @foreach($k->pelakuFrauds as $p) {{ $p->alamat_identitas }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Alamat Domisili">
                                @foreach($k->pelakuFrauds as $p) {{ $p->alamat_domisili }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Status Pelaku">
                                @foreach($k->pelakuFrauds as $p) {{ $p->statusPelaku ? ($p->statusPelaku->kode ? $p->statusPelaku->kode . ' (' . $p->statusPelaku->nama . ')' : $p->statusPelaku->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Pada Saat Fraud Terjadi">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jabatanKejadian ? ($p->jabatanKejadian->kode ? $p->jabatanKejadian->kode . ' (' . $p->jabatanKejadian->nama . ')' : $p->jabatanKejadian->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Jabatan Terjadi">
                                @foreach($k->pelakuFrauds as $p) {{ $p->ket_jabatan_kejadian }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Pada Saat Fraud Diketahui">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jabatanDiketahui ? ($p->jabatanDiketahui->kode ? $p->jabatanDiketahui->kode . ' (' . $p->jabatanDiketahui->nama . ')' : $p->jabatanDiketahui->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Jabatan Diketahui">
                                @foreach($k->pelakuFrauds as $p) {{ $p->ket_jabatan_diketahui }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Pelaku">
                                @foreach($k->pelakuFrauds as $p) {{ $p->keterangan }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Pengenaan Sanksi">
                                @forelse($k->pelakuFrauds as $p)
                                    {{ $p->sanksi ?? '' }}<br>
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td class="border p-2 text-center" data-column="Status Penanganan">
                                <span class="inline-flex whitespace-nowrap px-2 py-1 text-white rounded text-xs bg-slate-500">
                                    {{ $statusLabels[$k->status_penanganan] ?? $k->status_penanganan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="50" class="border p-4 text-center">Tidak ada data laporan semester.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- LAPORAN SIGNIFIKAN TABLE -->
        <div id="signifikanTableContainer" class="bg-white rounded-lg shadow overflow-x-auto mb-8 hidden">
            <table class="min-w-[3500px] text-xs border-collapse" id="signifikanTable">
                <colgroup id="signifikanColGroup"></colgroup>
                <thead class="bg-[#FF0000] text-white">
                    <tr>
                    <th rowspan="3" class="border p-2" data-column="No">No</th>
                    <th rowspan="3" class="border p-2" data-column="Kode Komponen">Kode Komponen</th>
                    <th rowspan="3" class="border p-2" data-column="Kejadian Fraud Menurut Pelaku">Kejadian Fraud Menurut Pelaku</th>
                    <th rowspan="3" class="border p-2" data-column="ID Kejadian Fraud">ID Kejadian Fraud</th>

                    <th colspan="2" class="border p-2" data-group="group-jenis-fraud">Jenis Fraud</th>

                    <th rowspan="3" class="border p-2" data-column="Aktivitas Terkait Fraud">Aktivitas Terkait Fraud</th>
                    <th rowspan="3" class="border p-2" data-column="Deskripsi Fraud / Modus Operandi">Deskripsi Fraud / Modus Operandi</th>

                    <th colspan="2" class="border p-2" data-group="group-lokasi-fraud">Lokasi Fraud</th>

                    <th rowspan="3" class="border p-2" data-column="Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud">Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud</th>
                    <th rowspan="3" class="border p-2" data-column="Pihak Yang Dirugikan">Pihak Yang Dirugikan</th>
                    <th rowspan="3" class="border p-2" data-column="Jumlah Kerugian Potensial">Jumlah Kerugian Potensial</th>
                    <th rowspan="3" class="border p-2" data-column="Tindak Lanjut LJK">Tindak Lanjut LJK</th>

                    <th colspan="3" class="border p-2" data-group="group-waktu">Waktu</th>
                    <th colspan="16" class="border p-2" data-group="group-pelaku-fraud">Pelaku Fraud</th>
                    <th rowspan="3" class="border p-2" data-column="Status Penanganan">Status Penanganan</th>
                </tr>
                <tr>
                    <th rowspan="2" class="border p-2" data-column="Jenis Fraud" data-group="group-jenis-fraud">Jenis Fraud</th>
                    <th rowspan="2" class="border p-2" data-column="Keterangan Jenis Fraud" data-group="group-jenis-fraud">Keterangan Jenis Fraud</th>

                    <th rowspan="2" class="border p-2" data-column="Lokasi Fraud" data-group="group-lokasi-fraud">Lokasi Fraud</th>
                    <th rowspan="2" class="border p-2" data-column="Keterangan Lokasi Fraud" data-group="group-lokasi-fraud">Keterangan Lokasi Fraud</th>

                    <th colspan="2" class="border p-2" data-group="group-waktu group-waktu-terjadi">Waktu Terjadi</th>
                    <th rowspan="2" class="border p-2" data-column="Fraud Diketahui" data-group="group-waktu">Fraud Diketahui</th>

                    <th rowspan="2" class="border p-2" data-column="Internal/Eksternal" data-group="group-pelaku-fraud">Internal/Eksternal</th>
                    <th colspan="8" class="border p-2" data-group="group-pelaku-fraud group-identitas-pelaku">Identitas Pelaku</th>
                    <th rowspan="2" class="border p-2" data-column="Status Pelaku" data-group="group-pelaku-fraud">Status Pelaku</th>
                    <th colspan="4" class="border p-2" data-group="group-pelaku-fraud group-jabatan-pelaku">Jabatan Pelaku</th>
                    <th rowspan="2" class="border p-2" data-column="Keterangan Pelaku" data-group="group-pelaku-fraud">Keterangan Pelaku</th>
                    <th rowspan="2" class="border p-2" data-column="Pengenaan Sanksi" data-group="group-pelaku-fraud">Pengenaan Sanksi</th>
                </tr>
                <tr>
                    <th class="border p-2" data-column="Waktu Terjadi Awal" data-group="group-waktu group-waktu-terjadi">Awal</th>
                    <th class="border p-2" data-column="Waktu Terjadi Akhir" data-group="group-waktu group-waktu-terjadi">Akhir</th>

                    <th class="border p-2" data-column="Nama Pelaku" data-group="group-pelaku-fraud group-identitas-pelaku">Nama</th>
                    <th class="border p-2" data-column="Jenis Identitas" data-group="group-pelaku-fraud group-identitas-pelaku">Jenis Identitas</th>
                    <th class="border p-2" data-column="Nomor Identitas" data-group="group-pelaku-fraud group-identitas-pelaku">Nomor Identitas</th>
                    <th class="border p-2" data-column="Jenis Kelamin" data-group="group-pelaku-fraud group-identitas-pelaku">Jenis Kelamin</th>
                    <th class="border p-2" data-column="Tempat Lahir" data-group="group-pelaku-fraud group-identitas-pelaku">Tempat Lahir</th>
                    <th class="border p-2" data-column="Tanggal Lahir" data-group="group-pelaku-fraud group-identitas-pelaku">Tanggal Lahir</th>
                    <th class="border p-2" data-column="Alamat Identitas" data-group="group-pelaku-fraud group-identitas-pelaku">Alamat Identitas</th>
                    <th class="border p-2" data-column="Alamat Domisili" data-group="group-pelaku-fraud group-identitas-pelaku">Alamat Domisili</th>
                    <th class="border p-2" data-column="Pada Saat Fraud Terjadi" data-group="group-pelaku-fraud group-jabatan-pelaku">Pada Saat Fraud Terjadi</th>
                    <th class="border p-2" data-column="Keterangan Jabatan Terjadi" data-group="group-pelaku-fraud group-jabatan-pelaku">Keterangan</th>
                    <th class="border p-2" data-column="Pada Saat Fraud Diketahui" data-group="group-pelaku-fraud group-jabatan-pelaku">Pada Saat Fraud Diketahui</th>
                    <th class="border p-2" data-column="Keterangan Jabatan Diketahui" data-group="group-pelaku-fraud group-jabatan-pelaku">Keterangan</th>
                </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($signifikanData['kasus'] as $k)
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="border p-2" data-column="No">{{ $loop->iteration }}</td>
                            <td class="border p-2" data-column="Kode Komponen">{{ $k->kode_komponen }}</td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Kejadian Fraud Menurut Pelaku">
                                @foreach($k->kejadianFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="ID Kejadian Fraud">
                                @foreach($k->kejadianFraud as $i) {{ $i->pivot->kode_kejadian ?? '' }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Jenis Fraud">
                                @foreach($k->jenisFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Jenis Fraud">
                                @foreach($k->jenisFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Aktivitas Terkait Fraud">
                                {{ $formatRefLabel($k->aktivitasTerkait) }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Deskripsi Fraud / Modus Operandi">{{ $k->deskripsi_fraud }}</td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Lokasi Fraud">
                                @foreach($k->lokasiFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Lokasi Fraud">
                                @foreach($k->lokasiFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud">{{ $k->divisi_unit }}</td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Pihak Yang Dirugikan">
                                {{ $formatRefLabel($k->pihakDirugikan) }}
                            </td>
                            <td class="border p-2" data-column="Jumlah Kerugian Potensial">{{ $k->kerugianFraud ? $formatCurrency($k->getTotalKerugianPotensial()) : '' }}</td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Tindak Lanjut LJK">{{ $k->tindak_lanjut_ljk ?? '-' }}</td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Waktu Terjadi Awal">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_awal ? \Carbon\Carbon::parse($k->waktuFraud->waktu_awal)->format('Y-m-d') : '' }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Waktu Terjadi Akhir">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_akhir ? \Carbon\Carbon::parse($k->waktuFraud->waktu_akhir)->format('Y-m-d') : '' }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Fraud Diketahui">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_diketahui ? \Carbon\Carbon::parse($k->waktuFraud->waktu_diketahui)->format('Y-m-d') : '' }}
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Internal/Eksternal">
                                @foreach($k->pelakuFrauds as $p) {{ $p->kategori_label }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Nama Pelaku">
                                @foreach($k->pelakuFrauds as $p) {{ $p->nama }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Jenis Identitas">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jenisIdentitas ? ($p->jenisIdentitas->kode ? $p->jenisIdentitas->kode . ' (' . $p->jenisIdentitas->nama . ')' : $p->jenisIdentitas->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Nomor Identitas">
                                @foreach($k->pelakuFrauds as $p) {{ $p->nomor_identitas }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Jenis Kelamin">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jenis_kelamin_label }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Tempat Lahir">
                                @foreach($k->pelakuFrauds as $p) {{ $p->tempat_lahir }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Tanggal Lahir">
                                @foreach($k->pelakuFrauds as $p) {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('Y-m-d') : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Alamat Identitas">
                                @foreach($k->pelakuFrauds as $p) {{ $p->alamat_identitas }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Alamat Domisili">
                                @foreach($k->pelakuFrauds as $p) {{ $p->alamat_domisili }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Status Pelaku">
                                @foreach($k->pelakuFrauds as $p) {{ $p->statusPelaku ? ($p->statusPelaku->kode ? $p->statusPelaku->kode . ' (' . $p->statusPelaku->nama . ')' : $p->statusPelaku->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Pada Saat Fraud Terjadi">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jabatanKejadian ? ($p->jabatanKejadian->kode ? $p->jabatanKejadian->kode . ' (' . $p->jabatanKejadian->nama . ')' : $p->jabatanKejadian->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Jabatan Terjadi">
                                @foreach($k->pelakuFrauds as $p) {{ $p->ket_jabatan_kejadian }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Pada Saat Fraud Diketahui">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jabatanDiketahui ? ($p->jabatanDiketahui->kode ? $p->jabatanDiketahui->kode . ' (' . $p->jabatanDiketahui->nama . ')' : $p->jabatanDiketahui->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Jabatan Diketahui">
                                @foreach($k->pelakuFrauds as $p) {{ $p->ket_jabatan_diketahui }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Pelaku">
                                @foreach($k->pelakuFrauds as $p) {{ $p->keterangan }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Pengenaan Sanksi">
                                @forelse($k->pelakuFrauds as $p)
                                    {{ $p->sanksi ?? '' }}<br>
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td class="border p-2 text-center" data-column="Status Penanganan">
                                <span class="inline-flex whitespace-nowrap px-2 py-1 text-white rounded text-xs bg-slate-500">
                                    {{ $statusLabels[$k->status_penanganan] ?? $k->status_penanganan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="38" class="border p-4 text-center">Tidak ada data laporan signifikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- LAPORAN NON-SIGNIFIKAN TABLE -->
        <div id="nonSignifikanTableContainer" class="bg-white rounded-lg shadow overflow-x-auto mb-8 hidden">
            <table class="min-w-[3500px] text-xs border-collapse" id="nonSignifikanTable">
                <colgroup id="nonSignifikanColGroup"></colgroup>
                <thead class="bg-[#FF0000] text-white">
                    <tr>
                    <th rowspan="3" class="border p-2" data-column="No">No</th>
                    <th rowspan="3" class="border p-2" data-column="Kode Komponen">Kode Komponen</th>
                    <th rowspan="3" class="border p-2" data-column="Kejadian Fraud Menurut Pelaku">Kejadian Fraud Menurut Pelaku</th>
                    <th rowspan="3" class="border p-2" data-column="ID Kejadian Fraud">ID Kejadian Fraud</th>

                    <th colspan="2" class="border p-2" data-group="group-jenis-fraud">Jenis Fraud</th>

                    <th rowspan="3" class="border p-2" data-column="Aktivitas Terkait Fraud">Aktivitas Terkait Fraud</th>
                    <th rowspan="3" class="border p-2" data-column="Deskripsi Fraud / Modus Operandi">Deskripsi Fraud / Modus Operandi</th>

                    <th colspan="2" class="border p-2" data-group="group-lokasi-fraud">Lokasi Fraud</th>

                    <th rowspan="3" class="border p-2" data-column="Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud">Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud</th>
                    <th rowspan="3" class="border p-2" data-column="Pihak Yang Dirugikan">Pihak Yang Dirugikan</th>
                    <th rowspan="3" class="border p-2" data-column="Jumlah Kerugian Potensial">Jumlah Kerugian Potensial</th>
                    <th rowspan="3" class="border p-2" data-column="Tindak Lanjut LJK">Tindak Lanjut LJK</th>

                    <th colspan="3" class="border p-2" data-group="group-waktu">Waktu</th>
                    <th colspan="16" class="border p-2" data-group="group-pelaku-fraud">Pelaku Fraud</th>
                    <th rowspan="3" class="border p-2" data-column="Status Penanganan">Status Penanganan</th>
                </tr>
                <tr>
                    <th rowspan="2" class="border p-2" data-column="Jenis Fraud" data-group="group-jenis-fraud">Jenis Fraud</th>
                    <th rowspan="2" class="border p-2" data-column="Keterangan Jenis Fraud" data-group="group-jenis-fraud">Keterangan Jenis Fraud</th>

                    <th rowspan="2" class="border p-2" data-column="Lokasi Fraud" data-group="group-lokasi-fraud">Lokasi Fraud</th>
                    <th rowspan="2" class="border p-2" data-column="Keterangan Lokasi Fraud" data-group="group-lokasi-fraud">Keterangan Lokasi Fraud</th>

                    <th colspan="2" class="border p-2" data-group="group-waktu group-waktu-terjadi">Waktu Terjadi</th>
                    <th rowspan="2" class="border p-2" data-column="Fraud Diketahui" data-group="group-waktu">Fraud Diketahui</th>

                    <th rowspan="2" class="border p-2" data-column="Internal/Eksternal" data-group="group-pelaku-fraud">Internal/Eksternal</th>
                    <th colspan="8" class="border p-2" data-group="group-pelaku-fraud group-identitas-pelaku">Identitas Pelaku</th>
                    <th rowspan="2" class="border p-2" data-column="Status Pelaku" data-group="group-pelaku-fraud">Status Pelaku</th>
                    <th colspan="4" class="border p-2" data-group="group-pelaku-fraud group-jabatan-pelaku">Jabatan Pelaku</th>
                    <th rowspan="2" class="border p-2" data-column="Keterangan Pelaku" data-group="group-pelaku-fraud">Keterangan Pelaku</th>
                    <th rowspan="2" class="border p-2" data-column="Pengenaan Sanksi" data-group="group-pelaku-fraud">Pengenaan Sanksi</th>
                </tr>
                <tr>
                    <th class="border p-2" data-column="Waktu Terjadi Awal" data-group="group-waktu group-waktu-terjadi">Awal</th>
                    <th class="border p-2" data-column="Waktu Terjadi Akhir" data-group="group-waktu group-waktu-terjadi">Akhir</th>

                    <th class="border p-2" data-column="Nama Pelaku" data-group="group-pelaku-fraud group-identitas-pelaku">Nama</th>
                    <th class="border p-2" data-column="Jenis Identitas" data-group="group-pelaku-fraud group-identitas-pelaku">Jenis Identitas</th>
                    <th class="border p-2" data-column="Nomor Identitas" data-group="group-pelaku-fraud group-identitas-pelaku">Nomor Identitas</th>
                    <th class="border p-2" data-column="Jenis Kelamin" data-group="group-pelaku-fraud group-identitas-pelaku">Jenis Kelamin</th>
                    <th class="border p-2" data-column="Tempat Lahir" data-group="group-pelaku-fraud group-identitas-pelaku">Tempat Lahir</th>
                    <th class="border p-2" data-column="Tanggal Lahir" data-group="group-pelaku-fraud group-identitas-pelaku">Tanggal Lahir</th>
                    <th class="border p-2" data-column="Alamat Identitas" data-group="group-pelaku-fraud group-identitas-pelaku">Alamat Identitas</th>
                    <th class="border p-2" data-column="Alamat Domisili" data-group="group-pelaku-fraud group-identitas-pelaku">Alamat Domisili</th>
                    <th class="border p-2" data-column="Pada Saat Fraud Terjadi" data-group="group-pelaku-fraud group-jabatan-pelaku">Pada Saat Fraud Terjadi</th>
                    <th class="border p-2" data-column="Keterangan Jabatan Terjadi" data-group="group-pelaku-fraud group-jabatan-pelaku">Keterangan</th>
                    <th class="border p-2" data-column="Pada Saat Fraud Diketahui" data-group="group-pelaku-fraud group-jabatan-pelaku">Pada Saat Fraud Diketahui</th>
                    <th class="border p-2" data-column="Keterangan Jabatan Diketahui" data-group="group-pelaku-fraud group-jabatan-pelaku">Keterangan</th>
                </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($nonSignifikanData['kasus'] as $k)
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="border p-2" data-column="No">{{ $loop->iteration }}</td>
                            <td class="border p-2" data-column="Kode Komponen">{{ $k->kode_komponen }}</td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Kejadian Fraud Menurut Pelaku">
                                @foreach($k->kejadianFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="ID Kejadian Fraud">
                                @foreach($k->kejadianFraud as $i) {{ $i->pivot->kode_kejadian ?? '' }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Jenis Fraud">
                                @foreach($k->jenisFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Jenis Fraud">
                                @foreach($k->jenisFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Aktivitas Terkait Fraud">
                                {{ $formatRefLabel($k->aktivitasTerkait) }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Deskripsi Fraud / Modus Operandi">{{ $k->deskripsi_fraud }}</td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Lokasi Fraud">
                                @foreach($k->lokasiFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Lokasi Fraud">
                                @foreach($k->lokasiFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud">{{ $k->divisi_unit }}</td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Pihak Yang Dirugikan">
                                {{ $formatRefLabel($k->pihakDirugikan) }}
                            </td>
                            <td class="border p-2" data-column="Jumlah Kerugian Potensial">{{ $k->kerugianFraud ? $formatCurrency($k->getTotalKerugianPotensial()) : '' }}</td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Tindak Lanjut LJK">{{ $k->tindak_lanjut_ljk ?? '' }}</td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Waktu Terjadi Awal">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_awal ? \Carbon\Carbon::parse($k->waktuFraud->waktu_awal)->format('Y-m-d') : '' }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Waktu Terjadi Akhir">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_akhir ? \Carbon\Carbon::parse($k->waktuFraud->waktu_akhir)->format('Y-m-d') : '' }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Fraud Diketahui">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_diketahui ? \Carbon\Carbon::parse($k->waktuFraud->waktu_diketahui)->format('Y-m-d') : '' }}
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Internal/Eksternal">
                                @foreach($k->pelakuFrauds as $p) {{ $p->kategori_label }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Nama Pelaku">
                                @foreach($k->pelakuFrauds as $p) {{ $p->nama }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Jenis Identitas">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jenisIdentitas ? ($p->jenisIdentitas->kode ? $p->jenisIdentitas->kode . ' (' . $p->jenisIdentitas->nama . ')' : $p->jenisIdentitas->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Nomor Identitas">
                                @foreach($k->pelakuFrauds as $p) {{ $p->nomor_identitas }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Jenis Kelamin">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jenis_kelamin_label }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Tempat Lahir">
                                @foreach($k->pelakuFrauds as $p) {{ $p->tempat_lahir }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Tanggal Lahir">
                                @foreach($k->pelakuFrauds as $p) {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('Y-m-d') : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Alamat Identitas">
                                @foreach($k->pelakuFrauds as $p) {{ $p->alamat_identitas }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Alamat Domisili">
                                @foreach($k->pelakuFrauds as $p) {{ $p->alamat_domisili }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Status Pelaku">
                                @foreach($k->pelakuFrauds as $p) {{ $p->statusPelaku ? ($p->statusPelaku->kode ? $p->statusPelaku->kode . ' (' . $p->statusPelaku->nama . ')' : $p->statusPelaku->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Pada Saat Fraud Terjadi">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jabatanKejadian ? ($p->jabatanKejadian->kode ? $p->jabatanKejadian->kode . ' (' . $p->jabatanKejadian->nama . ')' : $p->jabatanKejadian->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Jabatan Terjadi">
                                @foreach($k->pelakuFrauds as $p) {{ $p->ket_jabatan_kejadian }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Pada Saat Fraud Diketahui">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jabatanDiketahui ? ($p->jabatanDiketahui->kode ? $p->jabatanDiketahui->kode . ' (' . $p->jabatanDiketahui->nama . ')' : $p->jabatanDiketahui->nama) : '' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Jabatan Diketahui">
                                @foreach($k->pelakuFrauds as $p) {{ $p->ket_jabatan_diketahui }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Keterangan Pelaku">
                                @foreach($k->pelakuFrauds as $p) {{ $p->keterangan }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis" data-column="Pengenaan Sanksi">
                                @forelse($k->pelakuFrauds as $p)
                                    {{ $p->sanksi ?? '' }}<br>
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td class="border p-2 text-center" data-column="Status Penanganan">
                                <span class="inline-flex whitespace-nowrap px-2 py-1 text-white rounded text-xs bg-slate-500">
                                    {{ $statusLabels[$k->status_penanganan] ?? $k->status_penanganan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="38" class="border p-4 text-center">Tidak ada data laporan non-signifikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<style>
    #semesterTable th,
    #signifikanTable th,
    #nonSignifikanTable th {
        text-align: center;
        vertical-align: middle;
    }

    #semesterTable td,
    #signifikanTable td,
    #nonSignifikanTable td {
        vertical-align: top;
    }
</style>

<script>
// Column definitions for both report types
const semesterColumns = [
    'No',
    'Kode Komponen',
    'Kejadian Fraud Menurut Pelaku',
    'ID Kejadian Fraud',
    'Jenis Fraud',
    'Keterangan Jenis Fraud',
    'Aktivitas Terkait Fraud',
    'Deskripsi Fraud / Modus Operandi',
    'Lokasi Fraud',
    'Keterangan Lokasi Fraud',
    'Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud',
    'Pihak Yang Dirugikan',
    'Waktu Terjadi Awal',
    'Waktu Terjadi Akhir',
    'Fraud Diketahui',
    'LJK Rill',
    'LJK Potensial',
    'LJK Recovery',
    'Konsumen Rill',
    'Konsumen Potensial',
    'Konsumen Recovery',
    'Pihak Lain Rill',
    'Pihak Lain Potensial',
    'Pihak Lain Recovery',
    'Kelemahan Penyebab Fraud',
    'Keterangan Kelemahan',
    'Tindakan untuk Penanganan Fraud',
    'Keterangan Penanganan',
    'Tindakan Perbaikan untuk Pencegahan Fraud',
    'Keterangan Perbaikan',
    'Target Waktu Pelaksanaan',
    'Realisasi Pelaksanaan',
    'Internal/Eksternal',
    'Nama Pelaku',
    'Jenis Identitas',
    'Nomor Identitas',
    'Jenis Kelamin',
    'Tempat Lahir',
    'Tanggal Lahir',
    'Alamat Identitas',
    'Alamat Domisili',
    'Status Pelaku',
    'Pada Saat Fraud Terjadi',
    'Keterangan Jabatan Terjadi',
    'Pada Saat Fraud Diketahui',
    'Keterangan Jabatan Diketahui',
    'Keterangan Pelaku',
    'Pengenaan Sanksi',
    'Status Penanganan',
];

const signifikanColumns = [
    'No',
    'Kode Komponen',
    'Kejadian Fraud Menurut Pelaku',
    'ID Kejadian Fraud',
    'Jenis Fraud',
    'Keterangan Jenis Fraud',
    'Aktivitas Terkait Fraud',
    'Deskripsi Fraud / Modus Operandi',
    'Lokasi Fraud',
    'Keterangan Lokasi Fraud',
    'Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud',
    'Pihak Yang Dirugikan',
    'Jumlah Kerugian Potensial',
    'Tindak Lanjut LJK',
    'Waktu Terjadi Awal',
    'Waktu Terjadi Akhir',
    'Fraud Diketahui',
    'Internal/Eksternal',
    'Nama Pelaku',
    'Jenis Identitas',
    'Nomor Identitas',
    'Jenis Kelamin',
    'Tempat Lahir',
    'Tanggal Lahir',
    'Alamat Identitas',
    'Alamat Domisili',
    'Status Pelaku',
    'Pada Saat Fraud Terjadi',
    'Keterangan Jabatan Terjadi',
    'Pada Saat Fraud Diketahui',
    'Keterangan Jabatan Diketahui',
    'Keterangan Pelaku',
    'Pengenaan Sanksi',
    'Status Penanganan',
];

const nonSignifikanColumns = [...signifikanColumns];

function initializeColumnSelectors() {
    buildTableColGroup('semesterTable', semesterColumns.length);
    buildTableColGroup('signifikanTable', signifikanColumns.length);

    // Semester columns
    const semesterSelector = document.getElementById('semesterColumnsSelector');
    semesterColumns.forEach(col => {
        const label = document.createElement('label');
        label.className = 'flex items-center gap-2 cursor-pointer';
        label.innerHTML = `
            <input type="checkbox" class="semester-column-checkbox" value="${col}" checked 
                onchange="toggleColumn('semesterTable', this)">
            <span class="text-sm">${col}</span>
        `;
        semesterSelector.appendChild(label);
    });

    // Signifikan columns
    const signifikanSelector = document.getElementById('signifikanColumnsSelector');
    signifikanColumns.forEach(col => {
        const label = document.createElement('label');
        label.className = 'flex items-center gap-2 cursor-pointer';
        label.innerHTML = `
            <input type="checkbox" class="signifikan-column-checkbox" value="${col}" checked 
                onchange="toggleColumn('signifikanTable', this)">
            <span class="text-sm">${col}</span>
        `;
        signifikanSelector.appendChild(label);
    });

    // Non-signifikan columns
    const nonSignifikanSelector = document.getElementById('nonSignifikanColumnsSelector');
    nonSignifikanColumns.forEach(col => {
        const label = document.createElement('label');
        label.className = 'flex items-center gap-2 cursor-pointer';
        label.innerHTML = `
            <input type="checkbox" class="nonSignifikan-column-checkbox" value="${col}" checked 
                onchange="toggleColumn('nonSignifikanTable', this)">
            <span class="text-sm">${col}</span>
        `;
        nonSignifikanSelector.appendChild(label);
    });

    loadSelectedColumnsFromStorage();
}

function buildTableColGroup(tableId, columnCount) {
    const table = document.getElementById(tableId);
    const colGroup = table.querySelector('colgroup');
    if (!colGroup) return;
    colGroup.innerHTML = '';

    for (let i = 0; i < columnCount; i++) {
        const col = document.createElement('col');
        col.dataset.columnIndex = i;
        col.style.display = '';
        col.style.visibility = '';
        col.style.width = '';
        col.style.minWidth = '';
        colGroup.appendChild(col);
    }
}

function loadSelectedColumnsFromStorage() {
    const semesterSelected = localStorage.getItem('semesterSelectedColumns');
    const signifikanSelected = localStorage.getItem('signifikanSelectedColumns');

    if (semesterSelected) {
        const selected = JSON.parse(semesterSelected);
        document.querySelectorAll('.semester-column-checkbox').forEach(cb => {
            cb.checked = selected.includes(cb.value);
            toggleColumn('semesterTable', cb);
        });
    }

    if (signifikanSelected) {
        const selected = JSON.parse(signifikanSelected);
        document.querySelectorAll('.signifikan-column-checkbox').forEach(cb => {
            cb.checked = selected.includes(cb.value);
            toggleColumn('signifikanTable', cb);
        });
    }

    const nonSignifikanSelected = localStorage.getItem('nonSignifikanSelectedColumns');
    if (nonSignifikanSelected) {
        const selected = JSON.parse(nonSignifikanSelected);
        document.querySelectorAll('.nonSignifikan-column-checkbox').forEach(cb => {
            cb.checked = selected.includes(cb.value);
            toggleColumn('nonSignifikanTable', cb);
        });
    }
}

function toggleColumn(tableId, checkbox) {
    const table = document.getElementById(tableId);
    const colName = checkbox.value;
    const isChecked = checkbox.checked;
    const needColVisibilityUpdate = toggleColumnCells(table, colName, isChecked);

    if (needColVisibilityUpdate) {
        updateAllColsVisibility(table);
    }

    updateGroupHeaders(table);

    // Save to localStorage
    saveSelectedColumns(tableId);
}

function toggleColumnCells(table, colName, isVisible) {
    const escapedName = colName.replace(/"/g, '\\"');
    const cells = table.querySelectorAll(`[data-column="${escapedName}"]`);
    if (!cells.length) {
        return false;
    }

    cells.forEach(cell => {
        cell.style.display = isVisible ? '' : 'none';
    });

    return true;
}

function updateGroupHeaders(table) {
    const visibleLeafHeaders = Array.from(table.querySelectorAll('thead th[data-column]')).filter(th => th.style.display !== 'none');
    const groupHeaders = Array.from(table.querySelectorAll('thead th[data-group]:not([data-column])'));

    groupHeaders.forEach(groupCell => {
        const groupList = (groupCell.dataset.group || '').split(' ').filter(Boolean);
        if (!groupList.length) {
            return;
        }

        const visibleChildren = visibleLeafHeaders.filter(leaf => {
            const leafGroups = (leaf.dataset.group || '').split(' ').filter(Boolean);
            return groupList.every(group => leafGroups.includes(group));
        });

        if (!visibleChildren.length) {
            groupCell.style.display = 'none';
            groupCell.colSpan = 0;
        } else {
            groupCell.style.display = '';
            groupCell.colSpan = visibleChildren.length;
        }
    });
}

function updateAllColsVisibility(table) {
    const leafHeaders = Array.from(table.querySelectorAll('thead th[data-column]'));
    const cols = table.querySelectorAll('colgroup col');

    leafHeaders.forEach((header, index) => {
        const visible = header.style.display !== 'none';
        const col = cols[index];
        if (!col) {
            return;
        }

        if (visible) {
            col.style.display = '';
            col.style.visibility = '';
            col.style.width = '';
            col.style.minWidth = '';
        } else {
            col.style.display = 'none';
            col.style.visibility = 'collapse';
            col.style.width = '0px';
            col.style.minWidth = '0px';
        }
    });
}


function saveSelectedColumns(tableId) {
    const tableType = tableId === 'signifikanTable' ? 'signifikan' : tableId === 'nonSignifikanTable' ? 'nonSignifikan' : 'semester';
    const selector = tableType === 'signifikan'
        ? '.signifikan-column-checkbox'
        : tableType === 'nonSignifikan'
            ? '.nonSignifikan-column-checkbox'
            : '.semester-column-checkbox';

    const selected = Array.from(document.querySelectorAll(selector))
        .filter(cb => cb.checked)
        .map(cb => cb.value);
    
    const key = tableType === 'signifikan'
        ? 'signifikanSelectedColumns'
        : tableType === 'nonSignifikan'
            ? 'nonSignifikanSelectedColumns'
            : 'semesterSelectedColumns';
    localStorage.setItem(key, JSON.stringify(selected));
}

function selectAllColumns() {
    const reportType = reportTypeSelector.value;
    const selector = reportType === 'signifikan'
        ? '.signifikan-column-checkbox'
        : reportType === 'non-signifikan'
            ? '.nonSignifikan-column-checkbox'
            : '.semester-column-checkbox';
    const tableId = reportType === 'signifikan'
        ? 'signifikanTable'
        : reportType === 'non-signifikan'
            ? 'nonSignifikanTable'
            : 'semesterTable';
    
    document.querySelectorAll(selector).forEach(cb => {
        cb.checked = true;
        toggleColumn(tableId, cb);
    });
}

function deselectAllColumns() {
    const reportType = reportTypeSelector.value;
    const selector = reportType === 'signifikan'
        ? '.signifikan-column-checkbox'
        : reportType === 'non-signifikan'
            ? '.nonSignifikan-column-checkbox'
            : '.semester-column-checkbox';
    const tableId = reportType === 'signifikan'
        ? 'signifikanTable'
        : reportType === 'non-signifikan'
            ? 'nonSignifikanTable'
            : 'semesterTable';
    
    document.querySelectorAll(selector).forEach(cb => {
        cb.checked = false;
        toggleColumn(tableId, cb);
    });
}

function getSelectedColumns() {
    const reportType = reportTypeSelector.value;
    const selector = reportType === 'signifikan'
        ? '.signifikan-column-checkbox'
        : reportType === 'non-signifikan'
            ? '.nonSignifikan-column-checkbox'
            : '.semester-column-checkbox';
    
    return Array.from(document.querySelectorAll(selector))
        .filter(cb => cb.checked)
        .map(cb => cb.value)
        .join(',');
}

const reportTypeSelector = document.getElementById('reportTypeSelector');
const semesterContainer = document.getElementById('semesterTableContainer');
const signifikanContainer = document.getElementById('signifikanTableContainer');
const nonSignifikanContainer = document.getElementById('nonSignifikanTableContainer');
const semesterColumnsSelector = document.getElementById('semesterColumnsSelector');
const signifikanColumnsSelector = document.getElementById('signifikanColumnsSelector');
const nonSignifikanColumnsSelector = document.getElementById('nonSignifikanColumnsSelector');

function setVisibleTable() {
    if (reportTypeSelector.value === 'semester') {
        semesterContainer.classList.remove('hidden');
        signifikanContainer.classList.add('hidden');
        nonSignifikanContainer.classList.add('hidden');
        semesterColumnsSelector.classList.remove('hidden');
        signifikanColumnsSelector.classList.add('hidden');
        nonSignifikanColumnsSelector.classList.add('hidden');
    } else if (reportTypeSelector.value === 'signifikan') {
        semesterContainer.classList.add('hidden');
        signifikanContainer.classList.remove('hidden');
        nonSignifikanContainer.classList.add('hidden');
        semesterColumnsSelector.classList.add('hidden');
        signifikanColumnsSelector.classList.remove('hidden');
        nonSignifikanColumnsSelector.classList.add('hidden');
    } else {
        semesterContainer.classList.add('hidden');
        signifikanContainer.classList.add('hidden');
        nonSignifikanContainer.classList.remove('hidden');
        semesterColumnsSelector.classList.add('hidden');
        signifikanColumnsSelector.classList.add('hidden');
        nonSignifikanColumnsSelector.classList.remove('hidden');
    }
}

reportTypeSelector.addEventListener('change', function() {
    const currentUrl = new URL(window.location);
    const newReportType = reportTypeSelector.value;
    
    // Update jenis_laporan parameter
    currentUrl.searchParams.set('jenis_laporan', newReportType);
    
    // Navigate to new URL
    window.location.href = currentUrl.toString();
});

function resetFilters() {
    // Clear all filter fields
    document.getElementById('filterFromDate').value = '';
    document.getElementById('filterToDate').value = '';
    document.getElementById('filterStatus').value = '';
    // Submit the form to reload without filters
    document.getElementById('filterForm').submit();
}

function getFilterParams() {
    const params = new URLSearchParams();
    
    const fromDate = document.getElementById('filterFromDate').value || '';
    const toDate = document.getElementById('filterToDate').value || '';
    const status = document.getElementById('filterStatus').value || '';
    // Gunakan reportTypeSelector sebagai sumber jenis_laporan
    const jenisLaporan = reportTypeSelector.value || 'semester';
    
    if (fromDate) params.append('dari_tanggal', fromDate);
    if (toDate) params.append('sampai_tanggal', toDate);
    if (status) params.append('status_penanganan', status);
    params.append('jenis_laporan', jenisLaporan); // Selalu append jenis_laporan
    
    return params;
}

function exportToExcel() {
    const params = getFilterParams();
    const selectedColumns = getSelectedColumns();
    if (selectedColumns) {
        params.append('selected_columns', selectedColumns);
    }
    const queryString = params.toString();
    const url = "{{ route('kasus.export-excel') }}" + (queryString ? '?' + queryString : '');
    window.location.href = url;
}

setVisibleTable();
initializeColumnSelectors();
</script>

@endsection

