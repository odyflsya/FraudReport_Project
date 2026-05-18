<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fraud Report</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    @vite('resources/css/app.css')
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
               class="px-6 py-3 flex items-center gap-3 transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-orange-500 shadow-md' : 'hover:bg-white/10' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M3 13h8V3H3v10zM13 21h8v-6h-8v6zM13 3v8h8V3h-8zM3 21h8v-4H3v4z"/>
                </svg>

                Dashboard
            </a>

            <!-- Manajemen Kasus -->
            <a href="{{ route('kasus.index') }}"
               class="px-6 py-3 flex items-center gap-3 transition duration-200 {{ request()->routeIs('kasus.index', 'kasus.show', 'kasus.edit') ? 'bg-orange-500 shadow-md' : 'hover:bg-white/10' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M9 12h6M9 16h6M9 8h6M5 4h14v16H5z"/>
                </svg>

                Manajemen Kasus
            </a>

            <!-- Input Kasus -->
            <a href="{{ route('kasus.create') }}"
               class="px-6 py-3 flex items-center gap-3 transition duration-200 {{ request()->routeIs('kasus.create') ? 'bg-orange-500 shadow-md' : 'hover:bg-white/10' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M12 4v16m8-8H4"/>
                </svg>

                Input Kasus
            </a>

            <!-- Export -->
            <a href="{{ route('kasus.export') }}"
               class="px-6 py-3 flex items-center gap-3 transition duration-200 {{ request()->routeIs('kasus.export') ? 'bg-orange-500 shadow-md' : 'hover:bg-white/10' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M12 16v-8m0 0l-3 3m3-3l3 3M4 20h16"/>
                </svg>
                Export Laporan
            </a>

            <a href="{{ route('pengaturan.index') }}"
               class="px-6 py-3 flex items-center gap-3 transition duration-200 {{ request()->routeIs('pengaturan.index', 'profile.edit') ? 'bg-orange-500 shadow-md' : 'hover:bg-white/10' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M11.983 5.5a1.5 1.5 0 012.034 0l.78.76a1.5 1.5 0 001.46.38l1.05-.28a1.5 1.5 0 011.79 1.79l-.28 1.05a1.5 1.5 0 00.38 1.46l.76.78a1.5 1.5 0 010 2.034l-.76.78a1.5 1.5 0 00-.38 1.46l.28 1.05a1.5 1.5 0 01-1.79 1.79l-1.05-.28a1.5 1.5 0 00-1.46.38l-.78.76a1.5 1.5 0 01-2.034 0l-.78-.76a1.5 1.5 0 00-1.46-.38l-1.05.28a1.5 1.5 0 01-1.79-1.79l.28-1.05a1.5 1.5 0 00-.38-1.46l-.76-.78a1.5 1.5 0 010-2.034l.76-.78a1.5 1.5 0 00.38-1.46l-.28-1.05a1.5 1.5 0 011.79-1.79l1.05.28a1.5 1.5 0 001.46-.38l.78-.76z"/>
                </svg>

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

        <!-- RIGHT -->
        <div class="flex items-center gap-6">

            <!-- User -->
            <a href="{{ route('pengaturan.index') }}" class="flex items-center gap-2 hover:opacity-75 transition">
                @if(auth()->user()->profile_photo_path)
                    <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border-2 border-gray-300">
                @else
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
                <span class="text-sm">{{ auth()->user()->name }}</span>
            </a>

            <form method="POST" action="{{ route('profile.logout') }}" class="ml-2">
                @csrf
                <button type="submit" class="p-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition" title="Logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>

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