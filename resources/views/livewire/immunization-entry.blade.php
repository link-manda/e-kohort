<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Data Pasien Anak', 'url' => route('imunisasi.index')],
        ['label' => $child->name, 'url' => route('imunisasi.kunjungan', $child->id)],
        ['label' => 'Kunjungan Imunisasi'],
    ]" />

    <!-- Header with Patient Info (Sticky) -->
    <div class="sticky top-4 z-40 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold text-lg flex-shrink-0">
                        {{ strtoupper(substr($child->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-xl md:text-2xl font-bold truncate">{{ $child->name }}</h3>
                        <p class="text-blue-100">No. RM: <span class="font-semibold">{{ $child->no_rm }}</span></p>
                        <p class="text-sm text-blue-100 mt-1">Usia: <span
                                class="font-semibold">{{ $child->getDetailedAge() }}</span></p>
                    </div>
                </div>

                <div class="flex items-center justify-between lg:justify-end gap-4">
                    <div class="text-left lg:text-right">
                        <p class="text-sm text-blue-100">
                            @if($child->isExternalBirth())
                                Orang Tua
                            @else
                                Ibu
                            @endif
                        </p>
                        <p class="text-lg font-bold">{{ $child->parent_display_name }}</p>
                        <p class="text-sm text-blue-100">
                            @if($child->isExternalBirth())
                                {{ $child->parent_phone ?? '-' }}
                            @else
                                {{ $child->patient->phone ?? '-' }}
                            @endif
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        @if ($child->status === 'Hidup')
                            <span
                                class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">Sehat</span>
                        @else
                            <span
                                class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">Meninggal</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Validation Error Summary Banner -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-2 border-red-500 rounded-xl p-4 animate-pulse">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <h4 class="font-bold text-red-800 mb-2">❌ Form Tidak Dapat Disimpan - {{ $errors->count() }} Kesalahan Ditemukan</h4>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <p class="text-xs text-red-600 mt-3 font-semibold">💡 Silakan perbaiki field yang ditandai merah di bawah</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Step Progress Indicator -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h4 class="font-semibold text-gray-900">Progress Kunjungan</h4>
                <div class="flex items-center justify-between sm:justify-center flex-1 max-w-md mx-auto">
                    @for ($i = 1; $i <= $totalSteps; $i++)
                        <div class="flex items-center flex-1 {{ $i < $totalSteps ? 'mr-2' : '' }}">
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
                    @endfor
                </div>
                <div class="text-center sm:text-right">
                    <p class="text-sm text-gray-600">
                        @if ($currentStep == 1)
                            Langkah 1: Tanda Vital
                        @else
                            Langkah 2: Diagnosa & Vaksin
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <form id="immunization-form" wire:submit.prevent="save" class="space-y-6" novalidate>
        <!-- Step 1: Vital Signs -->
        @if ($currentStep == 1)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4 text-lg">🩺 Pemeriksaan Fisik & Tanda Vital</h3>

                <div class="grid grid-cols-1 gap-4">
                    <!-- Visit Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal & Jam Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" wire:model="visit_date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('visit_date') border-red-500 @enderror">
                        @error('visit_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Complaint -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Keluhan Utama
                        </label>
                        <textarea wire:model="complaint" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Keluhan atau kondisi anak saat ini..."></textarea>
                    </div>

                    <!-- Weight, Height, Head Circumference -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Berat Badan (kg) <span class="text-red-500">*</span>
                            </label>
                            <input id="weight" type="number" wire:model="weight" step="0.1" min="1"
                                max="100"
                                class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('weight') border-red-500 @enderror"
                                placeholder="kg">
                            <p id="client-weight-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            @error('weight')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tinggi Badan (cm) <span class="text-red-500">*</span>
                            </label>
                            <input id="height" type="number" wire:model="height" step="0.1" min="20"
                                max="200"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('height') border-red-500 @enderror"
                                placeholder="cm">
                            <p id="client-height-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            @error('height')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Lingkar Kepala (cm)
                            </label>
                            <input id="head_circumference" type="number" wire:model="head_circumference" step="0.1"
                                min="20" max="100"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('head_circumference') border-red-500 @enderror"
                                placeholder="cm">
                            <p id="client-head_circumference-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            @error('head_circumference')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Temperature - CRITICAL FIELD -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Suhu Tubuh (°C) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model.live="temperature" step="0.1" min="35" max="42"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('temperature') border-red-500 @enderror {{ $temperature_category === 'danger' ? 'border-red-500 bg-red-50' : ($temperature_category === 'warning' ? 'border-yellow-500 bg-yellow-50' : ($temperature_category === 'normal' ? 'border-green-500 bg-green-50' : 'border-gray-300')) }}"
                            placeholder="Contoh: 36.5">

                        @if ($temperature_warning)
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
                                    {{ $temperature_warning }}
                                </p>
                            </div>
                        @endif

                        @error('temperature')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Heart Rate & Respiratory Rate -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nadi (bpm)
                            </label>
                            <input id="heart_rate" type="number" wire:model="heart_rate" min="60"
                                max="200"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('heart_rate') border-red-500 @enderror"
                                placeholder="Denyut per menit">
                            <p id="client-heart_rate-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            @error('heart_rate')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nafas (per menit)
                            </label>
                            <input id="respiratory_rate" type="number" wire:model="respiratory_rate" min="10"
                                max="80"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('respiratory_rate') border-red-500 @enderror"
                                placeholder="Frekuensi napas">
                            <p id="client-respiratory_rate-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            @error('respiratory_rate')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Development Notes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Catatan Tumbuh Kembang
                        </label>
                        <textarea wire:model="development_notes" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Perkembangan motorik, bahasa, sosial, dll..."></textarea>
                    </div>

                    <!-- NEW: Status Gizi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status Gizi (BB/U) <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="nutritional_status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nutritional_status') border-red-500 @enderror">
                            <option value="">-- Pilih Status Gizi --</option>
                            <option value="Gizi Buruk">Gizi Buruk</option>
                            <option value="Gizi Kurang">Gizi Kurang</option>
                            <option value="Gizi Baik">Gizi Baik</option>
                            <option value="Gizi Lebih">Gizi Lebih</option>
                            <option value="Obesitas">Obesitas</option>
                        </select>
                        @error('nutritional_status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">💡 Sesuaikan dengan hasil plot KMS BB/U</p>
                    </div>
                </div>
            </div>

            <!-- NEW: Informed Consent Section -->
            <div
                class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl shadow-sm border-2 border-amber-300 p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 pt-1">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 mb-3 text-lg">📋 Persetujuan Tindakan Medis</h4>
                        <div class="bg-white rounded-lg p-4 mb-4 border border-amber-200">
                            <p class="text-sm text-gray-700 leading-relaxed">
                                Dengan mencentang kotak di bawah, orang tua/wali pasien <strong>menyatakan
                                    setuju</strong> untuk dilakukan tindakan imunisasi kepada anak sesuai jadwal dan
                                prosedur yang berlaku.
                            </p>
                        </div>

                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" wire:model="informed_consent"
                                class="w-6 h-6 mt-0.5 text-blue-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer @error('informed_consent') border-red-500 @enderror">
                            <span
                                class="text-base font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
                                ☑️ Ya, saya menyetujui tindakan imunisasi untuk anak saya
                                <span class="text-red-500">*</span>
                            </span>
                        </label>

                        @error('informed_consent')
                            <div class="mt-3 p-3 bg-red-50 border border-red-300 rounded-lg">
                                <p class="text-red-700 text-sm font-semibold">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- NEW: Medicine/KIPI Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" x-data="{ showMedicine: @entangle('medicine_given').live }">
                <h3 class="font-bold text-gray-800 mb-4 text-lg">💊 Pemberian Obat / KIPI</h3>

                <div class="space-y-4">
                    <!-- Medicine Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Obat yang Diberikan
                        </label>
                        <select wire:model.live="medicine_given"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('medicine_given') border-red-500 @enderror">
                            <option value="">-- Pilih Obat --</option>
                            <option value="Parasetamol Drop">Parasetamol Drop</option>
                            <option value="Parasetamol Sirup">Parasetamol Sirup</option>
                            <option value="Tidak Ada">Tidak Ada</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @error('medicine_given')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dosage Input (conditional) -->
                    <div x-show="showMedicine && showMedicine !== 'Tidak Ada'" x-cloak>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Dosis Obat
                        </label>
                        <input type="text" wire:model="medicine_dosage"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('medicine_dosage') border-red-500 @enderror"
                            placeholder="Contoh: 3x0.5ml atau 3x1 sendok takar">
                        @error('medicine_dosage')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">💡 Tulis dosis dan frekuensi pemberian obat</p>
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Keterangan Tambahan
                        </label>
                        <textarea wire:model="notes" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                            placeholder="Catatan tambahan, reaksi KIPI, dll..."></textarea>
                        @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 2: Diagnosis & Immunization -->
        @if ($currentStep == 2)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4 text-lg">💉 Diagnosa & Tindakan Imunisasi</h3>

                <!-- Diagnosis (Optional) with Searchable ICD-10 -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        🔍 Cari Kode ICD-10 (Opsional)
                    </label>

                    <div class="relative" x-data="{ open: @entangle('show_icd_dropdown') }">
                        <!-- Search Input -->
                        <div class="relative">
                            <input type="text" wire:model.live="icd_search" @focus="open = true"
                                class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Ketik: Polio, Campak, BCG, dll..." autocomplete="off">

                            @if ($icd_code)
                                <button type="button" wire:click="clearIcd10"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>

                        <!-- Dropdown Results -->
                        @if ($show_icd_dropdown && count($icd_results) > 0)
                            <div x-show="open" @click.away="open = false"
                                class="absolute z-50 w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-xl max-h-64 overflow-y-auto">
                                @foreach ($icd_results as $result)
                                    <button type="button" wire:click="selectIcd10('{{ $result['code'] }}')"
                                        class="w-full text-left px-4 py-3 hover:bg-blue-50 border-b border-gray-100 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded">
                                                {{ $result['code'] }}
                                            </span>
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-800 text-sm">{{ $result['name'] }}
                                                </p>
                                                <p class="text-xs text-gray-600 mt-1">{{ $result['description'] }}</p>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        <!-- Selected ICD Display -->
                        @if ($icd_code)
                            <div class="mt-3 p-3 bg-green-50 border border-green-300 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-green-600 font-semibold">Kode Dipilih:</p>
                                        <p class="font-bold text-green-800">{{ $icd_code }}</p>
                                        <p class="text-sm text-gray-700 mt-1">{{ $diagnosis_name }}</p>
                                    </div>
                                    <button type="button" wire:click="clearIcd10"
                                        class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <p class="text-xs text-gray-500 mt-2">
                        💡 Tip: Ketik kata kunci seperti "Polio", "Campak", atau "Hepatitis" untuk mencari kode
                    </p>
                </div>

                <!-- Vaccine Actions -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-bold text-gray-700">Vaksin yang Diberikan</h4>
                        <button type="button" wire:click="addVaccineRow"
                            class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Vaksin
                        </button>
                    </div>

                    @foreach ($vaccine_rows as $index => $row)
                        <div class="border-2 border-gray-200 rounded-lg p-4 mb-3 relative">
                            @if (count($vaccine_rows) > 1)
                                <button type="button" wire:click="removeVaccineRow({{ $index }})"
                                    class="absolute top-2 right-2 text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Vaccine Type -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Jenis Vaksin <span class="text-red-500">*</span>
                                    </label>
                                    <div x-data="{ open: {{ count($vaccine_search_results[$index] ?? []) > 0 ? 'true' : 'false' }} }" @click.away="open = false">
                                        <input type="text" wire:model.live="vaccine_search.{{ $index }}"
                                            @focus="open = true"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vaccine_rows.' . $index . '.vaccine_type') border-red-500 @enderror"
                                            placeholder="Cari vaksin atau ketik nama..." autocomplete="off"
                                            onkeydown="if(event.key==='Enter'){event.preventDefault();event.stopPropagation();}">

                                        @if (count($vaccine_search_results[$index] ?? []) > 0)
                                            <div x-show="open" x-cloak
                                                class="absolute z-50 w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                                                @foreach ($vaccine_search_results[$index] as $res)
                                                    <button type="button"
                                                        wire:click="selectVaccine({{ $index }}, '{{ $res['code'] }}')"
                                                        class="w-full text-left px-4 py-3 hover:bg-blue-50 border-b border-gray-100 transition-colors flex items-center justify-between">
                                                        <div>
                                                            <p class="font-semibold text-gray-800 text-sm">
                                                                {{ $res['name'] }}</p>
                                                            <p class="text-xs text-gray-500">{{ $res['code'] }} •
                                                                {{ $res['message'] }}</p>
                                                        </div>
                                                        @if ($res['status'] === 'appropriate')
                                                            <span
                                                                class="text-sm px-2 py-1 bg-green-100 text-green-700 rounded">✓</span>
                                                        @elseif($res['status'] === 'too_young')
                                                            <span
                                                                class="text-sm px-2 py-1 bg-red-100 text-red-700 rounded">!</span>
                                                        @else
                                                            <span
                                                                class="text-sm px-2 py-1 bg-yellow-100 text-yellow-700 rounded">?</span>
                                                        @endif
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Fallback: small select for power users --}}
                                        <div class="mt-2">
                                            <select wire:model="vaccine_rows.{{ $index }}.vaccine_type"
                                                class="w-full px-3 py-2 border border-gray-200 rounded text-sm mt-1">
                                                <option value="">-- Pilih dari daftar --</option>
                                                @foreach ($available_vaccines as $key => $v)
                                                    <option value="{{ $key }}">{{ $v['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @error('vaccine_rows.' . $index . '.vaccine_type')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Age Validation Warning -->
                                    @if (isset($vaccine_warnings[$index]))
                                        @php $vw = $vaccine_warnings[$index]; @endphp
                                        <div
                                            class="mt-2 p-2 rounded border {{ $vw['status'] === 'appropriate' ? 'bg-green-50 border-green-500 text-green-700' : ($vw['status'] === 'too_young' ? 'bg-red-50 border-red-500 text-red-700' : 'bg-yellow-50 border-yellow-500 text-yellow-700') }}">
                                            <p class="text-xs font-semibold flex items-center gap-2">
                                                @if ($vw['status'] === 'appropriate')
                                                    <svg class="w-4 h-4 text-green-700" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @elseif($vw['status'] === 'too_young')
                                                    <svg class="w-4 h-4 text-red-700" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-yellow-700" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20 10 10 0 010-20z" />
                                                    </svg>
                                                @endif
                                                {{ $vw['message'] }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Batch Number -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        No. Batch Vaksin
                                    </label>
                                    <input type="text" wire:model="vaccine_rows.{{ $index }}.batch_number"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Nomor batch">
                                </div>

                                <!-- Body Part -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Lokasi Suntikan
                                    </label>
                                    <select wire:model="vaccine_rows.{{ $index }}.body_part"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Pilih lokasi</option>
                                        <option value="Paha Kiri">Paha Kiri</option>
                                        <option value="Paha Kanan">Paha Kanan</option>
                                        <option value="Lengan">Lengan</option>
                                        <option value="Mulut">Mulut</option>
                                    </select>
                                </div>

                                <!-- Provider Name -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Nakes <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="vaccine_rows.{{ $index }}.provider_name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vaccine_rows.' . $index . '.provider_name') border-red-500 @enderror"
                                        placeholder="Nama petugas kesehatan">
                                    @error('vaccine_rows.' . $index . '.provider_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- NEW: Payment Information Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-800 mb-4 text-lg">💰 Informasi Pembayaran</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Service Fee -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Biaya Pelayanan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" wire:model="service_fee" step="1000" min="0"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('service_fee') border-red-500 @enderror"
                                    placeholder="0">
                            </div>
                            @error('service_fee')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">💡 Masukkan biaya pelayanan imunisasi</p>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="payment_method"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('payment_method') border-red-500 @enderror">
                                <option value="Umum">Umum</option>
                                <option value="BPJS">BPJS</option>
                            </select>
                            @error('payment_method')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Override Confirmation Modal -->
        @if ($vaccine_override_confirm['index'] !== null)
            <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="fixed inset-0 bg-black opacity-40"></div>
                <div class="bg-white rounded-lg shadow-lg z-50 max-w-lg w-full p-6">
                    <h4 class="text-lg font-bold mb-2">Konfirmasi Override Vaksin</h4>
                    <p class="text-sm text-gray-700 mb-4">Vaksin yang dipilih tidak sesuai dengan usia anak. Apakah
                        Anda yakin ingin melanjutkan dan melampirkan vaksin ini?</p>
                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="confirmOverrideSelection(false)"
                            class="px-4 py-2 bg-gray-200 rounded">Batal</button>
                        <button type="button" wire:click="confirmOverrideSelection(true)"
                            class="px-4 py-2 bg-red-600 text-white rounded">Override dan Pilih</button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Navigation Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-gray-200">
            @if ($currentStep > 1)
                <button type="button" wire:click="previousStep"
                    class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors order-2 sm:order-1">
                    ← Kembali
                </button>
            @endif

            @if ($currentStep < $totalSteps)
                <button type="button" wire:click="nextStep"
                    class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors order-1 sm:order-2">
                    Lanjut ke Diagnosa & Vaksin →
                </button>
            @else
                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                    class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg
                               transition-colors shadow-lg hover:shadow-xl flex items-center justify-center gap-2
                               disabled:opacity-50 disabled:cursor-not-allowed order-1 sm:order-2">
                    <!-- Loading Spinner -->
                    <svg wire:loading wire:target="save" class="animate-spin h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>

                    <!-- Default Icon -->
                    <svg wire:loading.remove wire:target="save" class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>

                    <span wire:loading.remove wire:target="save">Simpan Kunjungan Imunisasi</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            @endif
        </div>
    </form>

    <!-- History Section -->
    @if (count($existingVisits) > 0)
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-lg">📋 Riwayat Kunjungan Imunisasi</h3>

            <div class="space-y-4">
                @foreach ($existingVisits as $visit)
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-bold text-gray-900">
                                    {{ $visit->visit_date->format('d M Y, H:i') }}</p>
                                <p class="text-sm text-gray-600">Usia: {{ $child->getAgeAtVisit($visit->visit_date) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-700">Suhu:
                                    {{ $visit->temperature }}°C</p>
                                @if ($visit->has_fever)
                                    <span class="text-xs text-red-600 font-semibold">⚠️ Demam</span>
                                @endif
                            </div>
                        </div>

                        @if ($visit->immunizationActions->count() > 0)
                            <div class="mt-3">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Vaksin yang diberikan:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($visit->immunizationActions as $action)
                                        @php $vname = \App\Models\Vaccine::where('code', $action->vaccine_type)->value('name'); @endphp
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                            {{ $vname ?? $action->vaccine_type }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Success Toast -->
    @if ($showSuccess)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2 z-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Kunjungan imunisasi berhasil disimpan!</span>
        </div>
    @endif

    <!-- Browser event listener: show toast and redirect -->
    <div id="immunization-toast" class="fixed bottom-4 right-4 z-50 hidden">
        <div id="immunization-toast-inner"
            class="bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span id="immunization-toast-message">Aksi berhasil</span>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function() {
            // Use Livewire.on to listen for server dispatched events
            Livewire.on('immunizationSaved', function(payload) {
                try {
                    var toast = document.getElementById('immunization-toast');
                    var inner = document.getElementById('immunization-toast-inner');
                    var msg = document.getElementById('immunization-toast-message');

                    if (payload && payload.message) {
                        msg.textContent = payload.message;
                    }

                    toast.classList.remove('hidden');
                    inner.classList.remove('bg-red-600', 'bg-yellow-600');
                    inner.classList.add('bg-green-600');

                    var delay = (payload && payload.redirectDelay) ? payload.redirectDelay : 1500;
                    setTimeout(function() {
                        if (payload && payload.redirect) {
                            window.location = payload.redirect;
                        } else {
                            toast.classList.add('hidden');
                        }
                    }, delay);
                } catch (err) {
                    console.error('Error handling immunizationSaved event', err);
                }
            });

            // Debug listeners
            Livewire.on('immunizationSaveAttempt', function() {
                console.info('Immunization save attempt (event received).');
            });

            Livewire.on('immunizationValidationFailed', function(errors) {
                console.warn('Validation failed:', errors ? errors : {});
                try {
                    var toast = document.getElementById('immunization-toast');
                    var inner = document.getElementById('immunization-toast-inner');
                    var msg = document.getElementById('immunization-toast-message');

                    var firstMsg = 'Validasi gagal';
                    if (errors) {
                        var keys = Object.keys(errors);
                        if (keys.length > 0 && errors[keys[0]] && errors[keys[0]].length > 0) {
                            firstMsg = errors[keys[0]][0];
                        }
                    }

                    msg.textContent = firstMsg;
                    toast.classList.remove('hidden');
                    inner.classList.remove('bg-green-600');
                    inner.classList.add('bg-red-600');

                    setTimeout(function() {
                        toast.classList.add('hidden');
                    }, 4500);
                } catch (err) {
                    console.error('Error handling immunizationValidationFailed event', err);
                }
            });

            Livewire.on('immunizationSaveFailed', function(message) {
                console.error('Immunization save failed:', message ? message : 'unknown');
                try {
                    var toast = document.getElementById('immunization-toast');
                    var inner = document.getElementById('immunization-toast-inner');
                    var msg = document.getElementById('immunization-toast-message');

                    msg.textContent = message ? message : 'Terjadi kesalahan saat menyimpan.';
                    toast.classList.remove('hidden');
                    inner.classList.remove('bg-green-600');
                    inner.classList.add('bg-red-600');

                    setTimeout(function() {
                        toast.classList.add('hidden');
                    }, 3500);
                } catch (err) {
                    console.error('Error handling immunizationSaveFailed event', err);
                }
            });
            // Scroll to the first field that has server-side validation error
            function scrollToFirstError(errors) {
                if (!errors) return;
                var keys = Object.keys(errors);
                for (var i = 0; i < keys.length; i++) {
                    var k = keys[i];
                    // Try to find exact wire:model match
                    var el = document.querySelector('[wire\\:model="' + k + '"]');

                    // Try by id fallback (replace dots with underscores)
                    if (!el) {
                        var id = k.replace(/\./g, '_');
                        el = document.getElementById(id);
                    }

                    // Try to find by suffix match (for dynamic arrays)
                    if (!el) {
                        var short = k.split('.').pop();
                        el = document.querySelector('[wire\\:model*=".' + short + '"]') || document.querySelector(
                            '[wire\\:model$="' + short + '"]');
                    }

                    if (el) {
                        try {
                            el.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            if (typeof el.focus === 'function') el.focus({
                                preventScroll: true
                            });
                            el.classList.add('ring-2', 'ring-red-500');
                            setTimeout(function() {
                                el.classList.remove('ring-2', 'ring-red-500');
                            }, 3000);
                        } catch (err) {
                            console.error('scrollToFirstError failed', err);
                        }
                        break;
                    }
                }
            }

            // Call scrollToFirstError on server validation failure
            Livewire.on('immunizationValidationFailed', function(errors) {
                // Determine target step and emit to server to switch step first
                scrollToFirstError(errors);
            });

            // NEW: Listen for validationFailed event (general validation error with custom message)
            Livewire.on('validationFailed', function(payload) {
                try {
                    var toast = document.getElementById('immunization-toast');
                    var inner = document.getElementById('immunization-toast-inner');
                    var msg = document.getElementById('immunization-toast-message');

                    // Show error toast with custom message
                    if (payload && payload.message) {
                        msg.textContent = payload.message;
                    } else {
                        msg.textContent = 'Form tidak dapat disimpan. Silakan periksa kembali.';
                    }

                    inner.classList.remove('bg-green-600', 'bg-yellow-600');
                    inner.classList.add('bg-red-600');
                    toast.classList.remove('hidden');

                    // Hide after 5 seconds
                    setTimeout(function() {
                        toast.classList.add('hidden');
                    }, 5000);

                    console.warn('Validation failed:', payload.errors || {});
                } catch (err) {
                    console.error('Error handling validationFailed event', err);
                }
            });

            // NEW: Trigger scroll to first error
            Livewire.on('scrollToFirstError', function() {
                setTimeout(function() {
                    // Find first element with error class (border-red-500)
                    var firstError = document.querySelector('.border-red-500');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        setTimeout(function() {
                            if (typeof firstError.focus === 'function') {
                                firstError.focus();
                            }
                        }, 400);
                    }
                }, 300); // Wait for DOM update and step  change
            });

            // Enhanced scroll: if field is in another step, instruct server to switch first then focus
            function getStepForFieldKey(key) {
                if (!key) return 1;
                if (key.startsWith('vaccine_rows') || key === 'icd_code' || key === 'diagnosis_name') return 2;
                return 1;
            }

            function scrollToFirstError(errors) {
                if (!errors) return;
                var keys = Object.keys(errors);
                for (var i = 0; i < keys.length; i++) {
                    (function(k) {
                        var step = getStepForFieldKey(k);

                        // Ask server to set current step so DOM updates
                        try {
                            Livewire.emit('setCurrentStep', step);
                        } catch (err) {
                            console.warn('Could not emit setCurrentStep', err);
                        }

                        // Wait briefly for DOM to update after step switch
                        setTimeout(function() {
                            var el = document.querySelector('[wire\\:model="' + k + '"]');

                            if (!el) {
                                var id = k.replace(/\./g, '_');
                                el = document.getElementById(id);
                            }

                            if (!el) {
                                var short = k.split('.').pop();
                                el = document.querySelector('[wire\\:model*=\".' + short + '\"]') ||
                                    document.querySelector('[wire\\:model$="' + short + '"]');
                            }

                            if (el) {
                                try {
                                    el.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });
                                    if (typeof el.focus === 'function') el.focus({
                                        preventScroll: true
                                    });
                                    el.classList.add('ring-2', 'ring-red-500');
                                    setTimeout(function() {
                                        el.classList.remove('ring-2', 'ring-red-500');
                                    }, 3000);
                                } catch (err) {
                                    console.error('scrollToFirstError failed', err);
                                }
                            }
                        }, 320);
                    })(keys[i]);

                    break; // only focus first error
                }
            }

            // Client-side quick checks before submission
            var form = document.getElementById('immunization-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // perform quick validation
                    var weightEl = document.getElementById('weight');
                    var heightEl = document.getElementById('height');
                    var firstInvalid = null;
                    var clientErrorMsg = null;

                    // clear previous client messages
                    ['client-weight-error', 'client-height-error'].forEach(function(id) {
                        var el = document.getElementById(id);
                        if (el) {
                            el.textContent = '';
                            el.classList.add('hidden');
                        }
                    });

                    if (!weightEl || weightEl.value.trim() === '') {
                        clientErrorMsg = 'Berat badan wajib diisi';
                        var c = document.getElementById('client-weight-error');
                        if (c) {
                            c.textContent = clientErrorMsg;
                            c.classList.remove('hidden');
                        }
                        firstInvalid = weightEl || firstInvalid;
                    } else if (parseFloat(weightEl.value) < 1) {
                        clientErrorMsg = 'Berat badan minimal 1 kg';
                        var c = document.getElementById('client-weight-error');
                        if (c) {
                            c.textContent = clientErrorMsg;
                            c.classList.remove('hidden');
                        }
                        firstInvalid = weightEl || firstInvalid;
                    }

                    if (!heightEl || heightEl.value.trim() === '') {
                        clientErrorMsg = clientErrorMsg || 'Tinggi badan wajib diisi';
                        var c2 = document.getElementById('client-height-error');
                        if (c2) {
                            c2.textContent = 'Tinggi badan wajib diisi';
                            c2.classList.remove('hidden');
                        }
                        firstInvalid = heightEl || firstInvalid;
                    } else if (parseFloat(heightEl.value) < 20) {
                        var c2 = document.getElementById('client-height-error');
                        if (c2) {
                            c2.textContent = 'Tinggi badan minimal 20 cm';
                            c2.classList.remove('hidden');
                        }
                        firstInvalid = heightEl || firstInvalid;
                    }

                    if (firstInvalid) {
                        // prevent Livewire submission
                        e.preventDefault();
                        e.stopImmediatePropagation();

                        // show toast
                        try {
                            var toast = document.getElementById('immunization-toast');
                            var inner = document.getElementById('immunization-toast-inner');
                            var msg = document.getElementById('immunization-toast-message');
                            msg.textContent = clientErrorMsg || 'Periksa isian form';
                            toast.classList.remove('hidden');
                            inner.classList.remove('bg-green-600');
                            inner.classList.add('bg-red-600');
                            setTimeout(function() {
                                toast.classList.add('hidden');
                            }, 3500);
                        } catch (err) {
                            console.error('Error showing client error toast', err);
                        }

                        try {
                            firstInvalid.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            firstInvalid.focus();
                        } catch (err) {
                            console.error('Error focusing first invalid', err);
                        }

                        return false;
                    }

                    // allow submission to continue
                }, true);
            }
        });
    </script>
</div>
