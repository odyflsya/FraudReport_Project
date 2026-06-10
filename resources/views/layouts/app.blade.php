<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fraud Report</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100">

<div class="flex h-screen">

    <!-- SIDEBAR -->
<aside class="w-64 bg-[#0693E3] text-white flex flex-col fixed h-full">

        <!-- LOGO -->
        <div class="p-6 flex items-center gap-2 text-white font-semibold border-b border-white/20">
            <img src="{{ asset('assets/img/logo.png') }}" class="w-12 h-12">
            <span class="text-lg">Fraud <span class="text-brand-orange">Report</span></span>
        </div>

<!-- MENU -->
<nav class="mt-4 flex flex-col text-sm">

    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}"
       class="px-6 py-3 flex items-center gap-3 transition text-white/90 {{ request()->routeIs('dashboard') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
        <i class="fa-solid fa-table-columns w-5"></i>
        Dashboard
    </a>

    <!-- Manajemen Kasus -->
    <a href="{{ route('kasus.index') }}"
       class="px-6 py-3 flex items-center gap-3 transition text-white/90 {{ request()->routeIs('kasus.index', 'kasus.show', 'kasus.edit', 'kasus.import', 'kasus.import-form') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
        <i class="fa-solid fa-folder-open w-5"></i>
        Manajemen Kasus
    </a>

    <!-- Input Kasus -->
    <a href="{{ route('kasus.create') }}"
       class="px-6 py-3 flex items-center gap-3 transition text-white/90 {{ request()->routeIs('kasus.create') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
        <i class="fa-solid fa-plus w-5"></i>
        Input Kasus
    </a>

    <!-- Export Laporan -->
    <a href="{{ route('kasus.export') }}"
       class="px-6 py-3 flex items-center gap-3 transition text-white/90 {{ request()->routeIs('kasus.export') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
        <i class="fa-solid fa-file-export w-5"></i>
        Export Laporan
    </a>

    <!-- Pengaturan -->
    <a href="{{ route('pengaturan.index') }}"
       class="px-6 py-3 flex items-center gap-3 transition text-white/90 {{ request()->routeIs('pengaturan.index', 'profile.edit') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
        <i class="fa-solid fa-gear w-5"></i>
        Pengaturan
    </a>

</nav>
    </aside>

    <!-- MAIN -->
<main class="flex-1 ml-64 flex flex-col min-w-0">

<!-- NAVBAR -->
<div class="fixed top-0 left-64 right-0 bg-white shadow z-20">
    <div class="flex justify-between items-center px-8 py-4">

        <!-- LEFT -->
        <div>
            <h1 class="text-2xl font-semibold">
                @if(request()->routeIs('dashboard'))
                Dashboard
                @elseif(request()->routeIs('kasus.index', 'kasus.show', 'kasus.edit', 'kasus.import', 'kasus.import-form'))
                Manajemen Kasus
                @elseif(request()->routeIs('kasus.create'))
                Input Kasus
                @elseif(request()->routeIs('kasus.export'))
                Export Laporan
                @elseif(request()->routeIs('pengaturan.index', 'profile.edit'))
                Pengaturan
                @else
                Dashboard
                @endif
            </h1>
            <p class="text-gray-500 text-sm">
                Welcome {{ auth()->user()->name }}
            </p>
        </div>


            <!-- User -->
<!-- User Dropdown -->
<div x-data="{ open: false }" class="relative">

    <!-- TRIGGER -->
    <button type="button"
        @click="open = !open"
        class="flex items-center gap-2 hover:opacity-75 transition">

        @if(auth()->user()->profile_photo_path)
            <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}"
                 class="w-8 h-8 rounded-full object-cover border-2 border-gray-300">
        @else
            <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        @endif

        <span class="text-sm">{{ auth()->user()->name }}</span>
    </button>

    <!-- DROPDOWN -->
<div x-show="open"
     @click.outside="open = false"
     x-transition
     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-50">

    <!-- PENGATURAN -->
    <a href="{{ route('pengaturan.index') }}"
       class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">

        <i class="fa-solid fa-gear"></i>
        Pengaturan
    </a>

    <!-- LOGOUT -->
    <form method="POST" action="{{ route('profile.logout') }}">
        @csrf
        <button type="submit"
            class="flex items-center gap-2 w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50">

            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </button>
    </form>

</div>

        </div>

    </div>
</div>

<!-- CONTENT -->
<div class="mt-[80px] p-8 overflow-x-auto overflow-y-auto h-[calc(100vh-80px)]">
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-green-900">{{ __('Berhasil!') }}</h3>
                    <p class="mt-1 text-sm text-green-800 whitespace-pre-line">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-red-900">{{ __('Gagal!') }}</h3>
                    <p class="mt-1 text-sm text-red-800 whitespace-pre-line">{{ session('error') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="mb-6 rounded-lg border border-yellow-200 bg-yellow-50 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-yellow-900">{{ __('Peringatan!') }}</h3>
                    <p class="mt-1 text-sm text-yellow-800 whitespace-pre-line">{{ session('warning') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-yellow-600 hover:text-yellow-800">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @yield('content')
</div>

</main>

</div>

</body>
</html>