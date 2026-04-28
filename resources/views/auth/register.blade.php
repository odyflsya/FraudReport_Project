<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Fraud Report</title>
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
            Hello! <br>
            Fraud <span class="text-orange-500">Report</span>
        </h1>

        <p class="mt-4 text-lg text-gray-200 max-w-md">
            Create an account to begin managing fraud case records and reports
        </p>
    </div>

    <!-- FORM -->
<div class="absolute right-16 top-1/2 -translate-y-1/2 z-10 w-[420px]">

    <div class="bg-white px-8 py-6 rounded-2xl shadow-xl">

        <h2 class="text-2xl font-semibold text-center mb-6">
            Create Account
        </h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- NAME -->
            <div>
                <label class="block mb-1 text-sm">Name</label>
                <div class="flex items-center bg-gray-200 rounded-lg px-3">
                    <span class="mr-2">👤</span>
                    <input type="text" name="name"
                           class="w-full bg-transparent py-2.5 focus:outline-none"
                           required>
                </div>
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block mb-1 text-sm">Email</label>
                <div class="flex items-center bg-gray-200 rounded-lg px-3">
                    <span class="mr-2">📧</span>
                    <input type="email" name="email"
                           class="w-full bg-transparent py-2.5 focus:outline-none"
                           required>
                </div>
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block mb-1 text-sm">Password</label>
                <div class="flex items-center bg-gray-200 rounded-lg px-3">
                    <span class="mr-2">🔒</span>
                    <input type="password" name="password"
                           class="w-full bg-transparent py-2.5 focus:outline-none"
                           required>
                </div>
            </div>

            <!-- CONFIRM -->
            <div>
                <label class="block mb-1 text-sm">Confirm Password</label>
                <div class="flex items-center bg-gray-200 rounded-lg px-3">
                    <span class="mr-2">🔒</span>
                    <input type="password" name="password_confirmation"
                           class="w-full bg-transparent py-2.5 focus:outline-none"
                           required>
                </div>
            </div>

            <!-- BUTTON -->
            <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2.5 rounded-lg mt-2">
                Register
            </button>
        </form>

            <!-- LOGIN LINK -->
            <p class="text-center text-sm text-gray-500 mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">
                    Login
                </a>
            </p>

        </div>
    </div>

</div>

</body>
</html>