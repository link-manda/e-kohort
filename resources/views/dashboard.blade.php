<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-sm text-gray-600 mt-1">Selamat datang kembali, {{ Auth::user()->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-600">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                <a href="{{ route('patients.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Pasien Baru</span>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Pasien -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pasien</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPatients }}</p>
                    <p class="text-xs text-gray-500 mt-1">Terdaftar dalam sistem</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kehamilan Aktif -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Kehamilan Aktif</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $activePregnancies }}</p>
                    <p class="text-xs text-gray-500 mt-1">Sedang dalam pemantauan</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kunjungan Bulan Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Kunjungan Bulan Ini</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $visitsThisMonth }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ now()->locale('id')->format('F Y') }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Alert Risiko Tinggi -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Alert Risiko Tinggi</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $mapAlertCount + $tripleEliminationCount }}</p>
                    <p class="text-xs text-gray-500 mt-1">Perlu perhatian khusus</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Visits -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">Kunjungan Terbaru</h2>
                <a href="{{ route('anc-visits.index') }}"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua →</a>
            </div>

            @if ($recentVisits->count() > 0)
                <div class="space-y-3">
                    @foreach ($recentVisits as $visit)
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($visit->pregnancy->patient->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $visit->pregnancy->patient->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $visit->visit_code }} - UK
                                        {{ $visit->gestational_age }} minggu</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">
                                    {{ $visit->visit_date->locale('id')->diffForHumans() }}</p>
                                @if ($visit->risk_category === 'Tinggi' || $visit->risk_category === 'Ekstrem')
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full mt-1">
                                        {{ $visit->risk_category }}
                                    </span>
                                @else
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full mt-1">
                                        Normal
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <p class="text-gray-500">Belum ada kunjungan dalam 7 hari terakhir</p>
                </div>
            @endif
        </div>

        <!-- High Risk Patients -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">Pasien Risiko Tinggi</h2>
                <span
                    class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">{{ $highRiskPatients->count() }}</span>
            </div>

            @if ($highRiskPatients->count() > 0)
                <div class="space-y-3">
                    @foreach ($highRiskPatients as $visit)
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="font-semibold text-gray-800">{{ $visit->pregnancy->patient->name }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-600">MAP: {{ $visit->map_score ?? '-' }}</span>
                                <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-200 rounded">
                                    {{ $visit->risk_category }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $visit->visit_date->locale('id')->format('d M Y') }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">Tidak ada pasien risiko tinggi</p>
                </div>
            @endif
        </div>
    </div>

</x-dashboard-layout>
