<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kasus;
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
        $totalKasus = Kasus::where('jenis_laporan', 'semester')
            ->count();

        // ================= TOTAL KERUGIAN =================
        $totalKerugian = Kasus::where('jenis_laporan', 'semester')
            ->with('kerugianFraud')
            ->get()
            ->sum(function ($k) {
                return
                    ($k->kerugianFraud->ljk_rill ?? 0) +
                    ($k->kerugianFraud->konsumen_rill ?? 0) +
                    ($k->kerugianFraud->pihak_lain_rill ?? 0);
            });

        // ================= STATUS =================
        $statusCounts = Kasus::where('jenis_laporan', 'semester')
            ->selectRaw('status_penanganan, count(*) as total')
            ->groupBy('status_penanganan')
            ->pluck('total', 'status_penanganan')
            ->toArray();

        // ================= TOTAL PELAKU =================
        $totalPelaku = Kasus::where('jenis_laporan', 'semester')
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
            'kerugianFraud' => fn ($q) => $q->with('recoveries'),
            'kelemahanFraud',
            'penangananFraud',
            'pencegahanFraud' => function($query) {
                $query->with('refPencegahan');
            },
            'pelakuFrauds' => function($query) {
                $query->with(['jenisIdentitas', 'statusPelaku', 'jabatanKejadian', 'jabatanDiketahui']);
            }
        ])
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
            'kerugianFraud' => fn ($q) => $q->with('recoveries'),
            'pelakuFrauds' => function($query) {
                $query->with(['jenisIdentitas', 'statusPelaku', 'jabatanKejadian', 'jabatanDiketahui']);
            }
        ])
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
            'kerugianFraud' => fn ($q) => $q->with('recoveries'),
            'kelemahanFraud',
            'penangananFraud',
            'pencegahanFraud' => function($query) {
                $query->with('refPencegahan');
            },
            'pelakuFrauds' => function($query) {
                $query->with(['jenisIdentitas', 'statusPelaku', 'jabatanKejadian', 'jabatanDiketahui']);
            }
        ])
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

        // Get available years for filter (dashboard analisis)
        $availableYears = Kasus::where('jenis_laporan', 'semester')
            ->leftJoin('waktu_fraud', 'kasus.id', '=', 'waktu_fraud.kasus_id')
            ->selectRaw('YEAR(waktu_fraud.waktu_diketahui) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter(function ($y) {
                return $y !== null;
            })
            ->toArray();

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
            'availableYears'
        ));
    }
}
