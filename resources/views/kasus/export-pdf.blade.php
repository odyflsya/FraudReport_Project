<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Kasus Fraud</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.15;
            color: #333;
            font-size: 7.25pt;
        }
        
        @page {
            size: A4 landscape;
            margin: 8mm;
        }
        
        html, body {
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #dc2626;
            padding-bottom: 6px;
        }
        
        .header h1 {
            font-size: 14pt;
            margin-bottom: 2px;
            color: #dc2626;
        }
        
        .header p {
            font-size: 7pt;
            color: #666;
        }
        
        .filter-info {
            margin-bottom: 8px;
            padding: 5px;
            background-color: #f3f4f6;
            border-left: 3px solid #dc2626;
            font-size: 7pt;
        }
        
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            color: white;
            background-color: #dc2626;
            padding: 5px;
            margin-top: 12px;
            margin-bottom: 6px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 6.5pt;
            table-layout: auto;
        }
        
        thead {
            background-color: #f3f4f6;
            display: table-header-group;
        }
        
        th, td {
            padding: 1px;
            text-align: left;
            border: 1px solid #d1d5db;
            font-size: 6.5pt;
            word-break: normal;
            overflow-wrap: break-word;
            white-space: normal;
            vertical-align: top;
        }
        
        tbody tr:nth-child(odd) {
            background-color: #fafafa;
        }

        table, tr, th, td {
            page-break-inside: avoid;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .summary {
            margin-top: 20px;
            page-break-before: avoid;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 8px;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            font-size: 9pt;
        }
        
        .summary-label {
            font-weight: 600;
            width: 40%;
        }
        
        .summary-value {
            font-weight: 700;
            color: #dc2626;
            text-align: center;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8pt;
            color: #999;
            border-top: 1px solid #d1d5db;
            padding-top: 8px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .no-data {
            text-align: center;
            padding: 15px;
            color: #999;
            font-style: italic;
            background-color: #f9fafb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Laporan Kasus Fraud</h1>
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>

    <div class="filter-info">
        <strong>Filter Laporan:</strong>
        Periode: <strong>{{ $dari_tanggal !== '-' ? $dari_tanggal : 'Semua' }}</strong> s/d <strong>{{ $sampai_tanggal !== '-' ? $sampai_tanggal : 'Semua' }}</strong>
        @if(!empty($filters['jenis_laporan']))
            | Jenis: <strong>{{ ucfirst($filters['jenis_laporan']) }}</strong>
        @endif
        @if(!empty($filters['status_penanganan']))
            | Status: <strong>{{ $filters['status_penanganan'] === 'selesai' ? 'Selesai' : 'Dalam Proses' }}</strong>
        @endif
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

        $reportType = $reportType ?? ($filters['jenis_laporan'] ?? null);
    @endphp

    <!-- LAPORAN SEMESTER -->
    @if($reportType !== 'signifikan')
        @if(count($semesterData['data']) > 0)
            <div class="section-title">LAPORAN SEMESTER</div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Komponen</th>
                    <th>Kejadian Fraud Menurut Pelaku</th>
                    <th>ID Kejadian Fraud</th>
                    <th>Jenis Fraud</th>
                    <th>Keterangan Jenis Fraud</th>
                    <th>Aktivitas Terkait Fraud</th>
                    <th>Deskripsi Fraud / Modus Operandi</th>
                    <th>Lokasi Fraud</th>
                    <th>Keterangan Lokasi Fraud</th>
                    <th>Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud</th>
                    <th>Pihak Yang Dirugikan</th>
                    <th>Waktu Terjadi Awal</th>
                    <th>Waktu Terjadi Akhir</th>
                    <th>Fraud Diketahui</th>
                    <th>LJK Rill</th>
                    <th>LJK Potensial</th>
                    <th>LJK Recovery</th>
                    <th>Konsumen Rill</th>
                    <th>Konsumen Potensial</th>
                    <th>Konsumen Recovery</th>
                    <th>Pihak Lain Rill</th>
                    <th>Pihak Lain Potensial</th>
                    <th>Pihak Lain Recovery</th>
                    <th>Kelemahan Penyebab Fraud</th>
                    <th>Keterangan</th>
                    <th>Tindakan untuk Penanganan Fraud</th>
                    <th>Keterangan</th>
                    <th>Tindakan Perbaikan untuk Pencegahan Fraud</th>
                    <th>Keterangan</th>
                    <th>Target Waktu Pelaksanaan</th>
                    <th>Realisasi Pelaksanaan</th>
                    <th>Internal/Eksternal</th>
                    <th>Nama</th>
                    <th>Jenis Identitas</th>
                    <th>Nomor Identitas</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat Identitas</th>
                    <th>Alamat Domisili</th>
                    <th>Pada Saat Fraud Terjadi</th>
                    <th>Keterangan</th>
                    <th>Pada Saat Fraud Diketahui</th>
                    <th>Keterangan</th>
                    <th>Keterangan Pelaku</th>
                    <th>Status Pelaku</th>
                    <th>Pengenaan Sanksi</th>
                    <th>Status Penanganan</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($semesterData['kasus'] as $k)
                    <tr class="align-top">
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
                            {{ $k->aktivitasTerkait ? ($k->aktivitasTerkait->kode ? $k->aktivitasTerkait->kode . ' (' . $k->aktivitasTerkait->nama . ')' : $k->aktivitasTerkait->nama) : '-' }}
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
                            {{ $k->pihakDirugikan ? ($k->pihakDirugikan->kode ? $k->pihakDirugikan->kode . ' (' . $k->pihakDirugikan->nama . ')' : $k->pihakDirugikan->nama) : '-' }}
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
                        <td class="border p-2">{{ $k->kerugianFraud->ljk_rill !== null ? number_format($k->kerugianFraud->ljk_rill, 0, ',', '.') : '' }}</td>
                        <td class="border p-2">{{ $k->kerugianFraud->ljk_potensial !== null ? number_format($k->kerugianFraud->ljk_potensial, 0, ',', '.') : '' }}</td>
                        <td class="border p-2">{{ $k->kerugianFraud->ljk_recovery !== null ? number_format($k->kerugianFraud->ljk_recovery, 0, ',', '.') : '' }}</td>
                        <td class="border p-2">{{ $k->kerugianFraud->konsumen_rill !== null ? number_format($k->kerugianFraud->konsumen_rill, 0, ',', '.') : '' }}</td>
                        <td class="border p-2">{{ $k->kerugianFraud->konsumen_potensial !== null ? number_format($k->kerugianFraud->konsumen_potensial, 0, ',', '.') : '' }}</td>
                        <td class="border p-2">{{ $k->kerugianFraud->konsumen_recovery !== null ? number_format($k->kerugianFraud->konsumen_recovery, 0, ',', '.') : '' }}</td>
                        <td class="border p-2">{{ $k->kerugianFraud->pihak_lain_rill !== null ? number_format($k->kerugianFraud->pihak_lain_rill, 0, ',', '.') : '' }}</td>
                        <td class="border p-2">{{ $k->kerugianFraud->pihak_lain_potensial !== null ? number_format($k->kerugianFraud->pihak_lain_potensial, 0, ',', '.') : '' }}</td>
                        <td class="border p-2">{{ $k->kerugianFraud->pihak_lain_recovery !== null ? number_format($k->kerugianFraud->pihak_lain_recovery, 0, ',', '.') : '' }}</td>
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
                            {{ $k->status_penanganan }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
            <div class="section-title">LAPORAN SEMESTER</div>
            <div class="no-data">Tidak ada data laporan semester</div>
        @endif
    @endif


    <!-- LAPORAN SIGNIFIKAN -->
    @if($reportType !== 'semester')
        @if(count($signifikanData['data']) > 0)
            <div class="section-title">LAPORAN SIGNIFIKAN</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Komponen</th>
                    <th>Kejadian Fraud Menurut Pelaku</th>
                    <th>ID Kejadian Fraud</th>
                    <th>Jenis Fraud</th>
                    <th>Keterangan Jenis Fraud</th>
                    <th>Aktivitas Terkait Fraud</th>
                    <th>Deskripsi Fraud / Modus Operandi</th>
                    <th>Lokasi Fraud</th>
                    <th>Keterangan Lokasi Fraud</th>
                    <th>Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud</th>
                    <th>Pihak Yang Dirugikan</th>
                    <th>Jumlah Kerugian Potensial</th>
                    <th>Tindak Lanjut LJK</th>
                    <th>Waktu Terjadi Awal</th>
                    <th>Waktu Terjadi Akhir</th>
                    <th>Fraud Diketahui</th>
                    <th>Internal/Eksternal</th>
                    <th>Nama</th>
                    <th>Jenis Identitas</th>
                    <th>Nomor Identitas</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat Identitas</th>
                    <th>Alamat Domisili</th>
                    <th>Pada Saat Fraud Terjadi</th>
                    <th>Keterangan</th>
                    <th>Pada Saat Fraud Diketahui</th>
                    <th>Keterangan</th>
                    <th>Status Pelaku</th>
                    <th>Pengenaan Sanksi</th>
                    <th>Status Penanganan</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($signifikanData['kasus'] as $k)
                    <tr class="align-top">
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
                            {{ $k->aktivitasTerkait ? ($k->aktivitasTerkait->kode ? $k->aktivitasTerkait->kode . ' (' . $k->aktivitasTerkait->nama . ')' : $k->aktivitasTerkait->nama) : '-' }}
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
                            {{ $k->pihakDirugikan ? ($k->pihakDirugikan->kode ? $k->pihakDirugikan->kode . ' (' . $k->pihakDirugikan->nama . ')' : $k->pihakDirugikan->nama) : '-' }}
                        </td>
                        <td class="border p-2">{{ $k->kerugianFraud->ljk_potensial !== null ? number_format($k->kerugianFraud->ljk_potensial, 0, ',', '.') : '' }}</td>
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
                            {{ $k->status_penanganan }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
            <div class="section-title">LAPORAN SIGNIFIKAN</div>
            <div class="no-data">Tidak ada data laporan signifikan</div>
        @endif
    @endif

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh Sistem Manajemen Fraud Report</p>
        <p>{{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
