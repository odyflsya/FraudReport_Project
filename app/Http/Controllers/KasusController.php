<?php

namespace App\Http\Controllers;

use App\Models\Kasus;
use App\Models\PelakuFraud;
use App\Models\WaktuFraud;
use App\Models\KerugianFraud;
use App\Models\PencegahanFraud;

use App\Models\RefKejadianFraud;
use App\Models\RefJenisFraud;
use App\Models\RefAktivitasTerkait;
use App\Models\RefLokasiFraud;
use App\Models\RefPihakDirugikan;
use App\Models\RefKelemahanFraud;
use App\Models\RefTindakanPenanganan;
use App\Models\RefPencegahanFraud;
use App\Models\RefJenisIdentitas;
use App\Models\RefStatusPelaku;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\RefJabatan;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ExportService;
use Barryvdh\DomPDF\Facade\Pdf;

class KasusController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        // Ambil parameter dari request
        $search = $request->get('search');
        $status_penanganan = $request->get('status_penanganan');
        $jenis_fraud = $request->get('jenis_fraud');
        $jenis_laporan = $request->get('jenis_laporan');
        $tanggal_awal = $request->get('tanggal_awal');
        $tanggal_akhir = $request->get('tanggal_akhir');

        // Query dasar dengan eager loading
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
            'pencegahanFraud' => function($query) {
                $query->with('refPencegahan');
            },
            'pelakuFrauds' => function($query) {
                $query->with(['jenisIdentitas', 'statusPelaku', 'jabatanKejadian', 'jabatanDiketahui']);
            }
        ]);

        // Search global (kode_komponen, deskripsi_fraud, divisi_unit, nama pelaku)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode_komponen', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi_fraud', 'like', '%' . $search . '%')
                  ->orWhere('divisi_unit', 'like', '%' . $search . '%')
                  ->orWhereHas('pelakuFrauds', function($pelakuQuery) use ($search) {
                      $pelakuQuery->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter status penanganan
        $query->when($status_penanganan, function($q) use ($status_penanganan) {
            return $q->where('status_penanganan', $status_penanganan);
        });

        // Filter jenis fraud
        $query->when($jenis_fraud, function($q) use ($jenis_fraud) {
            return $q->whereHas('jenisFraud', function($jenisQuery) use ($jenis_fraud) {
                $jenisQuery->where('ref_jenis_fraud.id', $jenis_fraud);
            });
        });

        // Filter tanggal kejadian (range waktu_awal - waktu_akhir)
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereHas('waktuFraud', function($waktuQuery) use ($tanggal_awal, $tanggal_akhir) {
                $waktuQuery->whereBetween('waktu_awal', [$tanggal_awal, $tanggal_akhir])
                          ->orWhereBetween('waktu_akhir', [$tanggal_awal, $tanggal_akhir]);
            });
        } elseif ($tanggal_awal) {
            $query->whereHas('waktuFraud', function($waktuQuery) use ($tanggal_awal) {
                $waktuQuery->where('waktu_awal', '>=', $tanggal_awal)
                          ->orWhere('waktu_akhir', '>=', $tanggal_awal);
            });
        } elseif ($tanggal_akhir) {
            $query->whereHas('waktuFraud', function($waktuQuery) use ($tanggal_akhir) {
                $waktuQuery->where('waktu_awal', '<=', $tanggal_akhir)
                          ->orWhere('waktu_akhir', '<=', $tanggal_akhir);
            });
        }

        // Filter jenis laporan
        $query->when($jenis_laporan, function($q) use ($jenis_laporan) {
            return $q->where('jenis_laporan', $jenis_laporan);
        });

        // Pagination
        $kasus = $query->latest()->paginate(10)->withQueryString();

        // Data untuk filter dropdown
        $jenisFraudOptions = RefJenisFraud::orderBy('nama')->get();

        return view('kasus.index', compact('kasus', 'jenisFraudOptions'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('kasus.create', [
            'kejadianFraud' => RefKejadianFraud::all(),
            'jenisFraud' => RefJenisFraud::all(),
            'aktivitasTerkait' => RefAktivitasTerkait::all(),
            'lokasiFraud' => RefLokasiFraud::all(),
            'pihakDirugikan' => RefPihakDirugikan::all(),
            'kelemahanFraud' => RefKelemahanFraud::all(),
            'penangananFraud' => RefTindakanPenanganan::all(),
            'pencegahanFraud' => RefPencegahanFraud::all(),
            'jenisIdentitas' => RefJenisIdentitas::all(),
            'statusPelaku' => RefStatusPelaku::all(),
            'jabatanKejadian' => RefJabatan::all(),
            'jabatanSemua' => RefJabatan::all(),
        ]);
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'kode_komponen' => 'required|string|max:255',
            'aktivitas_terkait_id' => 'required|exists:ref_aktivitas_terkait,id',
            'deskripsi_fraud' => 'required|string',
            'divisi_unit' => 'required|string|max:255',
            'pihak_dirugikan_id' => 'required|exists:ref_pihak_dirugikan,id',
            'status_penanganan' => 'required|string',
            'jenis_laporan' => 'required|in:semester,signifikan',
            'tindak_lanjut_ljk' => 'required_if:jenis_laporan,signifikan|string|nullable',
        ]);

        DB::beginTransaction();

        try {
            // ================= SIMPAN KASUS =================
            $kasus = Kasus::create([
                'kode_komponen' => $request->kode_komponen,
                'aktivitas_terkait_id' => $request->aktivitas_terkait_id,
                'deskripsi_fraud' => $request->deskripsi_fraud,
                'divisi_unit' => $request->divisi_unit,
                'pihak_dirugikan_id' => $request->pihak_dirugikan_id,
                'status_penanganan' => $request->status_penanganan,
                'jenis_laporan' => $request->jenis_laporan,
                'tindak_lanjut_ljk' => $request->jenis_laporan === 'signifikan' ? $request->tindak_lanjut_ljk : null,
            ]);

            // ================= KEJADIAN FRAUD =================
            if ($request->kejadian_fraud) {
                $kejadianFraudIds = is_array($request->kejadian_fraud) ? $request->kejadian_fraud : [$request->kejadian_fraud];
                $data = [];
                foreach ($kejadianFraudIds as $id) {
                    $data[$id] = [
                        'kode_kejadian' => $request->id_kejadian ?? null,
                    ];
                }
                $kasus->kejadianFraud()->sync($data);
            }

            // ================= JENIS FRAUD =================
            if ($request->jenis_fraud) {
                $jenisFraudIds = is_array($request->jenis_fraud) ? $request->jenis_fraud : [$request->jenis_fraud];
                $data = [];
                foreach ($jenisFraudIds as $id) {
                    $data[$id] = [
                        'keterangan' => $request->jenis_fraud_keterangan ?? null
                    ];
                }
                $kasus->jenisFraud()->sync($data);
            }

            // ================= LOKASI =================
            if ($request->lokasi_fraud) {
                $lokasiFraudIds = is_array($request->lokasi_fraud) ? $request->lokasi_fraud : [$request->lokasi_fraud];
                $data = [];
                foreach ($lokasiFraudIds as $id) {
                    $data[$id] = [
                        'keterangan' => $request->lokasi_fraud_keterangan ?? null
                    ];
                }
                $kasus->lokasiFraud()->sync($data);
            }

            // ================= KELEMAHAN =================
            if ($request->jenis_laporan === 'signifikan') {
                $kasus->kelemahanFraud()->detach();
            } elseif ($request->kelemahan_fraud) {
                $kelemahanFraudIds = is_array($request->kelemahan_fraud) ? $request->kelemahan_fraud : [$request->kelemahan_fraud];
                $data = [];
                foreach ($kelemahanFraudIds as $id) {
                    $data[$id] = [
                        'keterangan' => $request->kelemahan_fraud_keterangan ?? null
                    ];
                }
                $kasus->kelemahanFraud()->sync($data);
            } else {
                $kasus->kelemahanFraud()->detach();
            }

            // ================= PENANGANAN =================
            if ($request->jenis_laporan === 'signifikan') {
                $kasus->penangananFraud()->detach();
            } elseif ($request->penanganan_fraud) {
                $penangananFraudIds = is_array($request->penanganan_fraud) ? $request->penanganan_fraud : [$request->penanganan_fraud];
                $data = [];
                foreach ($penangananFraudIds as $id) {
                    $data[$id] = [
                        'keterangan' => $request->penanganan_fraud_keterangan ?? null
                    ];
                }
                $kasus->penangananFraud()->sync($data);
            } else {
                $kasus->penangananFraud()->detach();
            }

            // ================= WAKTU =================
            WaktuFraud::create([
                'kasus_id' => $kasus->id,
                'waktu_awal' => $request->waktu_awal,
                'waktu_akhir' => $request->waktu_akhir,
                'waktu_diketahui' => $request->waktu_diketahui,
            ]);

            // ================= KERUGIAN =================
            KerugianFraud::create([
                'kasus_id' => $kasus->id,
                'ljk_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $request->ljk_rill,
                'ljk_potensial' => $request->ljk_potensial,
                'ljk_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $request->ljk_recovery,
                'konsumen_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $request->konsumen_rill,
                'konsumen_potensial' => $request->konsumen_potensial,
                'konsumen_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $request->konsumen_recovery,
                'pihak_lain_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $request->pihak_lain_rill,
                'pihak_lain_potensial' => $request->pihak_lain_potensial,
                'pihak_lain_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $request->pihak_lain_recovery,
            ]);

            // ================= PELAKU =================
            if ($request->pelaku_fraud) {
                $pelakuFraudData = $request->pelaku_fraud;
                $pelakuFraudData['keterangan'] = $pelakuFraudData['keterangan'] ?? '';
                PelakuFraud::create(array_merge($pelakuFraudData, ['kasus_id' => $kasus->id]));
            }

            // ================= PENCEGAHAN =================
            if ($request->pencegahan_fraud) {
                $pencegahanFraudData = $request->pencegahan_fraud;
                $pencegahanFraudData['keterangan'] = $pencegahanFraudData['keterangan'] ?? '';
                PencegahanFraud::create(array_merge($pencegahanFraudData, ['kasus_id' => $kasus->id]));
            }

            DB::commit();

            return redirect()->route('kasus.index')->with('success', 'Data berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // ================= SHOW =================
    public function show($id)
    {
        $kasus = Kasus::with([
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
        ])->findOrFail($id);
        return view('kasus.show', compact('kasus'));
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $kasus = Kasus::with([
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
        ])->findOrFail($id);
        
        return view('kasus.edit', [
            'kasus' => $kasus,
            'kejadianFraud' => RefKejadianFraud::all(),
            'jenisFraud' => RefJenisFraud::all(),
            'aktivitasTerkait' => RefAktivitasTerkait::all(),
            'lokasiFraud' => RefLokasiFraud::all(),
            'pihakDirugikan' => RefPihakDirugikan::all(),
            'kelemahanFraud' => RefKelemahanFraud::all(),
            'penangananFraud' => RefTindakanPenanganan::all(),
            'pencegahanFraud' => RefPencegahanFraud::all(),
            'jenisIdentitas' => RefJenisIdentitas::all(),
            'statusPelaku' => RefStatusPelaku::all(),
            'jabatanKejadian' => RefJabatan::all(),
            'jabatanSemua' => RefJabatan::all(),
        ]);
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_komponen' => 'required|string|max:255',
            'aktivitas_terkait_id' => 'required|exists:ref_aktivitas_terkait,id',
            'deskripsi_fraud' => 'required|string',
            'divisi_unit' => 'required|string|max:255',
            'pihak_dirugikan_id' => 'required|exists:ref_pihak_dirugikan,id',
            'status_penanganan' => 'required|string',
            'jenis_laporan' => 'required|in:semester,signifikan',
            'tindak_lanjut_ljk' => 'required_if:jenis_laporan,signifikan|string|nullable',
        ]);

        DB::beginTransaction();

        try {
            $kasus = Kasus::findOrFail($id);

            // ================= UPDATE KASUS =================
            $kasus->update([
                'kode_komponen' => $request->kode_komponen,
                'aktivitas_terkait_id' => $request->aktivitas_terkait_id,
                'deskripsi_fraud' => $request->deskripsi_fraud,
                'divisi_unit' => $request->divisi_unit,
                'pihak_dirugikan_id' => $request->pihak_dirugikan_id,
                'status_penanganan' => $request->status_penanganan,
                'jenis_laporan' => $request->jenis_laporan,
                'tindak_lanjut_ljk' => $request->jenis_laporan === 'signifikan' ? $request->tindak_lanjut_ljk : null,
            ]);

            // ================= KEJADIAN FRAUD =================
            if ($request->kejadian_fraud) {
                $kejadianFraudIds = is_array($request->kejadian_fraud) ? $request->kejadian_fraud : [$request->kejadian_fraud];
                $data = [];
                foreach ($kejadianFraudIds as $id) {
                    $data[$id] = [
                        'kode_kejadian' => $request->id_kejadian ?? null,
                    ];
                }
                $kasus->kejadianFraud()->sync($data);
            }

            // ================= JENIS FRAUD =================
            if ($request->jenis_fraud) {
                $jenisFraudIds = is_array($request->jenis_fraud) ? $request->jenis_fraud : [$request->jenis_fraud];
                $data = [];
                foreach ($jenisFraudIds as $id) {
                    $data[$id] = [
                        'keterangan' => $request->jenis_fraud_keterangan ?? null
                    ];
                }
                $kasus->jenisFraud()->sync($data);
            }

            // ================= LOKASI =================
            if ($request->lokasi_fraud) {
                $lokasiFraudIds = is_array($request->lokasi_fraud) ? $request->lokasi_fraud : [$request->lokasi_fraud];
                $data = [];
                foreach ($lokasiFraudIds as $id) {
                    $data[$id] = [
                        'keterangan' => $request->lokasi_fraud_keterangan ?? null
                    ];
                }
                $kasus->lokasiFraud()->sync($data);
            }

            // ================= KELEMAHAN =================
            if ($request->kelemahan_fraud) {
                $kelemahanFraudIds = is_array($request->kelemahan_fraud) ? $request->kelemahan_fraud : [$request->kelemahan_fraud];
                $data = [];
                foreach ($kelemahanFraudIds as $id) {
                    $data[$id] = [
                        'keterangan' => $request->kelemahan_fraud_keterangan ?? null
                    ];
                }
                $kasus->kelemahanFraud()->sync($data);
            }

            // ================= PENANGANAN =================
            if ($request->jenis_laporan === 'signifikan') {
                $kasus->penangananFraud()->detach();
            } elseif ($request->penanganan_fraud) {
                $penangananFraudIds = is_array($request->penanganan_fraud) ? $request->penanganan_fraud : [$request->penanganan_fraud];
                $data = [];
                foreach ($penangananFraudIds as $id) {
                    $data[$id] = [
                        'keterangan' => $request->penanganan_fraud_keterangan ?? null
                    ];
                }
                $kasus->penangananFraud()->sync($data);
            } else {
                $kasus->penangananFraud()->detach();
            }

            // ================= WAKTU =================
            if ($kasus->waktuFraud) {
                $kasus->waktuFraud->update([
                    'waktu_awal' => $request->waktu_awal,
                    'waktu_akhir' => $request->waktu_akhir,
                    'waktu_diketahui' => $request->waktu_diketahui,
                ]);
            } else {
                WaktuFraud::create([
                    'kasus_id' => $kasus->id,
                    'waktu_awal' => $request->waktu_awal,
                    'waktu_akhir' => $request->waktu_akhir,
                    'waktu_diketahui' => $request->waktu_diketahui,
                ]);
            }

            // ================= KERUGIAN =================
            if ($kasus->kerugianFraud) {
                $kasus->kerugianFraud->update([
                    'ljk_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $request->ljk_rill,
                    'ljk_potensial' => $request->ljk_potensial,
                    'ljk_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $request->ljk_recovery,
                    'konsumen_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $request->konsumen_rill,
                    'konsumen_potensial' => $request->konsumen_potensial,
                    'konsumen_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $request->konsumen_recovery,
                    'pihak_lain_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $request->pihak_lain_rill,
                    'pihak_lain_potensial' => $request->pihak_lain_potensial,
                    'pihak_lain_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $request->pihak_lain_recovery,
                ]);
            } else {
                KerugianFraud::create([
                    'kasus_id' => $kasus->id,
                    'ljk_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $request->ljk_rill,
                    'ljk_potensial' => $request->ljk_potensial,
                    'ljk_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $request->ljk_recovery,
                    'konsumen_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $request->konsumen_rill,
                    'konsumen_potensial' => $request->konsumen_potensial,
                    'konsumen_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $request->konsumen_recovery,
                    'pihak_lain_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $request->pihak_lain_rill,
                    'pihak_lain_potensial' => $request->pihak_lain_potensial,
                    'pihak_lain_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $request->pihak_lain_recovery,
                ]);
            }

            // ================= PENCEGAHAN =================
            if ($request->jenis_laporan === 'signifikan') {
                $kasus->pencegahanFraud()->delete();
            } elseif ($request->pencegahan_fraud) {
                $kasus->pencegahanFraud()->delete();
                $pencegahanFraudData = $request->pencegahan_fraud;
                $pencegahanFraudData['keterangan'] = $pencegahanFraudData['keterangan'] ?? '';
                PencegahanFraud::create(array_merge($pencegahanFraudData, ['kasus_id' => $kasus->id]));
            } else {
                $kasus->pencegahanFraud()->delete();
            }

            // ================= PELAKU =================
            if ($request->pelaku_fraud) {
                $kasus->pelakuFrauds()->delete();
                $pelakuFraudData = $request->pelaku_fraud;
                $pelakuFraudData['keterangan'] = $pelakuFraudData['keterangan'] ?? '';
                PelakuFraud::create(array_merge($pelakuFraudData, ['kasus_id' => $kasus->id]));
            }

            DB::commit();

            return redirect()->route('kasus.show', $kasus->id)->with('success', 'Data berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $kasus = Kasus::findOrFail($id);
            
            // Delete relasi many-to-many
            $kasus->kejadianFraud()->detach();
            $kasus->jenisFraud()->detach();
            $kasus->lokasiFraud()->detach();
            $kasus->kelemahanFraud()->detach();
            $kasus->penangananFraud()->detach();
            
            // Delete relasi one-to-many
            $kasus->pelakuFrauds()->delete();
            $kasus->pencegahanFraud()->delete();
            $kasus->waktuFraud()->delete();
            $kasus->kerugianFraud()->delete();
            
            // Delete kasus
            $kasus->delete();
            
            DB::commit();

            return redirect()->route('kasus.index')->with('success', 'Data kasus berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('kasus.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // ================= EXPORT =================
    public function export(Request $request)
    {
        // Map form parameters to filter keys
        $filters = [
            'search' => $request->get('search'),
            'status_penanganan' => $request->get('status_penanganan'),
            'jenis_fraud' => $request->get('jenis_fraud'),
            'jenis_laporan' => $request->get('jenis_laporan'),
            'tanggal_awal' => $request->get('dari_tanggal') ?? $request->get('tanggal_awal'),
            'tanggal_akhir' => $request->get('sampai_tanggal') ?? $request->get('tanggal_akhir'),
        ];

        $service = new ExportService();

        $kasus = $service->getFilteredKasus($filters);
        
        // Separate semester and signifikan data
        $semesterKasus = $kasus->filter(function($k) { return $k->jenis_laporan === 'semester'; });
        $signifikanKasus = $kasus->filter(function($k) { return $k->jenis_laporan === 'signifikan'; });
        
        $semesterData = $service->prepareExportDataSemester($semesterKasus);
        $signifikanData = $service->prepareExportDataSignifikan($signifikanKasus);
        $summary = $service->getSummary($kasus);
        $jenisFraudOptions = RefJenisFraud::orderBy('nama')->get();

        return view('kasus.export', compact('kasus', 'semesterData', 'signifikanData', 'summary', 'jenisFraudOptions'));
    }

    public function exportExcel(Request $request)
    {
        // Map form parameters to filter keys
        $filters = [
            'search' => $request->get('search'),
            'status_penanganan' => $request->get('status_penanganan'),
            'jenis_fraud' => $request->get('jenis_fraud'),
            'jenis_laporan' => $request->get('jenis_laporan'),
            'tanggal_awal' => $request->get('dari_tanggal') ?? $request->get('tanggal_awal'),
            'tanggal_akhir' => $request->get('sampai_tanggal') ?? $request->get('tanggal_akhir'),
        ];

        $service = new ExportService();

        $kasus = $service->getFilteredKasus($filters);
        $semesterKasus = $kasus->where('jenis_laporan', 'semester');
        $signifikanKasus = $kasus->where('jenis_laporan', 'signifikan');

        $semesterData = $service->prepareExportDataSemester($semesterKasus);
        $signifikanData = $service->prepareExportDataSignifikan($signifikanKasus);

        // Create Excel using PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Semester Sheet
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Semester');
        $this->createSemesterHeaders($sheet1);
        $this->styleSemesterHeaders($sheet1);

        // Set data for Semester starting at row 4
        $row = 4;
        foreach ($semesterData['data'] as $dataRow) {
            $colIndex = 1;
            foreach ($dataRow as $value) {
                $sheet1->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, $value);
                $colIndex++;
            }
            $row++;
        }

        // Signifikan Sheet
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Signifikan');
        $this->createSignifikanHeaders($sheet2);
        $this->styleSignifikanHeaders($sheet2);

        // Set data for Signifikan starting at row 4
        $row = 4;
        foreach ($signifikanData['data'] as $dataRow) {
            $colIndex = 1;
            foreach ($dataRow as $value) {
                $sheet2->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, $value);
                $colIndex++;
            }
            $row++;
        }

        // Auto size columns for all sheets
        foreach ($spreadsheet->getAllSheets() as $sheet) {
            for ($col = 1; $col <= max(count($semesterData['headers']), count($signifikanData['headers'])); $col++) {
                $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
            }
        }

        // Download
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'laporan-kasus-fraud-' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }


    private function createSemesterHeaders($sheet)
    {
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Komponen');
        $sheet->setCellValue('C1', 'Kejadian Fraud Menurut Pelaku');
        $sheet->setCellValue('D1', 'ID Kejadian Fraud');
        $sheet->setCellValue('E1', 'Jenis Fraud');
        $sheet->setCellValue('H1', 'Deskripsi Fraud / Modus Operandi');
        $sheet->setCellValue('I1', 'Lokasi Fraud');
        $sheet->setCellValue('K1', 'Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud');
        $sheet->setCellValue('L1', 'Pihak Yang Dirugikan');
        $sheet->setCellValue('M1', 'Waktu');
        $sheet->setCellValue('P1', 'Jumlah Kerugian');
        $sheet->setCellValue('Y1', 'Kelemahan Penyebab Fraud');
        $sheet->setCellValue('AA1', 'Tindakan untuk Penanganan Fraud');
        $sheet->setCellValue('AC1', 'Tindakan Perbaikan untuk Pencegahan Fraud');
        $sheet->setCellValue('AG1', 'Pelaku Fraud');
        $sheet->setCellValue('AW1', 'Status Penanganan');

        // Row 2 - Sub categories
        $sheet->setCellValue('E2', 'Jenis Fraud');
        $sheet->setCellValue('F2', 'Keterangan Jenis Fraud');
        $sheet->setCellValue('G1', 'Aktivitas Terkait Fraud');
        $sheet->setCellValue('I2', 'Lokasi Fraud');
        $sheet->setCellValue('J2', 'Keterangan Lokasi Fraud');
        $sheet->setCellValue('M2', 'Waktu Terjadi');
        $sheet->setCellValue('O2', 'Fraud Diketahui');
        $sheet->setCellValue('P2', 'LJK');
        $sheet->setCellValue('S2', 'Konsumen');
        $sheet->setCellValue('V2', 'Pihak Lain');
        $sheet->setCellValue('Y2', 'Kelemahan Penyebab Fraud');
        $sheet->setCellValue('Z2', 'Keterangan');
        $sheet->setCellValue('AA2', 'Tindakan untuk Penanganan Fraud');
        $sheet->setCellValue('AB2', 'Keterangan');
        $sheet->setCellValue('AC2', 'Tindakan Perbaikan untuk Pencegahan Fraud');
        $sheet->setCellValue('AD2', 'Keterangan');
        $sheet->setCellValue('AE2', 'Target Waktu Pelaksanaan');
        $sheet->setCellValue('AF2', 'Realisasi Pelaksanaan');
        $sheet->setCellValue('AG2', 'Internal/Eksternal');
        $sheet->setCellValue('AH2', 'Identitas Pelaku');
        $sheet->setCellValue('AP2', 'Jabatan Pelaku');
        $sheet->setCellValue('AT2', 'Keterangan Pelaku');
        $sheet->setCellValue('AU2', 'Status Pelaku');
        $sheet->setCellValue('AV2', 'Pengenaan Sanksi');

        // Row 3 - Final headers
        $sheet->setCellValue('M3', 'Awal');
        $sheet->setCellValue('N3', 'Akhir');
        $sheet->setCellValue('P3', 'Rill');
        $sheet->setCellValue('Q3', 'Pot');
        $sheet->setCellValue('R3', 'Rec');
        $sheet->setCellValue('S3', 'Rill');
        $sheet->setCellValue('T3', 'Pot');
        $sheet->setCellValue('U3', 'Rec');
        $sheet->setCellValue('V3', 'Rill');
        $sheet->setCellValue('W3', 'Pot');
        $sheet->setCellValue('X3', 'Rec');
        $sheet->setCellValue('AH3', 'Nama');
        $sheet->setCellValue('AI3', 'Jenis Identitas');
        $sheet->setCellValue('AJ3', 'Nomor Identitas');
        $sheet->setCellValue('AK3', 'Jenis Kelamin');
        $sheet->setCellValue('AL3', 'Tempat Lahir');
        $sheet->setCellValue('AM3', 'Tanggal Lahir');
        $sheet->setCellValue('AN3', 'Alamat Identitas');
        $sheet->setCellValue('AO3', 'Alamat Domisili');
        $sheet->setCellValue('AP3', 'Pada Saat Fraud Terjadi');
        $sheet->setCellValue('AQ3', 'Keterangan');
        $sheet->setCellValue('AR3', 'Pada Saat Fraud Diketahui');
        $sheet->setCellValue('AS3', 'Keterangan');

        // Merge cells for colspan/rowspan
        $sheet->mergeCells('E1:F1'); // Jenis Fraud
        $sheet->mergeCells('I1:J1'); // Lokasi Fraud
        $sheet->mergeCells('M1:O1'); // Waktu
        $sheet->mergeCells('P1:X1'); // Jumlah Kerugian
        $sheet->mergeCells('Y1:Z1'); // Kelemahan Penyebab Fraud
        $sheet->mergeCells('AA1:AB1'); // Tindakan untuk Penanganan Fraud
        $sheet->mergeCells('AC1:AF1'); // Tindakan Perbaikan untuk Pencegahan Fraud
        $sheet->mergeCells('AG1:AV1'); // Pelaku Fraud

        $sheet->mergeCells('A1:A3'); // No
        $sheet->mergeCells('B1:B3'); // Kode Komponen
        $sheet->mergeCells('C1:C3'); // Kejadian Fraud Menurut Pelaku
        $sheet->mergeCells('D1:D3'); // ID Kejadian Fraud
        $sheet->mergeCells('G1:G3'); // Aktivitas Terkait Fraud
        $sheet->mergeCells('H1:H3'); // Deskripsi Fraud / Modus Operandi
        $sheet->mergeCells('K1:K3'); // Divisi atau Unit Kerja...
        $sheet->mergeCells('L1:L3'); // Pihak Yang Dirugikan
        $sheet->mergeCells('AW1:AW3'); // Status Penanganan

        $sheet->mergeCells('M2:N2'); // Waktu Terjadi
        $sheet->mergeCells('P2:R2'); // LJK
        $sheet->mergeCells('S2:U2'); // Konsumen
        $sheet->mergeCells('V2:X2'); // Pihak Lain
        $sheet->mergeCells('AH2:AO2'); // Identitas Pelaku
        $sheet->mergeCells('AP2:AS2'); // Jabatan Pelaku
    }

    private function createSignifikanHeaders($sheet)
    {
        // Row 1 - Main categories
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Komponen');
        $sheet->setCellValue('C1', 'Kejadian Fraud Menurut Pelaku');
        $sheet->setCellValue('D1', 'ID Kejadian Fraud');
        $sheet->setCellValue('E1', 'Jenis Fraud');
        $sheet->setCellValue('G1', 'Aktivitas Terkait Fraud');
        $sheet->setCellValue('H1', 'Deskripsi Fraud / Modus Operandi');
        $sheet->setCellValue('I1', 'Lokasi Fraud');
        $sheet->setCellValue('K1', 'Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud');
        $sheet->setCellValue('L1', 'Pihak Yang Dirugikan');
        $sheet->setCellValue('M1', 'Jumlah Kerugian Potensial');
        $sheet->setCellValue('N1', 'Tindak Lanjut LJK');
        $sheet->setCellValue('O1', 'Waktu');
        $sheet->setCellValue('R1', 'Pelaku Fraud');
        $sheet->setCellValue('AH1', 'Status Penanganan');

        // Row 2 - Sub categories
        $sheet->setCellValue('E2', 'Jenis Fraud');
        $sheet->setCellValue('F2', 'Keterangan Jenis Fraud');
        $sheet->setCellValue('I2', 'Lokasi Fraud');
        $sheet->setCellValue('J2', 'Keterangan Lokasi Fraud');
        $sheet->setCellValue('O2', 'Waktu Terjadi');
        $sheet->setCellValue('Q2', 'Fraud Diketahui');
        $sheet->setCellValue('R2', 'Internal/Eksternal');
        $sheet->setCellValue('S2', 'Identitas Pelaku');
        $sheet->setCellValue('AA2', 'Jabatan Pelaku');
        $sheet->setCellValue('AE2', 'Keterangan Pelaku');
        $sheet->setCellValue('AF2', 'Status Pelaku');
        $sheet->setCellValue('AG2', 'Pengenaan Sanksi');

        // Row 3 - Final headers
        $sheet->setCellValue('O3', 'Awal');
        $sheet->setCellValue('P3', 'Akhir');
        $sheet->setCellValue('S3', 'Nama');
        $sheet->setCellValue('T3', 'Jenis Identitas');
        $sheet->setCellValue('U3', 'Nomor Identitas');
        $sheet->setCellValue('V3', 'Jenis Kelamin');
        $sheet->setCellValue('W3', 'Tempat Lahir');
        $sheet->setCellValue('X3', 'Tanggal Lahir');
        $sheet->setCellValue('Y3', 'Alamat Identitas');
        $sheet->setCellValue('Z3', 'Alamat Domisili');
        $sheet->setCellValue('AA3', 'Pada Saat Fraud Terjadi');
        $sheet->setCellValue('AB3', 'Keterangan');
        $sheet->setCellValue('AC3', 'Pada Saat Fraud Diketahui');
        $sheet->setCellValue('AD3', 'Keterangan');

        // Merge cells for colspan/rowspan
        $sheet->mergeCells('E1:F1'); // Jenis Fraud
        $sheet->mergeCells('I1:J1'); // Lokasi Fraud
        $sheet->mergeCells('O1:Q1'); // Waktu
        $sheet->mergeCells('R1:AG1'); // Pelaku Fraud

        $sheet->mergeCells('A1:A3'); // No
        $sheet->mergeCells('B1:B3'); // Kode Komponen
        $sheet->mergeCells('C1:C3'); // Kejadian Fraud Menurut Pelaku
        $sheet->mergeCells('D1:D3'); // ID Kejadian Fraud
        $sheet->mergeCells('G1:G3'); // Aktivitas Terkait Fraud
        $sheet->mergeCells('H1:H3'); // Deskripsi Fraud / Modus Operandi
        $sheet->mergeCells('K1:K3'); // Divisi atau Unit Kerja...
        $sheet->mergeCells('L1:L3'); // Pihak Yang Dirugikan
        $sheet->mergeCells('M1:M3'); // Jumlah Kerugian Potensial
        $sheet->mergeCells('N1:N3'); // Tindak Lanjut LJK
        $sheet->mergeCells('AH1:AH3'); // Status Penanganan

        $sheet->mergeCells('O2:P2'); // Waktu Terjadi
        $sheet->mergeCells('S2:Z2'); // Identitas Pelaku
        $sheet->mergeCells('AA2:AD2'); // Jabatan Pelaku
    }

    private function styleSemesterHeaders($sheet)
    {
        // Style for all header rows
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC2626'], // Red color like in the table
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A1:AW3')->applyFromArray($headerStyle);

        // Set row heights for better readability
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(25);
        $sheet->getRowDimension(3)->setRowHeight(25);
    }

    private function styleSignifikanHeaders($sheet)
    {
        // Style for all header rows
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC2626'], // Red color like in the table
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A1:AH3')->applyFromArray($headerStyle);

        // Set row heights for better readability
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(25);
        $sheet->getRowDimension(3)->setRowHeight(25);
    }

    public function exportPdf(Request $request)
    {
        // Map form parameters to filter keys
        $filters = [
            'search' => $request->get('search'),
            'status_penanganan' => $request->get('status_penanganan'),
            'jenis_fraud' => $request->get('jenis_fraud'),
            'jenis_laporan' => $request->get('jenis_laporan'),
            'tanggal_awal' => $request->get('dari_tanggal') ?? $request->get('tanggal_awal'),
            'tanggal_akhir' => $request->get('sampai_tanggal') ?? $request->get('tanggal_akhir'),
        ];

        $service = new ExportService();

        $kasus = $service->getFilteredKasus($filters);
        $semesterKasus = $kasus->where('jenis_laporan', 'semester');
        $signifikanKasus = $kasus->where('jenis_laporan', 'signifikan');

        $semesterData = $service->prepareExportDataSemester($semesterKasus);
        $signifikanData = $service->prepareExportDataSignifikan($signifikanKasus);
        $summary = $service->getSummary($kasus);

        $reportType = $request->get('report_type') ?? $request->get('jenis_laporan');

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('kasus.export-pdf', [
            'semesterData' => $semesterData,
            'signifikanData' => $signifikanData,
            'summary' => $summary,
            'filters' => $filters,
            'reportType' => $reportType,
            'dari_tanggal' => $request->get('dari_tanggal') ?? $request->get('tanggal_awal') ?? '-',
            'sampai_tanggal' => $request->get('sampai_tanggal') ?? $request->get('tanggal_akhir') ?? '-',
        ]);

        $filename = 'laporan-kasus-fraud' . ($reportType ? '-' . $reportType : '') . '-' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }
}
