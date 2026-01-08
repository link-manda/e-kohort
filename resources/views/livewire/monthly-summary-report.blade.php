<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-3xl font-bold">📊 Laporan Ringkasan Bulanan</h2>
                        <p class="mt-2 text-blue-100">Laporan komprehensif untuk Dinas Kesehatan</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-blue-100">Periode Laporan</p>
                        <p class="text-2xl font-bold">{{ $data['period']['month_name'] ?? '' }}
                            {{ $data['period']['year'] ?? '' }}</p>
                    </div>
                </div>
            </div>

            <!-- Date Selector -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex gap-4 items-end">
                    <div class="flex-1">
                        <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                        <select wire:model.live="month" id="month"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <select wire:model.live="year" id="year"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @for ($y = now()->year; $y >= 2020; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <button wire:click="exportPdf"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-lg transition duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Export PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics Cards -->
            <div class="p-6 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <!-- New Patients -->
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pasien Baru</p>
                                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $data['new_patients'] ?? 0 }}</p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- New Pregnancies -->
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Kehamilan Baru</p>
                                <p class="text-3xl font-bold text-green-600 mt-2">{{ $data['new_pregnancies'] ?? 0 }}
                                </p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Visits -->
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Kunjungan</p>
                                <p class="text-3xl font-bold text-purple-600 mt-2">{{ $data['total_visits'] ?? 0 }}</p>
                            </div>
                            <div class="bg-purple-100 rounded-full p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- High Risk -->
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Resiko Tinggi</p>
                                <p class="text-3xl font-bold text-red-600 mt-2">{{ $data['high_risk_count'] ?? 0 }}</p>
                            </div>
                            <div class="bg-red-100 rounded-full p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kunjungan ANC per Kode -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Kunjungan ANC per Kode
                    </h3>
                    <div class="grid grid-cols-4 md:grid-cols-8 gap-3">
                        @foreach (['K1', 'K2', 'K3', 'K4', 'K5', 'K6', 'K7', 'K8'] as $code)
                            <div
                                class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                                <p class="text-sm font-medium text-gray-600">{{ $code }}</p>
                                <p class="text-2xl font-bold text-blue-600 mt-1">
                                    {{ $data['visits_by_code'][$code] ?? 0 }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Health Indicators -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Risk Factors -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Faktor Resiko
                        </h3>
                        <div class="space-y-3">
                            <div
                                class="flex justify-between items-center p-3 bg-red-50 rounded border-l-4 border-red-500">
                                <span class="font-medium text-gray-700">MAP Ekstrem (>100)</span>
                                <span
                                    class="text-xl font-bold text-red-600">{{ $data['extreme_risk_count'] ?? 0 }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center p-3 bg-yellow-50 rounded border-l-4 border-yellow-500">
                                <span class="font-medium text-gray-700">MAP Tinggi (>90)</span>
                                <span
                                    class="text-xl font-bold text-yellow-600">{{ $data['high_risk_count'] ?? 0 }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center p-3 bg-orange-50 rounded border-l-4 border-orange-500">
                                <span class="font-medium text-gray-700">KEK (LILA <23.5)< /span>
                                        <span
                                            class="text-xl font-bold text-orange-600">{{ $data['kek_count'] ?? 0 }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center p-3 bg-pink-50 rounded border-l-4 border-pink-500">
                                <span class="font-medium text-gray-700">Anemia (Hb <11)< /span>
                                        <span
                                            class="text-xl font-bold text-pink-600">{{ $data['anemia_count'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Triple Eliminasi -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Triple Eliminasi Screening
                        </h3>
                        <div class="space-y-3">
                            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium text-gray-700">HIV Screening</span>
                                    <span
                                        class="text-lg font-bold text-blue-600">{{ $data['triple_eliminasi']['hiv_tested'] ?? 0 }}</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Reaktif: <span
                                        class="font-semibold text-red-600">{{ $data['triple_eliminasi']['hiv_reactive'] ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium text-gray-700">Syphilis Screening</span>
                                    <span
                                        class="text-lg font-bold text-blue-600">{{ $data['triple_eliminasi']['syphilis_tested'] ?? 0 }}</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Reaktif: <span
                                        class="font-semibold text-red-600">{{ $data['triple_eliminasi']['syphilis_reactive'] ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium text-gray-700">HBsAg Screening</span>
                                    <span
                                        class="text-lg font-bold text-blue-600">{{ $data['triple_eliminasi']['hbsag_tested'] ?? 0 }}</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Reaktif: <span
                                        class="font-semibold text-red-600">{{ $data['triple_eliminasi']['hbsag_reactive'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interventions & Services -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Immunization -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            Imunisasi TT
                        </h3>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach (['tt1', 'tt2', 'tt3', 'tt4', 'tt5'] as $tt)
                                <div class="text-center p-3 bg-purple-50 rounded border border-purple-200">
                                    <p class="text-xs font-medium text-gray-600">
                                        {{ strtoupper(str_replace('tt', 'TT', $tt)) }}</p>
                                    <p class="text-xl font-bold text-purple-600 mt-1">
                                        {{ $data['tt_immunization'][$tt] ?? 0 }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Other Services -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Layanan & Intervensi
                        </h3>
                        <div class="space-y-3">
                            <div
                                class="flex justify-between items-center p-3 bg-indigo-50 rounded border-l-4 border-indigo-500">
                                <span class="font-medium text-gray-700">ANC 12T Lengkap</span>
                                <span
                                    class="text-xl font-bold text-indigo-600">{{ $data['anc_12t_complete'] ?? 0 }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center p-3 bg-blue-50 rounded border-l-4 border-blue-500">
                                <span class="font-medium text-gray-700">USG Screening</span>
                                <span class="text-xl font-bold text-blue-600">{{ $data['usg_count'] ?? 0 }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center p-3 bg-green-50 rounded border-l-4 border-green-500">
                                <span class="font-medium text-gray-700">Konseling</span>
                                <span
                                    class="text-xl font-bold text-green-600">{{ $data['counseling_count'] ?? 0 }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center p-3 bg-yellow-50 rounded border-l-4 border-yellow-500">
                                <span class="font-medium text-gray-700">Rujukan</span>
                                <span class="text-xl font-bold text-yellow-600">{{ $data['referrals'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
