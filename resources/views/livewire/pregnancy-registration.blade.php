<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Patient Info Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center space-x-4">
                <div
                    class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-blue-600 text-2xl font-bold">
                    {{ strtoupper(substr($patient->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-2xl font-bold">{{ $patient->name }}</h3>
                    <p class="text-blue-100">{{ $patient->age }} tahun • NIK: {{ $patient->nik }}</p>
                </div>
            </div>
        </div>

        <!-- Gravida Input -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Status Kehamilan (Gravida)
            </h3>

            <div class="grid grid-cols-3 gap-4">
                <!-- G (Gravida) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Gravida (G) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" wire:model.live="gravida_g" min="1" max="20"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gravida_g') border-red-500 @enderror">
                    @error('gravida_g')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Kehamilan ke-</p>
                </div>

                <!-- P (Para) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Para (P) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" wire:model.live="gravida_p" min="0" max="20"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gravida_p') border-red-500 @enderror">
                    @error('gravida_p')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Melahirkan</p>
                </div>

                <!-- A (Abortus) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Abortus (A) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" wire:model.live="gravida_a" min="0" max="20"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gravida_a') border-red-500 @enderror">
                    @error('gravida_a')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Keguguran</p>
                </div>
            </div>

            <!-- Gravida Display -->
            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-700">Status Gravida:</p>
                <p class="text-2xl font-bold text-blue-900">
                    G{{ $gravida_g }}P{{ $gravida_p }}A{{ $gravida_a }}
                </p>
            </div>
        </div>

        <!-- HPHT & HPL -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Tanggal Penting
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- HPHT -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        HPHT (Hari Pertama Haid Terakhir) <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model.live="hpht" max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('hpht') border-red-500 @enderror">
                    @error('hpht')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- HPL (Auto-calculated) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        HPL (Hari Perkiraan Lahir) <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model="hpl"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('hpl') border-red-500 @enderror">
                    @error('hpl')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-green-600 mt-1">✓ Otomatis dihitung (HPHT + 9 bulan)</p>
                </div>
            </div>

            <!-- Gestational Age Display -->
            @if ($hpht)
                <div class="mt-4 p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-green-700">Usia Kehamilan Saat Ini:</p>
                    <p class="text-2xl font-bold text-green-900">
                        {{ (int) \Carbon\Carbon::parse($hpht)->diffInWeeks(now()) }} minggu
                    </p>
                </div>
            @endif
        </div>

        <!-- Additional Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Informasi Tambahan (Opsional)
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Pregnancy Gap -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Jarak Kehamilan (tahun)
                    </label>
                    <input type="number" wire:model="pregnancy_gap" min="0" max="50"
                        placeholder="Contoh: 2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Jarak dari kehamilan sebelumnya</p>
                </div>

                <!-- Weight Before Pregnancy -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        BB Sebelum Hamil (kg)
                    </label>
                    <input type="number" wire:model="weight_before" min="20" max="200" step="0.1"
                        placeholder="Contoh: 55.5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Berat badan sebelum hamil</p>
                </div>

                <!-- Height -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tinggi Badan (cm)
                    </label>
                    <input type="number" wire:model="height" min="100" max="250" step="0.1"
                        placeholder="Contoh: 160"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Tinggi badan ibu</p>
                </div>

                <!-- Risk Score Initial -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Skor Poedji Rochjati
                    </label>
                    <input type="number" wire:model="risk_score_initial" min="0" max="50"
                        placeholder="Contoh: 2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Skor risiko awal (jika sudah dihitung)</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('patients.show', $patient_id) }}"
                class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                Batal
            </a>
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors flex items-center space-x-2">
                <span wire:loading.remove>Daftarkan Kehamilan</span>
                <span wire:loading>
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
            </button>
        </div>

        <!-- Error Message -->
        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
    </form>
</div>
