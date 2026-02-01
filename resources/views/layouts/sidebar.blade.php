<aside x-data="{ open: true }" :class="open ? 'w-64' : 'w-20'"
    class="bg-gradient-to-b from-blue-600 to-blue-800 text-white transition-all duration-300 flex-shrink-0 flex flex-col
           fixed lg:sticky lg:top-0 lg:h-screen inset-y-0 left-0 z-50 lg:z-auto
           transform lg:transform-none"
    :class="{ 'translate-x-0': $parent.sidebarOpen, '-translate-x-full': !$parent.sidebarOpen }" x-init="open = window.innerWidth >= 1024">

    <!-- Logo Section -->
    <div class="flex items-center justify-between px-4 py-6 border-b border-blue-700">
        <div x-show="open" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <x-heroicon-o-document-text class="w-6 h-6 text-blue-600" />
            </div>
            <div>
                <h1 class="font-bold text-lg">E-Kohort</h1>
                <p class="text-xs text-blue-200">Klinik Bidan</p>
            </div>
        </div>
        <button @click="open = !open" class="p-2 rounded-lg hover:bg-green-600 transition-colors">
            <x-heroicon-o-bars-3 class="w-5 h-5" />
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="px-3 py-6 space-y-2 flex-1 overflow-y-auto pr-3">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
            <x-heroicon-o-home class="w-6 h-6 flex-shrink-0" />
            <span x-show="open" class="font-medium">Dashboard</span>
        </a>

        <!-- Pendaftaran -->
        @can('viewAny', App\Models\Patient::class)
            <a href="{{ route('patients.create') }}"
                class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('patients.create') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                <x-heroicon-o-queue-list class="w-6 h-6 flex-shrink-0" />
                <span x-show="open" class="font-medium">Pendaftaran</span>
            </a>
        @endcan

        <!-- Pasien -->
        @can('viewAny', App\Models\Patient::class)
            <a href="{{ route('patients.index') }}"
                class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('patients.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                <x-heroicon-o-user-group class="w-6 h-6 flex-shrink-0" />
                <span x-show="open" class="font-medium">Data Pasien</span>
            </a>
        @endcan

        <!-- Kunjungan ANC -->
        @can('viewAny', App\Models\AncVisit::class)
            <a href="{{ route('anc-visits.index') }}"
                class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('anc-visits.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                <x-heroicon-o-clipboard-document-list class="w-6 h-6 flex-shrink-0" />
                <span x-show="open" class="font-medium">Kunjungan ANC</span>
            </a>
        @endcan

        <!-- Imunisasi Anak -->
        @can('viewAny', App\Models\Patient::class)
            <a href="{{ route('imunisasi.index') }}"
                class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('imunisasi.*') || request()->routeIs('children.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                <x-heroicon-o-heart class="w-6 h-6 flex-shrink-0" />
                <span x-show="open" class="font-medium">Imunisasi Anak</span>
            </a>
        @endcan

        <!-- KB (Keluarga Berencana) -->
        @can('create', App\Models\KbVisit::class)
            <a href="{{ route('kb.index') }}"
                class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('kb.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                <x-heroicon-o-check-circle class="w-6 h-6 flex-shrink-0" />
                <span x-show="open" class="font-medium">KB (Keluarga Berencana)</span>
            </a>
        @endcan

        <!-- Divider -->
        <div x-show="open" class="border-t border-blue-700 my-4"></div>

        <!-- Export Menu (Dropdown) -->
        @can('export', App\Models\Patient::class)
            <div x-data="{ exportOpen: false }">
                <button @click="exportOpen = !exportOpen"
                    class="w-full flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('export.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                    <x-heroicon-o-arrow-down-tray class="w-6 h-6 flex-shrink-0" />
                    <span x-show="open" class="font-medium flex-1 text-left">Export Data</span>
                    <x-heroicon-o-chevron-down x-show="open" :class="{ 'rotate-180': exportOpen }" class="w-4 h-4 transition-transform" />
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
                        <x-heroicon-o-document-text class="w-4 h-4" />
                        <span>Register ANC</span>
                    </a>
                    <a href="{{ route('export.patient-list') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('export.patient-list') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <x-heroicon-o-user-group class="w-4 h-4" />
                        <span>Data Pasien</span>
                    </a>

                    @if (Route::has('export.general-register'))
                        <a href="{{ route('export.general-register') }}"
                            class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('export.general-register') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                            <x-heroicon-o-clipboard-document-list class="w-4 h-4" />
                            <span>Register Poli Umum</span>
                        </a>
                    @endif

                    <a href="{{ route('export.immunization.page') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('export.immunization.page') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                        <span>Imunisasi</span>
                    </a>

                    <a href="{{ route('export.kb') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('export.kb') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <x-heroicon-o-check-circle class="w-4 h-4" />
                        <span>Laporan KB</span>
                    </a>

                    <a href="{{ route('export.delivery-register') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('export.delivery-register') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <x-heroicon-o-heart class="w-4 h-4" />
                        <span>Register Persalinan</span>
                    </a>
                </div>
            </div>
        @endcan

        <!-- Laporan (Dropdown) -->
        @can('view-reports')
            <div x-data="{ reportOpen: false }">
                <button @click="reportOpen = !reportOpen"
                    class="w-full flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('reports.*') ? 'bg-green-600 shadow-lg' : 'hover:bg-green-600/60' }}">
                    <x-heroicon-o-chart-bar class="w-6 h-6 flex-shrink-0" />
                    <span x-show="open" class="font-medium flex-1 text-left">Laporan</span>
                    <x-heroicon-o-chevron-down x-show="open" :class="{ 'rotate-180': reportOpen }" class="w-4 h-4 transition-transform" />
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
                        <x-heroicon-o-chart-bar class="w-4 h-4" />
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
                    <x-heroicon-o-cog-6-tooth class="w-6 h-6 flex-shrink-0" />
                    <span x-show="open" class="font-medium flex-1 text-left">Admin</span>
                    <x-heroicon-o-chevron-down x-show="open" :class="{ 'rotate-180': adminOpen }" class="w-4 h-4 transition-transform" />
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
                        <x-heroicon-o-user-group class="w-4 h-4" />
                        <span>Kelola User</span>
                    </a>

                    <a href="{{ route('admin.vaccines') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('admin.vaccines') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <x-heroicon-o-plus class="w-4 h-4" />
                        <span>Master Vaksin</span>
                    </a>

                    <a href="{{ route('admin.icd10') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('admin.icd10') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <x-heroicon-o-list-bullet class="w-4 h-4" />
                        <span>Master ICD-10</span>
                    </a>

                    <div class="border-t border-blue-700/30 my-2"></div>

                    <a href="{{ route('admin.roles') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('admin.roles') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <x-heroicon-o-lock-closed class="w-4 h-4" />
                        <span>Kelola Role & Permission</span>
                    </a>

                    <a href="{{ route('admin.user-roles') }}"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors text-sm {{ request()->routeIs('admin.user-roles') ? 'bg-green-600' : 'hover:bg-green-600/40' }}">
                        <x-heroicon-o-user class="w-4 h-4" />
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
