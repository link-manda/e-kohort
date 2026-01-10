<div>
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Kunjungan ANC', 'url' => route('anc-visits.index')],
        ['label' => $isEditMode ? 'Edit Kunjungan' : 'Tambah Kunjungan'],
    ]" />

    <!-- Pregnancy Info Header -->
    <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h3 class="text-2xl font-bold">{{ $pregnancy->patient->name }}</h3>
                    @if ($isEditMode)
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-bold rounded-full bg-yellow-400 text-yellow-900">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                </path>
                            </svg>
                            Edit Mode
                        </span>
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-bold rounded-full bg-white text-purple-700">
                            {{ $originalVisitCode }}
                        </span>
                    @endif
                </div>
                <p class="text-purple-100">Gravida: {{ $pregnancy->gravida }} • UK: {{ $gestational_age_weeks }} minggu
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-purple-100">HPL</p>
                <p class="text-xl font-bold">{{ $pregnancy->hpl->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Step Progress Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            @for ($i = 1; $i <= $totalSteps; $i++)
                <div class="flex-1 {{ $i < $totalSteps ? 'mr-2' : '' }}">
                    <div class="relative">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center border-2
                                    {{ $currentStep >= $i ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-300 text-gray-500' }}">
                                    @if ($currentStep > $i)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        {{ $i }}
                                    @endif
                                </div>
                            </div>
                            @if ($i < $totalSteps)
                                <div class="flex-1 h-1 mx-2 {{ $currentStep > $i ? 'bg-blue-600' : 'bg-gray-300' }}">
                                </div>
                            @endif
                        </div>
                        <div
                            class="text-xs text-center mt-2 {{ $currentStep >= $i ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">
                            @if ($i == 1)
                                Info Kunjungan
                            @elseif($i == 2)
                                Pemeriksaan Fisik
                            @elseif($i == 3)
                                Tekanan Darah
                            @else
                                Lab & Skrining
                            @endif
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Step 1: Basic Visit Info -->
        @if ($currentStep == 1)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4 text-lg">📋 Informasi Kunjungan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="visit_date" max="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('visit_date') border-red-500 @enderror">
                        @error('visit_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Usia Kehamilan (minggu) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model.live="gestational_age_weeks" min="1" max="42"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gestational_age_weeks') border-red-500 @enderror">
                        @error('gestational_age_weeks')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Keluhan Utama
                    </label>
                    <textarea wire:model="chief_complaint" rows="3" placeholder="Contoh: Mual, pusing, sakit punggung..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>
        @endif

        <!-- Step 2: Physical Examination -->
        @if ($currentStep == 2)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4 text-lg">🩺 Pemeriksaan Fisik</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Berat Badan (kg)
                        </label>
                        <input type="number" wire:model.live="weight" min="30" max="200" step="0.1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('weight') border-red-500 @enderror">
                        @error('weight')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tinggi Badan (cm)
                        </label>
                        <input type="number" wire:model.live="height" min="130" max="200" step="0.1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('height') border-red-500 @enderror">
                        @error('height')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            LILA (cm)
                        </label>
                        <input type="number" wire:model.live="lila" min="15" max="50" step="0.1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('lila') border-red-500 @enderror">
                        @error('lila')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @elseif($has_kek)
                            <p class="text-red-500 text-xs mt-1 font-semibold">⚠️ KEK Terdeteksi (LILA < 23.5 cm)</p>
                                @elseif($lila && $lila >= 23.5)
                                    <p class="text-green-500 text-xs mt-1">✓ Normal</p>
                                @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            TFU (cm)
                        </label>
                        <input type="number" wire:model.live="tfu" min="10" max="50" step="0.1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tfu') border-red-500 @enderror">
                        @error('tfu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @else
                            <p class="text-xs text-gray-500 mt-1">Tinggi Fundus Uteri (minimal 10 cm)</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            DJJ (bpm)
                        </label>
                        <input type="number" wire:model.live="djj" min="100" max="180"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('djj') border-red-500 @enderror">
                        @error('djj')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @else
                            <p class="text-xs text-gray-500 mt-1">Denyut Jantung Janin (Normal: 120-160 bpm)</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Presentasi Janin
                        </label>
                        <select wire:model="fetal_presentation"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih presentasi</option>
                            <option value="Kepala">Kepala</option>
                            <option value="Sungsang">Sungsang</option>
                            <option value="Lintang">Lintang</option>
                            <option value="Belum Jelas">Belum Jelas</option>
                        </select>
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 3: Blood Pressure & MAP Calculator -->
        @if ($currentStep == 3)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4 text-lg">💉 Tekanan Darah & MAP Score</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Sistol (mmHg) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model.live="systolic" min="80" max="250"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('systolic') border-red-500 @enderror">
                        @error('systolic')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Diastol (mmHg) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model.live="diastolic" min="50" max="150"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('diastolic') border-red-500 @enderror">
                        @error('diastolic')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Real-time MAP Display -->
                @if ($systolic && $diastolic)
                    <div
                        class="mt-6 p-6 rounded-xl
                        {{ $map_risk_level === 'BAHAYA' ? 'bg-red-50 border-2 border-red-500' : '' }}
                        {{ $map_risk_level === 'WASPADA' ? 'bg-yellow-50 border-2 border-yellow-500' : '' }}
                        {{ $map_risk_level === 'NORMAL' ? 'bg-green-50 border-2 border-green-500' : '' }}">
                        <div class="text-center">
                            <p
                                class="text-sm font-semibold
                                {{ $map_risk_level === 'BAHAYA' ? 'text-red-700' : '' }}
                                {{ $map_risk_level === 'WASPADA' ? 'text-yellow-700' : '' }}
                                {{ $map_risk_level === 'NORMAL' ? 'text-green-700' : '' }}">
                                Mean Arterial Pressure (MAP)
                            </p>
                            <p
                                class="text-5xl font-bold mt-2
                                {{ $map_risk_level === 'BAHAYA' ? 'text-red-900' : '' }}
                                {{ $map_risk_level === 'WASPADA' ? 'text-yellow-900' : '' }}
                                {{ $map_risk_level === 'NORMAL' ? 'text-green-900' : '' }}">
                                {{ number_format($map_score, 1) }}
                            </p>
                            <p
                                class="text-xl font-semibold mt-2
                                {{ $map_risk_level === 'BAHAYA' ? 'text-red-800' : '' }}
                                {{ $map_risk_level === 'WASPADA' ? 'text-yellow-800' : '' }}
                                {{ $map_risk_level === 'NORMAL' ? 'text-green-800' : '' }}">
                                {{ $map_risk_level }}
                            </p>
                            <p
                                class="text-sm mt-2
                                {{ $map_risk_level === 'BAHAYA' ? 'text-red-600' : '' }}
                                {{ $map_risk_level === 'WASPADA' ? 'text-yellow-600' : '' }}
                                {{ $map_risk_level === 'NORMAL' ? 'text-green-600' : '' }}">
                                @if ($map_risk_level === 'BAHAYA')
                                    ⚠️ MAP > 100: Rujuk segera ke fasilitas kesehatan!
                                @elseif($map_risk_level === 'WASPADA')
                                    ⚠️ MAP > 90: Monitoring ketat diperlukan
                                @else
                                    ✓ MAP Normal (≤ 90)
                                @endif
                            </p>
                        </div>
                    </div>
                @else
                    <div class="mt-6 p-6 bg-gray-50 rounded-xl text-center">
                        <p class="text-gray-500">Masukkan nilai sistol dan diastol untuk menghitung MAP secara otomatis
                        </p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Step 4: Laboratory Results -->
        @if ($currentStep == 4)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4 text-lg">🔬 Hasil Laboratorium</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Hemoglobin (g/dL)
                        </label>
                        <input type="number" wire:model.live="hb" min="5" max="20" step="0.1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('hb') border-red-500 @enderror">
                        @error('hb')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @elseif($has_anemia)
                            <p class="text-red-500 text-xs mt-1 font-semibold">⚠️ Anemia (Hb < 11 g/dL)</p>
                                @elseif($hb && $hb >= 11)
                                    <p class="text-green-500 text-xs mt-1">✓ Normal</p>
                                @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Protein Urine
                        </label>
                        <select wire:model="protein_urine"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih hasil</option>
                            <option value="Negatif">Negatif (-)</option>
                            <option value="+1">+1</option>
                            <option value="+2">+2</option>
                            <option value="+3">+3</option>
                            <option value="+4">+4</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Gula Darah (mg/dL)
                        </label>
                        <input type="number" wire:model="blood_sugar" min="50" max="500"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Triple Elimination Screening -->
                <div class="mt-6 p-4 bg-purple-50 rounded-lg">
                    <h4 class="font-bold text-purple-900 mb-3">Skrining Triple Elimination</h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                HIV <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="hiv_status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="NR">Non-Reaktif</option>
                                <option value="R">Reaktif</option>
                            </select>
                            @if ($hiv_status === 'R')
                                <p class="text-red-500 text-xs mt-1 font-semibold">⚠️ Reaktif - Rujuk</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Syphilis <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="syphilis_status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="NR">Non-Reaktif</option>
                                <option value="R">Reaktif</option>
                            </select>
                            @if ($syphilis_status === 'R')
                                <p class="text-red-500 text-xs mt-1 font-semibold">⚠️ Reaktif - Rujuk</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                HBsAg <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="hbsag_status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="NR">Non-Reaktif</option>
                                <option value="R">Reaktif</option>
                            </select>
                            @if ($hbsag_status === 'R')
                                <p class="text-red-500 text-xs mt-1 font-semibold">⚠️ Reaktif - Rujuk</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tindakan & Intervensi -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-bold text-blue-900 mb-3">💊 Tindakan & Intervensi</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Imunisasi TT
                            </label>
                            <select wire:model="tt_immunization"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Belum/Tidak ada</option>
                                <option value="T1">T1</option>
                                <option value="T2">T2</option>
                                <option value="T3">T3</option>
                                <option value="T4">T4</option>
                                <option value="T5">T5</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Status imunisasi tetanus toksoid</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tablet Tambah Darah (TTD)
                            </label>
                            <input type="number" wire:model="fe_tablets" min="0" max="200"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Jumlah tablet yang diberikan">
                            <p class="text-xs text-gray-500 mt-1">Jumlah tablet Fe yang diberikan</p>
                        </div>
                    </div>
                </div>

                <!-- Pemeriksaan Tambahan -->
                <div class="mt-6 p-4 bg-indigo-50 rounded-lg">
                    <h4 class="font-bold text-indigo-900 mb-3">🔍 Pemeriksaan Tambahan</h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <!-- ANC 12T Checkbox -->
                        <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-indigo-200">
                            <input type="hidden" name="anc_12t" value="0">
                            <input type="checkbox" wire:model="anc_12t" id="anc_12t" value="1"
                                class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                            <label for="anc_12t" class="text-sm font-semibold text-gray-700 cursor-pointer">
                                ANC 12T Lengkap
                            </label>
                        </div>

                        <!-- USG Check -->
                        <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-indigo-200">
                            <input type="hidden" name="usg_check" value="0">
                            <input type="checkbox" wire:model="usg_check" id="usg_check" value="1"
                                class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                            <label for="usg_check" class="text-sm font-semibold text-gray-700 cursor-pointer">
                                Sudah USG
                            </label>
                        </div>

                        <!-- Counseling Check -->
                        <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-indigo-200">
                            <input type="hidden" name="counseling_check" value="0">
                            <input type="checkbox" wire:model="counseling_check" id="counseling_check"
                                value="1"
                                class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                            <label for="counseling_check" class="text-sm font-semibold text-gray-700 cursor-pointer">
                                Sudah Konseling
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- BMI (Auto-calculated) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                BMI (Indeks Massa Tubuh)
                            </label>
                            <input type="number" wire:model="bmi" readonly
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none"
                                placeholder="Auto-calculated">
                            @if ($bmi)
                                <p class="text-xs mt-1"
                                    @if ($bmi < 18.5) class="text-yellow-600 font-semibold">⚠ {{ number_format($bmi, 1) }} - Underweight
                                    @elseif ($bmi < 25)
                                        class="text-green-600 font-semibold">✓ {{ number_format($bmi, 1) }} - Normal
                                    @elseif ($bmi < 30)
                                        class="text-orange-600 font-semibold">⚠ {{ number_format($bmi, 1) }} - Overweight
                                    @else
                                        class="text-red-600 font-semibold">⚠ {{ number_format($bmi, 1) }} - Obesitas @endif
                                    </p>
                                @else
                                <p class="text-xs text-gray-500 mt-1">Masukkan BB & TB untuk kalkulasi otomatis</p>
                            @endif
                        </div>

                        <!-- Midwife Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Bidan/Nakes
                            </label>
                            <input type="text" wire:model="midwife_name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Nama tenaga kesehatan yang memeriksa">
                            <p class="text-xs text-gray-500 mt-1">Petugas yang melakukan pemeriksaan</p>
                        </div>
                    </div>
                </div>

                <!-- Diagnosis & Rujukan -->
                <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                    <h4 class="font-bold text-yellow-900 mb-3">📋 Diagnosis & Rujukan</h4>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Diagnosis / Catatan Klinis
                            </label>
                            <textarea wire:model="diagnosis" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                placeholder="Contoh: Kehamilan normal G2P1A0, usia kehamilan 20 minggu..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Catatan pemeriksaan dan diagnosis</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Rujukan (jika ada)
                            </label>
                            <input type="text" wire:model="referral_target"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                placeholder="Contoh: RSUD Badung, Puskesmas Abiansemal...">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada rujukan</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Analisa Resiko
                            </label>
                            <textarea wire:model="risk_level" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                placeholder="Contoh: Resiko KEK, Resiko Anemia, Resiko Preeklamsia..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Analisa faktor risiko yang ditemukan</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tindak Lanjut
                            </label>
                            <textarea wire:model="follow_up" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                placeholder="Contoh: Kontrol 4 minggu lagi, Rujuk ke RSUD, Tambah asupan gizi..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Rencana tindak lanjut pemeriksaan</p>
                        </div>
                    </div>
                </div>

                <!-- Risk Category Display -->
                @if ($risk_category)
                    <div
                        class="mt-6 p-4 rounded-lg
                        {{ $risk_category === 'Ekstrem' ? 'bg-red-100 border-2 border-red-500' : '' }}
                        {{ $risk_category === 'Tinggi' ? 'bg-orange-100 border-2 border-orange-500' : '' }}
                        {{ $risk_category === 'Rendah' ? 'bg-green-100 border-2 border-green-500' : '' }}">
                        <p
                            class="text-sm font-semibold
                            {{ $risk_category === 'Ekstrem' ? 'text-red-700' : '' }}
                            {{ $risk_category === 'Tinggi' ? 'text-orange-700' : '' }}
                            {{ $risk_category === 'Rendah' ? 'text-green-700' : '' }}">
                            Kategori Risiko:
                        </p>
                        <p
                            class="text-2xl font-bold
                            {{ $risk_category === 'Ekstrem' ? 'text-red-900' : '' }}
                            {{ $risk_category === 'Tinggi' ? 'text-orange-900' : '' }}
                            {{ $risk_category === 'Rendah' ? 'text-green-900' : '' }}">
                            {{ $risk_category }}
                        </p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Navigation Buttons -->
        <div class="flex items-center justify-between">
            @if ($currentStep > 1)
                <button type="button" wire:click="previousStep"
                    class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                    ← Sebelumnya
                </button>
            @else
                <a href="{{ route('patients.show', $pregnancy->patient_id) }}"
                    class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                    Batal
                </a>
            @endif

            @if ($currentStep < $totalSteps)
                <button type="button" wire:click="nextStep"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    Selanjutnya →
                </button>
            @else
                <button type="submit" wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors flex items-center space-x-2">
                    <span wire:loading.remove>
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        {{ $isEditMode ? 'Update Kunjungan ANC' : 'Simpan Kunjungan ANC' }}
                    </span>
                    <span wire:loading>
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>
                </button>
            @endif
        </div>

        <!-- Success Message -->
        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
    </form>
</div>
