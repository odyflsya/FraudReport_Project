<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fraud Report</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">

<div class="flex h-screen">

    <!-- SIDEBAR -->
<aside class="w-64 bg-sky-600 text-white flex flex-col fixed h-full">

        <!-- LOGO -->
        <div class="p-6 text-lg font-semibold flex items-center gap-2">
            <div class="w-6 h-6 bg-orange-500 rotate-45"></div>
            Fraud <span class="text-orange-400">Report</span>
        </div>

        <!-- MENU -->
        <nav class="mt-4 flex flex-col text-sm">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="px-6 py-3 flex items-center gap-3 transition {{ request()->routeIs('dashboard') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M3 13h8V3H3v10zM13 21h8v-6h-8v6zM13 3v8h8V3h-8zM3 21h8v-4H3v4z"/>
                </svg>

                Dashboard
            </a>

            <!-- Manajemen Kasus -->
            <a href="{{ route('kasus.index') }}"
               class="px-6 py-3 flex items-center gap-3 transition {{ request()->routeIs('kasus.index', 'kasus.show', 'kasus.edit') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M9 12h6M9 16h6M9 8h6M5 4h14v16H5z"/>
                </svg>

                Manajemen Kasus
            </a>

            <!-- Input Kasus -->
            <a href="{{ route('kasus.create') }}"
               class="px-6 py-3 flex items-center gap-3 transition {{ request()->routeIs('kasus.create') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M12 4v16m8-8H4"/>
                </svg>

                Input Kasus
            </a>

            <!-- Export -->
            <a href="{{ route('kasus.export') }}"
               class="px-6 py-3 flex items-center gap-3 transition {{ request()->routeIs('kasus.export') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M12 16v-8m0 0l-3 3m3-3l3 3M4 20h16"/>
                </svg>

                Export Laporan
            </a>

            <!-- Pengaturan -->
            <a href="#"
               class="px-6 py-3 hover:bg-sky-500 flex items-center gap-3 transition">

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

            <!-- Search -->
            <svg class="w-5 h-5 text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2"
                    d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>

            <!-- Notification -->
            <svg class="w-5 h-5 text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2"
                    d="M15 17h5l-1.4-1.4A2 2 0 0118 14V11a6 6 0 10-12 0v3a2 2 0 01-.6 1.6L4 17h5m6 0a3 3 0 11-6 0"/>
            </svg>

            <!-- User -->
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
                <span class="text-sm">{{ auth()->user()->name }}</span>
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