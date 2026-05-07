@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="mx-auto w-full px-4 sm:px-6 lg:px-8 max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Pengaturan</h1>
            <p class="mt-2 text-sm text-slate-600">Atur profil akun dan logout sistem</p>
        </div>

        <!-- Alert Messages -->
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-4 text-sm text-green-700">
                @if (session('status') === 'profile-updated')
                    Profil berhasil diperbarui.
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
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mt-3 list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Section -->
        <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-lg mb-6">
            <!-- Profile Header -->
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-8 sm:px-8">
                <h2 class="text-xl font-semibold text-slate-900">Profil</h2>
                <p class="mt-1 text-sm text-slate-600">Kelola informasi profil dan foto Anda</p>
            </div>

            <!-- Profile Content -->
            <div class="px-6 py-8 sm:px-8">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <!-- Profile Photo Section -->
                    <div class="flex flex-col items-center md:col-span-1">
                        <div class="mb-4">
                            @if ($user->profile_photo_path)
                                <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}"
                                    class="h-32 w-32 rounded-full object-cover border-4 border-slate-200 shadow-md">
                            @else
                                <div class="h-32 w-32 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center border-4 border-slate-200 shadow-md">
                                    <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Upload Photo Form -->
                        <form action="{{ route('profile.upload-photo') }}" method="POST" enctype="multipart/form-data" class="w-full">
                            @csrf
                            <input type="file" name="profile_photo" accept="image/*" class="hidden" id="profile_photo_input">
                            <button type="button" onclick="document.getElementById('profile_photo_input').click()"
                                class="mb-2 w-full rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition flex items-center justify-center gap-2">
                                <i class="fas fa-camera"></i> Ganti Foto
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
                            <form action="{{ route('profile.delete-photo') }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Hapus foto profil?')"
                                    class="w-full rounded-xl border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                                    Hapus Foto
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Profile Information Section -->
                    <div class="md:col-span-2">
                        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    required>
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    required>
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <p class="text-sm mt-2 text-amber-600">
                                        Email belum diverifikasi.
                                    </p>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit"
                                    class="rounded-xl bg-blue-600 px-6 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Section -->
        <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-lg mb-6">
            <!-- Password Header -->
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-8 sm:px-8">
                <h2 class="text-xl font-semibold text-slate-900">Keamanan</h2>
                <p class="mt-1 text-sm text-slate-600">Kelola password dan keamanan akun Anda</p>
            </div>

            <!-- Password Content -->
            <div class="px-6 py-8 sm:px-8">
                <div class="max-w-md mx-auto space-y-6">
                    <!-- Change Password Form -->
                    <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="rounded-[28px] border border-slate-200 bg-slate-50 p-6">
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">Ubah Password</h3>
                            <p class="text-sm text-slate-600 mb-6">Perbarui password akun Anda secara berkala untuk keamanan.</p>

                            <div class="space-y-5">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Password Saat Ini</label>
                                    <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                        required>
                                    @error('current_password', 'updatePassword')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                                    <input type="password" id="password" name="password" autocomplete="new-password"
                                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                        required>
                                    @error('password', 'updatePassword')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                        required>
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                    class="w-full rounded-xl bg-blue-600 px-6 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                                    Simpan Password
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Forgot Password Section -->
                    <div class="rounded-[28px] border border-slate-200 bg-white p-4 shadow-sm">
                        <h3 class="text-sm font-semibold text-slate-900 mb-2">Lupa Password?</h3>
                        <p class="text-xs text-slate-600 mb-3">Kirim tautan reset password ke email Anda</p>
                        <a href="{{ route('password.request') }}"
                            class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-4 py-2 text-xs font-medium text-white hover:bg-amber-700 transition">
                            Reset Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Section -->
        <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-lg">
            <!-- Logout Header -->
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-8 sm:px-8">
                <h2 class="text-xl font-semibold text-slate-900">Logout</h2>
                <p class="mt-1 text-sm text-slate-600">Keluar dari akun Anda</p>
            </div>

            <!-- Logout Content -->
            <div class="px-6 py-8 sm:px-8">
                <div class="flex flex-col items-start gap-4">
                    <p class="text-sm text-slate-600">Apakah Anda yakin ingin keluar dari akun ini?</p>
                    <form action="{{ route('profile.logout') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto rounded-xl bg-red-600 px-6 py-2 text-sm font-medium text-white hover:bg-red-700 transition flex items-center justify-center gap-2">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
