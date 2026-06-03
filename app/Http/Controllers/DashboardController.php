<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kasus;
use App\Services\DashboardAnalyticsService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter params
        $year = $request->query('year');
        $month = $request->query('month');

        // ================= OLD DASHBOARD DATA (SEMESTER/SIGNIFIKAN/NON-SIGNIFIKAN) =================
        
        // ================= TOTAL KASUS =================
        $totalKasus = Kasus::where('user_id', Auth::id())
            ->where('jenis_laporan', 'semester')
            ->count();

        // ================= TOTAL KERUGIAN =================
        $totalKerugian = Kasus::where('user_id', Auth::id())
            ->where('jenis_laporan', 'semester')
            ->with('kerugianFraud')
            ->get()
            ->sum(function ($k) {
                return
                    ($k->kerugianFraud->ljk_rill ?? 0) +
                    ($k->kerugianFraud->konsumen_rill ?? 0) +
                    ($k->kerugianFraud->pihak_lain_rill ?? 0);
            });

        // ================= STATUS =================
        $statusCounts = Kasus::where('user_id', Auth::id())
            ->where('jenis_laporan', 'semester')
            ->selectRaw('status_penanganan, count(*) as total')
            ->groupBy('status_penanganan')
            ->pluck('total', 'status_penanganan')
            ->toArray();

        // ================= TOTAL PELAKU =================
        $totalPelaku = Kasus::where('user_id', Auth::id())
            ->where('jenis_laporan', 'semester')
            ->withCount('pelakuFrauds')
            ->get()
            ->sum('pelaku_frauds_count');

        // ================= SEMESTER (5 TERBARU) =================
        $semesterKasus = Kasus::with([
            'kejadianFraud',
            'jenisFraud',
            'aktivitasTerkait',
            'lokasiFraud',
            'pihakDirugikan',
            'waktuFraud',
            'kerugianFraud',
            'kelemahanFraud',
            'penangananFraud',
            'pencegahanFraud' => function($query) {
                $query->with('refPencegahan');
            },
            'pelakuFrauds' => function($query) {
                $query->with(['jenisIdentitas', 'statusPelaku', 'jabatanKejadian', 'jabatanDiketahui']);
            }
        ])
        ->where('user_id', Auth::id())
        ->where('jenis_laporan', 'semester')
        ->orderBy('created_at', 'asc')
        ->limit(5)
        ->get();

        // ================= SIGNIFIKAN (5 TERBARU) =================
        $signifikanKasus = Kasus::with([
            'kejadianFraud',
            'jenisFraud',
            'aktivitasTerkait',
            'lokasiFraud',
            'pihakDirugikan',
            'waktuFraud',
            'kerugianFraud',
            'pelakuFrauds' => function($query) {
                $query->with(['jenisIdentitas', 'statusPelaku', 'jabatanKejadian', 'jabatanDiketahui']);
            }
        ])
        ->where('user_id', Auth::id())
        ->where('jenis_laporan', 'signifikan')
        ->orderBy('created_at', 'asc')
        ->limit(5)
        ->get();

        // ================= NON-SIGNIFIKAN (5 TERBARU) =================
        $nonSignifikanKasus = Kasus::with([
            'kejadianFraud',
            'jenisFraud',
            'aktivitasTerkait',
            'lokasiFraud',
            'pihakDirugikan',
            'waktuFraud',
            'kerugianFraud',
            'kelemahanFraud',
            'penangananFraud',
            'pencegahanFraud' => function($query) {
                $query->with('refPencegahan');
            },
            'pelakuFrauds' => function($query) {
                $query->with(['jenisIdentitas', 'statusPelaku', 'jabatanKejadian', 'jabatanDiketahui']);
            }
        ])
        ->where('user_id', Auth::id())
        ->where('jenis_laporan', 'non-signifikan')
        ->orderBy('created_at', 'asc')
        ->limit(5)
        ->get();

        // Tambahkan nomor urut per tabel dashboard
        foreach ($semesterKasus as $index => $k) {
            $k->nomor_urut = $index + 1;
        }
        foreach ($signifikanKasus as $index => $k) {
            $k->nomor_urut = $index + 1;
        }

        // ================= NEW ANALYTICS DATA =================
        $analytics = new DashboardAnalyticsService(Auth::id(), $year, $month);

        // Get available years for filter
        $availableYears = Kasus::where('user_id', Auth::id())
            ->where('jenis_laporan', 'semester')
            ->leftJoin('waktu_fraud', 'kasus.id', '=', 'waktu_fraud.kasus_id')
            ->selectRaw('YEAR(waktu_fraud.waktu_diketahui) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter(function ($y) {
                return $y !== null;
            })
            ->toArray();

        // KPI Data
        $analyticKpi = [
            'total_kasus' => $analytics->getTotalKasus(),
            'total_kerugian' => $analytics->getTotalKerugian(),
            'total_recovery' => $analytics->getTotalRecovery(),
            'recovery_rate' => $analytics->getRecoveryRate(),
            'active_cases' => $analytics->getActiveCases(),
            'completion_percentage' => $analytics->getCompletionPercentage(),
            'ontime_rate' => $analytics->getOnTimeCompletionRate(),
        ];

        // Chart Data
        $analyticCharts = [
            'trend_cases' => $analytics->getTrendCases(),
            'trend_loss' => $analytics->getTrendLoss(),
            'top_jenis_fraud' => $analytics->getTopJenisFraud(),
            'activity_related' => $analytics->getActivityRelated(),
            'fraud_by_division' => $analytics->getFraudByDivision(),
            'internal_vs_external' => $analytics->getInternalVsExternal(),
            'status_pelaku' => $analytics->getStatusPelaku(),
            'top_jabatan' => $analytics->getTopJabatanPelaku(),
            'loss_by_victim' => $analytics->getLossByVictim(),
            'handling_status' => $analytics->getHandlingStatus(),
            'top_kelemahan' => $analytics->getTopKelemahan(),
            'prevention_status' => $analytics->getPreventionStatus(),
            'top_cases_by_loss' => $analytics->getTop10CasesWithLargestLoss(),
        ];

        return view('dashboard', compact(
            'totalKasus',
            'totalKerugian',
            'totalPelaku',
            'statusCounts',
            'semesterKasus',
            'signifikanKasus',
            'nonSignifikanKasus',
            'year',
            'month',
            'availableYears',
            'analyticKpi',
            'analyticCharts'
        ));
    }
}

