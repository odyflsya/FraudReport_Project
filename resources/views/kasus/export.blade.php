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
                <div class="flex items-end">
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4 pt-4 border-t">
                <button type="button" onclick="resetFilters()"
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
                <button type="button" onclick="exportToPdf()"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    PDF
                </button>
            </div>
        </form>

        <!-- Active Filter Info -->
        @if(request('dari_tanggal') || request('sampai_tanggal') || request('status_penanganan'))
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                <p class="text-sm text-blue-700">
                    <strong>Filter Aktif:</strong>
                    @if(request('dari_tanggal'))
                        Dari {{ \Carbon\Carbon::createFromFormat('Y-m-d', request('dari_tanggal'))->format('d-m-Y') }}
                    @endif
                    @if(request('sampai_tanggal'))
                        sampai {{ \Carbon\Carbon::createFromFormat('Y-m-d', request('sampai_tanggal'))->format('d-m-Y') }}
                    @endif
                    @if(request('status_penanganan'))
                        | Status: 
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
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold"></h2>
                <select id="reportTypeSelector" class="px-7 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="semester" {{ request('jenis_laporan') !== 'signifikan' ? 'selected' : '' }}>Laporan Semester</option>
                    <option value="signifikan" {{ request('jenis_laporan') === 'signifikan' ? 'selected' : '' }}>Laporan Signifikan</option>
                </select>
            </div>
        </div>

        @php
            $statusLabels = [
                '001' => '001 (Proses internal LJK)',
                '002' => '002 (Selesai diproses internal LJK)',
                '003' => '003 (Dalam proses penanganan aparat penegak hukum)',
                '004' => '004 (Berkekuatan hukum tetap)',
            ];
            $formatRefLabel = function ($ref) {
                return $ref
                    ? ($ref->kode ? $ref->kode . ' (' . $ref->nama . ')' : $ref->nama)
                    : '-';
            };
        @endphp

        <!-- LAPORAN SEMESTER TABLE -->
        <div id="semesterTableContainer" class="bg-white rounded-lg shadow overflow-x-auto mb-8">
            <table class="min-w-full text-[10px] border-collapse" id="semesterTable">
                <thead class="bg-red-600 text-white sticky top-0">
                    <tr>
                        <th rowspan="3" class="border p-2">No</th>
                        <th rowspan="3" class="border p-2">Kode Komponen</th>
                        <th rowspan="3" class="border p-2">Kejadian Fraud Menurut Pelaku</th>
                        <th rowspan="3" class="border p-2">ID Kejadian Fraud</th>

                        <th colspan="2" class="border p-2">Jenis Fraud</th>

                        <th rowspan="3" class="border p-2">Aktivitas Terkait Fraud</th>
                        <th rowspan="3" class="border p-2">Deskripsi Fraud / Modus Operandi</th>

                        <th colspan="2" class="border p-2">Lokasi Fraud</th>

                        <th rowspan="3" class="border p-2">Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud</th>
                        <th rowspan="3" class="border p-2">Pihak Yang Dirugikan</th>

                        <th colspan="3" class="border p-2">Waktu</th>

                        <th colspan="9" class="border p-2">Jumlah Kerugian</th>

                        <th colspan="2" class="border p-2">Kelemahan Penyebab Fraud</th>
                        <th colspan="2" class="border p-2">Tindakan untuk Penanganan Fraud</th>

                        <th colspan="4" class="border p-2">Tindakan Perbaikan untuk Pencegahan Fraud</th>

                        <th colspan="16" class="border p-2">Pelaku Fraud</th>

                        <th rowspan="3" class="border p-2">Status Penanganan</th>
                    </tr>
                    <tr>
                        <th rowspan="2" class="border p-2">Jenis Fraud</th>
                        <th rowspan="2" class="border p-2">Keterangan Jenis Fraud</th>

                        <th rowspan="2" class="border p-2">Lokasi Fraud</th>
                        <th rowspan="2" class="border p-2">Keterangan Lokasi Fraud</th>

                        <th colspan="2" class="border p-2">Waktu Terjadi</th>
                        <th rowspan="2" class="border p-2">Fraud Diketahui</th>

                        <th colspan="3" class="border p-2">LJK</th>
                        <th colspan="3" class="border p-2">Konsumen</th>
                        <th colspan="3" class="border p-2">Pihak Lain</th>

                        <th rowspan="2" class="border p-2">Kelemahan Penyebab Fraud</th>
                        <th rowspan="2" class="border p-2">Keterangan</th>

                        <th rowspan="2" class="border p-2">Tindakan untuk Penanganan Fraud</th>
                        <th rowspan="2" class="border p-2">Keterangan</th>

                        <th rowspan="2" class="border p-2">Tindakan Perbaikan untuk Pencegahan Fraud</th>
                        <th rowspan="2" class="border p-2">Keterangan</th>
                        <th rowspan="2" class="border p-2">Target Waktu Pelaksanaan</th>
                        <th rowspan="2" class="border p-2">Realisasi Pelaksanaan</th>

                        <th rowspan="2" class="border p-2">Internal/Eksternal</th>
                        <th colspan="8" class="border p-2">Identitas Pelaku</th>
                        <th colspan="4" class="border p-2">Jabatan Pelaku</th>
                        <th rowspan="2" class="border p-2">Keterangan Pelaku</th>
                        <th rowspan="2" class="border p-2">Status Pelaku</th>
                        <th rowspan="2" class="border p-2">Pengenaan Sanksi</th>
                    </tr>
                    <tr>
                        <th class="border p-2">Awal</th>
                        <th class="border p-2">Akhir</th>

                        <th class="border p-2">Rill</th>
                        <th class="border p-2">Pot</th>
                        <th class="border p-2">Rec</th>

                        <th class="border p-2">Rill</th>
                        <th class="border p-2">Pot</th>
                        <th class="border p-2">Rec</th>

                        <th class="border p-2">Rill</th>
                        <th class="border p-2">Pot</th>
                        <th class="border p-2">Rec</th>

                        <th class="border p-2">Nama</th>
                        <th class="border p-2">Jenis Identitas</th>
                        <th class="border p-2">Nomor Identitas</th>
                        <th class="border p-2">Jenis Kelamin</th>
                        <th class="border p-2">Tempat Lahir</th>
                        <th class="border p-2">Tanggal Lahir</th>
                        <th class="border p-2">Alamat Identitas</th>
                        <th class="border p-2">Alamat Domisili</th>
                        <th class="border p-2">Pada Saat Fraud Terjadi</th>
                        <th class="border p-2">Keterangan</th>
                        <th class="border p-2">Pada Saat Fraud Diketahui</th>
                        <th class="border p-2">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($semesterData['kasus'] as $k)
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="border p-2">{{ $k->id }}</td>
                            <td class="border p-2">{{ $k->kode_komponen }}</td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->kejadianFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->kejadianFraud as $i) {{ $i->pivot->kode_kejadian ?? '-' }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->jenisFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->jenisFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                {{ $formatRefLabel($k->aktivitasTerkait) }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">{{ $k->deskripsi_fraud }}</td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->lokasiFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->lokasiFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">{{ $k->divisi_unit }}</td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                {{ $formatRefLabel($k->pihakDirugikan) }}
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_awal ? \Carbon\Carbon::parse($k->waktuFraud->waktu_awal)->format('Y-m-d') : '-' }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_akhir ? \Carbon\Carbon::parse($k->waktuFraud->waktu_akhir)->format('Y-m-d') : '-' }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_diketahui ? \Carbon\Carbon::parse($k->waktuFraud->waktu_diketahui)->format('Y-m-d') : '-' }}
                            </td>

                            <td class="border p-2">{{ $k->kerugianFraud->ljk_rill ?? 0 }}</td>
                            <td class="border p-2">{{ $k->kerugianFraud->ljk_potensial ?? 0 }}</td>
                            <td class="border p-2">{{ $k->kerugianFraud->ljk_recovery ?? 0 }}</td>

                            <td class="border p-2">{{ $k->kerugianFraud->konsumen_rill ?? 0 }}</td>
                            <td class="border p-2">{{ $k->kerugianFraud->konsumen_potensial ?? 0 }}</td>
                            <td class="border p-2">{{ $k->kerugianFraud->konsumen_recovery ?? 0 }}</td>

                            <td class="border p-2">{{ $k->kerugianFraud->pihak_lain_rill ?? 0 }}</td>
                            <td class="border p-2">{{ $k->kerugianFraud->pihak_lain_potensial ?? 0 }}</td>
                            <td class="border p-2">{{ $k->kerugianFraud->pihak_lain_recovery ?? 0 }}</td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->kelemahanFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->kelemahanFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->penangananFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->penangananFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pencegahanFraud as $i) {{ $i->refPencegahan ? ($i->refPencegahan->kode ? $i->refPencegahan->kode . ' (' . $i->refPencegahan->nama . ')' : $i->refPencegahan->nama) : '-' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pencegahanFraud as $i) {{ $i->keterangan }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pencegahanFraud as $i) {{ $i->target_waktu ? \Carbon\Carbon::parse($i->target_waktu)->format('Y-m-d') : '-' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pencegahanFraud as $i) {{ $i->realisasi ? \Carbon\Carbon::parse($i->realisasi)->format('Y-m-d') : '-' }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->kategori }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->nama }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jenisIdentitas ? ($p->jenisIdentitas->kode ? $p->jenisIdentitas->kode . ' (' . $p->jenisIdentitas->nama . ')' : $p->jenisIdentitas->nama) : '-' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->nomor_identitas }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jenis_kelamin }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->tempat_lahir }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('Y-m-d') : '-' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->alamat_identitas }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->alamat_domisili }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jabatanKejadian ? ($p->jabatanKejadian->kode ? $p->jabatanKejadian->kode . ' (' . $p->jabatanKejadian->nama . ')' : $p->jabatanKejadian->nama) : '-' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->ket_jabatan_kejadian }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jabatanDiketahui ? ($p->jabatanDiketahui->kode ? $p->jabatanDiketahui->kode . ' (' . $p->jabatanDiketahui->nama . ')' : $p->jabatanDiketahui->nama) : '-' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->ket_jabatan_diketahui }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->keterangan }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->statusPelaku ? ($p->statusPelaku->kode ? $p->statusPelaku->kode . ' (' . $p->statusPelaku->nama . ')' : $p->statusPelaku->nama) : '-' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @forelse($k->pelakuFrauds as $p)
                                    {{ $p->sanksi ?? '-' }}<br>
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td class="border p-2 text-center">
                                {{ $statusLabels[$k->status_penanganan] ?? $k->status_penanganan }}
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
            <table class="min-w-full text-[10px] border-collapse" id="signifikanTable">
                <thead class="bg-red-600 text-white sticky top-0">
                    <tr>
                        <th rowspan="3" class="border p-2">No</th>
                        <th rowspan="3" class="border p-2">Kode Komponen</th>
                        <th rowspan="3" class="border p-2">Kejadian Fraud Menurut Pelaku</th>
                        <th rowspan="3" class="border p-2">ID Kejadian Fraud</th>

                        <th colspan="2" class="border p-2">Jenis Fraud</th>

                        <th rowspan="3" class="border p-2">Aktivitas Terkait Fraud</th>
                        <th rowspan="3" class="border p-2">Deskripsi Fraud / Modus Operandi</th>

                        <th colspan="2" class="border p-2">Lokasi Fraud</th>

                        <th rowspan="3" class="border p-2">Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud</th>
                        <th rowspan="3" class="border p-2">Pihak Yang Dirugikan</th>
                        <th rowspan="3" class="border p-2">Jumlah Kerugian Potensial</th>
                        <th rowspan="3" class="border p-2">Tindak Lanjut LJK</th>

                        <th colspan="3" class="border p-2">Waktu</th>
                        <th colspan="16" class="border p-2">Pelaku Fraud</th>
                        <th rowspan="3" class="border p-2">Status Penanganan</th>
                    </tr>
                    <tr>
                        <th rowspan="2" class="border p-2">Jenis Fraud</th>
                        <th rowspan="2" class="border p-2">Keterangan Jenis Fraud</th>

                        <th rowspan="2" class="border p-2">Lokasi Fraud</th>
                        <th rowspan="2" class="border p-2">Keterangan Lokasi Fraud</th>

                        <th colspan="2" class="border p-2">Waktu Terjadi</th>
                        <th rowspan="2" class="border p-2">Fraud Diketahui</th>

                        <th rowspan="2" class="border p-2">Internal/Eksternal</th>
                        <th colspan="8" class="border p-2">Identitas Pelaku</th>
                        <th colspan="4" class="border p-2">Jabatan Pelaku</th>
                        <th rowspan="2" class="border p-2">Keterangan Pelaku</th>
                        <th rowspan="2" class="border p-2">Status Pelaku</th>
                        <th rowspan="2" class="border p-2">Pengenaan Sanksi</th>
                    </tr>
                    <tr>
                        <th class="border p-2">Awal</th>
                        <th class="border p-2">Akhir</th>

                        <th class="border p-2">Nama</th>
                        <th class="border p-2">Jenis Identitas</th>
                        <th class="border p-2">Nomor Identitas</th>
                        <th class="border p-2">Jenis Kelamin</th>
                        <th class="border p-2">Tempat Lahir</th>
                        <th class="border p-2">Tanggal Lahir</th>
                        <th class="border p-2">Alamat Identitas</th>
                        <th class="border p-2">Alamat Domisili</th>
                        <th class="border p-2">Pada Saat Fraud Terjadi</th>
                        <th class="border p-2">Keterangan</th>
                        <th class="border p-2">Pada Saat Fraud Diketahui</th>
                        <th class="border p-2">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($signifikanData['kasus'] as $k)
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="border p-2">{{ $k->id }}</td>
                            <td class="border p-2">{{ $k->kode_komponen }}</td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->kejadianFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->kejadianFraud as $i) {{ $i->pivot->kode_kejadian ?? '-' }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->jenisFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->jenisFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                {{ $formatRefLabel($k->aktivitasTerkait) }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">{{ $k->deskripsi_fraud }}</td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->lokasiFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->lokasiFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">{{ $k->divisi_unit }}</td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                {{ $formatRefLabel($k->pihakDirugikan) }}
                            </td>
                            <td class="border p-2">{{ $k->kerugianFraud->ljk_potensial ?? 0 }}</td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">{{ $k->tindak_lanjut_ljk ?? '-' }}</td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_awal ? \Carbon\Carbon::parse($k->waktuFraud->waktu_awal)->format('Y-m-d') : '-' }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_akhir ? \Carbon\Carbon::parse($k->waktuFraud->waktu_akhir)->format('Y-m-d') : '-' }}
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                {{ $k->waktuFraud && $k->waktuFraud->waktu_diketahui ? \Carbon\Carbon::parse($k->waktuFraud->waktu_diketahui)->format('Y-m-d') : '-' }}
                            </td>

                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->kategori }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->nama }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jenisIdentitas ? ($p->jenisIdentitas->kode ? $p->jenisIdentitas->kode . ' (' . $p->jenisIdentitas->nama . ')' : $p->jenisIdentitas->nama) : '-' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->nomor_identitas }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jenis_kelamin }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->tempat_lahir }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('Y-m-d') : '-' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->alamat_identitas }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->alamat_domisili }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jabatanKejadian ? ($p->jabatanKejadian->kode ? $p->jabatanKejadian->kode . ' (' . $p->jabatanKejadian->nama . ')' : $p->jabatanKejadian->nama) : '-' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->ket_jabatan_kejadian }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->jabatanDiketahui ? ($p->jabatanDiketahui->kode ? $p->jabatanDiketahui->kode . ' (' . $p->jabatanDiketahui->nama . ')' : $p->jabatanDiketahui->nama) : '-' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->ket_jabatan_diketahui }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->keterangan }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @foreach($k->pelakuFrauds as $p) {{ $p->statusPelaku ? ($p->statusPelaku->kode ? $p->statusPelaku->kode . ' (' . $p->statusPelaku->nama . ')' : $p->statusPelaku->nama) : '-' }}<br>@endforeach
                            </td>
                            <td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
                                @forelse($k->pelakuFrauds as $p)
                                    {{ $p->sanksi ?? '-' }}<br>
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td class="border p-2 text-center">
                                {{ $statusLabels[$k->status_penanganan] ?? $k->status_penanganan }}
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

        <!-- Summary Section -->
        @if($summary)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg shadow border-l-4 border-blue-600">
                <h3 class="text-sm font-medium text-gray-600 mb-2">📌 Total Kasus</h3>
                <p class="text-4xl font-bold text-blue-600">{{ $summary['total_kasus'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg shadow border-l-4 border-green-600">
                <h3 class="text-sm font-medium text-gray-600 mb-2">✅ Status Penanganan</h3>
                <p class="text-2xl font-bold"><span class="text-green-600">{{ $summary['selesai'] }} Selesai</span></p>
                <p class="text-lg font-bold"><span class="text-yellow-600">{{ $summary['dalam_proses'] }} Proses</span></p>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg shadow border-l-4 border-purple-600">
                <h3 class="text-sm font-medium text-gray-600 mb-2">👤 Total Pelaku</h3>
                <p class="text-4xl font-bold text-purple-600">{{ $summary['total_pelaku'] }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
const reportTypeSelector = document.getElementById('reportTypeSelector');
const semesterContainer = document.getElementById('semesterTableContainer');
const signifikanContainer = document.getElementById('signifikanTableContainer');

function setVisibleTable() {
    if (reportTypeSelector.value === 'semester') {
        semesterContainer.classList.remove('hidden');
        signifikanContainer.classList.add('hidden');
    } else {
        semesterContainer.classList.add('hidden');
        signifikanContainer.classList.remove('hidden');
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
setVisibleTable();

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
    const queryString = params.toString();
    const url = "{{ route('kasus.export-excel') }}" + (queryString ? '?' + queryString : '');
    window.location.href = url;
}

function exportToPdf() {
    const params = getFilterParams();
    params.append('report_type', reportTypeSelector.value);
    const queryString = params.toString();
    const url = "{{ route('kasus.export-pdf') }}" + (queryString ? '?' + queryString : '');
    window.location.href = url;
}
</script>

@endsection
