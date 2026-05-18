<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Fraud Report</title>
    @vite('resources/css/app.css')
</head>
<body class="h-screen w-screen">

<div class="relative h-full w-full">

    <!-- BACKGROUND -->
    <img src="{{ asset('assets/img/image1.png') }}"
         class="absolute inset-0 w-full h-full object-cover">

    <!-- OVERLAY -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- LOGO -->
    <div class="absolute top-6 left-8 flex items-center gap-2 text-white font-semibold z-10">
        <div class="w-8 h-8 bg-orange-500 rounded rotate-45"></div>
        <span>Fraud <span class="text-orange-500">Report</span></span>
    </div>

    <!-- TEXT KIRI -->
    <div class="absolute left-12 top-1/2 -translate-y-1/2 text-white z-10 hidden md:block">
        <h1 class="text-5xl font-bold leading-tight">
            Buat <br>
            <span class="text-orange-500">Password Baru</span>
        </h1>

        <p class="mt-4 text-lg text-gray-200 max-w-md">
            Masukkan password baru Anda untuk melanjutkan akses ke sistem
        </p>
    </div>

    <!-- FORM -->
    <div class="absolute right-10 top-1/2 -translate-y-1/2 z-10 w-full max-w-md">

        <div class="bg-white p-10 rounded-2xl shadow-lg">

            <h2 class="text-2xl font-semibold text-center mb-8">
                Reset Password
            </h2>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- EMAIL -->
                <div>
                    <label class="block mb-2 text-sm">Email</label>
                    <div class="flex items-center bg-gray-200 rounded-lg px-3">
                        <span class="mr-2">📧</span>
                        <input type="email" name="email"
                               value="{{ old('email', $request->email) }}"
                               class="w-full bg-transparent py-3 focus:outline-none"
                               required autofocus autocomplete="username">
                    </div>
                    @if ($errors->has('email'))
                        <p class="text-red-500 text-sm mt-2">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="block mb-2 text-sm">Password Baru</label>
                    <div class="flex items-center bg-gray-200 rounded-lg px-3">
                        <span class="mr-2">🔒</span>
                        <input type="password" name="password"
                               class="w-full bg-transparent py-3 focus:outline-none"
                               required autocomplete="new-password">
                    </div>
                    @if ($errors->has('password'))
                        <p class="text-red-500 text-sm mt-2">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- CONFIRM PASSWORD -->
                <div>
                    <label class="block mb-2 text-sm">Konfirmasi Password</label>
                    <div class="flex items-center bg-gray-200 rounded-lg px-3">
                        <span class="mr-2">🔒</span>
                        <input type="password" name="password_confirmation"
                               class="w-full bg-transparent py-3 focus:outline-none"
                               required autocomplete="new-password">
                    </div>
                    @if ($errors->has('password_confirmation'))
                        <p class="text-red-500 text-sm mt-2">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </div>

                <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg">
                    Reset Password
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Kembali ke
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">
                    Login
                </a>
            </p>

        </div>
    </div>

</div>

</body>
</html>
