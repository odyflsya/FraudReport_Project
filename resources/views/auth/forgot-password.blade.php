<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Fraud Report</title>
    @vite('resources/css/app.css')
</head>
<body class="h-screen w-screen">

<div class="relative h-full w-full">

    <!-- BACKGROUND FULL -->
    <img src="{{ asset('assets/img/image1.png') }}"
         class="absolute inset-0 w-full h-full object-cover">

    <!-- OVERLAY GELAP -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- LOGO -->
    <div class="absolute top-6 left-8 flex items-center gap-2 text-white font-semibold z-10">
        <div class="w-8 h-8 bg-orange-500 rounded rotate-45"></div>
        <span>Fraud <span class="text-orange-500">Report</span></span>
    </div>

    <!-- TEXT KIRI -->
    <div class="absolute left-12 top-1/2 -translate-y-1/2 text-white z-10 hidden md:block">
        <h1 class="text-5xl font-bold leading-tight">
            Lupa <br>
            <span class="text-orange-500">Password?</span>
        </h1>

        <p class="mt-4 text-lg text-gray-200 max-w-md">
            Tidak masalah, kami akan mengirimkan link reset password ke email Anda
        </p>
    </div>

    <!-- FORM -->
    <div class="absolute right-10 top-1/2 -translate-y-1/2 z-10 w-full max-w-md">

        <div class="bg-white p-10 rounded-2xl shadow-lg">

            <h2 class="text-2xl font-semibold text-center mb-8">
                Reset Password
            </h2>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="block mb-2 text-sm">Email</label>
                    <div class="flex items-center bg-gray-200 rounded-lg px-3">
                        <span class="mr-2">📧</span>
                        <input type="email" name="email"
                               value="{{ old('email') }}"
                               class="w-full bg-transparent py-3 focus:outline-none"
                               required autofocus>
                    </div>
                    @if ($errors->has('email'))
                        <p class="text-red-500 text-sm mt-2">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg">
                    Kirim Link Reset
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Ingat password Anda?
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">
                    Login
                </a>
            </p>

        </div>
    </div>

</div>

</body>
</html>
