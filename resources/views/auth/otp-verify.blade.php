<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun - Fraud Report</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="h-screen w-screen overflow-hidden">

<div class="relative h-full w-full flex items-center justify-center">

    <!-- BACKGROUND -->
    <img src="{{ asset('assets/img/image1.png') }}"
         class="absolute inset-0 w-full h-full object-cover">

    <!-- OVERLAY -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- LOGO -->
    <div class="absolute top-6 left-6 md:left-8 flex items-center gap-2 text-white font-semibold z-10">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Fraud Report Logo" class="w-12 h-12 max-w-12 max-h-12 object-contain">
        <span class="text-lg md:text-xl">Fraud <span class="text-brand-orange">Report</span></span>
    </div>

    <!-- MAIN CONTENT -->
    <div class="relative z-10 w-full max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between">

        <!-- TEXT KIRI -->
        <div class="text-white text-center md:text-left mb-8 md:mb-0 md:mr-12">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
                Verifikasi <br>
                <span class="text-brand-orange">Kode OTP</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 max-w-md">
                @if(($purpose ?? 'register') === 'register')
                    Verifikasi email Anda terlebih dahulu. Setelah itu, tunggu persetujuan administrator sebelum dapat login.
                @else
                    Masukkan kode OTP yang telah dikirim ke email Anda.
                @endif
            </p>
        </div>

        <!-- FORM OTP VERIFY -->
        <div class="w-full max-w-md bg-white/95 backdrop-blur-sm p-8 md:p-10 rounded-2xl shadow-2xl border border-white/20">

            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    Verifikasi Akun
                </h2>
                <p class="text-gray-600 text-sm">
                    @if(($purpose ?? 'register') === 'register')
                        Langkah 2: Verifikasi email dengan kode OTP
                    @else
                        Masukkan kode yang dikirim ke email
                    @endif
                </p>
            </div>

            @if(session('status'))
                <div class="mb-6 flex items-start gap-3 rounded-lg border border-green-300 bg-green-50 p-4 text-green-800">
                    <i class="fas fa-check-circle flex-shrink-0 mt-0.5"></i>
                    <div class="text-sm">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @if(!empty($showDevCode) && !empty($devOtpCode))
                <div class="mb-6 rounded-lg border border-amber-300 bg-amber-50 p-4 text-amber-900 text-sm">
                    <strong>Mode Development:</strong> Email dikirim ke log, bukan inbox.
                    Kode OTP Anda: <span class="font-mono font-bold text-lg tracking-widest">{{ $devOtpCode }}</span>
                    <p class="mt-1 text-xs text-amber-700">Juga tersedia di <code>storage/logs/laravel.log</code></p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 flex items-start gap-3 rounded-lg border border-red-300 bg-red-50 p-4 text-red-800">
                    <i class="fas fa-exclamation-circle flex-shrink-0 mt-0.5"></i>
                    <div class="text-sm">
                        <strong>Verifikasi gagal:</strong>
                        <ul class="mt-2 list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify.code') }}" class="space-y-6">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', $email) }}"
                               class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange transition-colors bg-gray-50 focus:bg-white"
                               placeholder="Masukkan email Anda"
                               required readonly>
                    </div>
                </div>

                <!-- OTP CODE -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode OTP
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="text" id="code" name="code"
                               value="{{ old('code') }}"
                               class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange transition-colors bg-gray-50 focus:bg-white"
                               placeholder="Masukkan kode 6 digit"
                               maxlength="6" required>
                    </div>
                    @error('code')
                        <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                            <i class="fas fa-times-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-brand-blue hover:bg-brand-orange text-white py-3 px-4 rounded-lg font-medium transition-colors focus:ring-2 focus:ring-brand-orange focus:ring-offset-2">
                    @if(($purpose ?? 'register') === 'register')
                        Verifikasi Email
                    @else
                        Verifikasi dan Masuk
                    @endif
                </button>
            </form>

            <!-- BACK LINK -->
            <div class="text-center mt-6">
                <div class="text-gray-600 text-sm flex items-center justify-center gap-2">
                    <span>Belum menerima kode?</span>
                    <form method="POST" action="{{ route('verification.resend.code') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-brand-blue hover:text-brand-orange font-medium transition-colors">
                            Minta kode baru
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
