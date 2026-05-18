<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Fraud Report</title>
    @vite('resources/css/app.css')
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
                Lupa <br>
                <span class="text-brand-orange">Password</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 max-w-md">
                Kami akan membantu Anda mengatur ulang kata sandi dan kembali ke akun Anda dengan aman.
            </p>
        </div>

        <!-- FORM FORGOT PASSWORD -->
        <div class="w-full max-w-md bg-white/95 backdrop-blur-sm p-8 md:p-10 rounded-2xl shadow-2xl border border-white/20">

            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    Reset Kata Sandi
                </h2>
                <p class="text-gray-600 text-sm">
                    Kami akan mengirim link reset ke email Anda
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-700">
                        {{ session('status') }}
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email"
                               class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange transition-colors bg-gray-50 focus:bg-white @error('email') border-red-500 @enderror"
                               placeholder="Masukkan email Anda"
                               value="{{ old('email') }}"
                               required autofocus>
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-brand-blue hover:bg-brand-orange text-white py-3 px-4 rounded-lg font-medium transition-colors focus:ring-2 focus:ring-brand-orange focus:ring-offset-2">
                    Kirim Link Reset
                </button>
            </form>

            <!-- BACK TO LOGIN -->
            <div class="text-center mt-6">
                <p class="text-gray-600">
                    Ingat kata sandi Anda?
                    <a href="{{ route('login') }}" class="text-brand-blue hover:text-brand-orange font-medium transition-colors">
                        Kembali ke Login
                    </a>
                </p>
            </div>

        </div>
    </div>

</div>

</body>
</html>
