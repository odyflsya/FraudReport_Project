<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Fraud Report</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-slate-100">

<div class="flex h-screen">

    <aside class="w-64 bg-[#0693E3] text-white flex flex-col fixed h-full z-30 shadow-lg">
        <div class="p-6 flex items-center gap-2 text-white font-semibold border-b border-white/20">
            <img src="{{ asset('assets/img/logo.png') }}" class="w-12 h-12" alt="Logo">
            <span class="text-lg">Fraud <span class="text-brand-orange">Report</span></span>
        </div>

        <nav class="mt-4 flex flex-col text-sm">
            <a href="{{ route('admin.users.index') }}"
               class="px-6 py-3 flex items-center gap-3 transition text-white/90 {{ request()->routeIs('admin.users.*') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
                <i class="fa-solid fa-users w-5"></i>
                Manajemen User
            </a>

            <a href="{{ route('admin.activities.index') }}"
               class="px-6 py-3 flex items-center gap-3 transition text-white/90 {{ request()->routeIs('admin.activities.*') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
                <i class="fa-solid fa-clock-rotate-left w-5"></i>
                Log Aktivitas
            </a>

            <a href="{{ route('pengaturan.index') }}"
               class="px-6 py-3 flex items-center gap-3 transition text-white/90 {{ request()->routeIs('pengaturan.index', 'profile.edit') ? 'bg-orange-500' : 'hover:bg-sky-500' }}">
                <i class="fa-solid fa-gear w-5"></i>
                Pengaturan
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-64 flex flex-col min-w-0">

        <header class="fixed top-0 left-64 right-0 bg-white border-b border-gray-200 z-20">
            <div class="flex justify-between items-center px-8 py-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Panel Admin</p>
                    <h1 class="text-xl font-semibold text-gray-800">
                        @if(request()->routeIs('admin.users.*'))
                            Manajemen User
                        @elseif(request()->routeIs('admin.activities.index'))
                            Log Aktivitas User
                        @elseif(request()->routeIs('admin.activities.show'))
                            Detail Aktivitas
                        @elseif(request()->routeIs('pengaturan.index', 'profile.edit'))
                            Pengaturan
                        @else
                            Dashboard Admin
                        @endif
                    </h1>
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button type="button" @click="open = !open"
                        class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-full border border-gray-200 hover:bg-gray-50 transition">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}"
                                 class="w-8 h-8 rounded-full object-cover" alt="">
                        @else
                            <div class="w-8 h-8 bg-gradient-to-br from-[#0693E3] to-blue-700 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="text-sm text-gray-700 hidden sm:block">{{ auth()->user()->name }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                    </button>

                    <div x-show="open" x-cloak @click.outside="open = false" x-transition
                         class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b bg-gray-50">
                            <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('pengaturan.index') }}"
                           class="flex items-center gap-2.5 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fa-solid fa-gear w-4 text-gray-400"></i> Pengaturan
                        </a>
                        <form method="POST" action="{{ route('profile.logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-2.5 w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 border-t">
                                <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="mt-[73px] p-6 lg:p-8 overflow-x-auto overflow-y-auto h-[calc(100vh-73px)]">

            @if (session('success'))
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 shadow-sm">
                    <i class="fa-solid fa-circle-xmark text-red-500"></i>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

@stack('modals')
</body>
</html>
