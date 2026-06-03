<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- LEFT -->
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-orange-500 rotate-45"></div>
                    <span class="font-semibold text-gray-800">Fraud Report</span>
                </a>

                <!-- Menu -->
                <a href="{{ route('dashboard') }}"
                   class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                    Dashboard
                </a>
                <a href="#"
                   class="text-sm font-medium text-gray-600 hover:text-blue-600">
                    Pencegahan
                </a>
                <a href="#"
                   class="text-sm font-medium text-gray-600 hover:text-blue-600">
                    Deteksi
                </a>

                <div x-data="{ investigasiOpen: false }" class="relative">
                    <button @click="investigasiOpen = !investigasiOpen"
                            class="text-sm font-medium text-gray-600 hover:text-blue-600 flex items-center gap-1">
                        Investigasi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="investigasiOpen"
                         @click.away="investigasiOpen = false"
                         x-transition
                         class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-50">
                        <a href="{{ route('kasus.index') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Manajemen Kasus
                        </a>
                        <a href="{{ route('kasus.create') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Input Kasus
                        </a>
                    </div>
                </div>

                <a href="#"
                   class="text-sm font-medium text-gray-600 hover:text-blue-600">
                    Pemantauan
                </a>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                <!-- DROPDOWN PROFILE -->
                <div x-data="{ dropdownOpen: false }" class="relative">

                    <!-- Trigger -->
                    <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center gap-2 focus:outline-none">

                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}"
                                 class="w-9 h-9 rounded-full object-cover border-2 border-gray-300">
                        @else
                            <div class="w-9 h-9 bg-blue-500 text-white flex items-center justify-center rounded-full text-sm font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif

                        <span class="text-sm text-gray-700 hidden sm:block">
                            {{ Auth::user()->name }}
                        </span>

                        <!-- Arrow -->
                        <svg class="w-4 h-4 text-gray-500"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="dropdownOpen"
                         @click.away="dropdownOpen = false"
                         x-transition
                         class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-lg border z-50">

                        <!-- Profile -->
                        <a href="{{ route('pengaturan.index') }}"
                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 rounded-t-xl">
                            ⚙️ Pengaturan
                        </a>

                        <!-- Divider -->
                        <div class="border-t"></div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-b-xl">
                                🚪 Logout
                            </button>
                        </form>
                    </div>

                </div>

                <!-- Hamburger (Mobile) -->
                <button @click="open = ! open"
                        class="sm:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }"
                              class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

            </div>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div x-show="open" class="sm:hidden px-4 pb-4">
        <a href="{{ route('dashboard') }}"
           class="block py-2 text-gray-700">
            Dashboard
        </a>
        <a href="#"
           class="block py-2 text-gray-700">
            Pencegahan
        </a>
        <a href="#"
           class="block py-2 text-gray-700">
            Deteksi
        </a>
        <div class="mt-2 border-t border-gray-200 pt-2">
            <span class="block py-2 text-gray-500 uppercase tracking-wide text-xs">Investigasi</span>
            <a href="{{ route('kasus.index') }}"
               class="block pl-3 py-2 text-gray-700 hover:bg-gray-50">
                Manajemen Kasus
            </a>
            <a href="{{ route('kasus.create') }}"
               class="block pl-3 py-2 text-gray-700 hover:bg-gray-50">
                Input Kasus
            </a>
        </div>
        <a href="#"
           class="block py-2 text-gray-700">
            Pemantauan
        </a>
    </div>

</nav>