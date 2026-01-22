<aside x-data="{ open: true }" :class="open ? 'w-64' : 'w-20'"
    class="bg-gradient-to-b from-blue-600 to-blue-800 text-white transition-all duration-300 flex-shrink-0 flex flex-col
           fixed lg:sticky lg:top-0 lg:h-screen inset-y-0 left-0 z-50 lg:z-auto
           transform lg:transform-none"
    :class="{ 'translate-x-0': $parent.sidebarOpen, '-translate-x-full': !$parent.sidebarOpen }" x-init="open = window.innerWidth >= 1024">

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
        <button @click="open = !open" class="p-2 rounded-lg hover:bg-green-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="px-3 py-6 space-y-2 flex-1 overflow-y-auto pr-3">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            <span x-show="open" class="font-medium">Dashboard</span>
        </a>

        <!-- Pasien -->
        @can('viewAny', App\Models\Patient::class)
            <a href="{{ route('patients.index') }}"
                class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('patients.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span x-show="open" class="font-medium">Data Pasien</span>
            </a>
        @endcan

        <!-- Kunjungan ANC -->
        @can('viewAny', App\Models\AncVisit::class)
            <a href="{{ route('anc-visits.index') }}"
                class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('anc-visits.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                <span x-show="open" class="font-medium">Kunjungan ANC</span>
            </a>
        @endcan

        <!-- Imunisasi Anak -->
        @can('viewAny', App\Models\Patient::class)
            <a href="{{ route('imunisasi.index') }}"
                class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('imunisasi.*') || request()->routeIs('children.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                <!-- Baby Icon -->
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 2c1.657 0 3 1.343 3 3 0 .513-.139.994-.379 1.407C17.36 7.17 20 9.97 20 14v3a1 1 0 01-1 1h-1v3a1 1 0 01-1 1H7a1 1 0 01-1-1v-3H5a1 1 0 01-1-1v-3c0-4.03 2.64-6.83 6.379-7.593A2.99 2.99 0 0012 5c0-1.657 1.343-3 3-3z">
                    </path>
                </svg>
                <span x-show="open" class="font-medium">Imunisasi Anak</span>
            </a>
        @endcan

        <!-- KB (Keluarga Berencana) -->
        @can('create', App\Models\KbVisit::class)
            <a href="{{ route('kb.index') }}"
                class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('kb.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <span x-show="open" class="font-medium">KB (Keluarga Berencana)</span>
            </a>
        @endcan

        <!-- Persalinan & Nifas (Dropdown) -->
        @can('create', App\Models\Pregnancy::class)
            <div x-data="{ postnatalOpen: false }">
                <button @click="postnatalOpen = !postnatalOpen"
                    class="w-full flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('pregnancies.delivery') || request()->routeIs('pregnancies.postnatal') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                    <span x-show="open" class="font-medium flex-1 text-left">Persalinan & Nifas</span>
                    <svg x-show="open" :class="{ 'rotate-180': postnatalOpen }" class="w-4 h-4 transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Items -->
                <div x-show="postnatalOpen && open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2" class="ml-9 mt-1 space-y-1">
                    <a href="{{ route('patients.index') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-green-600/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Catat Persalinan</span>
                    </a>
                    <a href="{{ route('patients.index') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-green-600/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span>Kunjungan Nifas</span>
                    </a>
                    <div class="border-t border-blue-700/30 my-1"></div>
                    <p class="px-3 py-1 text-xs text-blue-200 italic">Pilih dari halaman pasien</p>
                </div>
            </div>
        @endcan

        <!-- Divider -->
        <div x-show="open" class="border-t border-blue-700 my-4"></div>

        <!-- Export Menu (Dropdown) -->
        @can('export', App\Models\Patient::class)
            <div x-data="{ exportOpen: false }">
                <button @click="exportOpen = !exportOpen"
                    class="w-full flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('export.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span x-show="open" class="font-medium flex-1 text-left">Export Data</span>
                    <svg x-show="open" :class="{ 'rotate-180': exportOpen }" class="w-4 h-4 transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Items -->
                <div x-show="exportOpen && open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2" class="ml-9 mt-1 space-y-1">
                    <a href="{{ route('export.anc-register') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('export.anc-register') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Register ANC</span>
                    </a>
                    <a href="{{ route('export.patient-list') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('export.patient-list') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span>Data Pasien</span>
                    </a>

                    <a href="{{ route('export.immunization.page') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('export.immunization.page') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Imunisasi</span>
                    </a>

                    <a href="{{ route('export.kb') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('export.kb') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <span>Laporan KB</span>
                    </a>
                </div>
            </div>
        @endcan

        <!-- Laporan (Dropdown) -->
        @can('view-reports')
            <div x-data="{ reportOpen: false }">
                <button @click="reportOpen = !reportOpen"
                    class="w-full flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('reports.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span x-show="open" class="font-medium flex-1 text-left">Laporan</span>
                    <svg x-show="open" :class="{ 'rotate-180': reportOpen }" class="w-4 h-4 transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Items -->
                <div x-show="reportOpen && open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2" class="ml-9 mt-1 space-y-1">
                    <a href="{{ route('reports.monthly-summary') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('reports.monthly-summary') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <span>Ringkasan Bulanan</span>
                    </a>
                </div>
            </div>
        @endcan

        <!-- Admin (Dropdown) -->
        @can('manage-users')
            <div x-data="{ adminOpen: false }">
                <button @click="adminOpen = !adminOpen"
                    class="w-full flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                    <span x-show="open" class="font-medium flex-1 text-left">Admin</span>
                    <svg x-show="open" :class="{ 'rotate-180': adminOpen }" class="w-4 h-4 transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Items -->
                <div x-show="adminOpen && open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2" class="ml-9 mt-1 space-y-1">
                    <a href="{{ route('admin.users') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('admin.users') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span>Kelola User</span>
                    </a>

                    <a href="{{ route('admin.vaccines') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('admin.vaccines') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 0v4m0-4h4m-4 0H8" />
                        </svg>
                        <span>Master Vaksin</span>
                    </a>

                    <a href="{{ route('admin.icd10') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('admin.icd10') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                        <span>Master ICD-10</span>
                    </a>

                    <div class="border-t border-blue-700/30 my-2"></div>

                    <a href="{{ route('admin.roles') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('admin.roles') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Kelola Role & Permission</span>
                    </a>

                    <a href="{{ route('admin.user-roles') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('admin.user-roles') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Assign Role ke User</span>
                    </a>
                </div>
            </div>
        @endcan
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
