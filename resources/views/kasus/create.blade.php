@extends('layouts.app')

@section('content')
@php
    $formatCurrencyInput = function ($value) {
        $value = (string) ($value ?? '');
        if ($value === '') {
            return '';
        }

        // Jika sudah ada format titik sebagai ribuan, kembalikan apa adanya
        if (strpos($value, '.') !== false && strpos($value, ',') === false) {
            return $value;
        }

        // Jika ada koma (desimal), tangani sebagai format Eropa
        if (strpos($value, ',') !== false) {
            $parts = explode(',', $value);
            $integerPart = str_replace('.', '', $parts[0]);
            $decimalPart = $parts[1] ?? '';
            return number_format((float) ($integerPart . '.' . $decimalPart), 0, ',', '.');
        }

        // Jika angka biasa, format sebagai ribuan
        if (is_numeric($value)) {
            return number_format((float) $value, 0, ',', '.');
        }

        return $value;
    };
@endphp
<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="mx-auto w-full px-4 sm:px-6 lg:px-8">
        <!-- Main Card -->
        <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-lg">
            <!-- Header -->
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-8 sm:px-8">
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">Form Pencatatan Kasus Fraud</h1>
                        <p class="mt-2 text-sm text-slate-600">Silahkan isi data kasus fraud dengan lengkap.</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-8 sm:px-8">
                @if(session('error'))
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mt-3 list-inside space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('kasus.store') }}" method="POST" id="kasusForm" class="space-y-8">
                    @csrf

                    <!-- SECTION: KODE KOMPONEN -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">Kode Komponen</h3>
                        <div>
                            <label for="kode_komponen" class="mb-2 block text-sm font-medium text-slate-700">Kode Komponen <span class="text-red-500">*</span></label>
                            <input type="text" id="kode_komponen" name="kode_komponen" value="{{ old('kode_komponen') }}"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                placeholder="Contoh: 0101000000" required>
                            @error('kode_komponen')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <!-- SECTION: KEJADIAN FRAUD MENURUT PELAKU -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">Kejadian Fraud Menurut Pelaku</h3>
                        <div class="space-y-3">
                            <div>
                                <label for="kejadian_fraud" class="mb-2 block text-sm font-medium text-slate-700">Pilih Kejadian Fraud <span class="text-red-500">*</span></label>
                                <select id="kejadian_fraud" name="kejadian_fraud" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    onchange="validateDuplicateOptionValue(this); toggleKeteranganField(this, 'kejadian_fraud_keterangan_wrapper')">
                                    <option value="">Pilih Kejadian Fraud</option>
                                    @foreach($kejadianFraud as $kf)
                                        <option value="{{ $kf->id }}" data-keterangan="{{ (strpos(strtolower($kf->nama), 'lainnya') !== false || strpos(strtolower($kf->nama), 'lain') !== false) ? 'true' : 'false' }}" {{ old('kejadian_fraud') == $kf->id ? 'selected' : '' }}>{{ isset($kf->kode) ? $kf->kode . ' (' . $kf->nama . ')' : $kf->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kejadian_fraud')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="id_kejadian" class="mb-2 block text-sm font-medium text-slate-700">ID Kejadian</label>
                                <input type="text" id="id_kejadian" name="id_kejadian" value="{{ old('id_kejadian') }}"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    placeholder="Masukkan kode kejadian">
                                @error('id_kejadian')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div id="kejadian_fraud_keterangan_wrapper" class="hidden">
                                <label for="kejadian_fraud_keterangan" class="mb-2 block text-sm font-medium text-slate-700">Keterangan</label>
                                <input type="text" id="kejadian_fraud_keterangan" name="kejadian_fraud_keterangan" placeholder="Masukkan keterangan"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    value="{{ old('kejadian_fraud_keterangan', '') }}">
                                @error('kejadian_fraud_keterangan')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: JENIS FRAUD -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">Jenis Fraud</h3>
                        <div class="space-y-3">
                            <div>
                                <label for="jenis_fraud" class="mb-2 block text-sm font-medium text-slate-700">Pilih Jenis Fraud <span class="text-red-500">*</span></label>
                                <select id="jenis_fraud" name="jenis_fraud" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    onchange="toggleKeteranganField(this, 'jenis_fraud_keterangan_wrapper')">
                                    <option value="">Pilih Jenis Fraud</option>
                                    @foreach($jenisFraud as $jf)
                                        <option value="{{ $jf->id }}" data-keterangan-code="{{ $jf->kode ?? '' }}" {{ old('jenis_fraud') == $jf->id ? 'selected' : '' }}>{{ $jf->kode ? $jf->kode . ' (' . $jf->nama . ')' : $jf->nama }}</option>
                                    @endforeach
                                </select>
                                @error('jenis_fraud')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div id="jenis_fraud_keterangan_wrapper" class="hidden">
                                <label for="jenis_fraud_keterangan" class="mb-2 block text-sm font-medium text-slate-700">Keterangan</label>
                                <input type="text" id="jenis_fraud_keterangan" name="jenis_fraud_keterangan" placeholder="Masukkan keterangan"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    value="{{ old('jenis_fraud_keterangan', '') }}">
                                @error('jenis_fraud_keterangan')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: AKTIVITAS TERKAIT -->
                        <div>
                            <label for="aktivitas_terkait_id" class="mb-2 block text-sm font-medium text-slate-700">Aktivitas Terkait Fraud <span class="text-red-500">*</span></label>
                            <select id="aktivitas_terkait_id" name="aktivitas_terkait_id" required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                <option value="">Pilih Aktivitas Terkait</option>
                                @foreach($aktivitasTerkait as $at)
                                    <option value="{{ $at->id }}" {{ old('aktivitas_terkait_id') == $at->id ? 'selected' : '' }}>{{ $at->kode }} ({{ $at->nama }})</option>
                                @endforeach
                            </select>
                            @error('aktivitas_terkait_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    

                    <!-- SECTION: DESKRIPSI FRAUD -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">Deskripsi Fraud / Modus Operandi</h3>
                        <label for="deskripsi_fraud" class="mb-2 block text-sm font-medium text-slate-700">Deskripsi Fraud <span class="text-red-500">*</span></label>
                        <textarea id="deskripsi_fraud" name="deskripsi_fraud" rows="4"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            placeholder="Deskripsi lengkap tentang kasus fraud / modus operandi" required>{{ old('deskripsi_fraud') }}</textarea>
                        @error('deskripsi_fraud')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <!-- SECTION: LOKASI FRAUD -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">Lokasi Fraud</h3>
                        <div class="space-y-3">
                            <div>
                                <label for="lokasi_fraud" class="mb-2 block text-sm font-medium text-slate-700">Pilih Lokasi Fraud <span class="text-red-500">*</span></label>
                                <select id="lokasi_fraud" name="lokasi_fraud" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="">Pilih Lokasi Fraud</option>
                                    @foreach($lokasiFraud as $lf)
                                        <option value="{{ $lf->id }}" data-keterangan="{{ (strpos(strtolower($lf->nama), 'lainnya') !== false || strpos(strtolower($lf->nama), 'lain') !== false) ? 'true' : 'false' }}" {{ old('lokasi_fraud') == $lf->id ? 'selected' : '' }}>{{ isset($lf->kode) ? $lf->kode . ' (' . $lf->nama . ')' : $lf->nama }}</option>
                                    @endforeach
                                </select>
                                @error('lokasi_fraud')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div id="lokasi_fraud_keterangan_wrapper">
                                <label for="lokasi_fraud_keterangan" class="mb-2 block text-sm font-medium text-slate-700">Keterangan</label>
                                <input type="text" id="lokasi_fraud_keterangan" name="lokasi_fraud_keterangan" required placeholder="Masukkan keterangan lokasi"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    value="{{ old('lokasi_fraud_keterangan', '') }}">
                                @error('lokasi_fraud_keterangan')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                        <div>
                            <label for="divisi_unit" class="mb-2 block text-sm font-medium text-slate-700">Divisi/Unit <span class="text-red-500">*</span></label>
                            <input type="text" id="divisi_unit" name="divisi_unit" value="{{ old('divisi_unit') }}"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                placeholder="Contoh: Divisi Operasional" required>
                            @error('divisi_unit')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                    <!-- SECTION: PIHAK YANG DIRUGIKAN -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="pihak_dirugikan_id" class="mb-2 block text-sm font-medium text-slate-700">Pihak Dirugikan <span class="text-red-500">*</span></label>
                            <select id="pihak_dirugikan_id" name="pihak_dirugikan_id" required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                <option value="">Pilih Pihak yang Dirugikan</option>
                                @foreach($pihakDirugikan as $pd)
                                    <option value="{{ $pd->id }}" {{ old('pihak_dirugikan_id') == $pd->id ? 'selected' : '' }}>{{ $pd->kode }} - {{ $pd->nama }}</option>
                                @endforeach
                            </select>
                            @error('pihak_dirugikan_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="jenis_laporan" class="mb-2 block text-sm font-medium text-slate-700">Jenis Laporan <span class="text-red-500">*</span></label>
                            <select id="jenis_laporan" name="jenis_laporan" required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                <option value="semester" {{ old('jenis_laporan', 'semester') == 'semester' ? 'selected' : '' }}>Semester</option>
                                <option value="signifikan" {{ old('jenis_laporan') == 'signifikan' ? 'selected' : '' }}>Signifikan</option>
                            </select>
                            @error('jenis_laporan')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        <div id="tindak_lanjut_ljk_wrapper" class="hidden">
                            <label for="tindak_lanjut_ljk" class="mb-2 block text-sm font-medium text-slate-700">Tindak Lanjut LJK <span class="text-red-500">*</span></label>
                            <textarea id="tindak_lanjut_ljk" name="tindak_lanjut_ljk" rows="4"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                placeholder="Jelaskan tindak lanjut yang diambil oleh LJK">{{ old('tindak_lanjut_ljk') }}</textarea>
                            @error('tindak_lanjut_ljk')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <!-- SECTION: WAKTU FRAUD -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">Waktu </h3>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <label for="waktu_awal" class="mb-2 block text-sm font-medium text-slate-700">Waktu Awal</label>
                                <input type="date" id="waktu_awal" name="waktu_awal"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                @error('waktu_awal')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="waktu_akhir" class="mb-2 block text-sm font-medium text-slate-700">Waktu Akhir</label>
                                <input type="date" id="waktu_akhir" name="waktu_akhir"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                @error('waktu_akhir')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="waktu_diketahui" class="mb-2 block text-sm font-medium text-slate-700">Waktu Fraud Diketahui</label>
                                <input type="date" id="waktu_diketahui" name="waktu_diketahui"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                @error('waktu_diketahui')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: KERUGIAN FRAUD -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">Kerugian Fraud</h3>
                        <div class="space-y-6">
                            <div>
                                <h4 class="mb-3 font-medium text-slate-800">Kerugian LJK (Lembaga Jasa Keuangan)</h4>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <div class="signifikan-hidden">
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Kerugian Riil (Rp)</label>
                                        <input type="text" name="ljk_rill" inputmode="decimal" autocomplete="off"
                                            class="currency-input w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                            placeholder="" value="{{ $formatCurrencyInput(old('ljk_rill', '')) }}">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Kerugian Potensial (Rp)</label>
                                        <input type="text" name="ljk_potensial" inputmode="decimal" autocomplete="off"
                                            class="currency-input w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                            placeholder="" value="{{ $formatCurrencyInput(old('ljk_potensial', '')) }}">
                                    </div>
                                    <div class="signifikan-hidden">
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Recovery (Rp)</label>
                                        <input type="text" name="ljk_recovery" inputmode="decimal" autocomplete="off"
                                            class="currency-input w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                            placeholder="" value="{{ $formatCurrencyInput(old('ljk_recovery', '')) }}">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="mb-3 font-medium text-slate-800">Kerugian Konsumen</h4>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <div class="signifikan-hidden">
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Kerugian Riil (Rp)</label>
                                        <input type="text" name="konsumen_rill" inputmode="decimal" autocomplete="off"
                                            class="currency-input w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                            placeholder="" value="{{ $formatCurrencyInput(old('konsumen_rill', '')) }}">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Kerugian Potensial (Rp)</label>
                                        <input type="text" name="konsumen_potensial" inputmode="decimal" autocomplete="off"
                                            class="currency-input w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                            placeholder="" value="{{ $formatCurrencyInput(old('konsumen_potensial', '')) }}">
                                    </div>
                                    <div class="signifikan-hidden">
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Recovery (Rp)</label>
                                        <input type="text" name="konsumen_recovery" inputmode="decimal" autocomplete="off"
                                            class="currency-input w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                            placeholder="" value="{{ $formatCurrencyInput(old('konsumen_recovery', '')) }}">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="mb-3 font-medium text-slate-800">Kerugian Pihak Lain</h4>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <div class="signifikan-hidden">
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Kerugian Riil (Rp)</label>
                                        <input type="text" name="pihak_lain_rill" inputmode="decimal" autocomplete="off"
                                            class="currency-input w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                            placeholder="" value="{{ $formatCurrencyInput(old('pihak_lain_rill', '')) }}">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Kerugian Potensial (Rp)</label>
                                        <input type="text" name="pihak_lain_potensial" inputmode="decimal" autocomplete="off"
                                            class="currency-input w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                            placeholder="" value="{{ $formatCurrencyInput(old('pihak_lain_potensial', '')) }}">
                                    </div>
                                    <div class="signifikan-hidden">
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Recovery (Rp)</label>
                                        <input type="text" name="pihak_lain_recovery" inputmode="decimal" autocomplete="off"
                                            class="currency-input w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                            placeholder="" value="{{ $formatCurrencyInput(old('pihak_lain_recovery', '')) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: KELEMAHAN PENYEBAB FRAUD -->
                    <div id="semester_related_sections">
                        <div>
                            <h3 class="mb-4 text-lg font-semibold text-slate-900">Kelemahan Penyebab Fraud</h3>
                        <div class="space-y-3">
                            <div>
                                <label for="kelemahan_fraud" class="mb-2 block text-sm font-medium text-slate-700">Pilih Kelemahan Fraud <span class="text-red-500">*</span></label>
                                <select id="kelemahan_fraud" name="kelemahan_fraud" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    onchange="toggleKeteranganField(this, 'kelemahan_fraud_keterangan_wrapper')">
                                    <option value="">Pilih Kelemahan Fraud</option>
                                    @foreach($kelemahanFraud as $kf)
                                        <option value="{{ $kf->id }}" data-keterangan-code="{{ $kf->kode ?? '' }}" {{ old('kelemahan_fraud') == $kf->id ? 'selected' : '' }}>{{ $kf->kode ? $kf->kode . ' (' . $kf->nama . ')' : $kf->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kelemahan_fraud')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div id="kelemahan_fraud_keterangan_wrapper" class="hidden">
                                <label for="kelemahan_fraud_keterangan" class="mb-2 block text-sm font-medium text-slate-700">Keterangan</label>
                                <input type="text" id="kelemahan_fraud_keterangan" name="kelemahan_fraud_keterangan" placeholder="Masukkan keterangan"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    value="{{ old('kelemahan_fraud_keterangan', '') }}">
                                @error('kelemahan_fraud_keterangan')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: TINDAKAN PENANGANAN FRAUD -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">Tindakan untuk Penanganan Fraud</h3>
                        <div class="space-y-3">
                            <div>
                                <label for="penanganan_fraud" class="mb-2 block text-sm font-medium text-slate-700">Pilih Tindakan Penanganan <span class="text-red-500">*</span></label>
                                <select id="penanganan_fraud" name="penanganan_fraud" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    onchange="toggleKeteranganField(this, 'penanganan_fraud_keterangan_wrapper')">
                                    <option value="">Pilih Tindakan Penanganan</option>
                                    @foreach($penangananFraud as $pf)
                                        <option value="{{ $pf->id }}" data-keterangan="{{ ((strpos(strtolower($pf->nama), 'lainnya') !== false || strpos(strtolower($pf->nama), 'lain') !== false) || (strpos(strtolower($pf->kode), 'lainnya') !== false || strpos(strtolower($pf->kode), 'lain') !== false)) ? 'true' : 'false' }}" {{ old('penanganan_fraud') == $pf->id ? 'selected' : '' }}>{{ $pf->kode }} ({{ $pf->nama }})</option>
                                    @endforeach
                                </select>
                                @error('penanganan_fraud')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div id="penanganan_fraud_keterangan_wrapper" class="hidden">
                                <label for="penanganan_fraud_keterangan" class="mb-2 block text-sm font-medium text-slate-700">Keterangan</label>
                                <input type="text" id="penanganan_fraud_keterangan" name="penanganan_fraud_keterangan" placeholder="Masukkan keterangan"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    value="{{ old('penanganan_fraud_keterangan', '') }}">
                                @error('penanganan_fraud_keterangan')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: TINDAKAN PERBAIKAN / PENCEGAHAN FRAUD -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">Tindakan Perbaikan untuk Pencegahan Fraud</h3>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="pencegahan_id" class="mb-2 block text-sm font-medium text-slate-700">Pilih Tindakan Perbaikan<span class="text-red-500">*</span></label>
                                <select id="pencegahan_id" name="pencegahan_fraud[pencegahan_id]" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="">Pilih Tindakan Perbaikan</option>
                                    @foreach($pencegahanFraud as $pf)
                                        <option value="{{ $pf->id }}" {{ old('pencegahan_fraud.pencegahan_id') == $pf->id ? 'selected' : '' }}>{{ $pf->kode }} ({{ $pf->nama }})</option>
                                    @endforeach
                                </select>
                                @error('pencegahan_fraud.pencegahan_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="pencegahan_keterangan" class="mb-2 block text-sm font-medium text-slate-700">Keterangan <span class="text-red-500">*</span></label>
                                <input type="text" id="pencegahan_keterangan" name="pencegahan_fraud[keterangan]" value="{{ old('pencegahan_fraud.keterangan') }}"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    placeholder="Keterangan pencegahan" required>
                                @error('pencegahan_fraud.keterangan')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="pencegahan_target_waktu" class="mb-2 block text-sm font-medium text-slate-700">Target Waktu <span class="text-red-500">*</span></label>
                                <input type="date" id="pencegahan_target_waktu" name="pencegahan_fraud[target_waktu]" value="{{ old('pencegahan_fraud.target_waktu') }}"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                                @error('pencegahan_fraud.target_waktu')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="pencegahan_realisasi" class="mb-2 block text-sm font-medium text-slate-700">Realisasi</label>
                                <input type="date" id="pencegahan_realisasi" name="pencegahan_fraud[realisasi]" value="{{ old('pencegahan_fraud.realisasi') }}"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                @error('pencegahan_fraud.realisasi')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    </div>
                    <!-- SECTION: PELAKU FRAUD -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">Data Pelaku Fraud</h3>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="kategori" class="mb-2 block text-sm font-medium text-slate-700">Internal/Eksternal <span class="text-red-500">*</span></label>
                                <select id="kategori" name="pelaku_fraud[kategori]" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="">Pilih Kategori</option>
                                    <option value="internal" {{ old('pelaku_fraud.kategori') == 'internal' ? 'selected' : '' }}>Internal</option>
                                    <option value="eksternal" {{ old('pelaku_fraud.kategori') == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                                </select>
                                @error('pelaku_fraud.kategori')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="nama" class="mb-2 block text-sm font-medium text-slate-700">Nama <span class="text-red-500">*</span></label>
                                <input type="text" id="nama" name="pelaku_fraud[nama]" value="{{ old('pelaku_fraud.nama') }}"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    placeholder="Nama pelaku" required>
                                @error('pelaku_fraud.nama')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="jenis_identitas_id" class="mb-2 block text-sm font-medium text-slate-700">Jenis Identitas <span class="text-red-500">*</span></label>
                                <select id="jenis_identitas_id" name="pelaku_fraud[jenis_identitas_id]" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="">Pilih Jenis Identitas</option>
                                    @foreach($jenisIdentitas as $ji)
                                        <option value="{{ $ji->id }}" {{ old('pelaku_fraud.jenis_identitas_id') == $ji->id ? 'selected' : '' }}>{{ $ji->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pelaku_fraud.jenis_identitas_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="nomor_identitas" class="mb-2 block text-sm font-medium text-slate-700">Nomor Identitas <span class="text-red-500">*</span></label>
                                <input type="text" id="nomor_identitas" name="pelaku_fraud[nomor_identitas]" value="{{ old('pelaku_fraud.nomor_identitas') }}"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    placeholder="Nomor identitas" required>
                                @error('pelaku_fraud.nomor_identitas')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="jenis_kelamin" class="mb-2 block text-sm font-medium text-slate-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select id="jenis_kelamin" name="pelaku_fraud[jenis_kelamin]" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('pelaku_fraud.jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('pelaku_fraud.jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('pelaku_fraud.jenis_kelamin')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="tempat_lahir" class="mb-2 block text-sm font-medium text-slate-700">Tempat Lahir</label>
                                <input type="text" id="tempat_lahir" name="pelaku_fraud[tempat_lahir]" value="{{ old('pelaku_fraud.tempat_lahir') }}"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    placeholder="Tempat lahir">
                                @error('pelaku_fraud.tempat_lahir')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="tanggal_lahir" class="mb-2 block text-sm font-medium text-slate-700">Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" name="pelaku_fraud[tanggal_lahir]" value="{{ old('pelaku_fraud.tanggal_lahir') }}"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                @error('pelaku_fraud.tanggal_lahir')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="status_pelaku_id" class="mb-2 block text-sm font-medium text-slate-700">Status Pelaku <span class="text-red-500">*</span></label>
                                <select id="status_pelaku_id" name="pelaku_fraud[status_pelaku_id]" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="">Pilih Status Pelaku</option>
                                    @foreach($statusPelaku as $sp)
                                        <option value="{{ $sp->id }}" {{ old('pelaku_fraud.status_pelaku_id') == $sp->id ? 'selected' : '' }}>{{ $sp->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pelaku_fraud.status_pelaku_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="alamat_identitas" class="mb-2 block text-sm font-medium text-slate-700">Alamat Identitas</label>
                                <textarea id="alamat_identitas" name="pelaku_fraud[alamat_identitas]" rows="3"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    placeholder="Alamat sesuai identitas">{{ old('pelaku_fraud.alamat_identitas') }}</textarea>
                                @error('pelaku_fraud.alamat_identitas')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="alamat_domisili" class="mb-2 block text-sm font-medium text-slate-700">Alamat Domisili</label>
                                <textarea id="alamat_domisili" name="pelaku_fraud[alamat_domisili]" rows="3"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    placeholder="Alamat domisili">{{ old('pelaku_fraud.alamat_domisili') }}</textarea>
                                @error('pelaku_fraud.alamat_domisili')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="jabatan_saat_kejadian_id" class="mb-2 block text-sm font-medium text-slate-700">Jabatan Pada Saat Fraud Terjadi</label>
                                <select id="jabatan_saat_kejadian_id" name="pelaku_fraud[jabatan_saat_kejadian_id]"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="">Pilih Jabatan</option>
                                    @foreach($jabatanKejadian as $jk)
                                        <option value="{{ $jk->id }}" {{ old('pelaku_fraud.jabatan_saat_kejadian_id') == $jk->id ? 'selected' : '' }}>{{ $jk->kode }} ({{ $jk->nama }})</option>
                                    @endforeach
                                </select>
                                @error('pelaku_fraud.jabatan_saat_kejadian_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="ket_jabatan_kejadian" class="mb-2 block text-sm font-medium text-slate-700">Keterangan Jabatan Pada Saat Fraud Terjadi</label>
                                <input type="text" id="ket_jabatan_kejadian" name="pelaku_fraud[ket_jabatan_kejadian]" value="{{ old('pelaku_fraud.ket_jabatan_kejadian') }}"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    placeholder="Keterangan jabatan">
                                @error('pelaku_fraud.ket_jabatan_kejadian')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="jabatan_saat_diketahui_id" class="mb-2 block text-sm font-medium text-slate-700">Jabatan Pada Saat Fraud Diketahui</label>
                                <select id="jabatan_saat_diketahui_id" name="pelaku_fraud[jabatan_saat_diketahui_id]"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="">Pilih Jabatan</option>
                                    @foreach($jabatanSemua as $js)
                                        <option value="{{ $js->id }}" {{ old('pelaku_fraud.jabatan_saat_diketahui_id') == $js->id ? 'selected' : '' }}>{{ $js->kode }} ({{ $js->nama }})</option>
                                    @endforeach
                                </select>
                                @error('pelaku_fraud.jabatan_saat_diketahui_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="ket_jabatan_diketahui" class="mb-2 block text-sm font-medium text-slate-700">Keterangan Jabatan Pada Saat Fraud Diketahui</label>
                                <input type="text" id="ket_jabatan_diketahui" name="pelaku_fraud[ket_jabatan_diketahui]" value="{{ old('pelaku_fraud.ket_jabatan_diketahui') }}"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    placeholder="Keterangan jabatan">
                                @error('pelaku_fraud.ket_jabatan_diketahui')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
<div class="md:col-span-2">
    <label for="keterangan" class="mb-2 block text-sm font-medium text-slate-700">
        Keterangan Pelaku
    </label>

    <select id="keterangan" name="pelaku_fraud[keterangan]"
        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        
        <option value="">Pilih Keterangan</option>

        <option value="001 (Konsumen)" {{ old('pelaku_fraud.keterangan') == '001 (Konsumen)' ? 'selected' : '' }}>
            001 (Konsumen)
        </option>
        <option value="002 (Pihak yang bekerjasama dengan bank)" {{ old('pelaku_fraud.keterangan') == '002 (Pihak yang bekerjasama dengan bank)' ? 'selected' : '' }}>
            002 (Pihak yang bekerjasama dengan bank)
        </option>
        <option value="003 (Pihak yang tidak berhubungan langsung)" {{ old('pelaku_fraud.keterangan') == '003 (Pihak yang tidak berhubungan langsung)' ? 'selected' : '' }}>
            003 (Pihak yang tidak berhubungan langsung)
        </option>

    </select>

    @error('pelaku_fraud.keterangan')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>
                            <div class="md:col-span-2">
                                <label for="sanksi" class="mb-2 block text-sm font-medium text-slate-700">Pengenaan Sanksi</label>
                                <textarea id="sanksi" name="pelaku_fraud[sanksi]" rows="3"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    placeholder="Sanksi yang diberikan">{{ old('pelaku_fraud.sanksi') }}</textarea>
                                @error('pelaku_fraud.sanksi')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: STATUS PENANGANAN -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">Status Penanganan Kasus</h3>
                        <label for="status_penanganan" class="mb-2 block text-sm font-medium text-slate-700">Status Penanganan <span class="text-red-500">*</span></label>
                        <select id="status_penanganan" name="status_penanganan" required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">Pilih Status Penanganan</option>
                            <option value="001" {{ old('status_penanganan') == '001' ? 'selected' : '' }}>001 (Proses internal LJK)</option>
                            <option value="002" {{ old('status_penanganan') == '002' ? 'selected' : '' }}>002 (Selesai diproses internal LJK)</option>
                            <option value="003" {{ old('status_penanganan') == '003' ? 'selected' : '' }}>003 (Dalam proses penanganan aparat penegak hukum)</option>
                            <option value="004" {{ old('status_penanganan') == '004' ? 'selected' : '' }}>004 (Berkekuatan hukum tetap (Inkracht))</option>
                        </select>
                        @error('status_penanganan')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                        <a href="{{ route('kasus.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Reset
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-green-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function validateDuplicateOptionValue(selectElement) {
        const values = {};
        let hasDuplicate = false;

        Array.from(selectElement.options).forEach(option => {
            const value = option.value;
            if (!value) return;
            if (values[value]) {
                hasDuplicate = true;
            } else {
                values[value] = true;
            }
        });

        if (hasDuplicate && selectElement.value !== '') {
            selectElement.value = '';
        }

        return hasDuplicate;
    }

    // Function to toggle keterangan field visibility based on dropdown selection
    function toggleKeteranganField(selectElement, wrapperId) {
        const wrapper = document.getElementById(wrapperId);
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const selectedCode = selectedOption.getAttribute('data-keterangan-code');
        const keteranganInput = wrapper.querySelector('input, textarea');

        const requiresKeterangan = (selectedCode === '701' || selectedCode === '901') || selectedOption.getAttribute('data-keterangan') === 'true';

        if (requiresKeterangan && selectElement.value !== '') {
            wrapper.classList.remove('hidden');
            if (keteranganInput) {
                keteranganInput.required = true;
            }
        } else {
            wrapper.classList.add('hidden');
            if (keteranganInput) {
                keteranganInput.required = false;
                keteranganInput.value = '';
            }
        }
    }

    function clearInputsAndDisable(wrapper) {
        wrapper.querySelectorAll('input, select, textarea').forEach(element => {
            if (element.tagName === 'SELECT') {
                element.selectedIndex = 0;
            } else if (element.type === 'checkbox' || element.type === 'radio') {
                element.checked = false;
            } else {
                element.value = '';
            }
            element.required = false;
            element.disabled = true;
        });
    }

    function enableInputs(wrapper) {
        wrapper.querySelectorAll('input, select, textarea').forEach(element => {
            element.disabled = false;
        });
    }

    function formatCurrencyValue(value) {
        if (typeof value !== 'string' || value.trim() === '') {
            return value;
        }

        let cleaned = value.replace(/[^0-9,]/g, '');
        const parts = cleaned.split(',');
        let integerPart = parts[0].replace(/^0+(?=\d)/, '');
        const decimalPart = parts[1] || '';

        if (integerPart === '') {
            integerPart = '0';
        }

        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        return decimalPart ? `${integerPart},${decimalPart}` : integerPart;
    }

    function sanitizeCurrencyValue(value) {
        if (typeof value !== 'string' || value.trim() === '') {
            return value;
        }

        let cleaned = value.replace(/\./g, '');
        cleaned = cleaned.replace(/,/g, '.');
        return cleaned;
    }

    function initCurrencyInputs() {
        const currencyInputs = document.querySelectorAll('.currency-input');

        currencyInputs.forEach(input => {
            input.value = formatCurrencyValue(input.value);

            input.addEventListener('blur', () => {
                input.value = formatCurrencyValue(input.value);
            });
        });

        const form = document.getElementById('kasusForm');
        if (form) {
            form.addEventListener('submit', () => {
                currencyInputs.forEach(input => {
                    input.value = sanitizeCurrencyValue(input.value);
                });
            });
        }
    }

function toggleJenisLaporanFields() {
    const jenisLaporanSelect = document.getElementById('jenis_laporan');
    const wrapper = document.getElementById('tindak_lanjut_ljk_wrapper');
    const textarea = document.getElementById('tindak_lanjut_ljk');
    const relatedSection = document.getElementById('semester_related_sections');
    const hiddenFields = document.querySelectorAll('.signifikan-hidden');

    if (jenisLaporanSelect.value === 'signifikan') {
        wrapper.classList.remove('hidden');
        textarea.required = true;

        if (relatedSection) {
            relatedSection.classList.add('hidden');

            // 🔥 TAMBAHAN PENTING
            clearInputsAndDisable(relatedSection);
        }

        hiddenFields.forEach(field => {
            field.classList.add('hidden');
            clearInputsAndDisable(field);
        });

    } else {
        wrapper.classList.add('hidden');
        textarea.required = false;
        textarea.value = '';

        if (relatedSection) {
            relatedSection.classList.remove('hidden');

            // 🔥 AKTIFKAN KEMBALI
            enableInputs(relatedSection);
        }

        hiddenFields.forEach(field => {
            field.classList.remove('hidden');
            enableInputs(field);
        });
    }

    checkFormValidity();
}

    function checkFormValidity() {
        const form = document.getElementById('kasusForm');
        const submitButton = form.querySelector('button[type="submit"]');
        const isValid = form.checkValidity();

        submitButton.disabled = !isValid;
        submitButton.classList.toggle('opacity-50', !isValid);
        submitButton.classList.toggle('cursor-not-allowed', !isValid);
    }

    // Initialize visibility on page load for each dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const dropdowns = [
            { id: 'kejadian_fraud', wrapperId: 'kejadian_fraud_keterangan_wrapper' },
            { id: 'jenis_fraud', wrapperId: 'jenis_fraud_keterangan_wrapper' },
            { id: 'kelemahan_fraud', wrapperId: 'kelemahan_fraud_keterangan_wrapper' },
            { id: 'penanganan_fraud', wrapperId: 'penanganan_fraud_keterangan_wrapper' }
        ];

        dropdowns.forEach(dropdown => {
            const selectElement = document.getElementById(dropdown.id);
            if (selectElement) {
                if (dropdown.id === 'kejadian_fraud') {
                    validateDuplicateOptionValue(selectElement);
                }
                toggleKeteranganField(selectElement, dropdown.wrapperId);
            }
        });

        const jenisLaporanSelect = document.getElementById('jenis_laporan');
        if (jenisLaporanSelect) {
            jenisLaporanSelect.addEventListener('change', toggleJenisLaporanFields);
            toggleJenisLaporanFields();
        }

        // Add input event listeners to check form validity
        const form = document.getElementById('kasusForm');
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', checkFormValidity);
            input.addEventListener('change', checkFormValidity);
        });

        initCurrencyInputs();
        checkFormValidity();
    });
</script>
@endsection