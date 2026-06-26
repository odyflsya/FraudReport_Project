@extends('layouts.admin')

@section('content')

<div class="mb-5">
    <a href="{{ route('admin.activities.index') }}"
       class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#0693E3] transition">
        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Log Aktivitas
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main Info --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold text-white bg-gradient-to-br from-[#0693E3] to-blue-600">
                {{ strtoupper(substr($activity->name ?? '?', 0, 1)) }}
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">{{ $activity->display_activity }}</h2>
                <p class="text-sm text-gray-500">{{ $activity->name }} &middot; {{ $activity->email }}</p>
            </div>
            <div class="ml-auto">
                @php
                    $roleClass = $activity->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700';
                @endphp
                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $roleClass }}">{{ ucfirst($activity->role ?? '-') }}</span>
            </div>
        </div>

        <div class="p-6 space-y-5">
            <div>
                <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-1">Deskripsi</p>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $activity->description }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-lg p-4">
                    <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-1">Modul</p>
                    <p class="text-sm font-medium text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-cube text-[#0693E3] text-xs"></i> {{ $activity->module }}
                    </p>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-1">Waktu</p>
                    <p class="text-sm font-medium text-gray-800">{{ $activity->created_at->format('d F Y, H:i:s') }}</p>
                    <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Technical Details --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-microchip text-gray-400 text-xs"></i> Detail Teknis
            </h3>
        </div>
        <div class="divide-y divide-gray-100">
            <div class="px-5 py-4">
                <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-1">IP Address</p>
                <p class="text-sm font-mono text-gray-700 bg-slate-50 px-3 py-2 rounded-lg">{{ $activity->ip_address ?? '-' }}</p>
            </div>
            <div class="px-5 py-4">
                <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-1">User Agent</p>
                <p class="text-xs text-gray-600 leading-relaxed break-all bg-slate-50 px-3 py-2 rounded-lg">{{ $activity->user_agent ?? '-' }}</p>
            </div>
            <div class="px-5 py-4">
                <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-1">User ID</p>
                <p class="text-sm text-gray-700">#{{ $activity->user_id ?? '-' }}</p>
            </div>
            <div class="px-5 py-4">
                <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-1">Log ID</p>
                <p class="text-sm text-gray-700">#{{ $activity->id }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
