@extends('layouts.admin')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
            <i class="fa-solid fa-users text-[#0693E3] text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500">Total User</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 cursor-pointer hover:border-yellow-300 transition"
         onclick="window.location='{{ route('admin.users.index', ['status' => 'pending']) }}'">
        <div class="w-11 h-11 rounded-xl bg-yellow-50 flex items-center justify-center">
            <i class="fa-solid fa-hourglass-half text-yellow-500 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-500">Menunggu Approval</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center">
            <i class="fa-solid fa-circle-check text-green-500 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['active'] }}</p>
            <p class="text-xs text-gray-500">Aktif</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center">
            <i class="fa-solid fa-ban text-red-400 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['inactive'] }}</p>
            <p class="text-xs text-gray-500">Nonaktif / Ditolak</p>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6">
    <div class="flex items-center gap-2 mb-4">
        <i class="fa-solid fa-filter text-gray-400 text-sm"></i>
        <h2 class="text-sm font-semibold text-gray-700">Filter & Pencarian</h2>
    </div>
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
        <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Cari User</label>
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email..."
                       class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#0693E3]/30 focus:border-[#0693E3] outline-none">
            </div>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#0693E3]/30 focus:border-[#0693E3] outline-none bg-white">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Role</label>
            <select name="role" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#0693E3]/30 focus:border-[#0693E3] outline-none bg-white">
                <option value="">Semua Role</option>
                <option value="user" {{ request('role')=='user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div class="sm:col-span-2 lg:col-span-4 flex gap-2 pt-1">
            <button type="submit" class="inline-flex items-center gap-2 bg-[#0693E3] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-sky-600 transition">
                <i class="fa-solid fa-magnifying-glass text-xs"></i> Terapkan Filter
            </button>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                <i class="fa-solid fa-rotate-left text-xs"></i> Reset
            </a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-700">Daftar User</h2>
        <span class="text-xs text-gray-400">{{ $users->total() }} user ditemukan</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-200">
                <tr class="bg-gray-50 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-gray-600">User</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-600">Role</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-600">Status</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-600">Terdaftar</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/70 transition">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center text-xs font-bold text-white
                                {{ $user->role === 'admin' ? 'bg-gradient-to-br from-purple-500 to-purple-700' : 'bg-gradient-to-br from-[#0693E3] to-blue-600' }}">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-800 truncate">{{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="text-[10px] text-gray-400 font-normal">(Anda)</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                <i class="fa-solid fa-shield-halved text-[10px]"></i> Admin
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-user text-[10px]"></i> User
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        @php
                            $statusConfig = [
                                'active'   => ['bg-green-100 text-green-700', 'fa-circle-check', 'Aktif'],
                                'pending'  => ['bg-yellow-100 text-yellow-700', 'fa-clock', 'Pending'],
                                'inactive' => ['bg-gray-100 text-gray-600', 'fa-pause', 'Nonaktif'],
                                'rejected' => ['bg-red-100 text-red-700', 'fa-xmark', 'Ditolak'],
                            ];
                            [$sc, $si, $sl] = $statusConfig[$user->status] ?? ['bg-gray-100 text-gray-600', 'fa-question', $user->status];
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $sc }}">
                            <i class="fa-solid {{ $si }} text-[10px]"></i> {{ $sl }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-500 text-xs whitespace-nowrap">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="px-5 py-3.5 text-right whitespace-nowrap relative">
                        @if($user->id === auth()->id())
                            <span class="text-xs text-gray-400 italic">Akun Anda</span>
                        @else
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button type="button" @click="open = !open"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-[#0693E3] bg-blue-50 border border-blue-200 rounded-md hover:bg-[#0693E3] hover:text-white hover:border-[#0693E3] transition">
                                    Kelola
                                    <i class="fa-solid fa-chevron-down text-[9px]"></i>
                                </button>

                                <div x-show="open" x-cloak @click.outside="open = false" x-transition
                                     class="absolute right-0 mt-1.5 w-56 bg-white rounded-xl shadow-lg border border-gray-100 z-[100] overflow-hidden text-left">
                                    @if($user->status === 'pending')
                                        <div class="px-3 py-2 border-b border-gray-100">
                                            <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">Status</p>
                                        </div>
                                        <form action="{{ route('admin.users.approve', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" @click="open = false"
                                                class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-green-700 hover:bg-green-50 transition">
                                                <i class="fa-solid fa-check w-4 text-center"></i>
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.reject', $user->id) }}" method="POST"
                                              onsubmit="return confirm('Tolak pendaftaran {{ $user->name }}?')">
                                            @csrf
                                            <button type="submit" @click="open = false"
                                                class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                                <i class="fa-solid fa-xmark w-4 text-center"></i>
                                                Tolak
                                            </button>
                                        </form>
                                    @elseif(in_array($user->status, ['inactive', 'rejected']))
                                        <div class="px-3 py-2 border-b border-gray-100">
                                            <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">Status</p>
                                        </div>
                                        <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" @click="open = false"
                                                class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-green-700 hover:bg-green-50 transition">
                                                <i class="fa-solid fa-power-off w-4 text-center"></i>
                                                Aktifkan
                                            </button>
                                        </form>
                                    @elseif($user->status === 'active')
                                        <div class="px-3 py-2 border-b border-gray-100">
                                            <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">Status</p>
                                        </div>
                                        <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST"
                                              onsubmit="return confirm('Nonaktifkan akun {{ $user->name }}? User tidak bisa login hingga diaktifkan kembali.')">
                                            @csrf
                                            <button type="submit" @click="open = false"
                                                class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                                <i class="fa-solid fa-ban w-4 text-center text-gray-400"></i>
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    @endif

                                    <div class="px-3 py-2 border-t border-gray-100">
                                        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">Role</p>
                                    </div>
                                    <div class="px-4 py-2 text-xs text-gray-500">
                                        Saat ini:
                                        <span class="font-medium text-gray-700">{{ $user->role === 'admin' ? 'Admin' : 'User' }}</span>
                                    </div>
                                    @if($user->role !== 'user')
                                        <form action="{{ route('admin.users.changeRole', $user->id) }}" method="POST"
                                              onsubmit="return confirm('Ubah role {{ $user->name }} menjadi User?')">
                                            @csrf
                                            <input type="hidden" name="role" value="user">
                                            <button type="submit" @click="open = false"
                                                class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                                <i class="fa-solid fa-user w-4 text-center text-blue-500"></i>
                                                Ubah ke User
                                            </button>
                                        </form>
                                    @endif
                                    @if($user->role !== 'admin')
                                        <form action="{{ route('admin.users.changeRole', $user->id) }}" method="POST"
                                              onsubmit="return confirm('Ubah role {{ $user->name }} menjadi Admin?')">
                                            @csrf
                                            <input type="hidden" name="role" value="admin">
                                            <button type="submit" @click="open = false"
                                                class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                                <i class="fa-solid fa-shield-halved w-4 text-center text-purple-500"></i>
                                                Ubah ke Admin
                                            </button>
                                        </form>
                                    @endif

                                    <div class="border-t border-gray-100">
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus permanen akun {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" @click="open = false"
                                                class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                                <i class="fa-solid fa-trash w-4 text-center"></i>
                                                Hapus User
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-16 text-center">
                        <i class="fa-solid fa-users-slash text-3xl text-gray-200 mb-3 block"></i>
                        <p class="text-gray-500 text-sm">Tidak ada user ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
