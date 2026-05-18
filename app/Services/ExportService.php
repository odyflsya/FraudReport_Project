<?php

namespace App\Services;

use App\Models\Kasus;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ExportService
{
    /**
     * Get filtered kasus data based on criteria
     */
    public function getFilteredKasus(array $filters = []): Collection
    {
        $query = Kasus::with([
            'kejadianFraud',
            'jenisFraud',
            'aktivitasTerkait',
            'lokasiFraud',
            'pihakDirugikan',
            'waktuFraud',
            'kerugianFraud',
            'kelemahanFraud',
            'penangananFraud',
            'pencegahanFraud' => function($q) {
                $q->with('refPencegahan');
            },
            'pelakuFrauds' => function($q) {
                $q->with(['jenisIdentitas', 'statusPelaku', 'jabatanKejadian', 'jabatanDiketahui']);
            }
        ])->where('user_id', auth()->id());

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('kode_komponen', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi_fraud', 'like', '%' . $search . '%')
                  ->orWhere('divisi_unit', 'like', '%' . $search . '%')
                  ->orWhereHas('pelakuFrauds', function($pelakuQuery) use ($search) {
                      $pelakuQuery->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        if (!empty($filters['status_penanganan'])) {
            $query->where('status_penanganan', $filters['status_penanganan']);
        }

        if (!empty($filters['jenis_fraud'])) {
            $query->whereHas('jenisFraud', function($jenisQuery) use ($filters) {
                $jenisQuery->where('ref_jenis_fraud.id', $filters['jenis_fraud']);
            });
        }

        if (!empty($filters['jenis_laporan'])) {
            $query->where('jenis_laporan', $filters['jenis_laporan']);
        }

        if (!empty($filters['tanggal_awal']) || !empty($filters['tanggal_akhir'])) {
            $query->whereHas('waktuFraud', function($waktuQuery) use ($filters) {
                if (!empty($filters['tanggal_awal']) && !empty($filters['tanggal_akhir'])) {
                    $waktuQuery->whereDate('waktu_diketahui', '>=', $filters['tanggal_awal'])
                              ->whereDate('waktu_diketahui', '<=', $filters['tanggal_akhir']);
                } elseif (!empty($filters['tanggal_awal'])) {
                    $waktuQuery->whereDate('waktu_diketahui', '>=', $filters['tanggal_awal']);
                } elseif (!empty($filters['tanggal_akhir'])) {
                    $waktuQuery->whereDate('waktu_diketahui', '<=', $filters['tanggal_akhir']);
                }
            });
        }

        return $query->orderBy('created_at', 'asc')->get();
    }

    /**
     * Prepare data untuk export SEMESTER
     */
    private function getStatusLabels(): array
    {
        return [
            '001' => '001 (Proses internal LJK)',
            '002' => '002 (Selesai diproses internal LJK)',
            '003' => '003 (Dalam proses penanganan aparat penegak hukum)',
            '004' => '004 (Berkekuatan hukum tetap)',
        ];
    }

    private function sumExportNumbers(?int ...$values): ?int
    {
        $filtered = array_filter($values, function ($value) {
            return $value !== null;
        });

        if (empty($filtered)) {
            return null;
        }

        return array_sum($filtered);
    }

    public function prepareExportDataSemester(Collection $kasus): array
    {
        // Data sudah difilter di controller, tidak perlu filter lagi
        $semesterKasus = $kasus;
        
        $headers = [
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
            'Keterangan',
            'Tindakan untuk Penanganan Fraud',
            'Keterangan',
            'Tindakan Perbaikan untuk Pencegahan Fraud',
            'Keterangan',
            'Target Waktu Pelaksanaan',
            'Realisasi Pelaksanaan',
            'Internal/Eksternal',
            'Nama',
            'Jenis Identitas',
            'Nomor Identitas',
            'Jenis Kelamin',
            'Alamat Identitas',
            'Alamat Domisili',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Status Pelaku',
            'Pada Saat Fraud Terjadi',
            'Keterangan Jabatan',
            'Pada Saat Fraud Diketahui',
            'Keterangan Jabatan',
            'Keterangan Pelaku',
            'Pengenaan Sanksi',
            'Status Penanganan'
        ];

        $data = [];
        foreach ($semesterKasus as $index => $k) {
            $data[] = [
                'no' => $index + 1,
                'kode_komponen' => $k->kode_komponen ?? '-',
                'kejadian_fraud' => $k->kejadianFraud?->count() ? $k->kejadianFraud->map(function($item) {
                    return $item->kode ? $item->kode . ' (' . $item->nama . ')' : $item->nama;
                })->join("\n") : '-',
                'id_kejadian' => $k->kejadianFraud?->count() ? $k->kejadianFraud->pluck('pivot.kode_kejadian')->filter()->join("\n") : '-',
                'jenis_fraud' => $k->jenisFraud?->count() ? $k->jenisFraud->map(function($item) {
                    return $item->kode ? $item->kode . ' (' . $item->nama . ')' : $item->nama;
                })->join("\n") : '-',
                'keterangan_jenis' => $k->jenisFraud?->count() ? $k->jenisFraud->pluck('pivot.keterangan')->filter()->join("\n") : '-',
                'aktivitas_terkait' => $k->aktivitasTerkait ? ($k->aktivitasTerkait->kode ? $k->aktivitasTerkait->kode . ' (' . $k->aktivitasTerkait->nama . ')' : $k->aktivitasTerkait->nama) : '-',
                'deskripsi_fraud' => $k->deskripsi_fraud ?? '-',
                'lokasi_fraud' => $k->lokasiFraud?->count() ? $k->lokasiFraud->map(function($item) {
                    return $item->kode ? $item->kode . ' (' . $item->nama . ')' : $item->nama;
                })->join("\n") : '-',
                'keterangan_lokasi' => $k->lokasiFraud?->count() ? $k->lokasiFraud->pluck('pivot.keterangan')->filter()->join("\n") : '-',
                'divisi_unit' => $k->divisi_unit ?? '-',
                'pihak_dirugikan' => $k->pihakDirugikan ? ($k->pihakDirugikan->kode ? $k->pihakDirugikan->kode . ' (' . $k->pihakDirugikan->nama . ')' : $k->pihakDirugikan->nama) : '-',
                'waktu_awal' => $k->waktuFraud && $k->waktuFraud->waktu_awal ? \Carbon\Carbon::parse($k->waktuFraud->waktu_awal)->format('Y-m-d') : '-',
                'waktu_akhir' => $k->waktuFraud && $k->waktuFraud->waktu_akhir ? \Carbon\Carbon::parse($k->waktuFraud->waktu_akhir)->format('Y-m-d') : '-',
                'fraud_diketahui' => $k->waktuFraud && $k->waktuFraud->waktu_diketahui ? \Carbon\Carbon::parse($k->waktuFraud->waktu_diketahui)->format('Y-m-d') : '-',
                'ljk_rill' => $k->kerugianFraud?->ljk_rill,
                'ljk_potensial' => $k->kerugianFraud?->ljk_potensial,
                'ljk_recovery' => $k->kerugianFraud?->ljk_recovery,
                'konsumen_rill' => $k->kerugianFraud?->konsumen_rill,
                'konsumen_potensial' => $k->kerugianFraud?->konsumen_potensial,
                'konsumen_recovery' => $k->kerugianFraud?->konsumen_recovery,
                'pihak_lain_rill' => $k->kerugianFraud?->pihak_lain_rill,
                'pihak_lain_potensial' => $k->kerugianFraud?->pihak_lain_potensial,
                'pihak_lain_recovery' => $k->kerugianFraud?->pihak_lain_recovery,
                'kelemahan' => $k->kelemahanFraud?->count() ? $k->kelemahanFraud->map(function($item) {
                    return $item->kode ? $item->kode . ' (' . $item->nama . ')' : $item->nama;
                })->join("\n") : '-',
                'keterangan_kelemahan' => $k->kelemahanFraud?->count() ? $k->kelemahanFraud->pluck('pivot.keterangan')->filter()->join("\n") : '-',
                'tindakan_penanganan' => $k->penangananFraud?->count() ? $k->penangananFraud->map(function($item) {
                    return $item->kode ? $item->kode . ' (' . $item->nama . ')' : $item->nama;
                })->join("\n") : '-',
                'keterangan_penanganan' => $k->penangananFraud?->count() ? $k->penangananFraud->pluck('pivot.keterangan')->filter()->join("\n") : '-',
                'tindakan_perbaikan' => $k->pencegahanFraud?->count() ? $k->pencegahanFraud->map(function($item) {
                    return $item->refPencegahan ? ($item->refPencegahan->kode ? $item->refPencegahan->kode . ' (' . $item->refPencegahan->nama . ')' : $item->refPencegahan->nama) : '-';
                })->join("\n") : '-',
                'keterangan_perbaikan' => $k->pencegahanFraud?->count() ? $k->pencegahanFraud->pluck('keterangan')->filter()->join("\n") : '-',
                'target_waktu' => $k->pencegahanFraud?->count() ? $k->pencegahanFraud->map(function($item) {
                    return $item->target_waktu ? \Carbon\Carbon::parse($item->target_waktu)->format('Y-m-d') : '-';
                })->join("\n") : '-',
                'realisasi' => $k->pencegahanFraud?->count() ? $k->pencegahanFraud->map(function($item) {
                    return $item->realisasi ? \Carbon\Carbon::parse($item->realisasi)->format('Y-m-d') : '-';
                })->join("\n") : '-',
                'kategori_pelaku' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('kategori')->join("\n") : '-',
                'nama_pelaku' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('nama')->join("\n") : '-',
                'jenis_identitas' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->map(function($p) {
                    return $p->jenisIdentitas ? ($p->jenisIdentitas->kode ? $p->jenisIdentitas->kode . ' (' . $p->jenisIdentitas->nama . ')' : $p->jenisIdentitas->nama) : '-';
                })->join("\n") : '-',
                'nomor_identitas' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('nomor_identitas')->join("\n") : '-',
                'jenis_kelamin' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('jenis_kelamin_label')->join("\n") : '-',
                'alamat_identitas' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('alamat_identitas')->join("\n") : '-',
                'alamat_domisili' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('alamat_domisili')->join("\n") : '-',
                'tempat_lahir' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('tempat_lahir')->join("\n") : '-',
                'tanggal_lahir' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->map(function($p) {
                    return $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('Y-m-d') : '-';
                })->join("\n") : '-',
                'status_pelaku' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->map(function($p) {
                    return $p->statusPelaku ? ($p->statusPelaku->kode ? $p->statusPelaku->kode . ' (' . $p->statusPelaku->nama . ')' : $p->statusPelaku->nama) : '-';
                })->join("\n") : '-',
                                'jabatan_kejadian' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->map(function($p) {
                    return $p->jabatanKejadian ? ($p->jabatanKejadian->kode ? $p->jabatanKejadian->kode . ' (' . $p->jabatanKejadian->nama . ')' : $p->jabatanKejadian->nama) : '-';
                })->join("\n") : '-',
                'ket_jabatan_kejadian' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('ket_jabatan_kejadian')->join("\n") : '-',
                'jabatan_diketahui' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->map(function($p) {
                    return $p->jabatanDiketahui ? ($p->jabatanDiketahui->kode ? $p->jabatanDiketahui->kode . ' (' . $p->jabatanDiketahui->nama . ')' : $p->jabatanDiketahui->nama) : '-';
                })->join("\n") : '-',
                'ket_jabatan_diketahui' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('ket_jabatan_diketahui')->join("\n") : '-',
                'keterangan_pelaku' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('keterangan')->join("\n") : '-',
                'sanksi' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('sanksi')->map(function($s) { return $s ?? '-'; })->join("\n") : '-',
                'status_penanganan' => $this->getStatusLabels()[$k->status_penanganan] ?? ($k->status_penanganan ?? '-'),
            ];
        }

        return [
            'headers' => $headers,
            'data' => $data,
            'kasus' => $semesterKasus,
            'type' => 'semester'
        ];
    }

    public function prepareExportDataSignifikan(Collection $kasus): array
    {
        $signifikanKasus = $kasus;

        $headers = [
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
            'Nama',
            'Jenis Identitas',
            'Nomor Identitas',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat Identitas',
            'Alamat Domisili',
            'Status Pelaku',
            'Pada Saat Fraud Terjadi',
            'Keterangan Jabatan',
            'Pada Saat Fraud Diketahui',
            'Keterangan Jabatan',
            'Keterangan Pelaku',
            'Pengenaan Sanksi',
            'Status Penanganan'
        ];

        $data = [];
        foreach ($signifikanKasus as $index => $k) {
            $data[] = [
                'no' => $index + 1,
                'kode_komponen' => $k->kode_komponen ?? '-',
                'kejadian_fraud' => $k->kejadianFraud?->map(function($item) {
                    return $item->kode ? $item->kode . ' (' . $item->nama . ')' : $item->nama;
                })->join("\n") ?? '-',
                'id_kejadian' => $k->kejadianFraud?->pluck('pivot.kode_kejadian')->filter()->join("\n") ?? '-',
                'jenis_fraud' => $k->jenisFraud?->map(function($item) {
                    return $item->kode ? $item->kode . ' (' . $item->nama . ')' : $item->nama;
                })->join("\n") ?? '-',
                'keterangan_jenis' => $k->jenisFraud?->pluck('pivot.keterangan')->filter()->join("\n") ?? '-',
                'aktivitas_terkait' => $k->aktivitasTerkait ? ($k->aktivitasTerkait->kode ? $k->aktivitasTerkait->kode . ' (' . $k->aktivitasTerkait->nama . ')' : $k->aktivitasTerkait->nama) : '-',
                'deskripsi_fraud' => $k->deskripsi_fraud ?? '-',
                'lokasi_fraud' => $k->lokasiFraud?->map(function($item) {
                    return $item->kode ? $item->kode . ' (' . $item->nama . ')' : $item->nama;
                })->join("\n") ?? '-',
                'keterangan_lokasi' => $k->lokasiFraud?->pluck('pivot.keterangan')->filter()->join("\n") ?? '-',
                'divisi_unit' => $k->divisi_unit ?? '-',
                'pihak_dirugikan' => $k->pihakDirugikan ? ($k->pihakDirugikan->kode ? $k->pihakDirugikan->kode . ' (' . $k->pihakDirugikan->nama . ')' : $k->pihakDirugikan->nama) : '-',
                'kerugian_potensial' => $this->sumExportNumbers(
                    $k->kerugianFraud?->ljk_potensial,
                    $k->kerugianFraud?->konsumen_potensial,
                    $k->kerugianFraud?->pihak_lain_potensial
                ),
                'tindak_lanjut_ljk' => $k->tindak_lanjut_ljk ?? '-',
                'waktu_awal' => $k->waktuFraud && $k->waktuFraud->waktu_awal ? \Carbon\Carbon::parse($k->waktuFraud->waktu_awal)->format('Y-m-d') : '-',
                'waktu_akhir' => $k->waktuFraud && $k->waktuFraud->waktu_akhir ? \Carbon\Carbon::parse($k->waktuFraud->waktu_akhir)->format('Y-m-d') : '-',
                'fraud_diketahui' => $k->waktuFraud && $k->waktuFraud->waktu_diketahui ? \Carbon\Carbon::parse($k->waktuFraud->waktu_diketahui)->format('Y-m-d') : '-',
                'kategori_pelaku' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('kategori')->join("\n") : '-',
                'nama_pelaku' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('nama')->join("\n") : '-',
                'jenis_identitas' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->map(function($p) {
                    return $p->jenisIdentitas ? ($p->jenisIdentitas->kode ? $p->jenisIdentitas->kode . ' (' . $p->jenisIdentitas->nama . ')' : $p->jenisIdentitas->nama) : '-';
                })->join("\n") : '-',
                'nomor_identitas' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('nomor_identitas')->join("\n") : '-',
                'jenis_kelamin' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('jenis_kelamin_label')->join("\n") : '-',
                'tempat_lahir' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('tempat_lahir')->join("\n") : '-',
                'tanggal_lahir' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->map(function($p) {
                    return $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('Y-m-d') : '-';
                })->join("\n") : '-',
                'alamat_identitas' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('alamat_identitas')->join("\n") : '-',
                'alamat_domisili' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('alamat_domisili')->join("\n") : '-',
                'status_pelaku' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->map(function($p) {
                    return $p->statusPelaku ? ($p->statusPelaku->kode ? $p->statusPelaku->kode . ' (' . $p->statusPelaku->nama . ')' : $p->statusPelaku->nama) : '-';
                })->join("\n") : '-',
                'jabatan_kejadian' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->map(function($p) {
                    return $p->jabatanKejadian ? ($p->jabatanKejadian->kode ? $p->jabatanKejadian->kode . ' (' . $p->jabatanKejadian->nama . ')' : $p->jabatanKejadian->nama) : '-';
                })->join("\n") : '-',
                'ket_jabatan_kejadian' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('ket_jabatan_kejadian')->join("\n") : '-',
                'jabatan_diketahui' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->map(function($p) {
                    return $p->jabatanDiketahui ? ($p->jabatanDiketahui->kode ? $p->jabatanDiketahui->kode . ' (' . $p->jabatanDiketahui->nama . ')' : $p->jabatanDiketahui->nama) : '-';
                })->join("\n") : '-',
                'ket_jabatan_diketahui' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('ket_jabatan_diketahui')->join("\n") : '-',
                'keterangan_pelaku' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('keterangan')->join("\n") : '-',
                'sanksi' => $k->pelakuFrauds?->count() ? $k->pelakuFrauds->pluck('sanksi')->map(fn($s) => $s ?? '-')->join("\n") : '-',
                'status_penanganan' => $this->getStatusLabels()[$k->status_penanganan] ?? ($k->status_penanganan ?? '-'),
            ];
        }

        return [
            'headers' => $headers,
            'data' => $data,
            'kasus' => $signifikanKasus,
            'type' => 'signifikan'
        ];
    }

    /**
     * Get summary statistics
     */
    public function getSummary(Collection $kasus): array
    {
        $totalKerugian = $kasus->sum(function($k) {
            return ($k->kerugianFraud?->ljk_rill ?? 0) +
                   ($k->kerugianFraud?->ljk_potensial ?? 0) +
                   ($k->kerugianFraud?->konsumen_rill ?? 0) +
                   ($k->kerugianFraud?->konsumen_potensial ?? 0) +
                   ($k->kerugianFraud?->pihak_lain_rill ?? 0) +
                   ($k->kerugianFraud?->pihak_lain_potensial ?? 0);
        });

        return [
            'total_kasus' => $kasus->count(),
            'semester_count' => $kasus->where('jenis_laporan', 'semester')->count(),
            'signifikan_count' => $kasus->where('jenis_laporan', 'signifikan')->count(),
            'total_kerugian' => $totalKerugian,
            'selesai' => $kasus->whereIn('status_penanganan', ['002', '004'])->count(),
            'dalam_proses' => $kasus->whereNotIn('status_penanganan', ['002', '004'])->count(),
            'total_pelaku' => $kasus->sum(fn($k) => $k->pelakuFrauds->count()),
        ];
    }
}
