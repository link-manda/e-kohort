<x-dashboard-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header with Actions -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('patients.show', $visit->pregnancy->patient->id) }}"
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-2xl font-bold text-gray-900">Detail Kunjungan ANC</h1>
                        <span
                            class="inline-flex items-center px-4 py-1.5 text-lg font-bold rounded-full
                            {{ in_array($visit->visit_code, ['K1', 'K2']) ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ in_array($visit->visit_code, ['K3', 'K4']) ? 'bg-green-100 text-green-800' : '' }}
                            {{ in_array($visit->visit_code, ['K5', 'K6']) ? 'bg-purple-100 text-purple-800' : '' }}">
                            {{ $visit->visit_code }}
                        </span>
                        <span
                            class="inline-flex items-center px-4 py-1.5 text-sm font-bold rounded-full
                            {{ $visit->risk_category === 'Ekstrem' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $visit->risk_category === 'Tinggi' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $visit->risk_category === 'Rendah' ? 'bg-green-100 text-green-800' : '' }}">
                            Risiko {{ $visit->risk_category }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold">{{ $visit->pregnancy->patient->name }}</span> •
                        {{ $visit->visit_date->locale('id')->isoFormat('dddd, D MMMM YYYY') }} •
                        UK {{ $visit->gestational_age }} minggu
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('anc-visits.history', $visit->pregnancy_id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors no-print">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Riwayat Kunjungan
                </a>
                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors no-print">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak
                </button>
                <a href="{{ route('anc-visits.edit', ['pregnancy' => $visit->pregnancy_id, 'visit' => $visit->id]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors no-print">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        <!-- Patient & Pregnancy Info (Print Header) -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 border-2 border-blue-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Informasi Pasien</h3>
                    <p class="font-bold text-gray-900 text-lg">{{ $visit->pregnancy->patient->name }}</p>
                    <p class="text-sm text-gray-700">NIK: {{ $visit->pregnancy->patient->nik }}</p>
                    <p class="text-sm text-gray-700">Usia: {{ $visit->pregnancy->patient->age }} tahun</p>
                    <p class="text-sm text-gray-700">Gol. Darah: {{ $visit->pregnancy->patient->blood_type ?? '-' }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Informasi Kehamilan</h3>
                    <p class="text-sm text-gray-700">Gravida: <span
                            class="font-bold">{{ $visit->pregnancy->gravida }}</span></p>
                    <p class="text-sm text-gray-700">HPHT:
                        {{ $visit->pregnancy->hpht->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                    <p class="text-sm text-gray-700">HPL:
                        {{ $visit->pregnancy->hpl->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                    <p class="text-sm text-gray-700">UK Saat Ini: {{ $visit->pregnancy->gestational_age }} minggu</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Informasi Kunjungan</h3>
                    <p class="text-sm text-gray-700">Tanggal: <span
                            class="font-bold">{{ $visit->visit_date->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                    </p>
                    <p class="text-sm text-gray-700">Waktu: {{ $visit->created_at->locale('id')->isoFormat('HH:mm') }}
                        WITA</p>
                    <p class="text-sm text-gray-700">UK Kunjungan: {{ $visit->gestational_age }} minggu</p>
                    @if ($visit->updated_at != $visit->created_at)
                        <p class="text-xs text-gray-500 mt-2">Terakhir diubah:
                            {{ $visit->updated_at->diffForHumans() }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Anamnesis -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Anamnesis & Keluhan
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">
                            {{ $visit->anamnesis ?? 'Tidak ada keluhan' }}</p>
                    </div>
                </div>

                <!-- Physical Examination -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Pemeriksaan Fisik
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-blue-700 uppercase mb-1">Berat Badan</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $visit->weight }} <span
                                    class="text-sm">kg</span></p>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-red-700 uppercase mb-1">Tekanan Darah</p>
                            <p class="text-2xl font-bold text-red-900">{{ $visit->systolic }}/{{ $visit->diastolic }}
                                <span class="text-sm">mmHg</span>
                            </p>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-orange-700 uppercase mb-1">Suhu</p>
                            <p class="text-2xl font-bold text-orange-900">{{ $visit->temperature }} <span
                                    class="text-sm">°C</span></p>
                        </div>
                        @if ($visit->lila)
                            <div class="bg-purple-50 rounded-lg p-4">
                                <p class="text-xs font-semibold text-purple-700 uppercase mb-1">LILA</p>
                                <p class="text-2xl font-bold text-purple-900">{{ number_format($visit->lila, 1) }}
                                    <span class="text-sm">cm</span>
                                </p>
                                @if ($visit->lila < 23.5)
                                    <p class="text-xs text-red-600 font-semibold mt-1">⚠ Kurang dari 23.5 cm (KEK)</p>
                                @else
                                    <p class="text-xs text-green-600 font-semibold mt-1">✓ Normal (≥23.5 cm)</p>
                                @endif
                            </div>
                        @endif
                        @if ($visit->fundal_height)
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-xs font-semibold text-green-700 uppercase mb-1">Tinggi Fundus Uteri
                                    (TFU)
                                </p>
                                <p class="text-2xl font-bold text-green-900">{{ $visit->fundal_height }} <span
                                        class="text-sm">cm</span></p>
                            </div>
                        @endif
                        @if ($visit->fetal_heart_rate)
                            <div class="bg-pink-50 rounded-lg p-4">
                                <p class="text-xs font-semibold text-pink-700 uppercase mb-1">Denyut Jantung Janin
                                    (DJJ)
                                </p>
                                <p class="text-2xl font-bold text-pink-900">{{ $visit->fetal_heart_rate }} <span
                                        class="text-sm">bpm</span></p>
                                @if ($visit->fetal_heart_rate < 120 || $visit->fetal_heart_rate > 160)
                                    <p class="text-xs text-red-600 font-semibold mt-1">⚠ Di luar rentang normal</p>
                                @else
                                    <p class="text-xs text-green-600 font-semibold mt-1">✓ Normal (120-160 bpm)</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Laboratory Results -->
                @if ($visit->hb || $visit->protein_urine || $visit->glucose_urine)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z">
                                </path>
                            </svg>
                            Hasil Laboratorium
                        </h3>
                        <div class="space-y-3">
                            @if ($visit->hb)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Hemoglobin (Hb)</p>
                                        <p class="text-xs text-gray-500">Normal: ≥11 g/dL</p>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="text-xl font-bold {{ $visit->hb < 11 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($visit->hb, 1) }} g/dL
                                        </p>
                                        @if ($visit->hb < 11)
                                            <p class="text-xs text-red-600 font-semibold">⚠ Anemia</p>
                                        @else
                                            <p class="text-xs text-green-600 font-semibold">✓ Normal</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if ($visit->protein_urine)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Protein Urin</p>
                                        <p class="text-xs text-gray-500">Normal: Negatif</p>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="text-lg font-bold {{ $visit->protein_urine === 'Negatif' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $visit->protein_urine }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @if ($visit->glucose_urine)
                                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Glukosa Urin</p>
                                        <p class="text-xs text-gray-500">Normal: Negatif</p>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="text-lg font-bold {{ $visit->glucose_urine === 'Negatif' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $visit->glucose_urine }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Interventions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Intervensi & Tindakan
                    </h3>
                    <div class="space-y-2">
                        <div
                            class="flex items-center gap-3 p-3 rounded-lg {{ $visit->ttd_given ? 'bg-green-50' : 'bg-gray-50' }}">
                            @if ($visit->ttd_given)
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @endif
                            <p class="font-semibold {{ $visit->ttd_given ? 'text-green-900' : 'text-gray-500' }}">
                                Tablet Tambah Darah (TTD)</p>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 rounded-lg {{ $visit->fe_given ? 'bg-green-50' : 'bg-gray-50' }}">
                            @if ($visit->fe_given)
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @endif
                            <p class="font-semibold {{ $visit->fe_given ? 'text-green-900' : 'text-gray-500' }}">
                                Tablet Fe</p>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 rounded-lg {{ $visit->counseling_given ? 'bg-green-50' : 'bg-gray-50' }}">
                            @if ($visit->counseling_given)
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @endif
                            <p
                                class="font-semibold {{ $visit->counseling_given ? 'text-green-900' : 'text-gray-500' }}">
                                Konseling Gizi & Kesehatan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- MAP Score Calculation -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Perhitungan MAP Score
                    </h3>
                    <div class="bg-indigo-50 rounded-lg p-4 mb-4">
                        <p class="text-sm text-gray-700 mb-3">
                            <span class="font-semibold">Mean Arterial Pressure (MAP)</span> menunjukkan tekanan
                            rata-rata dalam arteri selama satu siklus jantung.
                        </p>
                        <div class="bg-white rounded-lg p-4 border-2 border-indigo-200">
                            <p class="text-xs text-gray-600 mb-2 font-mono">MAP = Diastolic + (Systolic - Diastolic) /
                                3</p>
                            <p class="text-xs text-gray-600 mb-3 font-mono">MAP = {{ $visit->diastolic }} +
                                ({{ $visit->systolic }} - {{ $visit->diastolic }}) / 3</p>
                            <div class="border-t-2 border-indigo-200 pt-3">
                                <p class="text-3xl font-bold text-indigo-900">
                                    {{ number_format($visit->map_score, 2) }}
                                    <span class="text-sm text-gray-600">mmHg</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p class="flex items-center gap-2">
                            <span
                                class="w-3 h-3 rounded-full {{ $visit->map_score < 70 ? 'bg-yellow-500' : 'bg-gray-300' }}"></span>
                            <span
                                class="{{ $visit->map_score < 70 ? 'font-bold text-yellow-700' : 'text-gray-600' }}">&lt;70:
                                Hipotensi</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span
                                class="w-3 h-3 rounded-full {{ $visit->map_score >= 70 && $visit->map_score <= 100 ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                            <span
                                class="{{ $visit->map_score >= 70 && $visit->map_score <= 100 ? 'font-bold text-green-700' : 'text-gray-600' }}">70-100:
                                Normal</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span
                                class="w-3 h-3 rounded-full {{ $visit->map_score > 100 && $visit->map_score <= 110 ? 'bg-orange-500' : 'bg-gray-300' }}"></span>
                            <span
                                class="{{ $visit->map_score > 100 && $visit->map_score <= 110 ? 'font-bold text-orange-700' : 'text-gray-600' }}">100-110:
                                Prehipertensi</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span
                                class="w-3 h-3 rounded-full {{ $visit->map_score > 110 ? 'bg-red-500' : 'bg-gray-300' }}"></span>
                            <span
                                class="{{ $visit->map_score > 110 ? 'font-bold text-red-700' : 'text-gray-600' }}">&gt;110:
                                Hipertensi</span>
                        </p>
                    </div>
                </div>

                <!-- Risk Detection Logic -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Logika Deteksi Risiko
                    </h3>
                    <div class="space-y-3">
                        <div
                            class="p-4 rounded-lg border-2 {{ $visit->risk_category === 'Ekstrem' ? 'bg-red-50 border-red-300' : ($visit->risk_category === 'Tinggi' ? 'bg-orange-50 border-orange-300' : 'bg-green-50 border-green-300') }}">
                            <p
                                class="font-bold text-lg mb-2 {{ $visit->risk_category === 'Ekstrem' ? 'text-red-900' : ($visit->risk_category === 'Tinggi' ? 'text-orange-900' : 'text-green-900') }}">
                                Kategori: {{ $visit->risk_category }}
                            </p>
                            <p
                                class="text-sm {{ $visit->risk_category === 'Ekstrem' ? 'text-red-700' : ($visit->risk_category === 'Tinggi' ? 'text-orange-700' : 'text-green-700') }}">
                                Berdasarkan hasil pemeriksaan pada kunjungan ini
                            </p>
                        </div>

                        <div class="space-y-2">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Faktor Risiko yang Terdeteksi:</p>

                            @php
                                $riskFactors = [];
                                if ($visit->map_score > 110) {
                                    $riskFactors[] = 'MAP Score > 110 (Hipertensi)';
                                }
                                if ($visit->hb && $visit->hb < 11) {
                                    $riskFactors[] = 'Hb < 11 g/dL (Anemia)';
                                }
                                if ($visit->lila && $visit->lila < 23.5) {
                                    $riskFactors[] = 'LILA < 23.5 cm (KEK)';
                                }
                                if ($visit->protein_urine && $visit->protein_urine !== 'Negatif') {
                                    $riskFactors[] = 'Protein Urin Positif';
                                }
                                if ($visit->glucose_urine && $visit->glucose_urine !== 'Negatif') {
                                    $riskFactors[] = 'Glukosa Urin Positif';
                                }
                                if ($visit->systolic >= 140 || $visit->diastolic >= 90) {
                                    $riskFactors[] = 'Tekanan Darah Tinggi';
                                }
                            @endphp

                            @if (count($riskFactors) > 0)
                                @foreach ($riskFactors as $factor)
                                    <div class="flex items-start gap-2 p-3 bg-red-50 rounded-lg border border-red-200">
                                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="text-sm text-red-800 font-medium">{{ $factor }}</p>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-start gap-2 p-3 bg-green-50 rounded-lg border border-green-200">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm text-green-800 font-medium">Tidak ada faktor risiko yang
                                        terdeteksi</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Triple Eliminasi Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Status Triple Eliminasi
                    </h3>
                    <div class="space-y-3">
                        <div
                            class="flex items-center justify-between p-4 rounded-lg {{ $visit->hiv_status === 'R' ? 'bg-red-50 border-2 border-red-300' : 'bg-green-50 border-2 border-green-300' }}">
                            <div>
                                <p class="font-bold text-gray-900">HIV</p>
                                <p class="text-xs text-gray-600">Human Immunodeficiency Virus</p>
                            </div>
                            <span
                                class="px-4 py-2 text-sm font-bold rounded-full {{ $visit->hiv_status === 'R' ? 'bg-red-600 text-white' : 'bg-green-600 text-white' }}">
                                {{ $visit->hiv_status === 'R' ? 'Reaktif' : 'Non-Reaktif' }}
                            </span>
                        </div>
                        <div
                            class="flex items-center justify-between p-4 rounded-lg {{ $visit->syphilis_status === 'R' ? 'bg-red-50 border-2 border-red-300' : 'bg-green-50 border-2 border-green-300' }}">
                            <div>
                                <p class="font-bold text-gray-900">Syphilis</p>
                                <p class="text-xs text-gray-600">Treponema pallidum</p>
                            </div>
                            <span
                                class="px-4 py-2 text-sm font-bold rounded-full {{ $visit->syphilis_status === 'R' ? 'bg-red-600 text-white' : 'bg-green-600 text-white' }}">
                                {{ $visit->syphilis_status === 'R' ? 'Reaktif' : 'Non-Reaktif' }}
                            </span>
                        </div>
                        <div
                            class="flex items-center justify-between p-4 rounded-lg {{ $visit->hbsag_status === 'R' ? 'bg-red-50 border-2 border-red-300' : 'bg-green-50 border-2 border-green-300' }}">
                            <div>
                                <p class="font-bold text-gray-900">Hepatitis B (HBsAg)</p>
                                <p class="text-xs text-gray-600">Hepatitis B Surface Antigen</p>
                            </div>
                            <span
                                class="px-4 py-2 text-sm font-bold rounded-full {{ $visit->hbsag_status === 'R' ? 'bg-red-600 text-white' : 'bg-green-600 text-white' }}">
                                {{ $visit->hbsag_status === 'R' ? 'Reaktif' : 'Non-Reaktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Clinical Notes -->
                @if ($visit->clinical_notes)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Catatan Klinis
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $visit->clinical_notes }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }

            .bg-gradient-to-r {
                background: #f3f4f6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .shadow-sm {
                box-shadow: none !important;
            }

            @page {
                margin: 1.5cm;
            }

            /* Preserve colors in print */
            .bg-red-50,
            .bg-red-100,
            .bg-orange-50,
            .bg-orange-100,
            .bg-green-50,
            .bg-green-100,
            .bg-blue-50,
            .bg-blue-100,
            .bg-purple-50,
            .bg-purple-100,
            .bg-yellow-50,
            .bg-pink-50,
            .bg-indigo-50,
            .bg-teal-50,
            .bg-gray-50 {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .text-red-600,
            .text-red-700,
            .text-red-800,
            .text-red-900,
            .text-orange-600,
            .text-orange-700,
            .text-orange-800,
            .text-orange-900,
            .text-green-600,
            .text-green-700,
            .text-green-800,
            .text-green-900,
            .text-blue-600,
            .text-blue-700,
            .text-blue-800,
            .text-blue-900 {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</x-dashboard-layout>
