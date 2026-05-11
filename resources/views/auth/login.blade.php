<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fraud Report</title>
    @vite('resources/css/app.css')
</head>
<body class="h-screen w-screen overflow-hidden">

<div class="relative h-full w-full flex items-center justify-center">

    <!-- BACKGROUND FULL -->
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
                Selamat Datang! <br>
                <span class="text-brand-orange">Fraud Report</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 max-w-md">
                Akses dashboard Anda untuk mengelola dan memantau kasus fraud secara efektif.
            </p>
        </div>

        <!-- FORM LOGIN -->
        <div class="w-full max-w-md bg-white/95 backdrop-blur-sm p-8 md:p-10 rounded-2xl shadow-2xl border border-white/20">

            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    Masuk
                </h2>
                <p class="text-gray-600">
                    Masukkan kredensial Anda untuk mengakses akun
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
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
                               class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange transition-colors bg-gray-50 focus:bg-white"
                               placeholder="Masukkan email Anda"
                               required>
                    </div>
                </div>

                <!-- PASSWORD -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password"
                               class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange transition-colors bg-gray-50 focus:bg-white"
                               placeholder="Masukkan kata sandi Anda"
                               required>
                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center opacity-0 transition-opacity duration-200">
                            <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- REMEMBER ME & FORGOT PASSWORD -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-brand-blue focus:ring-brand-blue border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Ingat saya
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-brand-blue hover:text-brand-orange transition-colors">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full bg-brand-blue hover:bg-brand-orange text-white py-3 px-4 rounded-lg font-medium transition-colors focus:ring-2 focus:ring-brand-orange focus:ring-offset-2">
                    Masuk
                </button>
            </form>

            <!-- REGISTER LINK -->
            <div class="text-center mt-6">
                <p class="text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-brand-blue hover:text-brand-orange font-medium transition-colors">
                        Buat di sini
                    </a>
                </p>
            </div>

        </div>
    </div>

</div>

<script>
document.getElementById('password').addEventListener('input', function() {
    const toggleButton = document.getElementById('toggle-password');
    if (this.value.length > 0) {
        toggleButton.classList.remove('opacity-0');
        toggleButton.classList.add('opacity-100');
    } else {
        toggleButton.classList.remove('opacity-100');
        toggleButton.classList.add('opacity-0');
    }
});

document.getElementById('toggle-password').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = this.querySelector('svg');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>';
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
});
</script>

</body>
</html>