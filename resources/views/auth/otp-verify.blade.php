<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Akun</title>
    @vite('resources/css/app.css')
</head>
<body class="h-screen w-screen">
<div class="relative h-full w-full">
    <img src="{{ asset('assets/img/image1.png') }}" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="absolute left-12 top-1/2 -translate-y-1/2 text-white z-10 hidden md:block">
        <h1 class="text-5xl font-bold leading-tight">
            Verifikasi Kode OTP
        </h1>
        <p class="mt-4 text-lg text-gray-200 max-w-md">
            Masukkan kode OTP yang telah dikirim ke email Anda untuk menyelesaikan proses login.
        </p>
    </div>
    <div class="absolute right-10 top-1/2 -translate-y-1/2 z-10 w-full max-w-md">
        <div class="bg-white p-10 rounded-2xl shadow-lg">
            <h2 class="text-2xl font-semibold text-center mb-8">
                Verifikasi Akun
            </h2>

            @if(session('status'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify.code') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block mb-2 text-sm">Email</label>
                    <div class="flex items-center bg-gray-200 rounded-lg px-3">
                        <span class="mr-2">📧</span>
                        <input type="email" name="email" value="{{ old('email', $email) }}"
                               class="w-full bg-transparent py-3 focus:outline-none"
                               required>
                    </div>
                    @error('email')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block mb-2 text-sm">Kode OTP</label>
                    <div class="flex items-center bg-gray-200 rounded-lg px-3">
                        <span class="mr-2">🔐</span>
                        <input type="text" name="code" value="{{ old('code') }}"
                               class="w-full bg-transparent py-3 focus:outline-none"
                               maxlength="6" required>
                    </div>
                    @error('code')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg">
                    Verifikasi dan Masuk
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Belum menerima kode?
                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Daftar ulang atau periksa kembali email Anda</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
