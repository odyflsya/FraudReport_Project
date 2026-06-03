@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="mx-auto w-full px-4 sm:px-6 lg:px-8">
        <!-- Main Card -->
        <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-lg">
            <!-- Header -->
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-8 sm:px-8">
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">Import Data Kasus Fraud</h1>
                        <p class="mt-2 text-sm text-slate-600">Upload file Excel untuk mengimpor data kasus fraud</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-8 sm:px-8">
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
                        <h3 class="font-semibold text-red-900">Terjadi kesalahan:</h3>
                        <ul class="mt-2 list-inside list-disc text-sm text-red-800">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('kasus.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Jenis Laporan -->
                    <div>
                        <label for="jenis_laporan" class="block text-sm font-medium text-slate-700 mb-2">
                            Jenis Laporan <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_laporan" name="jenis_laporan" required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">-- Pilih Jenis Laporan --</option>
                            <option value="semester" {{ old('jenis_laporan') === 'semester' ? 'selected' : '' }}>
                                Semester (01A)
                            </option>
                            <option value="signifikan" {{ old('jenis_laporan') === 'signifikan' ? 'selected' : '' }}>
                                Signifikan (01B)
                            </option>
                        </select>
                        @error('jenis_laporan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label for="file" class="block text-sm font-medium text-slate-700 mb-2">
                            File Excel <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" id="file" name="file" accept=".xlsx,.xls,.xlsm" required
                                class="block w-full cursor-pointer rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700"
                                onchange="updateFileName(this)">
                            <p id="fileName" class="mt-2 text-xs text-slate-500">Format: .xlsx, .xls, atau .xlsm</p>
                        </div>
                        @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                        <h3 class="font-semibold text-blue-900">ℹ️ Informasi Penting</h3>
                        <ul class="mt-2 space-y-1 text-sm text-blue-800">
                            <li>• File harus memiliki format yang sama dengan hasil export</li>
                            <li>• Jenis laporan yang dipilih harus sesuai dengan data di Excel</li>
                            <li>• Jika ada data dengan Kode Komponen yang sama, data akan diperbarui</li>
                            <li>• Data yang tidak valid akan di-skip tanpa menghentikan import</li>
                            <li>• Pastikan semua data sudah benar sebelum import</li>
                        </ul>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3">
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Import Data
                        </button>
                        <a href="{{ route('kasus.index') }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-6 py-3 font-medium text-slate-700 transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                            Batal
                        </a>
                    </div>
                </form>

                <!-- Template Info -->
            </div>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        const fileName = document.getElementById('fileName');
        if (input.files && input.files[0]) {
            fileName.textContent = '✓ File: ' + input.files[0].name;
            fileName.classList.remove('text-slate-500');
            fileName.classList.add('text-green-600');
        } else {
            fileName.textContent = 'Format: .xlsx atau .xls';
            fileName.classList.add('text-slate-500');
            fileName.classList.remove('text-green-600');
        }
    }
</script>
@endsection
