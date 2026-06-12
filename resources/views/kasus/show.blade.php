@extends('layouts.app')

@php
    $formatTanggal = function ($value) {
        if (!$value) {
            return '-';
        }
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return '-';
        }
    };
@endphp

@section('content')
<div class="min-h-screen bg-gray-100 p-4">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Kasus Fraud</h1>
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
        $jenisKelaminLabel = function ($value) {
        $value = strtoupper(trim((string) ($value ?? '')));
        if ($value === 'L') return 'L (Laki-laki)';
        if ($value === 'P') return 'P (Perempuan)';
        return $value !== '' ? $value : '-';
    };
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
                                            {{ $kejadian->kode }} ({{ $kejadian->nama }})
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
                                            {{ $jenis->kode }} ({{ $jenis->nama }})
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
                                {{ $kasus->aktivitasTerkait->kode }} ({{ $kasus->aktivitasTerkait->nama }})
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
                                            {{ $lokasi->kode }} ({{ $lokasi->nama }})
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
                                {{ $kasus->pihakDirugikan->kode }} ({{ $kasus->pihakDirugikan->nama }})
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
                    @if(in_array($kasus->jenis_laporan, ['signifikan', 'non-signifikan']))
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
                                <p class="text-base text-gray-900">{{ $formatTanggal($kasus->waktuFraud?->waktu_awal) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Waktu Akhir</p>
                                <p class="text-base text-gray-900">{{ $formatTanggal($kasus->waktuFraud?->waktu_akhir) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Waktu Fraud Diketahui</p>
                                <p class="text-base text-gray-900">{{ $formatTanggal($kasus->waktuFraud?->waktu_diketahui) }}</p>
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
                                        <th class="border border-gray-300 px-4 py-2 text-right">Riil (incurred)</th>
                                        <th class="border border-gray-300 px-4 py-2 text-right">Potensial (Potential)</th>
                                        <th class="border border-gray-300 px-4 py-2 text-right">Setelah Pengembalian (Recovery)</th>
                                    </tr>
                                </thead>
<tbody>
    @php
        $categories = [
            'LJK' => ['key' => 'ljk','rill' => 'ljk_rill', 'potensial' => 'ljk_potensial', 'recovery' => 'ljk_recovery'],
            'Konsumen' => ['key' => 'konsumen','rill' => 'konsumen_rill', 'potensial' => 'konsumen_potensial', 'recovery' => 'konsumen_recovery'],
            'Pihak Lain' => ['key' => 'pihak_lain','rill' => 'pihak_lain_rill', 'potensial' => 'pihak_lain_potensial', 'recovery' => 'pihak_lain_recovery'],
        ];
    @endphp

    @foreach($categories as $label => $fields)
    <tr>
        <td class="border border-gray-300 px-4 py-2 font-medium">{{ $label }}</td>

        <!-- Kolom Rill -->
        <td class="border border-gray-300 px-4 py-2 text-right">
            @php $rillVal = $kasus->kerugianFraud->{$fields['rill']} ?? 0; @endphp
            @if($kasus->jenis_laporan === 'semester' && $rillVal > 0)
                <button type="button" class="text-right text-blue-700 hover:text-blue-900" onclick="openKerugianDetailsModal('{{ $fields['key'] }}','riil')">{{ number_format($rillVal, 0, ',', '.') }}</button>
            @endif
        </td>

        <!-- Kolom Potensial -->
        <td class="border border-gray-300 px-4 py-2 text-right">
            @php $potVal = $kasus->kerugianFraud->{$fields['potensial']} ?? 0; @endphp
            @if($potVal > 0)
                <button type="button" class="text-right text-blue-700 hover:text-blue-900" onclick="openKerugianDetailsModal('{{ $fields['key'] }}','potensial')">{{ number_format($potVal, 0, ',', '.') }}</button>
            @endif
        </td>

        <!-- Kolom Setelah Recovery -->
        <td class="border border-gray-300 px-4 py-2 text-right">
            @php
                $outstandingVal = $kasus->kerugianFraud->getOutstandingForKategori($fields['key']);
                $hasRecoveryHistory = $kasus->kerugianFraud->getRecoveryTotalForKategori($fields['key']) > 0;
            @endphp
            @if($kasus->jenis_laporan === 'semester' && ($outstandingVal > 0 || $hasRecoveryHistory))
                <button type="button" class="text-right text-blue-700 hover:text-blue-900" onclick="openRecoveryDetailsModal()">
                    {{ number_format($outstandingVal, 0, ',', '.') }}
                </button>
            @else
                {{ $outstandingVal > 0 ? number_format($outstandingVal, 0, ',', '.') : '' }}
            @endif
        </td>
    </tr>
    @endforeach
</tbody>
                            </table>
                        </div>
                        @php
                            $ljk_rill = $kasus->kerugianFraud->ljk_rill ?? 0;
                            $konsumen_rill = $kasus->kerugianFraud->konsumen_rill ?? 0;
                            $pihak_lain_rill = $kasus->kerugianFraud->pihak_lain_rill ?? 0;

                            $ljk_pot = $kasus->kerugianFraud->ljk_potensial ?? 0;
                            $konsumen_pot = $kasus->kerugianFraud->konsumen_potensial ?? 0;
                            $pihak_lain_pot = $kasus->kerugianFraud->pihak_lain_potensial ?? 0;

                            $total_all = $kasus->kerugianFraud->getTotalOutstanding();
                            $recoveryHistory = $kasus->kerugianFraud->getRecoveryHistoryWithRunningTotals();
                        @endphp

                        <div class="mt-4 rounded bg-gray-50 p-4">
                            <p class="text-sm text-gray-600">Total Kerugian Tersisa (Riil + Potensial - Recovery)</p>
                            <p class="text-xl font-semibold text-gray-900">
                                @if($kasus->kerugianFraud->getTotalRecovery() > 0)
                                    <button type="button" class="text-blue-700 hover:text-blue-900" onclick="openRecoveryDetailsModal()">
                                        Rp {{ number_format($total_all, 0, ',', '.') }}
                                    </button>
                                @else
                                    Rp {{ number_format($total_all, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>

                        <!-- Recovery History Modal -->
                        <div id="recoveryDetailsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 py-6">
                            <div class="w-full max-w-3xl overflow-hidden rounded-3xl bg-white shadow-2xl">
                                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                                    <div>
                                        <h3 class="text-xl font-semibold text-slate-900">Histori Recovery</h3>
                                        <p class="text-sm text-slate-500">Perkembangan kerugian setelah setiap pengembalian dana.</p>
                                    </div>
                                    <button id="closeRecoveryModal" type="button" class="rounded-full border border-slate-300 bg-white px-3 py-2 text-slate-700 hover:bg-slate-100">Tutup</button>
                                </div>
                                <div class="max-h-[60vh] overflow-y-auto px-6 py-5">
                                    @if(count($recoveryHistory) > 0)
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full text-sm border-collapse">
                                                <thead>
                                                    <tr class="bg-gray-100">
                                                        <th class="border border-gray-200 px-3 py-2 text-left">Tanggal</th>
                                                        <th class="border border-gray-200 px-3 py-2 text-left">Kategori</th>
                                                        <th class="border border-gray-200 px-3 py-2 text-left">Nomor Rekening</th>
                                                        <th class="border border-gray-200 px-3 py-2 text-right">Nominal Recovery</th>
                                                        <th class="border border-gray-200 px-3 py-2 text-right">Total Kerugian Setelah Recovery</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recoveryHistory as $row)
                                                        <tr>
                                                            <td class="border border-gray-200 px-3 py-2">
                                                                {{ $formatTanggal($row['tanggal'] ?? null) }}
                                                            </td>
                                                            <td class="border border-gray-200 px-3 py-2">{{ strtoupper($row['kategori'] ?? '-') }}</td>
                                                            <td class="border border-gray-200 px-3 py-2">{{ $row['no_rekening'] ?? '-' }}</td>
                                                            <td class="border border-gray-200 px-3 py-2 text-right font-medium">
                                                                Rp {{ number_format($row['amount'], 0, ',', '.') }}
                                                            </td>
                                                            <td class="border border-gray-200 px-3 py-2 text-right font-semibold text-red-700">
                                                                Rp {{ number_format($row['total_outstanding'], 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="mt-4 text-xs text-slate-500">
                                            Total Kerugian = (Kerugian Riil + Kerugian Potensial) - Akumulasi Recovery
                                        </p>
                                    @else
                                        <p class="text-sm text-slate-600">Belum ada riwayat recovery.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <script>
                            function openRecoveryDetailsModal() {
                                var modal = document.getElementById('recoveryDetailsModal');
                                if (modal) {
                                    modal.classList.remove('hidden');
                                    modal.classList.add('flex');
                                }
                            }
                            document.addEventListener('DOMContentLoaded', function () {
                                var modal = document.getElementById('recoveryDetailsModal');
                                var closeModal = document.getElementById('closeRecoveryModal');
                                if (modal && closeModal) {
                                    closeModal.addEventListener('click', function () {
                                        modal.classList.add('hidden');
                                        modal.classList.remove('flex');
                                    });
                                    modal.addEventListener('click', function (event) {
                                        if (event.target === modal) {
                                            modal.classList.add('hidden');
                                            modal.classList.remove('flex');
                                        }
                                    });
                                }
                            });
                        </script>

                        <!-- Kerugian Details Modal (read-only) -->
                        <div id="kerugianDetailsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 py-6">
                            <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">
                                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                                    <h3 id="kerugianDetailsTitle" class="text-lg font-semibold text-slate-900">Rincian Kerugian</h3>
                                    <button id="closeKerugianModal" type="button" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm text-slate-700 hover:bg-slate-50">Tutup</button>
                                </div>
                                <div class="max-h-[60vh] overflow-y-auto px-5 py-4">
                                    <div class="overflow-x-auto rounded-lg border border-slate-200">
                                        <table class="min-w-full text-sm">
                                            <thead class="bg-slate-50">
                                                <tr>
                                                    <th class="border border-gray-200 px-3 py-2 text-left">No</th>
                                                    <th class="border border-gray-200 px-3 py-2 text-left">Nominal</th>
                                                    <th class="border border-gray-200 px-3 py-2 text-left">Nomor Rekening</th>
                                                    <th class="border border-gray-200 px-3 py-2 text-left">Tanggal Dibuat</th>
                                                </tr>
                                            </thead>
                                            <tbody id="kerugianDetailsTableBody">
                                                <tr>
                                                    <td colspan="4" class="px-3 py-6 text-center text-slate-500">Belum ada rincian.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p id="kerugianDetailsTotal" class="mt-4 text-right text-sm font-semibold text-slate-800"></p>
                                </div>
                            </div>
                        </div>

@php
    $kerugianDetailsData = ($kasus->kerugianFraud ? $kasus->kerugianFraud->details : collect())
        ->map(function ($d) {
            return [
                'kategori' => $d->kategori,
                'tipe' => $d->tipe,
                'nominal' => $d->nominal,
                'no_rekening' => $d->no_rekening,
                'created_at' => $d->created_at ? $d->created_at->format('Y-m-d') : null,
            ];
        })
        ->values()
        ->toArray();
@endphp

                          <script>
    // Data rincian kerugian dari Laravel ke Javascript
    window.kerugianDetailsData = @json($kerugianDetailsData);

    // Event modal
    document.addEventListener('DOMContentLoaded', function () {
        const kModal = document.getElementById('kerugianDetailsModal');
        const kClose = document.getElementById('closeKerugianModal');

        if (kClose) {
            kClose.addEventListener('click', function () {
                kModal.classList.add('hidden');
                kModal.classList.remove('flex');
            });
        }

        if (kModal) {
            kModal.addEventListener('click', function (e) {
                if (e.target === kModal) {
                    kModal.classList.add('hidden');
                    kModal.classList.remove('flex');
                }
            });
        }
    });

    // HARUS GLOBAL supaya bisa dipanggil dari onclick=""
    function openKerugianDetailsModal(kategori, tipe) {

        const modal = document.getElementById('kerugianDetailsModal');
        const title = document.getElementById('kerugianDetailsTitle');
        const tbody = document.getElementById('kerugianDetailsTableBody');
        const totalEl = document.getElementById('kerugianDetailsTotal');

        const tipeLabel =
            tipe === 'riil'
                ? 'Riil'
                : (tipe === 'potensial'
                    ? 'Potensial'
                    : tipe);

        title.textContent =
            'Rincian Kerugian ' +
            (kategori || '').toUpperCase() +
            ' (' +
            tipeLabel +
            ')';

        tbody.innerHTML = '';

        const list = (window.kerugianDetailsData || []).filter(function (item) {
            return item.kategori === kategori && item.tipe === tipe;
        });

        if (list.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="px-3 py-6 text-center text-slate-500">
                        Belum ada rincian untuk kategori ini.
                    </td>
                </tr>
            `;

            totalEl.textContent = '';
        } else {

            let total = 0;

            list.forEach(function (item, index) {

                const nominal = parseInt(item.nominal || 0);

                total += nominal;

                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50">
                        <td class="border-b px-3 py-2">
                            ${index + 1}
                        </td>

                        <td class="border-b px-3 py-2 text-right font-medium">
                            Rp ${new Intl.NumberFormat('id-ID').format(nominal)}
                        </td>

                        <td class="border-b px-3 py-2">
                            ${item.no_rekening ?? '-'}
                        </td>

                        <td class="border-b px-3 py-2">
                        ${item.created_at ?? '-'}
                        </td>
                    </tr>
                `;
            });

            totalEl.textContent =
                'Total Keseluruhan: Rp ' +
                new Intl.NumberFormat('id-ID').format(total);
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
</script>
                    @else
                        <p class="text-gray-400 mt-2">-</p>
                    @endif
                </div>

                @if($kasus->jenis_laporan === 'semester')
                <!-- Kelemahan Penyebab Fraud -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Kelemahan Penyebab Fraud</h3>
                    @if($kasus->kelemahanFraud->count() > 0)
                        <div class="mt-2 space-y-2">
                            @foreach($kasus->kelemahanFraud as $kelemahan)
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="font-medium">
                                        @if($kelemahan->kode)
                                            {{ $kelemahan->kode }} ({{ $kelemahan->nama }})
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
                                            {{ $penanganan->kode }} ({{ $penanganan->nama }})
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
                                            {{ $pencegahan->refPencegahan->kode }} ({{ $pencegahan->refPencegahan->nama }})
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
                                            <strong>Target Waktu:</strong> {{ $formatTanggal($pencegahan->target_waktu) }}
                                        </div>
                                        <div>
                                            <strong>Realisasi:</strong> {{ $formatTanggal($pencegahan->realisasi) }}
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
                                                <p class="text-sm text-gray-900">{{ $pelaku->kategori_label ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Nama</p>
                                                <p class="text-sm text-gray-900">{{ $pelaku->nama ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Jenis Identitas</p>
                                                <p class="text-sm text-gray-900">
                                                    @if($pelaku->jenisIdentitas && $pelaku->jenisIdentitas->kode)
                                                        {{ $pelaku->jenisIdentitas->kode }} ({{ $pelaku->jenisIdentitas->nama }})
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
                                                <p class="text-sm text-gray-900">{{ $jenisKelaminLabel($pelaku->jenis_kelamin) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Tempat Lahir</p>
                                                <p class="text-sm text-gray-900">{{ $pelaku->tempat_lahir ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Tanggal Lahir</p>
                                                <p class="text-sm text-gray-900">{{ $formatTanggal($pelaku->tanggal_lahir) }}</p>
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
                                                        {{ $pelaku->jabatanKejadian->kode }} ({{ $pelaku->jabatanKejadian->nama }})
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
                                                        {{ $pelaku->jabatanDiketahui->kode }} ({{ $pelaku->jabatanDiketahui->nama }})
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
                                                        {{ $pelaku->statusPelaku->kode }} ({{ $pelaku->statusPelaku->nama }})
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
                                {{ $formatTanggal($kasus->created_at) }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">Diupdate Pada</h3>
                            <p class="text-base font-medium text-gray-900">
                                {{ $formatTanggal($kasus->updated_at) }}
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
