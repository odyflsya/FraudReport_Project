<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi - Fraud Report</title>
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
                Reset <br>
                <span class="text-brand-orange">Password</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 max-w-md">
                Masukkan kata sandi baru Anda untuk mengamankan akun dan lanjutkan menggunakan aplikasi.
            </p>
        </div>

        <!-- FORM RESET PASSWORD -->
        <div class="w-full max-w-md bg-white/95 backdrop-blur-sm p-8 md:p-10 rounded-2xl shadow-2xl border border-white/20">

            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    Perbarui Kata Sandi
                </h2>
                <p class="text-gray-600 text-sm">
                    Masukkan dan konfirmasi kata sandi baru Anda
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                               value="{{ old('email', $request->email) }}"
                               required autofocus autocomplete="username">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PASSWORD BARU -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Kata Sandi Baru
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password"
                               class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange transition-colors bg-gray-50 focus:bg-white @error('password') border-red-500 @enderror"
                               placeholder="Buat kata sandi baru"
                               required autocomplete="new-password">
                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center opacity-0 transition-opacity duration-200">
                            <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- KONFIRMASI PASSWORD -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange transition-colors bg-gray-50 focus:bg-white @error('password_confirmation') border-red-500 @enderror"
                               placeholder="Konfirmasi kata sandi baru"
                               required autocomplete="new-password">
                        <button type="button" id="toggle-confirm-password" class="absolute inset-y-0 right-0 pr-3 flex items-center opacity-0 transition-opacity duration-200">
                            <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-brand-blue hover:bg-brand-orange text-white py-3 px-4 rounded-lg font-medium transition-colors focus:ring-2 focus:ring-brand-orange focus:ring-offset-2">
                    Reset Kata Sandi
                </button>
            </form>

            <!-- BACK TO LOGIN -->
            <div class="text-center mt-6">
                <p class="text-gray-600">
                    Kembali ke
                    <a href="{{ route('login') }}" class="text-brand-blue hover:text-brand-orange font-medium transition-colors">
                        Login
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
        eyeIcon.classList.add('text-brand-blue');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('text-brand-blue');
    }
});

document.getElementById('password_confirmation').addEventListener('input', function() {
    const toggleButton = document.getElementById('toggle-confirm-password');
    if (this.value.length > 0) {
        toggleButton.classList.remove('opacity-0');
        toggleButton.classList.add('opacity-100');
    } else {
        toggleButton.classList.remove('opacity-100');
        toggleButton.classList.add('opacity-0');
    }
});

document.getElementById('toggle-confirm-password').addEventListener('click', function() {
    const passwordInput = document.getElementById('password_confirmation');
    const eyeIcon = this.querySelector('svg');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.add('text-brand-blue');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('text-brand-blue');
    }
});
</script>

</body>
</html>
