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
    $formatRecovery = fn($value) => $value === null || $value === '' || $value === 0 ? '' : number_format($value, 0, ',', '.');
@endphp

<!-- TABLE -->
<div class="bg-white p-6 rounded-xl shadow mt-8">

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Kasus Terbaru</h2>
        <select id="dashboardReportTypeSelector" class="px-7 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="semester">Laporan Semester</option>
            <option value="signifikan">Laporan Signifikan</option>
            <option value="non-signifikan">Laporan Non-Signifikan</option>
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
<td class="border p-2">{{ $k->kerugianFraud ? $formatRecovery($k->kerugianFraud->ljk_recovery) : '-' }}</td>

<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->konsumen_rill) : '-' }}</td>
<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->konsumen_potensial) : '-' }}</td>
<td class="border p-2">{{ $k->kerugianFraud ? $formatRecovery($k->kerugianFraud->konsumen_recovery) : '-' }}</td>

<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->pihak_lain_rill) : '-' }}</td>
<td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->kerugianFraud->pihak_lain_potensial) : '-' }}</td>
<td class="border p-2">{{ $k->kerugianFraud ? $formatRecovery($k->kerugianFraud->pihak_lain_recovery) : '-' }}</td>

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
@foreach($k->pelakuFrauds as $p) {{ $p->kategori_label }}<br>@endforeach
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
@foreach($k->pelakuFrauds as $p) {{ $p->kategori_label }}<br>@endforeach
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

                <!-- LAPORAN NON-SIGNIFIKAN TABLE -->
            <div id="nonSignifikanTableContainerDashboard" class="overflow-x-auto hidden">
                <table class="min-w-[3200px] text-xs border-collapse">
    <thead class="bg-[#FF0000] text-white">
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
                    @forelse($nonSignifikanKasus as $k)
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

                        <td class="border p-2">{{ $k->kerugianFraud ? $formatCurrency($k->getTotalKerugianPotensial()) : '-' }}</td>
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
                            @foreach($k->pelakuFrauds as $p) {{ $p->kategori_label }}<br>@endforeach
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
                @empty
                        <tr>
                            <td colspan="38" class="border p-4 text-center">Tidak ada data laporan non-signifikan.</td>
                        </tr>
                    @endforelse
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
    (function() {
        const selector = document.getElementById('dashboardReportTypeSelector');
        const semesterContainer = document.getElementById('semesterTableContainerDashboard');
        const signifikanContainer = document.getElementById('signifikanTableContainerDashboard');
        const nonSignifikanContainer = document.getElementById('nonSignifikanTableContainerDashboard');

        if (!selector) return;

        const applyVisibility = (selectedType) => {
            if (selectedType === 'semester') {
                semesterContainer.classList.remove('hidden');
                signifikanContainer.classList.add('hidden');
                nonSignifikanContainer.classList.add('hidden');
            } else if (selectedType === 'signifikan') {
                semesterContainer.classList.add('hidden');
                signifikanContainer.classList.remove('hidden');
                nonSignifikanContainer.classList.add('hidden');
            } else if (selectedType === 'non-signifikan') {
                semesterContainer.classList.add('hidden');
                signifikanContainer.classList.add('hidden');
                nonSignifikanContainer.classList.remove('hidden');
            }
        };

        // Apply initial visibility based on current select value
        applyVisibility(selector.value);

        selector.addEventListener('change', function(e) {
            applyVisibility(e.target.value);
        });
    })();
</script>

<!-- ==================== ANALYTICS FILTER SECTION ==================== -->
<div class="mt-8 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Filter Dashboard Analisis</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="filter_year" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
            <select id="filter_year" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Tahun</option>
                @foreach($availableYears as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="filter_month" class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
            <select id="filter_month" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Bulan</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button id="btn_apply_filter" class="w-full px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                Terapkan
            </button>
            <button id="btn_reset_filter" class="w-full px-3 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition text-sm font-medium">
                Reset
            </button>
        </div>
    </div>
</div>

<!-- ==================== ANALYTICS KPI CARDS ==================== -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-medium">Total Kerugian (Analisis Filter)</p>
        <h3 class="text-xl font-bold text-gray-800 mt-2" id="kpi_total_kerugian">-</h3>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-medium">Total Recovery (Analisis Filter)</p>
        <h3 class="text-xl font-bold text-gray-800 mt-2" id="kpi_total_recovery">-</h3>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-medium">Recovery Rate (%)</p>
        <h3 class="text-xl font-bold text-gray-800 mt-2" id="kpi_recovery_rate">-</h3>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-medium">On-Time Completion Rate (%)</p>
        <h3 class="text-xl font-bold text-gray-800 mt-2" id="kpi_ontime_rate">-</h3>
    </div>
</div>

<!-- ==================== ANALYTICS CHARTS ==================== -->
<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-4">
    <!-- Tren Jumlah Kasus -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Tren Jumlah Kasus Fraud</h3>
        <div style="position: relative; height: 250px;">
            <canvas id="chart_trend_cases"></canvas>
        </div>
    </div>

    <!-- Tren Total Kerugian -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Tren Total Kerugian</h3>
        <div style="position: relative; height: 250px;">
            <canvas id="chart_trend_loss"></canvas>
        </div>
    </div>

    <!-- Top Jenis Fraud -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Top 10 Jenis Fraud</h3>
        <div style="position: relative; height: 250px;">
            <canvas id="chart_top_jenis_fraud"></canvas>
        </div>
    </div>

    <!-- Aktivitas Terkait -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Aktivitas Terkait Fraud</h3>
        <div style="position: relative; height: 250px;">
            <canvas id="chart_activity_related"></canvas>
        </div>
    </div>

    <!-- Fraud by Division -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Fraud Berdasarkan Divisi/Unit</h3>
        <div style="position: relative; height: 250px;">
            <canvas id="chart_fraud_by_division"></canvas>
        </div>
    </div>

    <!-- Internal vs External -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Pelaku: Internal vs Eksternal</h3>
        <div style="position: relative; height: 250px;">
            <canvas id="chart_internal_vs_external"></canvas>
        </div>
    </div>

    <!-- Status Pelaku -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Status Pelaku Fraud</h3>
        <div style="position: relative; height: 250px;">
            <canvas id="chart_status_pelaku"></canvas>
        </div>
    </div>

    <!-- Status Penanganan -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Status Penanganan Kasus</h3>
        <div style="position: relative; height: 250px;">
            <canvas id="chart_handling_status"></canvas>
        </div>
    </div>

    <!-- Top Jabatan Pelaku -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Top 10 Jabatan Pelaku</h3>
        <div style="position: relative; height: 250px;">
            <canvas id="chart_top_jabatan"></canvas>
        </div>
    </div>

    <!-- Kerugian by Victim -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Kerugian Berdasarkan Pihak Dirugikan</h3>
        <div style="position: relative; height: 250px;">
            <canvas id="chart_loss_by_victim"></canvas>
        </div>
    </div>

    <!-- Root Cause -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Root Cause Fraud (Top 10 Kelemahan)</h3>
        <div style="position: relative; height: 250px;">
            <canvas id="chart_root_cause"></canvas>
        </div>
    </div>

    <!-- Prevention Status -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Status Realisasi Tindakan Pencegahan</h3>
        <div style="position: relative; height: 250px;">
            <canvas id="chart_prevention_status"></canvas>
        </div>
    </div>
</div>

<!-- ==================== TOP 10 KERUGIAN TABLE ==================== -->
<div class="mt-8 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Top 10 Kasus Dengan Kerugian Terbesar</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-xs border-collapse">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="border p-2 text-left">No</th>
                    <th class="border p-2 text-left">ID Kasus</th>
                    <th class="border p-2 text-left">Jenis Fraud</th>
                    <th class="border p-2 text-left">Divisi</th>
                    <th class="border p-2 text-left">Status Penanganan</th>
                    <th class="border p-2 text-right">Total Kerugian</th>
                    <th class="border p-2 text-center sticky-aksi">Aksi</th>
                </tr>
            </thead>
            <tbody id="table_top_cases" class="divide-y divide-gray-200">
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-500">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
// =====================================================
// Utility Functions
// =====================================================

function formatCurrency(value) {
    if (!value || value === 0) return 'Rp 0';
    return 'Rp ' + value.toLocaleString('id-ID');
}

function formatPercentage(value) {
    return value.toFixed(2) + '%';
}

const chartInstances = {};

// =====================================================
// Load Analytics Data
// =====================================================

function loadAnalyticsData() {
    const year = document.getElementById('filter_year').value;
    const month = document.getElementById('filter_month').value;
    
    const params = new URLSearchParams();
    if (year) params.append('year', year);
    if (month) params.append('month', month);

    // Load KPI
    fetch(`{{ route('analytics.kpi') }}?${params}`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('kpi_total_kerugian').textContent = formatCurrency(data.total_kerugian);
            document.getElementById('kpi_total_recovery').textContent = formatCurrency(data.total_recovery);
            document.getElementById('kpi_recovery_rate').textContent = formatPercentage(data.recovery_rate);
            document.getElementById('kpi_ontime_rate').textContent = formatPercentage(data.on_time_completion_rate);
        });

    // Load Trend Data
    fetch(`{{ route('analytics.trend') }}?${params}`)
        .then(r => r.json())
        .then(data => updateTrendCharts(data));

    // Load Fraud Analysis
    fetch(`{{ route('analytics.fraud') }}?${params}`)
        .then(r => r.json())
        .then(data => updateFraudAnalysis(data));

    // Load Pelaku Analysis
    fetch(`{{ route('analytics.pelaku') }}?${params}`)
        .then(r => r.json())
        .then(data => updatePelakuAnalysis(data));

    // Load Kerugian Analysis
    fetch(`{{ route('analytics.kerugian') }}?${params}`)
        .then(r => r.json())
        .then(data => updateKerugianAnalysis(data));

    // Load Handling Analysis
    fetch(`{{ route('analytics.handling') }}?${params}`)
        .then(r => r.json())
        .then(data => updateHandlingAnalysis(data));

    // Load Root Cause
    fetch(`{{ route('analytics.rootcause') }}?${params}`)
        .then(r => r.json())
        .then(data => updateRootCauseAnalysis(data));

    // Load Prevention
    fetch(`{{ route('analytics.pencegahan') }}?${params}`)
        .then(r => r.json())
        .then(data => updatePencegahanAnalysis(data));
}

// =====================================================
// Chart Update Functions
// =====================================================

function updateTrendCharts(data) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const trendCasesArray = Array.from({ length: 12 }, (_, i) => data.trend_cases[i + 1] ?? 0);
    const trendLossArray = Array.from({ length: 12 }, (_, i) => data.trend_loss[i + 1] ?? 0);

    if (chartInstances.trendCases) chartInstances.trendCases.destroy();
    chartInstances.trendCases = new Chart(document.getElementById('chart_trend_cases'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Jumlah Kasus',
                data: trendCasesArray,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    if (chartInstances.trendLoss) chartInstances.trendLoss.destroy();
    chartInstances.trendLoss = new Chart(document.getElementById('chart_trend_loss'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Total Kerugian',
                data: trendLossArray,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

function updateFraudAnalysis(data) {
    if (chartInstances.topJenisFraud) chartInstances.topJenisFraud.destroy();
    chartInstances.topJenisFraud = new Chart(document.getElementById('chart_top_jenis_fraud'), {
        type: 'bar',
        data: {
            labels: data.top_jenis_fraud.map(d => d.nama),
            datasets: [{ label: 'Jumlah Kasus', data: data.top_jenis_fraud.map(d => d.count), backgroundColor: '#3b82f6' }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });

    if (chartInstances.activityRelated) chartInstances.activityRelated.destroy();
    chartInstances.activityRelated = new Chart(document.getElementById('chart_activity_related'), {
        type: 'doughnut',
        data: {
            labels: data.activity_related.map(d => d.nama),
            datasets: [{ data: data.activity_related.map(d => d.count), backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'] }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    if (chartInstances.fraudByDivision) chartInstances.fraudByDivision.destroy();
    chartInstances.fraudByDivision = new Chart(document.getElementById('chart_fraud_by_division'), {
        type: 'bar',
        data: {
            labels: data.fraud_by_division.map(d => d.divisi_unit || 'Tidak Diketahui'),
            datasets: [{ label: 'Jumlah Kasus', data: data.fraud_by_division.map(d => d.count), backgroundColor: '#06b6d4' }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

function updatePelakuAnalysis(data) {
    if (chartInstances.internalVsExternal) chartInstances.internalVsExternal.destroy();
    chartInstances.internalVsExternal = new Chart(document.getElementById('chart_internal_vs_external'), {
        type: 'doughnut',
        data: {
            labels: data.internal_vs_external.map(d => d.kategori),
            datasets: [{ data: data.internal_vs_external.map(d => d.count), backgroundColor: ['#ef4444', '#10b981'] }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    if (chartInstances.statusPelaku) chartInstances.statusPelaku.destroy();
    chartInstances.statusPelaku = new Chart(document.getElementById('chart_status_pelaku'), {
        type: 'bar',
        data: {
            labels: data.status_pelaku.map(d => d.nama),
            datasets: [{ label: 'Jumlah Pelaku', data: data.status_pelaku.map(d => d.count), backgroundColor: '#8b5cf6' }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });

    if (chartInstances.topJabatan) chartInstances.topJabatan.destroy();
    chartInstances.topJabatan = new Chart(document.getElementById('chart_top_jabatan'), {
        type: 'bar',
        data: {
            labels: data.top_jabatan_pelaku.map(d => d.nama),
            datasets: [{ label: 'Jumlah Pelaku', data: data.top_jabatan_pelaku.map(d => d.count), backgroundColor: '#f59e0b' }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });
}

function updateKerugianAnalysis(data) {
    if (chartInstances.lossByVictim) chartInstances.lossByVictim.destroy();
    chartInstances.lossByVictim = new Chart(document.getElementById('chart_loss_by_victim'), {
        type: 'pie',
        data: {
            labels: data.loss_by_victim.map(d => d.nama),
            datasets: [{ data: data.loss_by_victim.map(d => d.count), backgroundColor: ['#ef4444', '#10b981', '#3b82f6'] }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const tbody = document.getElementById('table_top_cases');
    tbody.innerHTML = '';
    if (data.top_cases_by_loss && data.top_cases_by_loss.length > 0) {
        data.top_cases_by_loss.forEach((kasus, index) => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            row.innerHTML = `
                <td class="border p-2">${index + 1}</td>
                <td class="border p-2">${kasus.kode_komponen}</td>
                <td class="border p-2 text-xs">${kasus.jenis_fraud}</td>
                <td class="border p-2 text-xs">${kasus.divisi}</td>
                <td class="border p-2 text-xs">${kasus.status_penanganan}</td>
                <td class="border p-2 text-right font-medium">${formatCurrency(kasus.total_kerugian)}</td>
                <td class="border p-2 text-center sticky-aksi">
                    <a href="/kasus/${kasus.id}" class="text-blue-600 hover:text-blue-800 text-xs">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            `;
            tbody.appendChild(row);
        });
    } else {
        tbody.innerHTML = '<tr><td colspan="7" class="border p-4 text-center text-gray-500">Tidak ada data</td></tr>';
    }
}

function updateHandlingAnalysis(data) {
    if (chartInstances.handlingStatus) chartInstances.handlingStatus.destroy();
    chartInstances.handlingStatus = new Chart(document.getElementById('chart_handling_status'), {
        type: 'pie',
        data: {
            labels: data.handling_status.map(d => d.status),
            datasets: [{ data: data.handling_status.map(d => d.count), backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'] }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
}

function updateRootCauseAnalysis(data) {
    if (chartInstances.rootCause) chartInstances.rootCause.destroy();
    chartInstances.rootCause = new Chart(document.getElementById('chart_root_cause'), {
        type: 'bar',
        data: {
            labels: data.top_kelemahan.map(d => d.nama),
            datasets: [{ label: 'Jumlah Kasus', data: data.top_kelemahan.map(d => d.count), backgroundColor: '#dc2626' }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });
}

function updatePencegahanAnalysis(data) {
    if (chartInstances.preventionStatus) chartInstances.preventionStatus.destroy();
    chartInstances.preventionStatus = new Chart(document.getElementById('chart_prevention_status'), {
        type: 'doughnut',
        data: {
            labels: data.prevention_status.map(d => d.status),
            datasets: [{ data: data.prevention_status.map(d => d.count), backgroundColor: ['#10b981', '#ef4444', '#6b7280'] }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
}

// =====================================================
// Event Listeners
// =====================================================

document.getElementById('btn_apply_filter').addEventListener('click', () => {
    const year = document.getElementById('filter_year').value;
    const month = document.getElementById('filter_month').value;
    const params = new URLSearchParams();
    if (year) params.append('year', year);
    if (month) params.append('month', month);
    window.location.href = `{{ route('dashboard') }}?${params}`;
});

document.getElementById('btn_reset_filter').addEventListener('click', () => {
    window.location.href = '{{ route('dashboard') }}';
});

document.addEventListener('DOMContentLoaded', () => {
    loadAnalyticsData();
});
</script>

@endsection