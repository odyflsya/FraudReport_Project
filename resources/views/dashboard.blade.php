@extends('layouts.app')

@section('content')
<style>
    tbody td.sticky-aksi {
        position: sticky;
        right: 0;
        background-color: white;
        box-shadow: -6px 0 12px -8px rgba(0,0,0,0.25);
        border-left: 1px solid #d1d5db;
        z-index: 5;
    }
    thead th.sticky-aksi {
        position: sticky;
        right: 0;
        background-color: #FF0000;
        box-shadow: -6px 0 12px -8px rgba(0,0,0,0.25);
        z-index: 11;
    }
</style>

@php
    $formatCurrency = fn($value) => $value === null || $value === '' ? '' : number_format($value, 0, ',', '.');
@endphp

<!-- CARD ATAS -->
<div class="flex flex-wrap items-start gap-4">

    <!-- TOTAL KASUS -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3 w-fit hover:shadow-md transition">
        <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
            <i class="fas fa-folder-open text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Kasus</p>
            <h2 class="text-2xl font-bold text-gray-800 leading-tight">
                {{ $totalKasus }}
            </h2>
        </div>
    </div>

    <!-- TOTAL KERUGIAN -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3 w-fit hover:shadow-md transition">
        <div class="bg-green-100 text-green-600 p-3 rounded-xl">
            <i class="fas fa-money-bill-wave text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Kerugian</p>
            <h2 class="text-xl font-bold text-gray-800 whitespace-nowrap leading-tight">
                Rp {{ number_format($totalKerugian,0,',','.') }}
            </h2>
        </div>
    </div>

    <!-- TOTAL PELAKU -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3 w-fit hover:shadow-md transition">
        <div class="bg-purple-100 text-purple-600 p-3 rounded-xl">
            <i class="fas fa-user-secret text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Pelaku</p>
            <h2 class="text-2xl font-bold text-gray-800 leading-tight">
                {{ $totalPelaku }}
            </h2>
        </div>
    </div>

</div>

<!-- STATUS DI BAWAH -->
<div class="mt-4 bg-white p-5 rounded-xl shadow-sm border border-gray-100 max-w-3xl">

    <!-- HEADER -->
    <div class="flex items-center gap-3 mb-4">
        <div class="bg-yellow-100 text-yellow-600 p-3 rounded-xl">
            <i class="fas fa-chart-pie text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Status Penanganan</p>
            <h2 class="text-lg font-bold text-gray-800">
                Ringkasan Status Kasus
            </h2>
        </div>
    </div>

    <!-- STATUS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        <!-- STATUS 001 -->
        <div class="flex items-center justify-between bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">
                    <i class="fas fa-spinner"></i>
                </div>
                <p class="text-sm font-medium text-gray-700">Proses internal LJK</p>
            </div>
            <span class="text-lg font-bold text-blue-600">
                {{ $statusCounts['001'] ?? 0 }}
            </span>
        </div>

        <!-- STATUS 002 -->
        <div class="flex items-center justify-between bg-green-50 border border-green-100 rounded-xl px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="bg-green-100 text-green-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p class="text-sm font-medium text-gray-700">Selesai diproses internal</p>
            </div>
            <span class="text-lg font-bold text-green-600">
                {{ $statusCounts['002'] ?? 0 }}
            </span>
        </div>

        <!-- STATUS 003 -->
        <div class="flex items-center justify-between bg-yellow-50 border border-yellow-100 rounded-xl px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="bg-yellow-100 text-yellow-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">
                    <i class="fas fa-gavel"></i>
                </div>
                <p class="text-sm font-medium text-gray-700">Penanganan aparat hukum</p>
            </div>
            <span class="text-lg font-bold text-yellow-600">
                {{ $statusCounts['003'] ?? 0 }}
            </span>
        </div>

        <!-- STATUS 004 -->
        <div class="flex items-center justify-between bg-red-50 border border-red-100 rounded-xl px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="bg-red-100 text-red-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">
                    <i class="fas fa-scale-balanced"></i>
                </div>
                <p class="text-sm font-medium text-gray-700">Berkekuatan hukum tetap</p>
            </div>
            <span class="text-lg font-bold text-red-600">
                {{ $statusCounts['004'] ?? 0 }}
            </span>
        </div>

    </div>
</div>

<!-- TABLE -->
<div class="bg-white p-6 rounded-xl shadow mt-8">

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Kasus Terbaru</h2>
        <select id="dashboardReportTypeSelector" class="px-7 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="semester">Laporan Semester</option>
            <option value="signifikan">Laporan Signifikan</option>
        </select>
    </div>

@php
    $formatRefLabel = function ($ref) {
        return $ref
            ? ($ref->kode ? $ref->kode . ' (' . $ref->nama . ')' : $ref->nama)
            : '-';
    };
@endphp

    <div id="semesterTableContainerDashboard" class="overflow-x-auto">
        <table class="min-w-[3500px] text-xs border-collapse">

    <!-- HEADER -->
<thead class="bg-[#FF0000] text-white">
    <!-- ROW 1 -->
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
        <th rowspan="3" class="border p-2 text-center sticky-aksi">Aksi</th>
    </tr>

    <!-- ROW 2 -->
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

        <!-- PELAKU ROW 2 -->
        <th rowspan="2" class="border p-2">Internal/Eksternal</th>
        <th colspan="8" class="border p-2">Identitas Pelaku</th>
        <th rowspan="2" class="border p-2">Status Pelaku</th>
        <th colspan="4" class="border p-2">Jabatan Pelaku</th>
        <th rowspan="2" class="border p-2">Keterangan Pelaku</th>
        <th rowspan="2" class="border p-2">Pengenaan Sanksi</th>
    </tr>

    <!-- ROW 3 -->
    <tr>
        <th class="border p-2">Awal</th>
        <th class="border p-2">Akhir</th>

        <th class="border p-2">Riil (incurred)</th>
        <th class="border p-2">Potensial (Potential)</th>
        <th class="border p-2">Setelah Pengembalian (Recovery)</th>

        <th class="border p-2">Riil (incurred)</th>
        <th class="border p-2">Potensial (Potential)</th>
        <th class="border p-2">Setelah Pengembalian (Recovery)</th>

        <th class="border p-2">Riil (incurred)</th>
        <th class="border p-2">Potensial (Potential)</th>
        <th class="border p-2">Setelah Pengembalian (Recovery)</th>

        <!-- PELAKU ROW 3 -->
        <th class="border p-2">Nama</th>
        <th class="border p-2">Jenis Identitas</th>
        <th class="border p-2">Nomor Identitas</th>
        <th class="border p-2">Jenis Kelamin</th>
        <th class="border p-2">Tempat Lahir</th>
        <th class="border p-2">Tanggal Lahir</th>
        <th class="border p-2">Alamat Identitas</th>
        <th class="border p-2">Alamat Domisili</th>
        <th class="border p-2">Pada Saat Fraud Terjadi</th>
        <th class="border p-2">Keterangan Jabatan</th>
        <th class="border p-2">Pada Saat Fraud Diketahui</th>
        <th class="border p-2">Keterangan Jabatan</th>
    </tr>

    </thead>

    <!-- BODY -->
    <tbody class="bg-white">

@php
    $statusLabels = [
        '001' => '001 (Proses internal LJK)',
        '002' => '002 (Selesai diproses internal LJK)',
        '003' => '003 (Dalam proses penanganan aparat penegak hukum)',
        '004' => '004 (Berkekuatan hukum tetap)',
    ];
@endphp

@foreach($semesterKasus as $k)
<tr class="hover:bg-gray-50 align-top">

<td class="border p-2">{{ $loop->iteration }}</td>
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
{{ $formatRefLabel($k->aktivitasTerkait) }}</td>
<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
{{ $k->deskripsi_fraud }}</td>

<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
@foreach($k->lokasiFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
</td>

<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
@foreach($k->lokasiFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
</td>

<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
{{ $k->divisi_unit }}</td>
<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
{{ $formatRefLabel($k->pihakDirugikan) }}</td>

<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
{{ $k->waktuFraud && $k->waktuFraud->waktu_awal ? \Carbon\Carbon::parse($k->waktuFraud->waktu_awal)->format('Y-m-d') : '-' }}</td>
<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
{{ $k->waktuFraud && $k->waktuFraud->waktu_akhir ? \Carbon\Carbon::parse($k->waktuFraud->waktu_akhir)->format('Y-m-d') : '-' }}</td>
<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
{{ $k->waktuFraud && $k->waktuFraud->waktu_diketahui ? \Carbon\Carbon::parse($k->waktuFraud->waktu_diketahui)->format('Y-m-d') : '-' }}</td>

<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->ljk_rill) : '-' }}</td>
<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->ljk_potensial) : '-' }}</td>
<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->ljk_recovery) : '-' }}</td>

<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->konsumen_rill) : '-' }}</td>
<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->konsumen_potensial) : '-' }}</td>
<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->konsumen_recovery) : '-' }}</td>

<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->pihak_lain_rill) : '-' }}</td>
<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->pihak_lain_potensial) : '-' }}</td>
<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->pihak_lain_recovery) : '-' }}</td>

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
@foreach($k->pelakuFrauds as $p) {{ $p->jenis_kelamin_label }}<br>@endforeach
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
@foreach($k->pelakuFrauds as $p) {{ $p->statusPelaku ? ($p->statusPelaku->kode ? $p->statusPelaku->kode . ' (' . $p->statusPelaku->nama . ')' : $p->statusPelaku->nama) : '-' }}<br>@endforeach
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
@forelse($k->pelakuFrauds as $p)
{{ $p->sanksi ?? '-' }}<br>
@empty
-
@endforelse
</td>

<td class="border p-2 whitespace-nowrap">
<span class="inline-flex whitespace-nowrap px-2 py-1 text-white rounded text-xs bg-slate-500">
{{ $statusLabels[$k->status_penanganan] ?? $k->status_penanganan }}
</span>
</td>

<td class="border p-2 text-center sticky-aksi">
<div class="flex gap-1 justify-center">
<a href="{{ route('kasus.show',$k->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600" title="View">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
</a>
<a href="{{ route('kasus.edit',$k->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600" title="Edit">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
</a>
<form action="{{ route('kasus.destroy',$k->id) }}" method="POST" style="display:inline;">
@csrf @method('DELETE')
<button class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600" title="Delete" onclick="return confirm('Yakin ingin menghapus?')">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
</button>
</form>
</div>
</td>

</tr>
@endforeach

    </tbody>
    </table>
    </div>

    <div id="signifikanTableContainerDashboard" class="overflow-x-auto hidden">
        <table class="min-w-[3200px] text-xs border-collapse">

    <!-- HEADER -->
<thead class="bg-[#FF0000] text-white">

    <!-- ROW 1 -->
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
        <th rowspan="3" class="border p-2 text-center sticky-aksi">Aksi</th>
    </tr>

    <!-- ROW 2 -->
    <tr>
        <th rowspan="2" class="border p-2">Jenis Fraud</th>
        <th rowspan="2" class="border p-2">Keterangan Jenis Fraud</th>

        <th rowspan="2" class="border p-2">Lokasi Fraud</th>
        <th rowspan="2" class="border p-2">Keterangan Lokasi Fraud</th>

        <th colspan="2" class="border p-2">Waktu Terjadi</th>
        <th rowspan="2" class="border p-2">Fraud Diketahui</th>

        <th rowspan="2" class="border p-2">Internal/Eksternal</th>
        <th colspan="8" class="border p-2">Identitas Pelaku</th>
        <th rowspan="2" class="border p-2">Status Pelaku</th>
        <th colspan="4" class="border p-2">Jabatan Pelaku</th>
        <th rowspan="2" class="border p-2">Keterangan Pelaku</th>
        <th rowspan="2" class="border p-2">Pengenaan Sanksi</th>
    </tr>

    <!-- ROW 3 -->
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

    <!-- BODY -->
    <tbody class="bg-white">

@foreach($signifikanKasus as $k)
<tr class="hover:bg-gray-50 align-top">

<td class="border p-2">{{ $loop->iteration }}</td>
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
{{ $formatRefLabel($k->aktivitasTerkait) }}</td>
<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
{{ $k->deskripsi_fraud }}</td>

<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
@foreach($k->lokasiFraud as $i) {{ $i->kode ? $i->kode . ' (' . $i->nama . ')' : $i->nama }}<br>@endforeach
</td>

<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
@foreach($k->lokasiFraud as $i) {{ $i->pivot->keterangan }}<br>@endforeach
</td>

<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
{{ $k->divisi_unit }}</td>
<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
{{ $formatRefLabel($k->pihakDirugikan) }}</td>

<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->getTotalKerugianPotensial()) : '-' }}</td>
<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">{{ $k->tindak_lanjut_ljk ?? '-' }}</td>

<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
{{ $k->waktuFraud && $k->waktuFraud->waktu_awal ? \Carbon\Carbon::parse($k->waktuFraud->waktu_awal)->format('Y-m-d') : '-' }}</td>
<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
{{ $k->waktuFraud && $k->waktuFraud->waktu_akhir ? \Carbon\Carbon::parse($k->waktuFraud->waktu_akhir)->format('Y-m-d') : '-' }}</td>
<td class="border p-2 whitespace-nowrap max-w-[250px] overflow-hidden text-ellipsis">
{{ $k->waktuFraud && $k->waktuFraud->waktu_diketahui ? \Carbon\Carbon::parse($k->waktuFraud->waktu_diketahui)->format('Y-m-d') : '-' }}</td>

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
@foreach($k->pelakuFrauds as $p) {{ $p->jenis_kelamin_label }}<br>@endforeach
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
@foreach($k->pelakuFrauds as $p) {{ $p->statusPelaku ? ($p->statusPelaku->kode ? $p->statusPelaku->kode . ' (' . $p->statusPelaku->nama . ')' : $p->statusPelaku->nama) : '-' }}<br>@endforeach
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
@forelse($k->pelakuFrauds as $p)
{{ $p->sanksi ?? '-' }}<br>
@empty
-
@endforelse
</td>

<td class="border p-2 whitespace-nowrap">
<span class="inline-flex whitespace-nowrap px-2 py-1 text-white rounded text-xs bg-slate-500">
{{ $statusLabels[$k->status_penanganan] ?? $k->status_penanganan }}
</span>
</td>

<td class="border p-2 text-center sticky-aksi">
<div class="flex gap-1 justify-center">
<a href="{{ route('kasus.show',$k->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600" title="View">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
</a>
<a href="{{ route('kasus.edit',$k->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600" title="Edit">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
</a>
<form action="{{ route('kasus.destroy',$k->id) }}" method="POST" style="display:inline;">
@csrf @method('DELETE')
<button class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600" title="Delete" onclick="return confirm('Yakin ingin menghapus?')">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
</button>
</form>
</div>
</td>

</tr>
@endforeach

    </tbody>
    </table>
    </div>

    <div class="mt-4 text-right">
        <a href="{{ route('kasus.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Lihat Semua Kasus →
        </a>
    </div>

</div>

<script>
    document.getElementById('dashboardReportTypeSelector').addEventListener('change', function(e) {
        const selectedType = e.target.value;
        const semesterContainer = document.getElementById('semesterTableContainerDashboard');
        const signifikanContainer = document.getElementById('signifikanTableContainerDashboard');
        
        if (selectedType === 'semester') {
            semesterContainer.classList.remove('hidden');
            signifikanContainer.classList.add('hidden');
        } else {
            semesterContainer.classList.add('hidden');
            signifikanContainer.classList.remove('hidden');
        }
    });
</script>

@endsection