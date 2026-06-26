@extends('layouts.admin')

@section('content')

{{-- Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
            <i class="fa-solid fa-list-check text-[#0693E3] text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalLogs) }}</p>
            <p class="text-xs text-gray-500">Total Log Tersimpan</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-orange-50 flex items-center justify-center">
            <i class="fa-solid fa-filter text-orange-400 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $activities->total() }}</p>
            <p class="text-xs text-gray-500">Hasil Filter Saat Ini</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center">
            <i class="fa-solid fa-users text-green-500 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $users->count() }}</p>
            <p class="text-xs text-gray-500">User Terdaftar</p>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6">
    <div class="flex items-center gap-2 mb-4">
        <i class="fa-solid fa-filter text-gray-400 text-sm"></i>
        <h2 class="text-sm font-semibold text-gray-700">Filter & Pencarian</h2>
    </div>
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
        <div class="sm:col-span-2 lg:col-span-1">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Cari Nama / Email</label>
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email..."
                       class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#0693E3]/30 focus:border-[#0693E3] outline-none">
            </div>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">User</label>
            <select name="user_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#0693E3]/30 focus:border-[#0693E3] outline-none bg-white">
                <option value="">Semua User</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Aktivitas</label>
            <select name="activity" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#0693E3]/30 focus:border-[#0693E3] outline-none bg-white">
                <option value="">Semua Aktivitas</option>
                @foreach($activityTypes as $type)
                    <option value="{{ $type }}" {{ \App\Models\UserActivity::normalizeActivity(request('activity')) === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Modul</label>
            <select name="module" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#0693E3]/30 focus:border-[#0693E3] outline-none bg-white">
                <option value="">Semua Modul</option>
                @foreach($modules as $mod)
                    <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Dari Tanggal</label>
            <input type="date" name="from" value="{{ request('from') }}"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#0693E3]/30 focus:border-[#0693E3] outline-none">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Sampai Tanggal</label>
            <input type="date" name="to" value="{{ request('to') }}"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#0693E3]/30 focus:border-[#0693E3] outline-none">
        </div>
        <div class="sm:col-span-2 lg:col-span-3 flex gap-2 pt-1">
            <button type="submit" class="inline-flex items-center gap-2 bg-[#0693E3] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-sky-600 transition">
                <i class="fa-solid fa-magnifying-glass text-xs"></i> Terapkan Filter
            </button>
            <a href="{{ route('admin.activities.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                <i class="fa-solid fa-rotate-left text-xs"></i> Reset
            </a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-700">Riwayat Aktivitas</h2>
        <span class="text-xs text-gray-400">Halaman {{ $activities->currentPage() }} dari {{ $activities->lastPage() }}</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-200">
                <tr class="bg-gray-50 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-gray-600">Waktu</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-600">User</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-600">Aktivitas</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-600">Modul</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-600">Deskripsi</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-600">IP</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-600 text-right">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($activities as $a)
                <tr class="hover:bg-slate-50/70 transition">
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <p class="text-gray-800 font-medium text-xs">{{ $a->created_at->format('d M Y') }}</p>
                        <p class="text-gray-400 text-[11px]">{{ $a->created_at->format('H:i:s') }}</p>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-bold text-white bg-gradient-to-br from-[#0693E3] to-blue-600">
                                {{ strtoupper(substr($a->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-gray-800 text-xs font-medium truncate max-w-[140px]">{{ $a->name }}</p>
                                <p class="text-[11px] text-gray-400 truncate max-w-[140px]">{{ $a->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        @php
                            $actColors = [
                                'Login' => 'bg-green-100 text-green-700',
                                'Logout' => 'bg-gray-100 text-gray-600',
                                'Failed Login' => 'bg-red-100 text-red-700',
                                'Blocked Login' => 'bg-orange-100 text-orange-700',
                            ];
                            $ac = $actColors[$a->display_activity] ?? 'bg-blue-100 text-blue-700';
                        @endphp
                        <span class="inline-block px-2 py-0.5 rounded-full text-[11px] font-medium {{ $ac }} whitespace-nowrap">
                            {{ $a->display_activity }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-600 whitespace-nowrap">
                            {{ $a->module }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 max-w-[200px]">
                        <p class="text-xs text-gray-600 truncate" title="{{ $a->description }}">{{ $a->description }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-xs text-gray-400 font-mono whitespace-nowrap">{{ $a->ip_address }}</td>
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.activities.show', $a->id) }}"
                           class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-gray-200 text-gray-400 hover:text-[#0693E3] hover:border-[#0693E3] transition"
                           title="Lihat detail">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-16 text-center">
                        <i class="fa-solid fa-clock-rotate-left text-3xl text-gray-200 mb-3 block"></i>
                        <p class="text-gray-500 text-sm">Tidak ada aktivitas ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($activities->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $activities->links() }}
        </div>
    @endif
</div>
@endsection
