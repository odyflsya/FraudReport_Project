<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Fraud Report</title>
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
            Welcome! <br>
            Fraud <span class="text-orange-500">Report</span>
        </h1>

        <p class="mt-4 text-lg text-gray-200">
            Manage and report fraud cases easily
        </p>
    </div>

    <!-- FORM LOGIN -->
    <div class="absolute right-10 top-1/2 -translate-y-1/2 z-10 w-full max-w-md">

        <div class="bg-white p-10 rounded-2xl shadow-lg">

            <h2 class="text-2xl font-semibold text-center mb-8">
                Login To Fraud Report
            </h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="block mb-2 text-sm">Email</label>
                    <div class="flex items-center bg-gray-200 rounded-lg px-3">
                        <span class="mr-2">📧</span>
                        <input type="email" name="email"
                               class="w-full bg-transparent py-3 focus:outline-none"
                               required>
                    </div>
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="block mb-2 text-sm">Password</label>
                    <div class="flex items-center bg-gray-200 rounded-lg px-3">
                        <span class="mr-2">🔒</span>
                        <input type="password" name="password"
                               class="w-full bg-transparent py-3 focus:outline-none"
                               required>
                    </div>
                </div>

            <div class="mt-4 text-right">
                <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">
                    Lupa Password?
                </a>
            </div>

                <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg">
                    Login
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">
                    Register
                </a>
            </p>

        </div>
    </div>

</div>

</body>
</html>