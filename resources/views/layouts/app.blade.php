<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
       class="px-6 py-3 flex items-center gap-3 transition {{ request()->routeIs('dashboard') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
        
        <i class="fa-solid fa-table-columns w-5"></i>
        Dashboard
    </a>

    <!-- Manajemen Kasus -->
    <a href="{{ route('kasus.index') }}"
       class="px-6 py-3 flex items-center gap-3 transition {{ request()->routeIs('kasus.index', 'kasus.show', 'kasus.edit') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
        
        <i class="fa-solid fa-folder-open w-5"></i>
        Manajemen Kasus
    </a>

    <!-- Input Kasus -->
    <a href="{{ route('kasus.create') }}"
       class="px-6 py-3 flex items-center gap-3 transition {{ request()->routeIs('kasus.create') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
        
        <i class="fa-solid fa-plus w-5"></i>
        Input Kasus
    </a>

    <!-- Export -->
    <a href="{{ route('kasus.export') }}"
       class="px-6 py-3 flex items-center gap-3 transition {{ request()->routeIs('kasus.export') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
        
        <i class="fa-solid fa-file-export w-5"></i>
        Export Laporan
    </a>

    <!-- Pengaturan -->
    <a href="{{ route('pengaturan.index') }}"
       class="px-6 py-3 flex items-center gap-3 transition {{ request()->routeIs('pengaturan.index', 'profile.edit') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
        
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
                @elseif(request()->routeIs('kasus.index', 'kasus.show', 'kasus.edit'))
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
    @yield('content')
</div>

</main>

</div>

</body>
</html>