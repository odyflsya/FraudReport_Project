@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto w-full max-w-5xl px-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Pengaturan</h1>
            <p class="mt-2 text-slate-600">Kelola profil akun, keamanan, dan preferensi Anda</p>
        </div>

        <!-- Alert Messages -->
        @if (session('status'))
            <div class="mb-6 flex items-start gap-3 rounded-lg border border-green-300 bg-green-50 p-4 text-green-800">
                <i class="fas fa-check-circle flex-shrink-0 mt-0.5"></i>
                <div class="text-sm">
                    @if (session('status') === 'profile-updated')
                        Profil Anda berhasil diperbarui.
                    @elseif (session('status') === 'photo-uploaded')
                        Foto profil berhasil diunggah.
                    @elseif (session('status') === 'photo-deleted')
                        Foto profil berhasil dihapus.
                    @elseif (session('status') === 'password-updated')
                        Password berhasil diperbarui.
                    @else
                        {{ session('status') }}
                    @endif
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 flex items-start gap-3 rounded-lg border border-red-300 bg-red-50 p-4 text-red-800">
                <i class="fas fa-exclamation-circle flex-shrink-0 mt-0.5"></i>
                <div class="text-sm">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mt-2 list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Profile Section -->
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm mb-6">
            <!-- Profile Header -->
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-user text-blue-600"></i>
                    Profil Pengguna
                </h2>
                <p class="mt-1 text-sm text-slate-600">Perbarui informasi profil dan foto Anda</p>
            </div>

            <!-- Profile Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                    <!-- Profile Photo Section -->
                    <div class="flex flex-col items-center">
                        <div class="mb-4">
                            @if ($user->profile_photo_path)
                                <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}"
                                    class="h-32 w-32 rounded-lg object-cover border-2 border-slate-200 shadow-md">
                            @else
                                <div class="h-32 w-32 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center border-2 border-slate-200 shadow-md">
                                    <span class="text-4xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Upload Photo Form -->
                        <form action="{{ route('profile.upload-photo') }}" method="POST" enctype="multipart/form-data" class="w-full space-y-2">
                            @csrf
                            <input type="file" name="profile_photo" accept="image/*" class="hidden" id="profile_photo_input">
                            <button type="button" onclick="document.getElementById('profile_photo_input').click()"
                                class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition flex items-center justify-center gap-2">
                                <i class="fas fa-camera text-xs"></i> Ganti Foto
                            </button>
                            <script>
                                document.getElementById('profile_photo_input').addEventListener('change', function() {
                                    if (this.files.length > 0) {
                                        this.form.submit();
                                    }
                                });
                            </script>
                        </form>

                        @if ($user->profile_photo_path)
                            <form action="{{ route('profile.delete-photo') }}" method="POST" class="w-full mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Hapus foto profil?')"
                                    class="w-full rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                                    Hapus Foto
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Profile Information Section -->
                    <div class="md:col-span-3">
                        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-slate-900 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                        placeholder="Masukkan nama lengkap Anda"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                        required>
                                    @error('name')
                                        <span class="text-red-500 text-xs mt-1.5 flex items-center gap-1 block"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-slate-900 mb-2">Email <span class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                        placeholder="contoh@email.com"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                        required>
                                    @error('email')
                                        <span class="text-red-500 text-xs mt-1.5 flex items-center gap-1 block"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                                    @enderror
                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <p class="text-xs mt-2 text-amber-600 flex items-center gap-1.5 bg-amber-50 p-2 rounded">
                                            <i class="fas fa-info-circle flex-shrink-0"></i> <span>Email belum diverifikasi.</span>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4 flex gap-3">
                                <button type="submit"
                                    class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 active:bg-blue-800 transition inline-flex items-center gap-2 shadow-sm">
                                    <i class="fas fa-save text-xs"></i> Simpan Perubahan
                                </button>
                                <button type="reset"
                                    class="rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition inline-flex items-center gap-2">
                                    <i class="fas fa-undo text-xs"></i> Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Section -->
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm mb-6">
            <!-- Password Header -->
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-lock text-blue-600"></i>
                    Keamanan Akun
                </h2>
                <p class="mt-1 text-sm text-slate-600">Kelola password dan keamanan akun Anda</p>
            </div>

            <!-- Password Content -->
            <div class="p-6">
                <div class="max-w-2xl space-y-6">
                    <!-- Change Password Form -->
                    <div class="border border-slate-200 rounded-lg p-5 bg-slate-50">
                        <h3 class="font-semibold text-slate-900 mb-1 flex items-center gap-2">
                            <i class="fas fa-key text-sm text-slate-600"></i>
                            Ubah Password
                        </h3>
                        <p class="text-xs text-slate-600 mb-5">Perbarui password Anda secara berkala untuk keamanan maksimal</p>

                        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-slate-900 mb-2">Password Saat Ini <span class="text-red-500">*</span></label>
                                <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                                    placeholder="Masukkan password Anda saat ini"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    required>
                                @error('current_password', 'updatePassword')
                                    <span class="text-red-500 text-xs mt-1.5 flex items-center gap-1 block"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">Password Baru <span class="text-red-500">*</span></label>
                                    <input type="password" id="password" name="password" autocomplete="new-password"
                                        placeholder="Buat password yang kuat"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                        required>
                                    @error('password', 'updatePassword')
                                        <span class="text-red-500 text-xs mt-1.5 flex items-center gap-1 block"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                                    @enderror
                                    <p class="text-xs text-slate-500 mt-1.5">✓ Minimum 8 karakter, kombinasi huruf, angka, dan simbol</p>
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-900 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                        placeholder="Ulangi password baru Anda"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                        required>
                                </div>
                            </div>

                            <div class="pt-4 flex gap-3">
                                <button type="submit"
                                    class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 active:bg-blue-800 transition inline-flex items-center gap-2 shadow-sm">
                                    <i class="fas fa-save text-xs"></i> Simpan Password
                                </button>
                                <button type="reset"
                                    class="rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition inline-flex items-center gap-2">
                                    <i class="fas fa-undo text-xs"></i> Batal
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Forgot Password Section -->
                    <div class="border border-slate-200 rounded-lg p-5 bg-amber-50">
                        <h3 class="font-semibold text-slate-900 mb-1 flex items-center gap-2">
                            <i class="fas fa-question-circle text-sm text-amber-600"></i>
                            Lupa Password?
                        </h3>
                        <p class="text-xs text-slate-600 mb-4">Kami akan mengirimkan tautan reset password ke email Anda</p>
                        <a href="{{ route('password.request') }}"
                            class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-5 py-2 text-sm font-medium text-white hover:bg-amber-700 transition gap-2">
                            <i class="fas fa-paper-plane text-xs"></i> Reset Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Section -->
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <!-- Logout Header -->
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-sign-out-alt text-red-600"></i>
                    Keluar dari Akun
                </h2>
                <p class="mt-1 text-sm text-slate-600">Logout dari sistem</p>
            </div>

            <!-- Logout Content -->
            <div class="p-6">
                <div class="flex flex-col gap-4">
                    <p class="text-sm text-slate-600">Anda akan keluar dari akun Anda dan sesi akan berakhir di semua perangkat.</p>
                    <form action="{{ route('profile.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white hover:bg-red-700 transition inline-flex items-center gap-2">
                            <i class="fas fa-sign-out-alt text-xs"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection