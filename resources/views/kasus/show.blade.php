@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-4">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Kasus Fraud</h1>
        <p class="text-gray-600 mt-1">ID: {{ $kasus->id }}</p>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Orange Left Border -->
        <div class="flex">
            <div class="w-1 bg-orange-500"></div>
            <div class="flex-1 p-8">

@php
    $statusLabels = [
        '001' => '001 (Proses internal LJK)',
        '002' => '002 (Selesai diproses internal LJK)',
        '003' => '003 (Dalam proses penanganan aparat penegak hukum)',
        '004' => '004 (Berkekuatan hukum tetap)',
    ];
@endphp

                <!-- Kode Komponen -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Kode Komponen</h3>
                    <p class="text-base font-medium text-gray-900 mt-2">{{ $kasus->kode_komponen }}</p>
                </div>

                <!-- Kejadian Fraud Menurut Pelaku -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Kejadian Fraud Menurut Pelaku</h3>
                    @if($kasus->kejadianFraud->count() > 0)
                        <div class="mt-2 space-y-2">
                            @foreach($kasus->kejadianFraud as $kejadian)
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="font-medium">
                                        @if($kejadian->kode)
                                            {{ $kejadian->kode }} - {{ $kejadian->nama }}
                                        @else
                                            {{ $kejadian->nama }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>ID Kejadian:</strong> {{ $kejadian->pivot->kode_kejadian ?? '-' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 mt-2">-</p>
                    @endif
                </div>

                <!-- Jenis Fraud -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Jenis Fraud</h3>
                    @if($kasus->jenisFraud->count() > 0)
                        <div class="mt-2 space-y-2">
                            @foreach($kasus->jenisFraud as $jenis)
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="font-medium">
                                        @if($jenis->kode)
                                            {{ $jenis->kode }} - {{ $jenis->nama }}
                                        @else
                                            {{ $jenis->nama }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Keterangan:</strong> {{ $jenis->pivot->keterangan ?? '-' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 mt-2">-</p>
                    @endif
                </div>

                <!-- Aktivitas Terkait Fraud -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Aktivitas Terkait Fraud</h3>
                    @if($kasus->aktivitasTerkait)
                        <p class="text-base font-medium text-gray-900 mt-2">
                            @if($kasus->aktivitasTerkait->kode)
                                {{ $kasus->aktivitasTerkait->kode }} - {{ $kasus->aktivitasTerkait->nama }}
                            @else
                                {{ $kasus->aktivitasTerkait->nama }}
                            @endif
                        </p>
                    @else
                        <p class="text-gray-400 mt-2">-</p>
                    @endif
                </div>

                <!-- Deskripsi Fraud / Modus Operandi -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Deskripsi Fraud / Modus Operandi</h3>
                    <p class="text-gray-700 leading-relaxed mt-2">{{ $kasus->deskripsi_fraud ?? '-' }}</p>
                </div>

                <!-- Lokasi Fraud -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Lokasi Fraud</h3>
                    @if($kasus->lokasiFraud->count() > 0)
                        <div class="mt-2 space-y-2">
                            @foreach($kasus->lokasiFraud as $lokasi)
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="font-medium">
                                        @if($lokasi->kode)
                                            {{ $lokasi->kode }} - {{ $lokasi->nama }}
                                        @else
                                            {{ $lokasi->nama }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Keterangan:</strong> {{ $lokasi->pivot->keterangan ?? '-' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 mt-2">-</p>
                    @endif
                </div>

                <!-- Divisi atau Unit Kerja -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud</h3>
                    <p class="text-base font-medium text-gray-900 mt-2">{{ $kasus->divisi_unit ?? '-' }}</p>
                </div>

                <!-- Pihak Yang Dirugikan -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Pihak Yang Dirugikan</h3>
                    @if($kasus->pihakDirugikan)
                        <p class="text-base font-medium text-gray-900 mt-2">
                            @if($kasus->pihakDirugikan->kode)
                                {{ $kasus->pihakDirugikan->kode }} - {{ $kasus->pihakDirugikan->nama }}
                            @else
                                {{ $kasus->pihakDirugikan->nama }}
                            @endif
                        </p>
                    @else
                        <p class="text-gray-400 mt-2">-</p>
                    @endif
                </div>

                <!-- Jenis Laporan -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Jenis Laporan</h3>
                    <p class="text-base font-medium text-gray-900 mt-2">{{ ucfirst($kasus->jenis_laporan ?? 'semester') }}</p>
                    @if($kasus->jenis_laporan === 'signifikan')
                        <p class="text-sm text-gray-600 mt-2"><strong>Tindak Lanjut LJK:</strong> {{ $kasus->tindak_lanjut_ljk ?? '-' }}</p>
                    @endif
                </div>

                <!-- Waktu -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Waktu</h3>
                    @if($kasus->waktuFraud)
                        <div class="mt-2 grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Waktu Awal</p>
                                <p class="text-base text-gray-900">{{ $kasus->waktuFraud && $kasus->waktuFraud->waktu_awal ? \Carbon\Carbon::parse($kasus->waktuFraud->waktu_awal)->format('Y-m-d') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Waktu Akhir</p>
                                <p class="text-base text-gray-900">{{ $kasus->waktuFraud && $kasus->waktuFraud->waktu_akhir ? \Carbon\Carbon::parse($kasus->waktuFraud->waktu_akhir)->format('Y-m-d') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Waktu Diketahui</p>
                                <p class="text-base text-gray-900">{{ $kasus->waktuFraud && $kasus->waktuFraud->waktu_diketahui ? \Carbon\Carbon::parse($kasus->waktuFraud->waktu_diketahui)->format('Y-m-d') : '-' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-400 mt-2">-</p>
                    @endif
                </div>

                <!-- Jumlah Kerugian -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Jumlah Kerugian</h3>
                    @if($kasus->kerugianFraud)
                        <div class="mt-2 overflow-x-auto">
                            <table class="min-w-full table-auto border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border border-gray-300 px-4 py-2 text-left">Kategori</th>
                                        <th class="border border-gray-300 px-4 py-2 text-right">Rill</th>
                                        <th class="border border-gray-300 px-4 py-2 text-right">Potensial</th>
                                        <th class="border border-gray-300 px-4 py-2 text-right">Recovery</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2 font-medium">LJK</td>
                                        <td class="border border-gray-300 px-4 py-2 text-right">{{ $kasus->jenis_laporan === 'signifikan' ? '-' : (optional($kasus->kerugianFraud)->ljk_rill !== null ? number_format(optional($kasus->kerugianFraud)->ljk_rill, 0, ',', '.') : '') }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-right">{{ optional($kasus->kerugianFraud)->ljk_potensial !== null ? number_format(optional($kasus->kerugianFraud)->ljk_potensial, 0, ',', '.') : '' }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-right">{{ $kasus->jenis_laporan === 'signifikan' ? '-' : (optional($kasus->kerugianFraud)->ljk_recovery !== null ? number_format(optional($kasus->kerugianFraud)->ljk_recovery, 0, ',', '.') : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2 font-medium">Konsumen</td>
                                        <td class="border border-gray-300 px-4 py-2 text-right">{{ $kasus->jenis_laporan === 'signifikan' ? '-' : (optional($kasus->kerugianFraud)->konsumen_rill !== null ? number_format(optional($kasus->kerugianFraud)->konsumen_rill, 0, ',', '.') : '') }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-right">{{ optional($kasus->kerugianFraud)->konsumen_potensial !== null ? number_format(optional($kasus->kerugianFraud)->konsumen_potensial, 0, ',', '.') : '' }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-right">{{ $kasus->jenis_laporan === 'signifikan' ? '-' : (optional($kasus->kerugianFraud)->konsumen_recovery !== null ? number_format(optional($kasus->kerugianFraud)->konsumen_recovery, 0, ',', '.') : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2 font-medium">Pihak Lain</td>
                                        <td class="border border-gray-300 px-4 py-2 text-right">{{ $kasus->jenis_laporan === 'signifikan' ? '-' : (optional($kasus->kerugianFraud)->pihak_lain_rill !== null ? number_format(optional($kasus->kerugianFraud)->pihak_lain_rill, 0, ',', '.') : '') }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-right">{{ optional($kasus->kerugianFraud)->pihak_lain_potensial !== null ? number_format(optional($kasus->kerugianFraud)->pihak_lain_potensial, 0, ',', '.') : '' }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-right">{{ $kasus->jenis_laporan === 'signifikan' ? '-' : (optional($kasus->kerugianFraud)->pihak_lain_recovery !== null ? number_format(optional($kasus->kerugianFraud)->pihak_lain_recovery, 0, ',', '.') : '') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-400 mt-2">-</p>
                    @endif
                </div>

                @if($kasus->jenis_laporan !== 'signifikan')
                <!-- Kelemahan Penyebab Fraud -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Kelemahan Penyebab Fraud</h3>
                    @if($kasus->kelemahanFraud->count() > 0)
                        <div class="mt-2 space-y-2">
                            @foreach($kasus->kelemahanFraud as $kelemahan)
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="font-medium">
                                        @if($kelemahan->kode)
                                            {{ $kelemahan->kode }} - {{ $kelemahan->nama }}
                                        @else
                                            {{ $kelemahan->nama }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Keterangan:</strong> {{ $kelemahan->pivot->keterangan ?? '-' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 mt-2">-</p>
                    @endif
                </div>

                <!-- Tindakan untuk Penanganan Fraud -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Tindakan untuk Penanganan Fraud</h3>
                    @if($kasus->penangananFraud->count() > 0)
                        <div class="mt-2 space-y-2">
                            @foreach($kasus->penangananFraud as $penanganan)
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="font-medium">
                                        @if($penanganan->kode)
                                            {{ $penanganan->kode }} - {{ $penanganan->nama }}
                                        @else
                                            {{ $penanganan->nama }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Keterangan:</strong> {{ $penanganan->pivot->keterangan ?? '-' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 mt-2">-</p>
                    @endif
                </div>

                <!-- Tindakan Perbaikan untuk Pencegahan Fraud -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Tindakan Perbaikan untuk Pencegahan Fraud</h3>
                    @if($kasus->pencegahanFraud->count() > 0)
                        <div class="mt-2 space-y-2">
                            @foreach($kasus->pencegahanFraud as $pencegahan)
                                <div class="bg-red-50 p-3 rounded border-l-4 border-red-400">
                                    <p class="font-medium">
                                        @if($pencegahan->refPencegahan && $pencegahan->refPencegahan->kode)
                                            {{ $pencegahan->refPencegahan->kode }} - {{ $pencegahan->refPencegahan->nama }}
                                        @elseif($pencegahan->refPencegahan)
                                            {{ $pencegahan->refPencegahan->nama }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                    <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <strong>Keterangan:</strong> {{ $pencegahan->keterangan ?? '-' }}
                                        </div>
                                        <div>
                                            <strong>Target Waktu:</strong> {{ $pencegahan->target_waktu ? \Carbon\Carbon::parse($pencegahan->target_waktu)->format('Y-m-d') : '-' }}
                                        </div>
                                        <div>
                                            <strong>Realisasi:</strong> {{ $pencegahan->realisasi ? \Carbon\Carbon::parse($pencegahan->realisasi)->format('Y-m-d') : '-' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 mt-2">-</p>
                    @endif
                </div>
                @endif

                <!-- Pelaku Fraud -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Pelaku Fraud</h3>
                    @if($kasus->pelakuFrauds->count() > 0)
                        <div class="mt-2 space-y-4">
                            @foreach($kasus->pelakuFrauds as $pelaku)
                                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Kategori</p>
                                                <p class="text-sm text-gray-900">{{ $pelaku->kategori ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Nama</p>
                                                <p class="text-sm text-gray-900">{{ $pelaku->nama ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Jenis Identitas</p>
                                                <p class="text-sm text-gray-900">
                                                    @if($pelaku->jenisIdentitas && $pelaku->jenisIdentitas->kode)
                                                        {{ $pelaku->jenisIdentitas->kode }} - {{ $pelaku->jenisIdentitas->nama }}
                                                    @elseif($pelaku->jenisIdentitas)
                                                        {{ $pelaku->jenisIdentitas->nama }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Nomor Identitas</p>
                                                <p class="text-sm text-gray-900">{{ $pelaku->nomor_identitas ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Jenis Kelamin</p>
                                                <p class="text-sm text-gray-900">{{ $pelaku->jenis_kelamin ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Tempat Lahir</p>
                                                <p class="text-sm text-gray-900">{{ $pelaku->tempat_lahir ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Tanggal Lahir</p>
                                                <p class="text-sm text-gray-900">{{ $pelaku->tanggal_lahir ? \Carbon\Carbon::parse($pelaku->tanggal_lahir)->format('Y-m-d') : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Alamat Identitas</p>
                                                <p class="text-sm text-gray-900">{{ $pelaku->alamat_identitas ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Alamat Domisili</p>
                                                <p class="text-sm text-gray-900">{{ $pelaku->alamat_domisili ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Jabatan Saat Kejadian</p>
                                                <p class="text-sm text-gray-900">
                                                    @if($pelaku->jabatanKejadian && $pelaku->jabatanKejadian->kode)
                                                        {{ $pelaku->jabatanKejadian->kode }} - {{ $pelaku->jabatanKejadian->nama }}
                                                    @elseif($pelaku->jabatanKejadian)
                                                        {{ $pelaku->jabatanKejadian->nama }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-600 mt-1">Keterangan: {{ $pelaku->ket_jabatan_kejadian ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Jabatan Saat Diketahui</p>
                                                <p class="text-sm text-gray-900">
                                                    @if($pelaku->jabatanDiketahui && $pelaku->jabatanDiketahui->kode)
                                                        {{ $pelaku->jabatanDiketahui->kode }} - {{ $pelaku->jabatanDiketahui->nama }}
                                                    @elseif($pelaku->jabatanDiketahui)
                                                        {{ $pelaku->jabatanDiketahui->nama }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-600 mt-1">Keterangan: {{ $pelaku->ket_jabatan_diketahui ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Keterangan Pelaku</p>
                                                <p class="text-sm text-gray-900">{{ $pelaku->keterangan ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Status Pelaku</p>
                                                <p class="text-sm text-gray-900">
                                                    @if($pelaku->statusPelaku && $pelaku->statusPelaku->kode)
                                                        {{ $pelaku->statusPelaku->kode }} - {{ $pelaku->statusPelaku->nama }}
                                                    @elseif($pelaku->statusPelaku)
                                                        {{ $pelaku->statusPelaku->nama }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Pengenaan Sanksi</p>
                                                <p class="text-sm text-gray-900">{{ $pelaku->sanksi ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 mt-2">-</p>
                    @endif
                </div>

                <!-- Status Penanganan -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Status Penanganan</h3>
                    <p class="text-base font-medium text-gray-900 mt-2">{{ $statusLabels[$kasus->status_penanganan] ?? $kasus->status_penanganan }}</p>
                </div>

                <!-- Metadata -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">Dibuat Pada</h3>
                            <p class="text-base font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($kasus->created_at)->format('d F Y, H:i') }} WIB
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">Diupdate Pada</h3>
                            <p class="text-base font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($kasus->updated_at)->format('d F Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex gap-3 justify-end">
        <a href="{{ route('kasus.index') }}"
            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>

        <a href="{{ route('kasus.edit', $kasus->id) }}"
            class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        </a>

        <form action="{{ route('kasus.destroy', $kasus->id) }}" method="POST" class="inline"
            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus
            </button>
        </form>
    </div>
</div>

@endsection
