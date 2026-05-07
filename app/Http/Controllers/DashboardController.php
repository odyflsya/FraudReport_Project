<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kasus;

class DashboardController extends Controller
{
    public function index()
    {
        // ================= TOTAL KASUS =================
        $totalKasus = Kasus::where('user_id', Auth::id())->count();

        // ================= TOTAL KERUGIAN =================
        $totalKerugian = Kasus::where('user_id', Auth::id())
            ->with('kerugianFraud')
            ->get()
            ->sum(function ($k) {
                return
                    ($k->kerugianFraud->ljk_rill ?? 0) +
                    ($k->kerugianFraud->konsumen_rill ?? 0) +
                    ($k->kerugianFraud->pihak_lain_rill ?? 0);
            });

        // ================= STATUS =================
        $open = Kasus::where('user_id', Auth::id())->where('status_penanganan', 'Open')->count();
        $closed = Kasus::where('user_id', Auth::id())->where('status_penanganan', 'Closed')->count();

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
        ->latest()
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
        ->latest()
        ->limit(5)
        ->get();

        // Tambahkan nomor urut per tabel dashboard
        foreach ($semesterKasus as $index => $k) {
            $k->nomor_urut = $index + 1;
        }
        foreach ($signifikanKasus as $index => $k) {
            $k->nomor_urut = $index + 1;
        }

        return view('dashboard', compact(
            'totalKasus',
            'totalKerugian',
            'open',
            'closed',
            'semesterKasus',
            'signifikanKasus'
        ));
    }
}