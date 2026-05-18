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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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
        ])->where('user_id', auth()->id());

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

        // Pagination (urutkan dari kasus pertama yang dibuat ke yang paling baru)
        $kasus = $query->orderBy('created_at', 'asc')->paginate(10)->withQueryString();

        // Data untuk filter dropdown
        $jenisFraudOptions = RefJenisFraud::orderBy('nama')->get();

        return view('kasus.index', compact('kasus', 'jenisFraudOptions'));
    }

    private function buildExportQuery(Request $request)
    {
        $search = $request->get('search');
        $status_penanganan = $request->get('status_penanganan');
        $jenis_fraud = $request->get('jenis_fraud');
        $jenis_laporan = $request->get('jenis_laporan');
        $tanggal_awal = $request->get('tanggal_awal');
        $tanggal_akhir = $request->get('tanggal_akhir');

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
            'pencegahanFraud' => function ($query) {
                $query->with('refPencegahan');
            },
            'pelakuFrauds' => function ($query) {
                $query->with(['jenisIdentitas', 'statusPelaku', 'jabatanKejadian', 'jabatanDiketahui']);
            }
        ])->where('user_id', auth()->id());

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_komponen', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi_fraud', 'like', '%' . $search . '%')
                  ->orWhere('divisi_unit', 'like', '%' . $search . '%')
                  ->orWhereHas('pelakuFrauds', function ($pelakuQuery) use ($search) {
                      $pelakuQuery->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        $query->when($status_penanganan, function ($q) use ($status_penanganan) {
            return $q->where('status_penanganan', $status_penanganan);
        });

        $query->when($jenis_fraud, function ($q) use ($jenis_fraud) {
            return $q->whereHas('jenisFraud', function ($jenisQuery) use ($jenis_fraud) {
                $jenisQuery->where('ref_jenis_fraud.id', $jenis_fraud);
            });
        });

        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereHas('waktuFraud', function ($waktuQuery) use ($tanggal_awal, $tanggal_akhir) {
                $waktuQuery->whereBetween('waktu_awal', [$tanggal_awal, $tanggal_akhir])
                          ->orWhereBetween('waktu_akhir', [$tanggal_awal, $tanggal_akhir]);
            });
        } elseif ($tanggal_awal) {
            $query->whereHas('waktuFraud', function ($waktuQuery) use ($tanggal_awal) {
                $waktuQuery->where('waktu_awal', '>=', $tanggal_awal)
                          ->orWhere('waktu_akhir', '>=', $tanggal_awal);
            });
        } elseif ($tanggal_akhir) {
            $query->whereHas('waktuFraud', function ($waktuQuery) use ($tanggal_akhir) {
                $waktuQuery->where('waktu_awal', '<=', $tanggal_akhir)
                          ->orWhere('waktu_akhir', '<=', $tanggal_akhir);
            });
        }

        $query->when($jenis_laporan, function ($q) use ($jenis_laporan) {
            return $q->where('jenis_laporan', $jenis_laporan);
        });

        return $query->orderBy('created_at', 'asc');
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

    // Helper function untuk konversi nilai numerik dari form
    private function parseNumericField($value)
    {
        if (is_null($value)) {
            return null;
        }

        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        // Remove formatting characters
        $value = str_replace(['Rp', '.', ',', ' '], '', $value);
        return is_numeric($value) ? (int) $value : null;
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
                'user_id' => auth()->id(),
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
// ================= KERUGIAN =================
KerugianFraud::create([
    'kasus_id' => $kasus->id,
    
    // Ganti semua angka 0 menjadi null
    'ljk_rill' => $request->jenis_laporan === 'signifikan' ? null : $this->parseNumericField($request->ljk_rill),
    'ljk_potensial' => $this->parseNumericField($request->ljk_potensial),
    'ljk_recovery' => $request->jenis_laporan === 'signifikan' ? null : $this->parseNumericField($request->ljk_recovery),
    
    'konsumen_rill' => $request->jenis_laporan === 'signifikan' ? null : $this->parseNumericField($request->konsumen_rill),
    'konsumen_potensial' => $this->parseNumericField($request->konsumen_potensial),
    'konsumen_recovery' => $request->jenis_laporan === 'signifikan' ? null : $this->parseNumericField($request->konsumen_recovery),
    
    'pihak_lain_rill' => $request->jenis_laporan === 'signifikan' ? null : $this->parseNumericField($request->pihak_lain_rill),
    'pihak_lain_potensial' => $this->parseNumericField($request->pihak_lain_potensial),
    'pihak_lain_recovery' => $request->jenis_laporan === 'signifikan' ? null : $this->parseNumericField($request->pihak_lain_recovery),
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
        ])->where('user_id', auth()->id())->findOrFail($id);
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
        ])->where('user_id', auth()->id())->findOrFail($id);
        
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
            $kasus = Kasus::where('user_id', auth()->id())->findOrFail($id);

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
$kerugianData = [
    'ljk_rill' => $request->jenis_laporan === 'signifikan' ? null : $this->parseNumericField($request->ljk_rill),
    'ljk_potensial' => $this->parseNumericField($request->ljk_potensial),
    'ljk_recovery' => $request->jenis_laporan === 'signifikan' ? null : $this->parseNumericField($request->ljk_recovery),
    
    'konsumen_rill' => $request->jenis_laporan === 'signifikan' ? null : $this->parseNumericField($request->konsumen_rill),
    'konsumen_potensial' => $this->parseNumericField($request->konsumen_potensial),
    'konsumen_recovery' => $request->jenis_laporan === 'signifikan' ? null : $this->parseNumericField($request->konsumen_recovery),
    
    'pihak_lain_rill' => $request->jenis_laporan === 'signifikan' ? null : $this->parseNumericField($request->pihak_lain_rill),
    'pihak_lain_potensial' => $this->parseNumericField($request->pihak_lain_potensial),
    'pihak_lain_recovery' => $request->jenis_laporan === 'signifikan' ? null : $this->parseNumericField($request->pihak_lain_recovery),
];

if ($kasus->kerugianFraud) {
    $kasus->kerugianFraud->update($kerugianData);
} else {
    // Tambahkan kasus_id jika membuat data baru
    $kerugianData['kasus_id'] = $kasus->id;
    KerugianFraud::create($kerugianData);
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
            $kasus = Kasus::where('user_id', auth()->id())->findOrFail($id);
            
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
        $sheet1->setTitle('01A');
        $this->createSemesterHeaders($sheet1);
        $this->setSheetTitle($sheet1, '01A - Laporan Penerapan SAF yang mencakup informasi kejadian fraud dan informasi pelaku fraud', 'AW');
        $this->styleSemesterHeaders($sheet1);
        $this->setSemesterColumnWidths($sheet1);

        // Set data for Semester starting at row 6 (01A template has a blank row after header)
        $row = 6;
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
        $sheet2->setTitle('01B');
        $this->createSignifikanHeaders($sheet2);
        $this->setSheetTitle($sheet2, '01B - Laporan Penerapan SAF berdampak signifikan yang mencakup informasi kejadian fraud dan informasi pelaku fraud berdampak signifikan', 'AH');
        $this->styleSignifikanHeaders($sheet2);
        $this->setSignifikanColumnWidths($sheet2);

        // Set data for Signifikan starting at row 6
        $row = 6;
        foreach ($signifikanData['data'] as $dataRow) {
            $colIndex = 1;
            foreach ($dataRow as $value) {
                $sheet2->setCellValue(Coordinate::stringFromColumnIndex($colIndex) . $row, $value);
                $colIndex++;
            }
            $row++;
        }

        // Format numeric kerugian values in exported Excel (comma separator, no decimals)
        $semesterLastRow = $sheet1->getHighestRow();
        if ($semesterLastRow >= 6) {
            $sheet1->getStyle('P6:X' . $semesterLastRow)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
        }

        $signifikanLastRow = $sheet2->getHighestRow();
        if ($signifikanLastRow >= 6) {
            $sheet2->getStyle('M6:M' . $signifikanLastRow)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
        }

        // Keep vertical top alignment on data rows and wrap text so rows grow with content.
        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $lastRow = $sheet->getHighestRow();
            $lastColumn = $sheet->getHighestColumn();

            $sheet->getStyle('A6:' . $lastColumn . $lastRow)
                ->getAlignment()
                ->setWrapText(true)
                ->setVertical(Alignment::VERTICAL_TOP)
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);

            for ($i = 6; $i <= $lastRow; $i++) {
                $sheet->getRowDimension($i)->setRowHeight(-1);
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
    // --- ROW 2: Primary Headings ---
    $sheet->setCellValue('A2', 'No');
    $sheet->setCellValue('B2', 'Kode Komponen');
    $sheet->setCellValue('C2', 'Kejadian Fraud Menurut Pelaku');
    $sheet->setCellValue('D2', 'ID Kejadian Fraud');
    $sheet->setCellValue('E2', 'Jenis Fraud');
    $sheet->setCellValue('G2', 'Aktivitas Terkait Fraud');
    $sheet->setCellValue('H2', 'Deskripsi Fraud / Modus Operandi');
    $sheet->setCellValue('I2', 'Lokasi Fraud');
    $sheet->setCellValue('K2', 'Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud');
    $sheet->setCellValue('L2', 'Pihak Yang Dirugikan');
    $sheet->setCellValue('M2', 'Waktu');
    $sheet->setCellValue('P2', 'Jumlah Kerugian');
    $sheet->setCellValue('Y2', 'Kelemahan Penyebab Fraud');
    $sheet->setCellValue('AA2', 'Tindakan untuk Penanganan Fraud');
    $sheet->setCellValue('AC2', 'Tindakan Perbaikan untuk Pencegahan Fraud');

    // Pelaku Fraud sekarang membawahi AG sampai AV
    $sheet->setCellValue('AG2', 'Pelaku Fraud'); 
    
    // Status Penanganan tetap di pojok kanan dari Baris 2
    $sheet->setCellValue('AW2', 'Status Penanganan');

    // --- ROW 3: Secondary Headings (Semua Sejajar) ---
    $sheet->setCellValue('E3', 'Jenis Fraud');
    $sheet->setCellValue('F3', 'Keterangan Jenis Fraud');
    $sheet->setCellValue('I3', 'Lokasi Fraud');
    $sheet->setCellValue('J3', 'Keterangan Lokasi Fraud');
    $sheet->setCellValue('M3', 'Waktu Terjadi');
    $sheet->setCellValue('O3', 'Fraud Diketahui');
    $sheet->setCellValue('P3', 'LJK');
    $sheet->setCellValue('S3', 'Konsumen');
    $sheet->setCellValue('V3', 'Pihak Lain');
    $sheet->setCellValue('Y3', 'Kelemahan Penyebab Fraud');
    $sheet->setCellValue('Z3', 'Keterangan');
    $sheet->setCellValue('AA3', 'Tindakan untuk Penanganan Fraud');
    $sheet->setCellValue('AB3', 'Keterangan');
    $sheet->setCellValue('AC3', 'Tindakan Perbaikan untuk Pencegahan Fraud');
    $sheet->setCellValue('AD3', 'Keterangan');
    $sheet->setCellValue('AE3', 'Target Waktu Pelaksanaan');
    $sheet->setCellValue('AF3', 'Realisasi Pelaksanaan');
    
    // BAGIAN PELAKU (BARIS 3 SEJAJAR)
    $sheet->setCellValue('AG3', 'Internal / Eksternal');
    $sheet->setCellValue('AH3', 'Identitas Pelaku'); 
    $sheet->setCellValue('AP3', 'Status Pelaku'); // Status Pelaku
    $sheet->setCellValue('AQ3', 'Jabatan Pelaku'); // Sejajar Identitas
    $sheet->setCellValue('AU3', 'Keterangan Pelaku'); // SEJAJAR Status Pelaku (Permintaan Anda)
    $sheet->setCellValue('AV3', 'Pengenaan Sanksi'); // SEJAJAR Status Pelaku (Permintaan Anda)

    // --- ROW 4: Final Detail Headings (Semua Sejajar) ---
    $sheet->setCellValue('M4', 'Awal');
    $sheet->setCellValue('N4', 'Akhir');
    $sheet->setCellValue('P4', 'Riil (incurred)');
    $sheet->setCellValue('Q4', 'Potensial (Potential)');
    $sheet->setCellValue('R4', 'Setelah Pengembalian (Recovery)');
    $sheet->setCellValue('S4', 'Riil (incurred)');
    $sheet->setCellValue('T4', 'Potensial (Potential)');
    $sheet->setCellValue('U4', 'Setelah Pengembalian (Recovery)');
    $sheet->setCellValue('V4', 'Riil (incurred)');
    $sheet->setCellValue('W4', 'Potensial (Potential)');
    $sheet->setCellValue('X4', 'Setelah Pengembalian (Recovery)');

    // Detail Identitas
    $sheet->setCellValue('AH4', 'Nama');
    $sheet->setCellValue('AI4', 'Jenis Identitas');
    $sheet->setCellValue('AJ4', 'Nomor Identitas');
    $sheet->setCellValue('AK4', 'Jenis Kelamin');
    $sheet->setCellValue('AL4', 'Alamat Identitas');
    $sheet->setCellValue('AM4', 'Alamat Domisili');
    $sheet->setCellValue('AN4', 'Tempat Lahir');
    $sheet->setCellValue('AO4', 'Tanggal Lahir');

    // Detail Jabatan (Sejajar Tanggal Lahir)
    $sheet->setCellValue('AQ4', 'Pada Saat Fraud Terjadi');
    $sheet->setCellValue('AR4', 'Keterangan Jabatan');
    $sheet->setCellValue('AS4', 'Pada Saat Fraud Diketahui');
    $sheet->setCellValue('AT4', 'Keterangan Jabatan');

    // --- MERGING CELLS ---
    
    // Kolom-kolom standar (A-AF)
    $sheet->mergeCells('A2:A4'); $sheet->mergeCells('B2:B4');
    $sheet->mergeCells('C2:C4'); $sheet->mergeCells('D2:D4');
    $sheet->mergeCells('E2:F2'); $sheet->mergeCells('E3:E4'); $sheet->mergeCells('F3:F4');
    $sheet->mergeCells('G2:G4'); $sheet->mergeCells('H2:H4');
    $sheet->mergeCells('I2:J2'); $sheet->mergeCells('I3:I4'); $sheet->mergeCells('J3:J4');
    $sheet->mergeCells('K2:K4'); $sheet->mergeCells('L2:L4');
    $sheet->mergeCells('M2:O2'); $sheet->mergeCells('M3:N3'); $sheet->mergeCells('O3:O4');
    $sheet->mergeCells('P2:X2'); $sheet->mergeCells('P3:R3'); $sheet->mergeCells('S3:U3'); $sheet->mergeCells('V3:X3');
    $sheet->mergeCells('Y2:Z2'); $sheet->mergeCells('Y3:Y4'); $sheet->mergeCells('Z3:Z4');
    $sheet->mergeCells('AA2:AB2'); $sheet->mergeCells('AA3:AA4'); $sheet->mergeCells('AB3:AB4');
    $sheet->mergeCells('AC2:AF2'); $sheet->mergeCells('AC3:AC4'); $sheet->mergeCells('AD3:AD4'); $sheet->mergeCells('AE3:AE4'); $sheet->mergeCells('AF3:AF4');

    // STRUKTUR PELAKU FRAUD (AG - AV)
    $sheet->mergeCells('AG2:AV2'); // Header besar memayungi sampai Sanksi
    $sheet->mergeCells('AG3:AG4'); // Internal/Eksternal vertikal
    $sheet->mergeCells('AH3:AO3'); // Identitas Pelaku Group
    $sheet->mergeCells('AP3:AP4'); // Status Pelaku vertikal
    $sheet->mergeCells('AQ3:AT3'); // Jabatan Pelaku Group
    $sheet->mergeCells('AU3:AU4'); // Keterangan Pelaku vertikal (Sejajar Status)
    $sheet->mergeCells('AV3:AV4'); // Pengenaan Sanksi vertikal (Sejajar Status)

    // KOLOM STATUS PENANGANAN (Paling Kanan)
    $sheet->mergeCells('AW2:AW4');
}

    private function createSignifikanHeaders($sheet)
{
    // --- ROW 2: Primary Headings ---
    $sheet->setCellValue('A2', 'No');
    $sheet->setCellValue('B2', 'Kode Komponen');
    $sheet->setCellValue('C2', 'Kejadian Fraud Menurut Pelaku');
    $sheet->setCellValue('D2', 'ID Kejadian Fraud');
    $sheet->setCellValue('E2', 'Jenis Fraud');
    $sheet->setCellValue('G2', 'Aktivitas Terkait Fraud');
    $sheet->setCellValue('H2', 'Deskripsi Fraud / Modus Operandi');
    $sheet->setCellValue('I2', 'Lokasi Fraud');
    $sheet->setCellValue('K2', 'Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud');
    $sheet->setCellValue('L2', 'Pihak Yang Dirugikan');
    $sheet->setCellValue('M2', 'Jumlah Kerugian Potensial');
    $sheet->setCellValue('N2', 'Tindak Lanjut LJK');
    $sheet->setCellValue('O2', 'Waktu');
    $sheet->setCellValue('R2', 'Pelaku Fraud');
    $sheet->setCellValue('AH2', 'Status Penanganan');

    // --- ROW 3: Secondary Headings ---
    $sheet->setCellValue('E3', 'Jenis Fraud');
    $sheet->setCellValue('F3', 'Keterangan Jenis Fraud');
    $sheet->setCellValue('I3', 'Lokasi Fraud');
    $sheet->setCellValue('J3', 'Keterangan Lokasi Fraud');
    $sheet->setCellValue('O3', 'Waktu Terjadi');
    $sheet->setCellValue('Q3', 'Fraud Diketahui');
    $sheet->setCellValue('R3', 'Internal / Eksternal');
    $sheet->setCellValue('S3', 'Nama');
    $sheet->setCellValue('T3', 'Jenis Identitas');
    $sheet->setCellValue('U3', 'Nomor Identitas');
    $sheet->setCellValue('V3', 'Jenis Kelamin');
    $sheet->setCellValue('W3', 'Tempat Lahir');
    $sheet->setCellValue('X3', 'Tanggal Lahir');
    $sheet->setCellValue('Y3', 'Alamat Identitas');
    $sheet->setCellValue('Z3', 'Alamat Domisili');
    $sheet->setCellValue('AA3', 'Status Pelaku');
    $sheet->setCellValue('AB3', 'Jabatan Pelaku');
    $sheet->setCellValue('AF3', 'Keterangan Pelaku');
    $sheet->setCellValue('AG3', 'Pengenaan Sanksi');
    $sheet->setCellValue('AH3', 'Status Penanganan');

    // --- ROW 4: Detail Headings ---
    $sheet->setCellValue('O4', 'Awal');
    $sheet->setCellValue('P4', 'Akhir');
    $sheet->setCellValue('AB4', 'Pada Saat Fraud Terjadi');
    $sheet->setCellValue('AC4', 'Keterangan Jabatan');
    $sheet->setCellValue('AD4', 'Pada Saat Fraud Diketahui');
    $sheet->setCellValue('AE4', 'Keterangan Jabatan');

    // --- MERGE CELLS ---
    $sheet->mergeCells('A2:A4');
    $sheet->mergeCells('B2:B4');
    $sheet->mergeCells('C2:C4');
    $sheet->mergeCells('D2:D4');
    $sheet->mergeCells('E2:F2');
    $sheet->mergeCells('E3:E4');
    $sheet->mergeCells('F3:F4');
    $sheet->mergeCells('G2:G4');
    $sheet->mergeCells('H2:H4');
    $sheet->mergeCells('I2:J2');
    $sheet->mergeCells('I3:I4');
    $sheet->mergeCells('J3:J4');
    $sheet->mergeCells('K2:K4');
    $sheet->mergeCells('L2:L4');
    $sheet->mergeCells('M2:M4');
    $sheet->mergeCells('N2:N4');
    $sheet->mergeCells('O2:Q2');
    $sheet->mergeCells('O3:P3');
    $sheet->mergeCells('Q3:Q4');
    $sheet->mergeCells('R2:AG2');
    $sheet->mergeCells('R3:R4');
    $sheet->mergeCells('S3:S4');
    $sheet->mergeCells('T3:T4');
    $sheet->mergeCells('U3:U4');
    $sheet->mergeCells('V3:V4');
    $sheet->mergeCells('W3:W4');
    $sheet->mergeCells('X3:X4');
    $sheet->mergeCells('Y3:Y4');
    $sheet->mergeCells('Z3:Z4');
    $sheet->mergeCells('AA3:AA4');
    $sheet->mergeCells('AB3:AE3');
    $sheet->mergeCells('AF3:AF4');
    $sheet->mergeCells('AG3:AG4');
    $sheet->mergeCells('AH2:AH4');
}

    private function styleSemesterHeaders($sheet)
{
    // Style untuk seluruh header (Baris 2 sampai 4)
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'FF0000'], 
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
    ];

    // Terapkan style header hingga kolom AW
    $sheet->getStyle('A2:AW4')->applyFromArray($headerStyle);

    // Style untuk Baris Penomoran (Baris 5) - Diperluas hingga AW
    $sheet->getStyle('A5:AW5')->applyFromArray([
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'BFBFBF'], // Abu-abu
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
    ]);
    
    // --- PENOMORAN KOLOM ---
    // Kolom A (index 1) dibiarkan kosong sesuai permintaan
    $sheet->setCellValue('A5', ''); 

    // Perulangan dari kolom B (index 2) sampai AW (index 49)
    // Penomoran dimulai dari angka 2
    for ($col = 2; $col <= 49; $col++) {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        
        // Menggunakan $col sebagai nilai karena ingin mulai dari 2 di kolom B
        $sheet->setCellValue($colLetter . '5', (string)$col);
    }

    // Set row heights agar proporsional
    $sheet->getRowDimension(1)->setRowHeight(21);
    $sheet->getRowDimension(2)->setRowHeight(15.6);
    $sheet->getRowDimension(3)->setRowHeight(15.6);
    $sheet->getRowDimension(4)->setRowHeight(46.8);
    $sheet->getRowDimension(5)->setRowHeight(15);
}

    private function styleSignifikanHeaders($sheet)
    {
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FF0000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A2:AH4')->applyFromArray($headerStyle);
        $sheet->getStyle('A5:AH5')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'BFBFBF'],
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
        ]);
        
        for ($col = 2; $col <= 34; $col++) {
            $colLetter = Coordinate::stringFromColumnIndex($col);
            $sheet->setCellValue($colLetter . '5', (string)$col);
        }

        $sheet->getRowDimension(1)->setRowHeight(21);
        $sheet->getRowDimension(2)->setRowHeight(15.6);
        $sheet->getRowDimension(3)->setRowHeight(15.6);
        $sheet->getRowDimension(4)->setRowHeight(46.8);
        $sheet->getRowDimension(5)->setRowHeight(15);
    }

    private function setSheetTitle($sheet, string $title, string $lastColumn)
    {
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells(sprintf('A1:%s1', $lastColumn));
        $sheet->getStyle(sprintf('A1:%s1', $lastColumn))->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    private function setSemesterColumnWidths($sheet)
    {
        $widths = [
            'A' => 4.88671875,
            'B' => 15.21875,
            'C' => 48.77734375,
            'D' => 24.5546875,
            'E' => 36.21875,
            'F' => 25.5546875,
            'G' => 69,
            'H' => 117.88671875,
            'I' => 45.109375,
            'J' => 33.6640625,
            'K' => 39.88671875,
            'L' => 15.44140625,
            'M' => 11,
            'N' => 11,
            'O' => 21.88671875,
            'P' => 14.21875,
            'Q' => 15.44140625,
            'R' => 18.109375,
            'S' => 11.6640625,
            'T' => 13.21875,
            'U' => 18.109375,
            'V' => 8.21875,
            'W' => 13.21875,
            'X' => 18.109375,
            'Y' => 67.21875,
            'Z' => 15.88671875,
            'AA' => 47.21875,
            'AB' => 15.88671875,
            'AC' => 60.6640625,
            'AD' => 116,
            'AE' => 17.5546875,
            'AF' => 19.5546875,
            'AG' => 25.5546875,
            'AH' => 22.109375,
            'AI' => 39.33203125,
            'AJ' => 20.5546875,
            'AK' => 15.21875,
            'AL' => 72.88671875,
            'AM' => 72.88671875,
            'AN' => 11.77734375,
            'AO' => 10.6640625,
            'AP' => 19.33203125,
            'AQ' => 34.6640625,
            'AR' => 48.21875,
            'AS' => 34.6640625,
            'AT' => 53.44140625,
            'AU' => 21.5546875,
            'AV' => 19.6640625,
            'AW' => 24,
            'AX' => 9.21875,
        ];

        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    }

    private function setSignifikanColumnWidths($sheet)
    {
        $widths = [
            'A' => 4.88671875,
            'B' => 15.21875,
            'C' => 48.77734375,
            'D' => 24.5546875,
            'E' => 36.21875,
            'F' => 25.5546875,
            'G' => 69,
            'H' => 117.88671875,
            'I' => 45.109375,
            'J' => 33.6640625,
            'K' => 39.88671875,
            'L' => 15.44140625,
            'M' => 35,
            'N' => 80,
            'O' => 11,
            'P' => 11,
            'Q' => 21.88671875,
            'R' => 25.5546875,
            'S' => 22.109375,
            'T' => 39.33203125,
            'U' => 20.5546875,
            'V' => 15.21875,
            'W' => 11.77734375,
            'X' => 10.6640625,
            'Y' => 72.88671875,
            'Z' => 72.88671875,
            'AA' => 34.6640625,
            'AB' => 34.6640625,
            'AC' => 19.33203125,
            'AD' => 48.21875,
            'AE' => 21.5546875,
            'AF' => 24,
            'AG' => 24,
            'AH' => 24,
        ];

        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
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
