<?php

namespace App\Services;

use App\Models\Kasus;
use App\Models\KerugianRecovery;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsService
{
    /** Rumus total kerugian per kasus (sama dengan ExportService / ringkasan sistem). */
    private const KERUGIAN_PER_KASUS = '(
        COALESCE(kerugian_fraud.ljk_rill, 0) + COALESCE(kerugian_fraud.ljk_potensial, 0) +
        COALESCE(kerugian_fraud.konsumen_rill, 0) + COALESCE(kerugian_fraud.konsumen_potensial, 0) +
        COALESCE(kerugian_fraud.pihak_lain_rill, 0) + COALESCE(kerugian_fraud.pihak_lain_potensial, 0)
    ) - COALESCE((SELECT SUM(amount) FROM kerugian_recoveries WHERE kerugian_recoveries.kerugian_fraud_id = kerugian_fraud.id), 0)';

    protected $year;
    protected $month;

    public function __construct($year = null, $month = null)
    {
        $this->year = $year ? (int) $year : null;
        $this->month = $month ? (int) $month : null;
    }

    private function getBaseQuery()
    {
        $query = Kasus::query()
            ->where('kasus.jenis_laporan', 'semester');

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

    private function applyDateFilterToQuery($query)
    {
        if (!$this->year && !$this->month) {
            return $query;
        }

        return $query->whereHas('waktuFraud', function ($wq) {
            if ($this->year) {
                $wq->whereYear('waktu_diketahui', $this->year);
            }
            if ($this->month && $this->year) {
                $wq->whereMonth('waktu_diketahui', $this->month);
            }
        });
    }

    // ============================================
    // KPI
    // ============================================

    public function getTotalKasus(): int
    {
        return (int) $this->getBaseQuery()->count('kasus.id');
    }

    public function getTotalKerugian(): float
    {
        return (float) ($this->getBaseQuery()
            ->leftJoin('kerugian_fraud', 'kasus.id', '=', 'kerugian_fraud.kasus_id')
            ->selectRaw('SUM(' . self::KERUGIAN_PER_KASUS . ') as total')
            ->value('total') ?? 0);
    }

    public function getTotalRecovery(): float
    {
        return (float) KerugianRecovery::whereHas('kerugianFraud', function ($query) {
            $query->whereHas('kasus', function ($q) {
                $q->where('jenis_laporan', 'semester');
                $this->applyDateFilterToQuery($q);
            });
        })->sum('amount');
    }

    public function getRecoveryRate(): float
    {
        $totalKerugian = $this->getTotalKerugian();
        if ($totalKerugian == 0) {
            return 0;
        }

        return round(($this->getTotalRecovery() / $totalKerugian) * 100, 2);
    }

    public function getKpiSummary(): array
    {
        return [
            'total_kasus' => $this->getTotalKasus(),
            'total_kerugian' => $this->getTotalKerugian(),
            'total_recovery' => $this->getTotalRecovery(),
        ];
    }

    // ============================================
    // TREN
    // ============================================

    public function getTrendCombined(): array
    {
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        if ($this->year && $this->month) {
            $cases = $this->getTotalKasus();
            $loss = $this->getTotalKerugian();

            return [
                'labels' => [$monthNames[$this->month - 1] . ' ' . $this->year],
                'cases' => [$cases],
                'loss' => [$loss],
            ];
        }

        $casesQuery = Kasus::where('jenis_laporan', 'semester')
            ->leftJoin('waktu_fraud', 'kasus.id', '=', 'waktu_fraud.kasus_id')
            ->select(
                DB::raw('MONTH(waktu_fraud.waktu_diketahui) as month'),
                DB::raw('COUNT(kasus.id) as count')
            )
            ->whereNotNull('waktu_fraud.waktu_diketahui');

        $lossQuery = Kasus::where('jenis_laporan', 'semester')
            ->leftJoin('waktu_fraud', 'kasus.id', '=', 'waktu_fraud.kasus_id')
            ->leftJoin('kerugian_fraud', 'kasus.id', '=', 'kerugian_fraud.kasus_id')
            ->select(
                DB::raw('MONTH(waktu_fraud.waktu_diketahui) as month'),
                DB::raw('SUM(' . self::KERUGIAN_PER_KASUS . ') as total')
            )
            ->whereNotNull('waktu_fraud.waktu_diketahui');

        if ($this->year) {
            $casesQuery->whereYear('waktu_fraud.waktu_diketahui', $this->year);
            $lossQuery->whereYear('waktu_fraud.waktu_diketahui', $this->year);
        }

        $casesByMonth = $casesQuery
            ->groupBy(DB::raw('MONTH(waktu_fraud.waktu_diketahui)'))
            ->pluck('count', 'month');

        $lossByMonth = $lossQuery
            ->groupBy(DB::raw('MONTH(waktu_fraud.waktu_diketahui)'))
            ->pluck('total', 'month');

        $labels = [];
        $cases = [];
        $loss = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $monthNames[$i - 1];
            $cases[] = (int) ($casesByMonth[$i] ?? 0);
            $loss[] = (float) ($lossByMonth[$i] ?? 0);
        }

        return compact('labels', 'cases', 'loss');
    }

    // ============================================
    // CHART DATA
    // ============================================

    public function getInternalVsExternal(): array
    {
        return $this->getBaseQuery()
            ->select('pelaku_fraud.kategori', DB::raw('COUNT(DISTINCT kasus.id) as count'))
            ->join('pelaku_fraud', 'kasus.id', '=', 'pelaku_fraud.kasus_id')
            ->groupBy('pelaku_fraud.kategori')
            ->orderByDesc('count')
            ->get()
            ->map(fn ($row) => [
                'kategori' => $row->kategori,
                'count' => (int) $row->count,
            ])
            ->values()
            ->all();
    }

    public function getTopJabatanPelaku(int $limit = 10): array
    {
        return $this->getBaseQuery()
            ->select('ref_jabatan.nama', DB::raw('COUNT(DISTINCT pelaku_fraud.id) as count'))
            ->join('pelaku_fraud', 'kasus.id', '=', 'pelaku_fraud.kasus_id')
            ->join('ref_jabatan', 'pelaku_fraud.jabatan_saat_kejadian_id', '=', 'ref_jabatan.id')
            ->groupBy('ref_jabatan.id', 'ref_jabatan.nama')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => ['nama' => $row->nama, 'count' => (int) $row->count])
            ->all();
    }

    public function getTopJenisFraud(int $limit = 10): array
    {
        return $this->getBaseQuery()
            ->select('ref_jenis_fraud.nama', DB::raw('COUNT(DISTINCT kasus.id) as count'))
            ->join('kasus_jenis_fraud', 'kasus.id', '=', 'kasus_jenis_fraud.kasus_id')
            ->join('ref_jenis_fraud', 'kasus_jenis_fraud.jenis_fraud_id', '=', 'ref_jenis_fraud.id')
            ->groupBy('ref_jenis_fraud.id', 'ref_jenis_fraud.nama')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => ['nama' => $row->nama, 'count' => (int) $row->count])
            ->all();
    }

    public function getDivisionByLoss(int $limit = 15): array
    {
        return $this->getBaseQuery()
            ->leftJoin('kerugian_fraud', 'kasus.id', '=', 'kerugian_fraud.kasus_id')
            ->select(
                'kasus.divisi_unit',
                DB::raw('SUM(' . self::KERUGIAN_PER_KASUS . ') as total_loss')
            )
            ->whereNotNull('kasus.divisi_unit')
            ->where('kasus.divisi_unit', '!=', '')
            ->groupBy('kasus.divisi_unit')
            ->orderByDesc('total_loss')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'divisi' => $row->divisi_unit,
                'total_loss' => (float) $row->total_loss,
            ])
            ->all();
    }

    public function getTopKelemahan(int $limit = 10): array
    {
        return $this->getBaseQuery()
            ->select('ref_kelemahan_fraud.nama', DB::raw('COUNT(DISTINCT kasus.id) as count'))
            ->join('kasus_kelemahan', 'kasus.id', '=', 'kasus_kelemahan.kasus_id')
            ->join('ref_kelemahan_fraud', 'kasus_kelemahan.kelemahan_id', '=', 'ref_kelemahan_fraud.id')
            ->groupBy('ref_kelemahan_fraud.id', 'ref_kelemahan_fraud.nama')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => ['nama' => $row->nama, 'count' => (int) $row->count])
            ->all();
    }

    public function getActivityRelated(): array
    {
        return $this->getBaseQuery()
            ->select('ref_aktivitas_terkait.nama', DB::raw('COUNT(kasus.id) as count'))
            ->join('ref_aktivitas_terkait', 'kasus.aktivitas_terkait_id', '=', 'ref_aktivitas_terkait.id')
            ->whereNotNull('kasus.aktivitas_terkait_id')
            ->groupBy('ref_aktivitas_terkait.id', 'ref_aktivitas_terkait.nama')
            ->orderByDesc('count')
            ->get()
            ->map(fn ($row) => ['nama' => $row->nama, 'count' => (int) $row->count])
            ->all();
    }

    public function getHandlingStatus(): array
    {
        $statuses = [
            '001' => 'Proses Internal LJK',
            '002' => 'Selesai Diproses Internal LJK',
            '003' => 'Dalam Proses Penegak Hukum',
            '004' => 'Berkekuatan Hukum Tetap',
        ];

        $data = [];
        foreach ($statuses as $code => $label) {
            $count = (int) $this->getBaseQuery()
                ->where('kasus.status_penanganan', $code)
                ->count('kasus.id');
            $data[] = [
                'code' => $code,
                'status' => $label,
                'count' => $count,
            ];
        }

        return $data;
    }

    /**
     * Satu payload untuk seluruh dashboard analisis.
     */
    public function getDashboardData(): array
    {
        return [
            'kpi' => $this->getKpiSummary(),
            'trend' => $this->getTrendCombined(),
            'internal_vs_external' => $this->getInternalVsExternal(),
            'top_jabatan' => $this->getTopJabatanPelaku(),
            'top_jenis_fraud' => $this->getTopJenisFraud(),
            'division_loss' => $this->getDivisionByLoss(),
            'top_kelemahan' => $this->getTopKelemahan(),
            'activity_related' => $this->getActivityRelated(),
            'handling_status' => $this->getHandlingStatus(),
        ];
    }

    // ============================================
    // DRILL DOWN
    // ============================================

    public function getDrilldownCases(string $type, string $value): array
    {
        $query = Kasus::with(['jenisFraud', 'kerugianFraud'])
            ->where('jenis_laporan', 'semester');

        $this->applyDateFilterToQuery($query);

        switch ($type) {
            case 'internal_external':
                $query->whereHas('pelakuFrauds', function ($q) use ($value) {
                    $q->whereRaw('LOWER(kategori) = ?', [strtolower($value)]);
                });
                break;
            case 'jabatan':
                $query->whereHas('pelakuFrauds', function ($q) use ($value) {
                    $q->whereHas('jabatanKejadian', function ($jq) use ($value) {
                        $jq->where('nama', $value);
                    });
                });
                break;
            case 'jenis_fraud':
                $query->whereHas('jenisFraud', function ($q) use ($value) {
                    $q->where('nama', $value);
                });
                break;
            case 'divisi':
                $query->where('divisi_unit', $value);
                break;
            case 'kelemahan':
                $query->whereHas('kelemahanFraud', function ($q) use ($value) {
                    $q->where('nama', $value);
                });
                break;
            case 'aktivitas':
                $query->whereHas('aktivitasTerkait', function ($q) use ($value) {
                    $q->where('nama', $value);
                });
                break;
            case 'status_penanganan':
                $query->where('status_penanganan', $value);
                break;
            default:
                return [];
        }

        return $query
            ->orderByDesc('kasus.id')
            ->limit(200)
            ->get()
            ->map(fn ($k) => $this->formatCaseForDrilldown($k))
            ->values()
            ->all();
    }

    private function formatCaseForDrilldown(Kasus $k): array
    {
        $totalLoss = 0;
        if ($k->kerugianFraud) {
            $kf = $k->kerugianFraud;
            $totalLoss = ($kf->ljk_rill ?? 0) + ($kf->ljk_potensial ?? 0)
                + ($kf->konsumen_rill ?? 0) + ($kf->konsumen_potensial ?? 0)
                + ($kf->pihak_lain_rill ?? 0) + ($kf->pihak_lain_potensial ?? 0);
        }

        return [
            'id' => $k->id,
'kode_kejadian' => $k->kejadianFraud
    ->pluck('pivot')
    ->pluck('kode_kejadian')
    ->filter()
    ->join(', ') ?: '-',
            'jenis_fraud' => $k->jenisFraud->pluck('nama')->join(', ') ?: '-',
            'divisi' => $k->divisi_unit ?: '-',
            'status_penanganan' => $k->status_penanganan,
            'status_label' => $this->getStatusLabel($k->status_penanganan),
            'total_kerugian' => $totalLoss,
            'show_url' => route('kasus.show', $k->id),
        ];
    }

    private function getStatusLabel(?string $code): string
    {
        $statuses = [
            '001' => 'Proses Internal LJK',
            '002' => 'Selesai Diproses Internal LJK',
            '003' => 'Dalam Proses Penegak Hukum',
            '004' => 'Berkekuatan Hukum Tetap',
        ];

        return $statuses[$code] ?? ($code ?? '-');
    }
}
