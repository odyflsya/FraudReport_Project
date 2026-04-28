<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.35),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(99,102,241,0.25),_transparent_25%),linear-gradient(180deg,#0f172a_0%,#020617_100%)]"></div>
            <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-10">
                <div class="w-full max-w-3xl bg-white/95 backdrop-blur-xl shadow-2xl rounded-[32px] overflow-hidden border border-white/20">
                    <div class="grid grid-cols-1 lg:grid-cols-2">
                        <div class="hidden lg:flex flex-col justify-between bg-slate-900 p-10 text-white">
                            <div>
                                <div class="inline-flex items-center gap-2 rounded-full bg-sky-500/20 px-4 py-2 text-sm font-semibold uppercase tracking-[0.2em] text-sky-200">
                                    Fraud Report
                                </div>
                                <h1 class="mt-10 text-4xl font-bold tracking-tight">Welcome Back</h1>
                                <p class="mt-4 text-slate-300 leading-7">Login to access fraud case management, export reports, and monitor your investigation dashboard.</p>
                            </div>
                            <div class="space-y-3 text-slate-400 text-sm">
                                <p><span class="font-semibold text-slate-100">Secure.</span> Fast login experience.</p>
                                <p><span class="font-semibold text-slate-100">Modern.</span> Report and export UI.</p>
                            </div>
                        </div>
                        <div class="px-8 py-10 sm:px-10 bg-white">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
