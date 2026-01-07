<aside x-data="{ open: true }" :class="open ? 'w-64' : 'w-20'"
    class="bg-gradient-to-b from-blue-600 to-blue-800 text-white transition-all duration-300 flex-shrink-0">

    <!-- Logo Section -->
    <div class="flex items-center justify-between px-4 py-6 border-b border-blue-700">
        <div x-show="open" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <div>
                <h1 class="font-bold text-lg">E-Kohort</h1>
                <p class="text-xs text-blue-200">Klinik Bidan</p>
            </div>
        </div>
        <button @click="open = !open" class="p-2 rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="px-3 py-6 space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-700/50' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            <span x-show="open" class="font-medium">Dashboard</span>
        </a>

        <!-- Pasien -->
        <a href="{{ route('patients.index') }}"
            class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('patients.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-700/50' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            <span x-show="open" class="font-medium">Data Pasien</span>
        </a>

        <!-- Kunjungan ANC -->
        <a href="{{ route('anc-visits.index') }}"
            class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('anc-visits.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-700/50' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                </path>
            </svg>
            <span x-show="open" class="font-medium">Kunjungan ANC</span>
        </a>

        <!-- Divider -->
        <div x-show="open" class="border-t border-blue-700 my-4"></div>

        <!-- Laporan -->
        <a href="{{ route('reports.index') }}"
            class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('reports.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-700/50' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <span x-show="open" class="font-medium">Laporan</span>
        </a>

        <!-- Alert Pasien -->
        <a href="{{ route('alerts.index') }}"
            class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('alerts.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-700/50' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>
            <span x-show="open" class="font-medium">Alert Risiko</span>
            <span x-show="open" class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">3</span>
        </a>
    </nav>

    {{-- Bottom Section - DISABLED FOR TESTING
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-700">
        <div x-show="open" class="bg-blue-700/50 rounded-lg p-3">
            <p class="text-xs text-blue-100 mb-2">Sistem Kohort Ibu</p>
            <p class="text-sm font-semibold">v1.0.0</p>
        </div>
    </div>
    --}}
</aside>
