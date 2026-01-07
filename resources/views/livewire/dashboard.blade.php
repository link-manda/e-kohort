<div>
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Patients Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Pasien</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalPatients }}</p>
                    <p class="text-xs text-gray-500 mt-2">Terdaftar di sistem</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Pregnancies Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Kehamilan Aktif</p>
                    <p class="text-3xl font-bold text-green-600">{{ $activePregnancies }}</p>
                    <p class="text-xs text-gray-500 mt-2">Sedang dipantau</p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Visits Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Kunjungan Hari Ini</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $todayVisits }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ now()->isoFormat('D MMMM YYYY') }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- High-Risk Patients Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pasien Risiko Tinggi</p>
                    <p class="text-3xl font-bold text-red-600">{{ $highRiskPatients }}</p>
                    <p class="text-xs text-gray-500 mt-2">Perlu perhatian khusus</p>
                </div>
                <div class="bg-red-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- High-Risk Patient Alert List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">⚠️ Pasien Risiko Tinggi</h3>
                    <p class="text-sm text-gray-500 mt-1">Daftar pasien yang memerlukan perhatian khusus</p>
                </div>

                <div class="divide-y divide-gray-200">
                    @forelse($highRiskList as $item)
                        <a href="{{ route('patients.show', $item['patient_id']) }}"
                            class="block p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="font-semibold text-gray-900">{{ $item['patient_name'] }}</h4>
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $item['risk_category'] === 'Ekstrem' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $item['risk_category'] === 'Tinggi' ? 'bg-orange-100 text-orange-800' : '' }}
                                            {{ $item['risk_category'] === 'Rendah' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ $item['risk_category'] }}
                                        </span>
                                    </div>

                                    <p class="text-xs text-gray-500 mb-2">
                                        NIK: {{ $item['nik'] }} • UK: {{ $item['gestational_age'] }} minggu •
                                        Kunjungan terakhir:
                                        {{ \Carbon\Carbon::parse($item['visit_date'])->isoFormat('D MMM YYYY') }}
                                    </p>

                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($item['risks'] as $risk)
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded
                                                {{ $risk['color'] === 'red' ? 'bg-red-100 text-red-700' : '' }}
                                                {{ $risk['color'] === 'orange' ? 'bg-orange-100 text-orange-700' : '' }}
                                                {{ $risk['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                                {{ $risk['type'] }}: {{ $risk['value'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <svg class="w-5 h-5 text-gray-400 ml-4 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="w-16 h-16 text-green-400 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Tidak ada pasien risiko tinggi</p>
                            <p class="text-sm text-gray-400 mt-1">Semua pasien dalam kondisi baik</p>
                        </div>
                    @endforelse
                </div>

                @if (count($highRiskList) > 0)
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        <a href="{{ route('patients.index') }}"
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat semua pasien →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Visits Timeline -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">🕐 Kunjungan Terbaru</h3>
                    <p class="text-sm text-gray-500 mt-1">10 kunjungan terakhir</p>
                </div>

                <div class="p-4 space-y-4 max-h-[600px] overflow-y-auto">
                    @forelse($recentVisits as $visit)
                        <a href="{{ route('patients.show', $visit['patient_id']) }}"
                            class="block p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-1">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold
                                        {{ $visit['visit_code'] === 'K1' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $visit['visit_code'] === 'K2' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $visit['visit_code'] === 'K3' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ in_array($visit['visit_code'], ['K4', 'K5', 'K6']) ? 'bg-purple-100 text-purple-700' : '' }}">
                                        {{ $visit['visit_code'] }}
                                    </span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 text-sm truncate">{{ $visit['patient_name'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($visit['visit_date'])->isoFormat('D MMM YYYY') }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="text-xs text-gray-600">UK: {{ $visit['gestational_age'] }}
                                            mgg</span>
                                        <span class="text-gray-300">•</span>
                                        <span
                                            class="text-xs font-medium
                                            {{ $visit['map_score'] > 100 ? 'text-red-600' : '' }}
                                            {{ $visit['map_score'] > 90 && $visit['map_score'] <= 100 ? 'text-yellow-600' : '' }}
                                            {{ $visit['map_score'] <= 90 ? 'text-green-600' : '' }}">
                                            MAP: {{ number_format($visit['map_score'], 1) }}
                                        </span>
                                    </div>
                                </div>

                                <span
                                    class="flex-shrink-0 px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $visit['risk_category'] === 'Ekstrem' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $visit['risk_category'] === 'Tinggi' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $visit['risk_category'] === 'Rendah' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ $visit['risk_category'] }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="py-8 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="text-gray-500 text-sm">Belum ada kunjungan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('patients.index') }}"
            class="p-6 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-white hover:from-blue-600 hover:to-blue-700 transition-all shadow-lg hover:shadow-xl">
            <div class="flex items-center gap-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <div>
                    <p class="font-bold text-lg">Kelola Pasien</p>
                    <p class="text-sm text-blue-100">Lihat & kelola data pasien</p>
                </div>
            </div>
        </a>

        <a href="{{ route('patients.index') }}"
            class="p-6 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white hover:from-green-600 hover:to-green-700 transition-all shadow-lg hover:shadow-xl">
            <div class="flex items-center gap-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <div>
                    <p class="font-bold text-lg">Tambah Kunjungan</p>
                    <p class="text-sm text-green-100">Catat kunjungan ANC baru</p>
                </div>
            </div>
        </a>

        <a href="#"
            class="p-6 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl text-white hover:from-purple-600 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl">
            <div class="flex items-center gap-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <div>
                    <p class="font-bold text-lg">Export Data</p>
                    <p class="text-sm text-purple-100">Download laporan Excel</p>
                </div>
            </div>
        </a>
    </div>
</div>
