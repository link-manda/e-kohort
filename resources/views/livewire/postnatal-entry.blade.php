<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{
    activeTab: 'form',
    // State Alpine untuk modal dihapus, tidak diperlukan lagi
}"
    x-on:postnatal-visit-saved.window="activeTab = 'history'">

    <!-- MODAL OVERLAY: External Birth Form -->
    @if ($showExternalBirthModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Background overlay -->
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white" id="modal-title">
                                    Konfirmasi Riwayat Persalinan
                                </h3>
                                <p class="text-sm text-blue-100 mt-1">Data belum tercatat di sistem ini</p>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="bg-white px-6 py-5">
                        <!-- Warning Message -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Pasien ini belum tercatat melahirkan di klinik ini.</strong><br>
                                        Jika pasien melahirkan di tempat lain, mohon lengkapi data persalinan luar
                                        sebelum mengisi data Nifas.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Form -->
                        <form wire:submit.prevent="saveExternalBirth" class="space-y-5">
                            <!-- Tanggal & Jam Lahir -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <span class="text-red-500">*</span> Tanggal & Jam Lahir
                                </label>
                                <input type="datetime-local" wire:model="external_delivery_datetime"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                @error('external_delivery_datetime')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin Bayi -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <span class="text-red-500">*</span> Jenis Kelamin Bayi
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label
                                        class="flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition-all"
                                        :class="$wire.external_baby_gender === 'L' ? 'border-blue-500 bg-blue-50' :
                                            'border-gray-300 hover:border-blue-300'">
                                        <input type="radio" wire:model="external_baby_gender" value="L"
                                            class="sr-only">
                                        <span class="text-2xl mr-2">👦</span>
                                        <span class="font-medium">Laki-laki</span>
                                    </label>
                                    <label
                                        class="flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition-all"
                                        :class="$wire.external_baby_gender === 'P' ? 'border-pink-500 bg-pink-50' :
                                            'border-gray-300 hover:border-pink-300'">
                                        <input type="radio" wire:model="external_baby_gender" value="P"
                                            class="sr-only">
                                        <span class="text-2xl mr-2">👧</span>
                                        <span class="font-medium">Perempuan</span>
                                    </label>
                                </div>
                                @error('external_baby_gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Berat Bayi -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <span class="text-red-500">*</span> Berat Bayi (gram)
                                </label>
                                <input type="number" wire:model="external_baby_weight" min="500" max="6000"
                                    step="10" placeholder="Contoh: 3200"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                @error('external_baby_weight')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tempat Bersalin (Opsional) -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tempat Bersalin <span class="text-gray-400 text-xs">(Opsional)</span>
                                </label>
                                <input type="text" wire:model="external_birth_place"
                                    placeholder="Contoh: RSUP Sanglah"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('external_birth_place')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3 pt-4">
                                <button type="button" wire:click="cancelExternalBirth"
                                    class="flex-1 px-5 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="flex-1 px-5 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                                    💾 Simpan & Lanjut Nifas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            @if ($pregnancy && $pregnancy->patient_id)
                <a href="{{ route('patients.show', $pregnancy->patient_id) }}"
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
            @endif
            <div>
                <h1 class="text-2xl font-bold text-gray-900">🤱 Kunjungan Nifas</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $pregnancy?->patient?->name ?? 'Pasien Baru' }} - {{ $pregnancy?->gravida ?? 'G?' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Inline Success Toast -->
    @if ($showSuccessModal)
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="fixed top-20 right-4 z-50">
            <div class="bg-white border-l-4 border-green-500 shadow px-4 py-3 rounded-lg">
                <p class="font-semibold text-green-700">Berhasil!</p>
                <p class="text-sm text-gray-700">Kunjungan nifas telah berhasil disimpan.</p>
            </div>
        </div>
    @endif

    @if ($errorMessage)
        <!-- Error Message -->
        <div class="bg-red-50 border-2 border-red-500 rounded-xl p-6 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-bold text-red-900 mb-1">Tidak Dapat Melanjutkan</h3>
                    <p class="text-red-700">{{ $errorMessage }}</p>
                    @if ($pregnancy && $pregnancy->patient_id)
                        <a href="{{ route('patients.show', $pregnancy->patient_id) }}"
                            class="inline-block mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Kembali ke Detail Pasien
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                            class="inline-block mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Kembali ke Dashboard
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @else
        <!-- Tabs Navigation -->
        <div class="bg-white rounded-t-xl shadow-sm border border-gray-200 border-b-0">
            <div class="flex">
                <button @click="activeTab = 'form'"
                    :class="activeTab === 'form' ? 'border-b-4 border-blue-600 text-blue-600' :
                        'text-gray-600 hover:text-gray-800'"
                    class="flex-1 px-6 py-4 font-semibold transition-colors">
                    📝 Form Kunjungan
                </button>
                <button @click="activeTab = 'history'"
                    :class="activeTab === 'history' ? 'border-b-4 border-blue-600 text-blue-600' :
                        'text-gray-600 hover:text-gray-800'"
                    class="flex-1 px-6 py-4 font-semibold transition-colors">
                    📋 Riwayat Kunjungan ({{ count($existingVisits) }})
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="bg-white rounded-b-xl shadow-sm border border-gray-200 p-8">
            <!-- Form Tab -->
            <div x-show="activeTab === 'form'" x-transition>
                <form wire:submit.prevent="save">
                    <div class="space-y-6">
                        <!-- Alert Info -->
                        <div class="bg-blue-50 border-2 border-blue-500 rounded-xl p-4 flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-blue-900">Jadwal Kunjungan Nifas</p>
                                <ul class="text-sm text-blue-700 mt-2 space-y-1">
                                    <li><strong>KF1:</strong> 6 jam - 2 hari setelah persalinan</li>
                                    <li><strong>KF2:</strong> 3 - 7 hari setelah persalinan</li>
                                    <li><strong>KF3:</strong> 8 - 28 hari setelah persalinan</li>
                                    <li><strong>KF4:</strong> 29 - 42 hari setelah persalinan</li>
                                </ul>
                            </div>
                        </div>

                        @if ($visit_date_warning)
                            <!-- Tanggal Kunjungan (validasi) -->
                            <div
                                class="rounded-xl p-4 flex items-start gap-3 {{ $visit_date_is_valid ? 'bg-green-50 border-2 border-green-500' : 'bg-yellow-50 border-2 border-yellow-500' }}">
                                <svg class="w-6 h-6 flex-shrink-0 mt-0.5 {{ $visit_date_is_valid ? 'text-green-600' : 'text-yellow-600' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                <div>
                                    <p
                                        class="font-semibold {{ $visit_date_is_valid ? 'text-green-900' : 'text-yellow-900' }}">
                                        {{ $visit_date_is_valid ? 'Tanggal Sesuai Jadwal' : 'Peringatan Tanggal Kunjungan' }}
                                    </p>
                                    <p
                                        class="text-sm {{ $visit_date_is_valid ? 'text-green-700' : 'text-yellow-700' }} mt-1">
                                        {{ $visit_date_warning }}</p>
                                </div>
                            </div>
                        @endif

                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Informasi Kunjungan
                        </h3>

                        <!-- Kode Kunjungan & Tanggal -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kode Kunjungan <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="visit_code"
                                    class="w-full px-4 py-3 border-2 rounded-lg
                                          @error('visit_code') border-red-500 @else border-gray-300 @enderror
                                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                                    <option value="">-- Pilih Kode Kunjungan --</option>
                                    <option value="KF1">KF1 (6 jam - 2 hari)</option>
                                    <option value="KF2">KF2 (3 - 7 hari)</option>
                                    <option value="KF3">KF3 (8 - 28 hari)</option>
                                    <option value="KF4">KF4 (29 - 42 hari)</option>
                                </select>
                                @error('visit_code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Kunjungan <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model.blur="visit_date"
                                    class="w-full px-4 py-3 border-2 rounded-lg
                                          @error('visit_date') border-red-500 @else border-gray-300 @enderror
                                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                                @error('visit_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                            Tanda Vital
                        </h3>

                        <!-- Tekanan Darah -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tekanan Darah <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input type="number" wire:model.live="td_systolic"
                                        class="w-full px-4 py-3 border-2 rounded-lg
                                              @error('td_systolic') border-red-500 @else border-gray-300 @enderror
                                              focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                        placeholder="Sistolik (mmHg)" min="80" max="250">
                                    @error('td_systolic')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <input type="number" required wire:model.live="td_diastolic"
                                        class="w-full px-4 py-3 border-2 rounded-lg
                                              @error('td_diastolic') border-red-500 @else border-gray-300 @enderror
                                              focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                        placeholder="Diastolik (mmHg)" min="50" max="150">
                                    @error('td_diastolic')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            @if ($blood_pressure_status)
                                <div
                                    class="mt-3 p-3 rounded-lg border-2
                                        {{ $blood_pressure_category === 'danger' ? 'bg-red-50 border-red-500' : '' }}
                                        {{ $blood_pressure_category === 'warning' ? 'bg-yellow-50 border-yellow-500' : '' }}
                                        {{ $blood_pressure_category === 'normal' ? 'bg-green-50 border-green-500' : '' }}">
                                    <p
                                        class="text-sm font-semibold
                                            {{ $blood_pressure_category === 'danger' ? 'text-red-700' : '' }}
                                            {{ $blood_pressure_category === 'warning' ? 'text-yellow-700' : '' }}
                                            {{ $blood_pressure_category === 'normal' ? 'text-green-700' : '' }}">
                                        {{ $blood_pressure_status }}
                                    </p>
                                </div>
                            @else
                                <p class="mt-2 text-xs text-gray-500">Normal: Sistol 90-139 mmHg, Diastol 60-89 mmHg
                                </p>
                            @endif
                        </div>

                        <!-- Suhu Tubuh -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Suhu Tubuh (°C) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.1" wire:model.live="temperature"
                                class="w-full px-4 py-3 border-2 rounded-lg
                                      {{ $errors->has('temperature') ? 'border-red-500' : 'border-gray-300' }}
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                placeholder="Contoh: 36.5" min="35" max="42">

                            @if ($temperature_status)
                                <div
                                    class="mt-3 p-3 rounded-lg border-2
                                        {{ $temperature_category === 'danger' ? 'bg-red-50 border-red-500' : '' }}
                                        {{ $temperature_category === 'warning' ? 'bg-yellow-50 border-yellow-500' : '' }}
                                        {{ $temperature_category === 'normal' ? 'bg-green-50 border-green-500' : '' }}">
                                    <p
                                        class="text-sm font-semibold
                                            {{ $temperature_category === 'danger' ? 'text-red-700' : '' }}
                                            {{ $temperature_category === 'warning' ? 'text-yellow-700' : '' }}
                                            {{ $temperature_category === 'normal' ? 'text-green-700' : '' }}">
                                        {{ $temperature_status }}
                                    </p>
                                </div>
                            @else
                                <p class="mt-2 text-xs text-gray-500">Normal: 36.0 - 37.4°C | Demam: ≥ 38°C | Minimal:
                                    35°C</p>
                            @endif

                            @error('temperature')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Pemeriksaan Nifas
                        </h3>

                        <!-- Lochea -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Lochea <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="lochea"
                                class="w-full px-4 py-3 border-2 rounded-lg
                                      @error('lochea') border-red-500 @else border-gray-300 @enderror
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                                <option value="">-- Pilih Jenis Lochea --</option>
                                <option value="Rubra">Rubra (Merah tua, 1-3 hari)</option>
                                <option value="Sanguinolenta">Sanguinolenta (Merah kecoklatan, 4-7 hari)</option>
                                <option value="Serosa">Serosa (Kuning kecoklatan, 7-14 hari)</option>
                                <option value="Alba">Alba (Putih/kuning, >14 hari)</option>
                            </select>
                            @error('lochea')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-2 text-xs text-gray-500">
                                    💡 Tip: Jenis lochea menunjukkan tahap penyembuhan rahim pasca persalinan
                                </p>
                            @enderror
                        </div>

                        <!-- Involusi Uterus -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Involusi Uterus (TFU)
                            </label>
                            <input type="text" wire:model="uterine_involution"
                                class="w-full px-4 py-3 border-2 rounded-lg border-gray-300
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                placeholder="Contoh: 2 jari di bawah pusat atau Normal">
                            <p class="mt-2 text-xs text-gray-500">
                                💡 Tip: Tinggi fundus uteri (TFU) menurun sekitar 1 cm/hari pasca persalinan
                            </p>
                            @error('uterine_involution')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                            Pemberian Suplemen
                        </h3>

                        <!-- Vitamin A & Fe Tablets -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all
                                             {{ $vitamin_a ? 'border-green-500 bg-green-50' : 'border-gray-300 hover:border-green-300' }}">
                                    <input type="checkbox" wire:model="vitamin_a"
                                        class="w-5 h-5 text-green-600 rounded">
                                    <div>
                                        <span class="font-semibold block">Vitamin A</span>
                                        <span class="text-xs text-gray-600">200.000 IU (2 kapsul merah)</span>
                                    </div>
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tablet Fe (jumlah)
                                </label>
                                <input type="number" wire:model="fe_tablets"
                                    class="w-full px-4 py-3 border-2 rounded-lg border-gray-300
                                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                    placeholder="Jumlah tablet" min="0">
                                <p class="mt-2 text-xs text-gray-500">
                                    💡 Rekomendasi: 40 tablet untuk masa nifas (42 hari)
                                </p>
                                @error('fe_tablets')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Komplikasi -->
                        <div>
                            <label
                                class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all
                                         {{ $complication_check ? 'border-red-500 bg-red-50' : 'border-gray-300 hover:border-red-300' }}">
                                <input type="checkbox" wire:model="complication_check"
                                    class="w-5 h-5 text-red-600 rounded mt-0.5">
                                <div>
                                    <span class="font-semibold block text-gray-900">Terdapat Komplikasi</span>
                                    <span class="text-sm text-gray-600">Centang jika ditemukan komplikasi seperti
                                        perdarahan, infeksi, atau masalah lain</span>
                                </div>
                            </label>
                        </div>

                        <!-- Kesimpulan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kesimpulan & Tindakan
                            </label>
                            <textarea wire:model="conclusion" rows="4"
                                class="w-full px-4 py-3 border-2 rounded-lg border-gray-300
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                placeholder="Tulis kesimpulan pemeriksaan dan tindakan yang diberikan..."></textarea>
                            @error('conclusion')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-6 border-t border-gray-200">
                            <button type="submit" wire:loading.attr="disabled" wire:target="save"
                                class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg
                                       transition-colors shadow-lg hover:shadow-xl flex items-center justify-center gap-2
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                                <!-- Loading Spinner -->
                                <svg wire:loading wire:target="save" class="animate-spin h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                <!-- Default Icon -->
                                <svg wire:loading.remove wire:target="save" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>

                                <!-- Button Text -->
                                <span wire:loading.remove wire:target="save">
                                    {{ $isEditMode ? 'Update Kunjungan' : 'Simpan Kunjungan' }}
                                </span>
                                <span wire:loading wire:target="save">
                                    Menyimpan...
                                </span>
                            </button>

                            @if ($isEditMode)
                                <button type="button" wire:click="cancelEdit"
                                    class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg
                                           transition-colors">
                                    Batal Edit
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- History Tab -->
            <div x-show="activeTab === 'history'" x-transition>
                @if (count($existingVisits) > 0)
                    <div class="space-y-4">
                        @foreach ($existingVisits as $visit)
                            <div
                                class="border-2 border-gray-200 rounded-xl p-6 hover:border-blue-300 transition-colors">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-purple-500
                                                    flex items-center justify-center text-white font-bold text-lg">
                                            {{ $visit->visit_code }}
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">{{ $visit->visit_code_label }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($visit->visit_date)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="editVisit({{ $visit->id }})"
                                            @click="activeTab = 'form'"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button
                                            onclick="if(!confirm('Hapus kunjungan nifas ini? Tindakan ini tidak dapat dibatalkan.')) event.stopImmediatePropagation()"
                                            wire:click="deleteVisit({{ $visit->id }})"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Tekanan Darah</p>
                                        <p class="font-semibold text-gray-900">
                                            {{ $visit->td_systolic }}/{{ $visit->td_diastolic }} mmHg</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Suhu</p>
                                        <p class="font-semibold text-gray-900">{{ $visit->temperature }}°C</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Lochea</p>
                                        <p class="font-semibold text-gray-900">{{ $visit->lochea }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Vitamin A</p>
                                        <p
                                            class="font-semibold {{ $visit->vitamin_a ? 'text-green-600' : 'text-gray-400' }}">
                                            {{ $visit->vitamin_a ? '✓ Diberikan' : '✗ Tidak' }}
                                        </p>
                                    </div>
                                </div>

                                @if ($visit->uterine_involution)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <p class="text-xs text-gray-500 mb-1">Involusi Uterus</p>
                                        <p class="text-sm text-gray-700">{{ $visit->uterine_involution }}</p>
                                    </div>
                                @endif

                                @if ($visit->complication_check)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <div class="flex items-center gap-2 text-red-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                </path>
                                            </svg>
                                            <span class="font-semibold">Terdapat Komplikasi</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($visit->conclusion)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <p class="text-xs text-gray-500 mb-1">Kesimpulan</p>
                                        <p class="text-sm text-gray-700">{{ $visit->conclusion }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Riwayat Kunjungan</h3>
                        <p class="text-gray-500 mb-6">Mulai tambahkan kunjungan nifas dari form di samping</p>
                        <button @click="activeTab = 'form'"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                            Tambah Kunjungan Pertama
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
