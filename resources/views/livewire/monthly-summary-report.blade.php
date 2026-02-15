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
                        <a href="{{ route('reports.monthly-summary.export', ['year' => $year, 'month' => $month]) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-lg transition duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Export PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Loading Overlay -->
            <div wire:loading class="absolute inset-0 bg-white bg-opacity-75 z-50 flex items-center justify-center">
                <div class="text-center">
                    <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600 font-medium">Memuat data laporan...</p>
                </div>
            </div>

            <!-- Executive Summary Cards -->
            <div class="p-6 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📈 Ringkasan Eksekutif</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Total Patients -->
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pasien Baru</p>
                                <p class="text-3xl font-bold text-blue-600 mt-2">
                                    {{ ($data['patient_demographics']['new_patients_total'] ?? 0) + ($data['patient_demographics']['new_children'] ?? 0) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $data['patient_demographics']['new_children'] ?? 0 }} balita
                                </p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- ANC Visits -->
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Kunjungan ANC</p>
                                <p class="text-3xl font-bold text-purple-600 mt-2">{{ $data['total_visits'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $data['new_pregnancies'] ?? 0 }} kehamilan baru</p>
                            </div>
                            <div class="bg-purple-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Deliveries -->
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Persalinan</p>
                                <p class="text-3xl font-bold text-green-600 mt-2">{{ $data['delivery']['total'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $data['delivery']['live_births'] ?? 0 }} bayi lahir</p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Immunization -->
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-pink-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Imunisasi Balita</p>
                                <p class="text-3xl font-bold text-pink-600 mt-2">{{ $data['immunization']['total_actions'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $data['immunization']['total_visits'] ?? 0 }} kunjungan</p>
                            </div>
                            <div class="bg-pink-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- KB -->
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-indigo-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-600">KB</p>
                                <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $data['kb']['total_visits'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $data['kb']['new_acceptors'] ?? 0 }} akseptor baru</p>
                            </div>
                            <div class="bg-indigo-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Poli Umum -->
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Poli Umum</p>
                                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $data['general_visits']['total'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $data['general_visits']['child_patients'] ?? 0 }} anak</p>
                            </div>
                            <div class="bg-yellow-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"  />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Postnatal -->
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-teal-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Kunjungan Nifas</p>
                                <p class="text-3xl font-bold text-teal-600 mt-2">{{ $data['postnatal']['total_visits'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">KF1-KF3</p>
                            </div>
                            <div class="bg-teal-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- High Risk -->
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Risiko Tinggi</p>
                                <p class="text-3xl font-bold text-red-600 mt-2">{{ $data['high_risk_count'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $data['extreme_risk_count'] ?? 0 }} ekstrem</p>
                            </div>
                            <div class="bg-red-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 1: KESEHATAN IBU & ANAK --}}
                <div class="mb-8" x-data="{ open: true }">
                    <button @click="open = !open" class="w-full flex items-center justify-between text-xl font-bold text-gray-800 mb-4 p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                        <span class="flex items-center gap-2">
                            🤰 Kesehatan Ibu & Anak
                        </span>
                        <svg :class="{'rotate-180': !open}" class="h-6 w-6 text-gray-600 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" x-transition class="space-y-6">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        {{-- ANC --}}
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-md font-bold text-gray-700 mb-4">Kunjungan ANC per Kode</h4>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach (['K1', 'K2', 'K3', 'K4', 'K5', 'K6', 'K7', 'K8'] as $code)
                                    <div class="text-center p-3 bg-purple-50 rounded border border-purple-200">
                                        <p class="text-xs font-medium text-gray-600">{{ $code }}</p>
                                        <p class="text-xl font-bold text-purple-600 mt-1">
                                            {{ $data['visits_by_code'][$code] ?? 0 }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Persalinan --}}
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-md font-bold text-gray-700 mb-4">Persalinan</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between p-2 bg-green-50 rounded">
                                    <span class="text-sm text-gray-700">Normal</span>
                                    <span class="font-bold text-green-600">{{ $data['delivery']['normal'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between p-2 bg-blue-50 rounded">
                                    <span class="text-sm text-gray-700">SC</span>
                                    <span class="font-bold text-blue-600">{{ $data['delivery']['caesarean'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between p-2 bg-red-50 rounded">
                                    <span class="text-sm text-gray-700">Komplikasi</span>
                                    <span class="font-bold text-red-600">{{ $data['delivery']['with_complications'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm text-gray-700">Lahir Mati</span>
                                    <span class="font-bold text-gray-600">{{ $data['delivery']['stillbirths'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Postnatal --}}
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-md font-bold text-gray-700 mb-4">Kunjungan Nifas</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between p-2 bg-teal-50 rounded">
                                    <span class="text-sm text-gray-700">KF1 (6 jam - 3 hari)</span>
                                    <span class="font-bold text-teal-600">{{ $data['postnatal']['kf1'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between p-2 bg-teal-50 rounded">
                                    <span class="text-sm text-gray-700">KF2 (4-28 hari)</span>
                                    <span class="font-bold text-teal-600">{{ $data['postnatal']['kf2'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between p-2 bg-teal-50 rounded">
                                    <span class="text-sm text-gray-700">KF3 (29-42 hari)</span>
                                    <span class="font-bold text-teal-600">{{ $data['postnatal']['kf3'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between p-2 bg-red-50 rounded">
                                    <span class="text-sm text-gray-700">Komplikasi</span>
                                    <span class="font-bold text-red-600">{{ $data['postnatal']['with_complications'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Risk Factors & Triple Eliminasi --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-md font-bold text-gray-700 mb-4 flex items-center gap-2">
                                ⚠️ Faktor Risiko
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-red-50 rounded border-l-4 border-red-500">
                                    <span class="font-medium text-gray-700">MAP Ekstrem (>100)</span>
                                    <span class="text-xl font-bold text-red-600">{{ $data['extreme_risk_count'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-yellow-50 rounded border-l-4 border-yellow-500">
                                    <span class="font-medium text-gray-700">MAP Tinggi (>90)</span>
                                    <span class="text-xl font-bold text-yellow-600">{{ $data['high_risk_count'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-orange-50 rounded border-l-4 border-orange-500">
                                    <span class="font-medium text-gray-700">KEK (LILA <23.5)</span>
                                    <span class="text-xl font-bold text-orange-600">{{ $data['kek_count'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-pink-50 rounded border-l-4 border-pink-500">
                                    <span class="font-medium text-gray-700">Anemia (Hb <11)</span>
                                    <span class="text-xl font-bold text-pink-600">{{ $data['anemia_count'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-md font-bold text-gray-700 mb-4 flex items-center gap-2">
                                ✅ Triple Eliminasi Screening
                            </h4>
                            <div class="space-y-3">
                                <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-medium text-gray-700">HIV Screening</span>
                                        <span class="text-lg font-bold text-blue-600">{{ $data['triple_eliminasi']['hiv_tested'] ?? 0 }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        Reaktif: <span class="font-semibold text-red-600">{{ $data['triple_eliminasi']['hiv_reactive'] ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-medium text-gray-700">Syphilis Screening</span>
                                        <span class="text-lg font-bold text-blue-600">{{ $data['triple_eliminasi']['syphilis_tested'] ?? 0 }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        Reaktif: <span class="font-semibold text-red-600">{{ $data['triple_eliminasi']['syphilis_reactive'] ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-medium text-gray-700">HBsAg Screening</span>
                                        <span class="text-lg font-bold text-blue-600">{{ $data['triple_eliminasi']['hbsag_tested'] ?? 0 }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        Reaktif: <span class="font-semibold text-red-600">{{ $data['triple_eliminasi']['hbsag_reactive'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: KESEHATAN BALITA --}}
                <div class="mb-8" x-data="{ open: true }">
                    <button @click="open = !open" class="w-full flex items-center justify-between text-xl font-bold text-gray-800 mb-4 p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                        <span class="flex items-center gap-2">
                            👶 Kesehatan Balita
                        </span>
                        <svg :class="{'rotate-180': !open}" class="h-6 w-6 text-gray-600 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Immunization --}}
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-md font-bold text-gray-700 mb-4">💉 Imunisasi</h4>
                            <div class="mb-4 p-3 bg-pink-50 rounded">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-700">Total Tindakan Imunisasi</span>
                                    <span class="text-xl font-bold text-pink-600">{{ $data['immunization']['total_actions'] ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                @if (!empty($data['immunization']['by_vaccine']))
                                    @foreach ($data['immunization']['by_vaccine'] as $vaccine => $count)
                                        <div class="flex justify-between p-2 bg-gray-50 rounded">
                                            <span class="text-sm text-gray-700">{{ $vaccine }}</span>
                                            <span class="font-bold text-gray-800">{{ $count }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-sm text-gray-500 italic">Tidak ada data vaksinasi bulan ini</p>
                                @endif
                            </div>
                        </div>

                        {{-- Child Growth --}}
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-md font-bold text-gray-700 mb-4">📈 Pertumbuhan & Gizi</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between p-3 bg-blue-50 rounded">
                                    <span class="text-sm font-medium text-gray-700">Total Penimbangan</span>
                                    <span class="text-xl font-bold text-blue-600">{{ $data['child_growth']['total_measurements'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between p-3 bg-green-50 rounded">
                                    <span class="text-sm font-medium text-gray-700">Vitamin A</span>
                                    <span class="text-xl font-bold text-green-600">{{ $data['child_growth']['vitamin_a'] ?? 0 }}</span>
                                </div>
                                @if (isset($data['child_growth']['nutrition_summary']))
                                    <div class="p-3 bg-gray-50 rounded">
                                        <p class="text-xs font-medium text-gray-600 mb-2">Status Gizi:</p>
                                        <div class="grid grid-cols-3 gap-2 text-center">
                                            <div>
                                                <p class="text-lg font-bold text-green-600">{{ $data['child_growth']['nutrition_summary']->normal ?? 0 }}</p>
                                                <p class="text-xs text-gray-600">Normal</p>
                                            </div>
                                            <div>
                                                <p class="text-lg font-bold text-orange-600">{{ $data['child_growth']['nutrition_summary']->wasting ?? 0 }}</p>
                                                <p class="text-xs text-gray-600">Kurus</p>
                                            </div>
                                            <div>
                                                <p class="text-lg font-bold text-red-600">{{ $data['child_growth']['nutrition_summary']->stunting ?? 0 }}</p>
                                                <p class="text-xs text-gray-600">Stunting</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: KB & POLI UMUM --}}
                <div class="mb-8" x-data="{ open: true }">
                    <button @click="open = !open" class="w-full flex items-center justify-between text-xl font-bold text-gray-800 mb-4 p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                        <span class="flex items-center gap-2">
                            🏥 KB & Poli Umum
                        </span>
                        <svg :class="{'rotate-180': !open}" class="h-6 w-6 text-gray-600 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- KB --}}
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-md font-bold text-gray-700 mb-4">👨‍👩‍👧 Keluarga Berencana</h4>
                            <div class="mb-4 p-3 bg-indigo-50 rounded">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-700">Akseptor Baru</span>
                                    <span class="text-xl font-bold text-indigo-600">{{ $data['kb']['new_acceptors'] ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <p class="text-xs font-medium text-gray-600 mb-2">Metode KB:</p>
                                @if (!empty($data['kb']['by_method']))
                                    @foreach ($data['kb']['by_method'] as $method => $count)
                                        <div class="flex justify-between p-2 bg-gray-50 rounded">
                                            <span class="text-sm text-gray-700">{{ $method }}</span>
                                            <span class="font-bold text-gray-800">{{ $count }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-sm text-gray-500 italic">Tidak ada data KB bulan ini</p>
                                @endif
                            </div>
                        </div>

                        {{-- General Visits --}}
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-md font-bold text-gray-700 mb-4">🏥 Poli Umum</h4>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="p-3 bg-yellow-50 rounded">
                                    <p class="text-sm text-gray-600">Pasien Dewasa</p>
                                    <p class="text-2xl font-bold text-yellow-600">{{ $data['general_visits']['adult_patients'] ?? 0 }}</p>
                                </div>
                                <div class="p-3 bg-orange-50 rounded">
                                    <p class="text-sm text-gray-600">Pasien Anak</p>
                                    <p class="text-2xl font-bold text-orange-600">{{ $data['general_visits']['child_patients'] ?? 0 }}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <p class="text-xs font-medium text-gray-600 mb-2">Top 5 Diagnosa:</p>
                                @if (!empty($data['general_visits']['top_diagnoses']))
                                    @foreach (array_slice($data['general_visits']['top_diagnoses'], 0, 5) as $diagnosis)
                                        <div class="p-2 bg-gray-50 rounded">
                                            <div class="flex justify-between items-start">
                                                <span class="text-xs text-gray-700 flex-1">
                                                    <span class="font-semibold">{{ $diagnosis['code'] }}</span> - {{ $diagnosis['description'] }}
                                                </span>
                                                <span class="text-sm font-bold text-gray-800 ml-2">{{ $diagnosis['count'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-sm text-gray-500 italic">Tidak ada data diagnosa</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                </div>

                {{-- SECTION 4: LAYANAN LAIN --}}
                <div class="mb-8" x-data="{ open: true }">
                    <button @click="open = !open" class="w-full flex items-center justify-between text-xl font-bold text-gray-800 mb-4 p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                        <span class="flex items-center gap-2">
                            📋 Layanan & Intervensi Lainnya
                        </span>
                        <svg :class="{'rotate-180': !open}" class="h-6 w-6 text-gray-600 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-md font-bold text-gray-700 mb-4">Imunisasi TT (Ibu Hamil)</h4>
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

                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-md font-bold text-gray-700 mb-4">Layanan Tambahan</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-indigo-50 rounded border-l-4 border-indigo-500">
                                    <span class="font-medium text-gray-700">ANC 12T Lengkap</span>
                                    <span class="text-xl font-bold text-indigo-600">{{ $data['anc_12t_complete'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-blue-50 rounded border-l-4 border-blue-500">
                                    <span class="font-medium text-gray-700">USG Screening</span>
                                    <span class="text-xl font-bold text-blue-600">{{ $data['usg_count'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-green-50 rounded border-l-4 border-green-500">
                                    <span class="font-medium text-gray-700">Konseling</span>
                                    <span class="text-xl font-bold text-green-600">{{ $data['counseling_count'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-yellow-50 rounded border-l-4 border-yellow-500">
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
</div>
