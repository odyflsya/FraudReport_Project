<?php

namespace App\Services;

use App\Models\Kasus;
use App\Models\KerugianRecovery;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardAnalyticsService
{
    protected $userId;
    protected $year;
    protected $month;

    public function __construct($userId, $year = null, $month = null)
    {
        $this->userId = $userId;
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * Get base query with filters applied
     */
    private function getBaseQuery()
    {
        $query = Kasus::where('user_id', $this->userId)
            ->where('jenis_laporan', 'semester');

        if ($this->year || $this->month) {
            $query->leftJoin('waktu_fraud', 'kasus.id', '=', 'waktu_fraud.kasus_id');
            
            if ($this->year) {
                $query->whereYear('waktu_fraud.waktu_diketahui', $this->year);
            }

            if ($this->month && $this->year) {
                $query->whereMonth('waktu_fraud.waktu_diketahui', $this->month);
            }
        }

        return $query;
    }

    /**
     * Get base query with join to waktuFraud
     */
    private function getBaseQueryWithDate()
    {
        return $this->getBaseQuery()
            ->leftJoin('waktu_fraud', 'kasus.id', '=', 'waktu_fraud.kasus_id');
    }

    // ============================================
    // KPI CARDS
    // ============================================

    /**
     * Total Kasus Fraud
     */
    public function getTotalKasus()
    {
        return $this->getBaseQuery()->count();
    }

    /**
     * Total Kerugian = (Kerugian Riil + Kerugian Potensial - Recovery)
     */
    public function getTotalKerugian()
    {
        $kasus = $this->getBaseQuery()
            ->with('kerugianFraud')
            ->get();

        $totalKerugian = 0;
        foreach ($kasus as $k) {
            if ($k->kerugianFraud) {
                $riil = ($k->kerugianFraud->ljk_rill ?? 0) +
                       ($k->kerugianFraud->konsumen_rill ?? 0) +
                       ($k->kerugianFraud->pihak_lain_rill ?? 0);

                $potensial = ($k->kerugianFraud->ljk_potensial ?? 0) +
                           ($k->kerugianFraud->konsumen_potensial ?? 0) +
                           ($k->kerugianFraud->pihak_lain_potensial ?? 0);

                $totalKerugian += $riil + $potensial;
            }
        }

        return $totalKerugian;
    }

    /**
     * Total Recovery
     */
    public function getTotalRecovery()
    {
        return KerugianRecovery::whereHas('kerugianFraud', function ($query) {
            $query->whereHas('kasus', function ($q) {
                $q->where('user_id', $this->userId)
                  ->where('jenis_laporan', 'semester');
                
                if ($this->year) {
                    $q->whereHas('waktuFraud', function ($wq) {
                        $wq->whereYear('waktu_diketahui', $this->year);
                    });
                }
                
                if ($this->month && $this->year) {
                    $q->whereHas('waktuFraud', function ($wq) {
                        $wq->whereMonth('waktu_diketahui', $this->month);
                    });
                }
            });
        })->sum('amount');
    }

    /**
     * Recovery Rate = (Total Recovery / Total Kerugian) × 100%
     */
    public function getRecoveryRate()
    {
        $totalKerugian = $this->getTotalKerugian();
        if ($totalKerugian == 0) {
            return 0;
        }

        $totalRecovery = $this->getTotalRecovery();
        return round(($totalRecovery / $totalKerugian) * 100, 2);
    }

    /**
     * Kasus Aktif (Status 001 dan 003)
     */
    public function getActiveCases()
    {
        return $this->getBaseQuery()
            ->whereIn('status_penanganan', ['001', '003'])
            ->count();
    }

    /**
     * Persentase Kasus Selesai (Status 002 dan 004)
     */
    public function getCompletionPercentage()
    {
        $total = $this->getTotalKasus();
        if ($total == 0) {
            return 0;
        }

        $completed = $this->getBaseQuery()
            ->whereIn('status_penanganan', ['002', '004'])
            ->count();

        return round(($completed / $total) * 100, 2);
    }

    // ============================================
    // TREN ANALYSIS
    // ============================================

    /**
     * Tren Jumlah Kasus Fraud per Bulan
     */
    public function getTrendCases()
    {
        $query = Kasus::where('user_id', $this->userId)
            ->where('jenis_laporan', 'semester')
            ->leftJoin('waktu_fraud', 'kasus.id', '=', 'waktu_fraud.kasus_id')
            ->select(
                DB::raw('MONTH(waktu_fraud.waktu_diketahui) as month'),
                DB::raw('COUNT(kasus.id) as count')
            );

        if ($this->year) {
            $query->whereYear('waktu_fraud.waktu_diketahui', $this->year);
        }

        $query = $query->groupBy(DB::raw('MONTH(waktu_fraud.waktu_diketahui)'))
            ->orderBy(DB::raw('MONTH(waktu_fraud.waktu_diketahui)'))
            ->get();

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $record = $query->where('month', $i)->first();
            $data[$i] = $record ? $record->count : 0;
        }

        return $data;
    }

    /**
     * Tren Total Kerugian per Bulan
     */
    public function getTrendLoss()
    {
        $query = Kasus::where('user_id', $this->userId)
            ->where('jenis_laporan', 'semester')
            ->leftJoin('waktu_fraud', 'kasus.id', '=', 'waktu_fraud.kasus_id')
            ->leftJoin('kerugian_fraud', 'kasus.id', '=', 'kerugian_fraud.kasus_id')
            ->select(
                DB::raw('MONTH(waktu_fraud.waktu_diketahui) as month'),
                DB::raw('SUM((COALESCE(kerugian_fraud.ljk_rill, 0) + COALESCE(kerugian_fraud.konsumen_rill, 0) + COALESCE(kerugian_fraud.pihak_lain_rill, 0) +
                         COALESCE(kerugian_fraud.ljk_potensial, 0) + COALESCE(kerugian_fraud.konsumen_potensial, 0) + COALESCE(kerugian_fraud.pihak_lain_potensial, 0))) as total')
            );

        if ($this->year) {
            $query->whereYear('waktu_fraud.waktu_diketahui', $this->year);
        }

        $query = $query->groupBy(DB::raw('MONTH(waktu_fraud.waktu_diketahui)'))
            ->orderBy(DB::raw('MONTH(waktu_fraud.waktu_diketahui)'))
            ->get();

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $record = $query->where('month', $i)->first();
            $data[$i] = $record ? round($record->total ?? 0) : 0;
        }

        return $data;
    }

    // ============================================
    // FRAUD ANALYSIS
    // ============================================

    /**
     * Top Jenis Fraud (Top 10)
     */
    public function getTopJenisFraud($limit = 10)
    {
        return $this->getBaseQuery()
            ->select('ref_jenis_fraud.nama', DB::raw('COUNT(kasus.id) as count'))
            ->join('kasus_jenis_fraud', 'kasus.id', '=', 'kasus_jenis_fraud.kasus_id')
            ->join('ref_jenis_fraud', 'kasus_jenis_fraud.jenis_fraud_id', '=', 'ref_jenis_fraud.id')
            ->groupBy('ref_jenis_fraud.id', 'ref_jenis_fraud.nama')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Aktivitas Terkait Fraud
     */
    public function getActivityRelated()
    {
        return $this->getBaseQuery()
            ->select('ref_aktivitas_terkait.nama', DB::raw('COUNT(kasus.id) as count'))
            ->join('ref_aktivitas_terkait', 'kasus.aktivitas_terkait_id', '=', 'ref_aktivitas_terkait.id')
            ->groupBy('ref_aktivitas_terkait.id', 'ref_aktivitas_terkait.nama')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Fraud Berdasarkan Divisi/Unit Kerja
     */
    public function getFraudByDivision()
    {
        return $this->getBaseQuery()
            ->select('kasus.divisi_unit', DB::raw('COUNT(kasus.id) as count'))
            ->groupBy('kasus.divisi_unit')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();
    }

    // ============================================
    // PELAKU ANALYSIS
    // ============================================

    /**
     * Internal vs Eksternal Pelaku
     */
    public function getInternalVsExternal()
    {
        return $this->getBaseQuery()
            ->select('pelaku_fraud.kategori', DB::raw('COUNT(DISTINCT pelaku_fraud.id) as count'))
            ->join('pelaku_fraud', 'kasus.id', '=', 'pelaku_fraud.kasus_id')
            ->groupBy('pelaku_fraud.kategori')
            ->get()
            ->toArray();
    }

    /**
     * Status Pelaku
     */
    public function getStatusPelaku()
    {
        return $this->getBaseQuery()
            ->select('ref_status_pelaku.id', 'ref_status_pelaku.nama', DB::raw('COUNT(DISTINCT pelaku_fraud.id) as count'))
            ->join('pelaku_fraud', 'kasus.id', '=', 'pelaku_fraud.kasus_id')
            ->join('ref_status_pelaku', 'pelaku_fraud.status_pelaku_id', '=', 'ref_status_pelaku.id')
            ->groupBy('ref_status_pelaku.id', 'ref_status_pelaku.nama')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Top Jabatan Pelaku (Top 10)
     */
    public function getTopJabatanPelaku($limit = 10)
    {
        return $this->getBaseQuery()
            ->select('ref_jabatan.id', 'ref_jabatan.nama', DB::raw('COUNT(DISTINCT pelaku_fraud.id) as count'))
            ->join('pelaku_fraud', 'kasus.id', '=', 'pelaku_fraud.kasus_id')
            ->join('ref_jabatan', 'pelaku_fraud.jabatan_saat_kejadian_id', '=', 'ref_jabatan.id')
            ->groupBy('ref_jabatan.id', 'ref_jabatan.nama')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    // ============================================
    // KERUGIAN ANALYSIS
    // ============================================

    /**
     * Kerugian Berdasarkan Pihak Dirugikan
     */
    public function getLossByVictim()
    {
        return $this->getBaseQuery()
            ->select('ref_pihak_dirugikan.id', 'ref_pihak_dirugikan.nama', DB::raw('COUNT(kasus.id) as count'))
            ->join('ref_pihak_dirugikan', 'kasus.pihak_dirugikan_id', '=', 'ref_pihak_dirugikan.id')
            ->groupBy('ref_pihak_dirugikan.id', 'ref_pihak_dirugikan.nama')
            ->get()
            ->toArray();
    }

    /**
     * Top 10 Kasus Dengan Kerugian Terbesar
     */
    public function getTop10CasesWithLargestLoss()
    {
        return $this->getBaseQuery()
            ->select(
                'kasus.id',
                'kasus.kode_komponen',
                'kasus.divisi_unit',
                'kasus.status_penanganan',
                DB::raw('(kerugian_fraud.ljk_rill + kerugian_fraud.konsumen_rill + kerugian_fraud.pihak_lain_rill +
                         kerugian_fraud.ljk_potensial + kerugian_fraud.konsumen_potensial + kerugian_fraud.pihak_lain_potensial) as total_kerugian')
            )
            ->leftJoin('kerugian_fraud', 'kasus.id', '=', 'kerugian_fraud.kasus_id')
            ->with([
                'jenisFraud',
            ])
            ->orderBy('total_kerugian', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($k) {
                return [
                    'id' => $k->id,
                    'kode_komponen' => $k->kode_komponen,
                    'jenis_fraud' => $k->jenisFraud->pluck('nama')->join(', '),
                    'divisi' => $k->divisi_unit,
                    'status_penanganan' => $this->getStatusLabel($k->status_penanganan),
                    'total_kerugian' => $k->total_kerugian ?? 0,
                ];
            })
            ->toArray();
    }

    // ============================================
    // PENANGANAN ANALYSIS
    // ============================================

    /**
     * Status Penanganan Kasus
     */
    public function getHandlingStatus()
    {
        $statuses = [
            '001' => 'Proses internal LJK',
            '002' => 'Selesai diproses internal LJK',
            '003' => 'Dalam proses aparat penegak hukum',
            '004' => 'Berkekuatan hukum tetap',
        ];

        $data = [];
        foreach ($statuses as $code => $label) {
            $count = $this->getBaseQuery()
                ->where('status_penanganan', $code)
                ->count();
            $data[] = [
                'status' => $label,
                'count' => $count,
            ];
        }

        return $data;
    }

    // ============================================
    // ROOT CAUSE ANALYSIS
    // ============================================

    /**
     * Top Kelemahan Penyebab Fraud (Top 10)
     */
    public function getTopKelemahan($limit = 10)
    {
        return $this->getBaseQuery()
            ->select('ref_kelemahan_fraud.id', 'ref_kelemahan_fraud.nama', DB::raw('COUNT(kasus.id) as count'))
            ->join('kasus_kelemahan', 'kasus.id', '=', 'kasus_kelemahan.kasus_id')
            ->join('ref_kelemahan_fraud', 'kasus_kelemahan.kelemahan_id', '=', 'ref_kelemahan_fraud.id')
            ->groupBy('ref_kelemahan_fraud.id', 'ref_kelemahan_fraud.nama')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    // ============================================
    // PENCEGAHAN ANALYSIS
    // ============================================

    /**
     * Status Realisasi Pencegahan
     */
    public function getPreventionStatus()
    {
        $tepat_waktu = $this->getBaseQuery()
            ->select(DB::raw('COUNT(DISTINCT pencegahan_fraud.id) as count'))
            ->join('pencegahan_fraud', 'kasus.id', '=', 'pencegahan_fraud.kasus_id')
            ->whereRaw('pencegahan_fraud.realisasi <= pencegahan_fraud.target_waktu')
            ->whereNotNull('pencegahan_fraud.realisasi')
            ->first()
            ->count ?? 0;

        $terlambat = $this->getBaseQuery()
            ->select(DB::raw('COUNT(DISTINCT pencegahan_fraud.id) as count'))
            ->join('pencegahan_fraud', 'kasus.id', '=', 'pencegahan_fraud.kasus_id')
            ->whereRaw('pencegahan_fraud.realisasi > pencegahan_fraud.target_waktu')
            ->whereNotNull('pencegahan_fraud.realisasi')
            ->first()
            ->count ?? 0;

        $belum_direalisasikan = $this->getBaseQuery()
            ->select(DB::raw('COUNT(DISTINCT pencegahan_fraud.id) as count'))
            ->join('pencegahan_fraud', 'kasus.id', '=', 'pencegahan_fraud.kasus_id')
            ->whereNull('pencegahan_fraud.realisasi')
            ->first()
            ->count ?? 0;

        return [
            ['status' => 'Tepat Waktu', 'count' => $tepat_waktu],
            ['status' => 'Terlambat', 'count' => $terlambat],
            ['status' => 'Belum Direalisasikan', 'count' => $belum_direalisasikan],
        ];
    }

    /**
     * On-Time Completion Rate
     */
    public function getOnTimeCompletionRate()
    {
        $total_pencegahan = $this->getBaseQuery()
            ->select(DB::raw('COUNT(DISTINCT pencegahan_fraud.id) as count'))
            ->join('pencegahan_fraud', 'kasus.id', '=', 'pencegahan_fraud.kasus_id')
            ->first()
            ->count ?? 0;

        if ($total_pencegahan == 0) {
            return 0;
        }

        $tepat_waktu = $this->getBaseQuery()
            ->select(DB::raw('COUNT(DISTINCT pencegahan_fraud.id) as count'))
            ->join('pencegahan_fraud', 'kasus.id', '=', 'pencegahan_fraud.kasus_id')
            ->whereRaw('pencegahan_fraud.realisasi <= pencegahan_fraud.target_waktu')
            ->whereNotNull('pencegahan_fraud.realisasi')
            ->first()
            ->count ?? 0;

        return round(($tepat_waktu / $total_pencegahan) * 100, 2);
    }

    // ============================================
    // HELPERS
    // ============================================

    private function getStatusLabel($code)
    {
        $statuses = [
            '001' => 'Proses internal LJK',
            '002' => 'Selesai diproses internal LJK',
            '003' => 'Dalam proses aparat penegak hukum',
            '004' => 'Berkekuatan hukum tetap',
        ];

        return $statuses[$code] ?? $code;
    }
}
