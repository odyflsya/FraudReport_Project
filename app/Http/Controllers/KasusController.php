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
use App\Models\RefJabatan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasusController extends Controller
{
    private function userKasusQuery()
    {
        return Kasus::with([
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
        ])->where('user_id', Auth::id());
    }

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

        // Query dasar dengan eager loading hanya untuk kasus milik user saat ini
        $query = $this->userKasusQuery();

        // Search global (kode_komponen, deskripsi_fraud, divisi_unit, nama pelaku)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('deskripsi_fraud', 'like', '%' . $search . '%')
                  ->orWhere('divisi_unit', 'like', '%' . $search . '%')
                  ->orWhereHas('kejadianFraud', function($subQ) use ($search) {
                      $subQ->where('kasus_kejadian_fraud.kode_kejadian', 'like', '%' . $search . '%');
                })
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
        $kasus = $query->orderBy('created_at', 'asc')->paginate(10)->withQueryString();

        // Tambahkan nomor urut absolut per user
        $startNumber = ($kasus->currentPage() - 1) * $kasus->perPage() + 1;
        foreach ($kasus as $index => $item) {
            $item->nomor_urut = $startNumber + $index;
        }

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
                'user_id' => Auth::id(),
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
                'ljk_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->ljk_rill),
                'ljk_potensial' => $this->sanitizeCurrencyValue($request->ljk_potensial),
                'ljk_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->ljk_recovery),
                'konsumen_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->konsumen_rill),
                'konsumen_potensial' => $this->sanitizeCurrencyValue($request->konsumen_potensial),
                'konsumen_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->konsumen_recovery),
                'pihak_lain_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->pihak_lain_rill),
                'pihak_lain_potensial' => $this->sanitizeCurrencyValue($request->pihak_lain_potensial),
                'pihak_lain_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->pihak_lain_recovery),
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

    private function sanitizeCurrencyValue($value)
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        $cleaned = str_replace('.', '', (string) $value);
        $cleaned = str_replace(',', '.', $cleaned);
        $cleaned = preg_replace('/[^0-9\.\-]/', '', $cleaned);

        return $cleaned === '' ? null : $cleaned;
    }

    // ================= SHOW =================
    public function show($id)
    {
        $kasus = $this->userKasusQuery()->findOrFail($id);
        return view('kasus.show', compact('kasus'));
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $kasus = $this->userKasusQuery()->findOrFail($id);
        
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
            $kasus = $this->userKasusQuery()->findOrFail($id);

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
                    'ljk_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->ljk_rill),
                    'ljk_potensial' => $this->sanitizeCurrencyValue($request->ljk_potensial),
                    'ljk_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->ljk_recovery),
                    'konsumen_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->konsumen_rill),
                    'konsumen_potensial' => $this->sanitizeCurrencyValue($request->konsumen_potensial),
                    'konsumen_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->konsumen_recovery),
                    'pihak_lain_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->pihak_lain_rill),
                    'pihak_lain_potensial' => $this->sanitizeCurrencyValue($request->pihak_lain_potensial),
                    'pihak_lain_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->pihak_lain_recovery),
                ]);
            } else {
                KerugianFraud::create([
                    'kasus_id' => $kasus->id,
                    'ljk_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->ljk_rill),
                    'ljk_potensial' => $this->sanitizeCurrencyValue($request->ljk_potensial),
                    'ljk_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->ljk_recovery),
                    'konsumen_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->konsumen_rill),
                    'konsumen_potensial' => $this->sanitizeCurrencyValue($request->konsumen_potensial),
                    'konsumen_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->konsumen_recovery),
                    'pihak_lain_rill' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->pihak_lain_rill),
                    'pihak_lain_potensial' => $this->sanitizeCurrencyValue($request->pihak_lain_potensial),
                    'pihak_lain_recovery' => $request->jenis_laporan === 'signifikan' ? 0 : $this->sanitizeCurrencyValue($request->pihak_lain_recovery),
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
            $kasus = $this->userKasusQuery()->findOrFail($id);
            
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
    public function export()
    {
        $kasus = $this->userKasusQuery()->latest()->get();

        // Tambahkan nomor urut absolut
        foreach ($kasus as $index => $item) {
            $item->nomor_urut = $index + 1;
        }

        return view('kasus.export', compact('kasus'));
    }
}