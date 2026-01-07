<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('patients.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pendaftaran Pasien Baru</h1>
                <p class="text-sm text-gray-500 mt-1">Lengkapi data pasien untuk mendaftar</p>
            </div>
        </div>
    </div>

    @if ($showSuccess)
        <!-- Success Message -->
        <div class="bg-green-50 border-2 border-green-500 rounded-xl p-6 mb-6 text-center" x-data
            x-init="setTimeout(() => window.location.href = '{{ route('patients.show', $registeredPatientId) }}', 2000)">
            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-green-900 mb-2">Pendaftaran Berhasil!</h3>
            <p class="text-green-700 mb-4">Data pasien telah tersimpan di sistem</p>
            <p class="text-sm text-green-600">Mengalihkan ke halaman detail pasien...</p>
        </div>
    @else
        <!-- Progress Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg
                            {{ $currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                        @if ($currentStep > 1)
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        @else
                            1
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Data Pasien</p>
                        <p class="text-xs text-gray-500">Informasi pribadi</p>
                    </div>
                </div>

                <div class="flex-1 h-1 mx-4 rounded-full {{ $currentStep >= 2 ? 'bg-blue-600' : 'bg-gray-200' }}">
                </div>

                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg
                            {{ $currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                        2
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Data Suami</p>
                        <p class="text-xs text-gray-500">Opsional</p>
                    </div>
                </div>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                    style="width: {{ $progressPercentage }}%"></div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form wire:submit.prevent="submit">

                <!-- Step 1: Patient Personal Data -->
                @if ($currentStep === 1)
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Data Pribadi Pasien
                        </h3>

                        <!-- NIK -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model.live="nik" maxlength="16"
                                class="w-full px-4 py-3 border-2 rounded-lg font-mono text-lg
                                              @error('nik') border-red-500 @else border-gray-300 @enderror
                                              focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                placeholder="1234567890123456">
                            @error('nik')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @else
                                <p class="mt-1 text-xs text-gray-500">16 digit sesuai KTP</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="name"
                                class="w-full px-4 py-3 border-2 rounded-lg
                                              @error('name') border-red-500 @else border-gray-300 @enderror
                                              focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                placeholder="Masukkan nama lengkap sesuai KTP">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- DOB & Blood Type -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model="dob" max="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border-2 rounded-lg
                                                  @error('dob') border-red-500 @else border-gray-300 @enderror
                                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                                @error('dob')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Golongan Darah <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="blood_type"
                                    class="w-full px-4 py-3 border-2 rounded-lg
                                                   @error('blood_type') border-red-500 @else border-gray-300 @enderror
                                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                                    <option value="">Pilih golongan darah</option>
                                    @foreach ($bloodTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('blood_type')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- No KK & No BPJS -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    No. Kartu Keluarga (KK)
                                </label>
                                <input type="text" wire:model="no_kk" maxlength="16"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-mono
                                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                    placeholder="1234567890123456">
                                <p class="mt-1 text-xs text-gray-500">Opsional</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    No. BPJS
                                </label>
                                <input type="text" wire:model="no_bpjs"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-mono
                                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                    placeholder="0001234567890">
                                <p class="mt-1 text-xs text-gray-500">Opsional</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                No. Telepon / WhatsApp
                            </label>
                            <input type="text" wire:model.live="phone"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg
                                              focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                placeholder="08123456789">
                            <p class="mt-1 text-xs text-gray-500">Format: 08xxx atau 628xxx (auto-format)</p>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="address" rows="3"
                                class="w-full px-4 py-3 border-2 rounded-lg
                                                 @error('address') border-red-500 @else border-gray-300 @enderror
                                                 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                placeholder="Jalan, Desa/Kelurahan, Kecamatan, Kabupaten, Provinsi"></textarea>
                            @error('address')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                @endif

                <!-- Step 2: Husband Data -->
                @if ($currentStep === 2)
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Data Suami (Opsional)
                        </h3>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-blue-800 font-medium">Bagian ini opsional</p>
                                    <p class="text-xs text-blue-700 mt-1">Data suami dapat diisi sekarang atau
                                        nanti saat edit. Namun sangat direkomendasikan untuk kelengkapan data ibu
                                        hamil.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Husband Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap Suami
                            </label>
                            <input type="text" wire:model="husband_name"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg
                                              focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                placeholder="Nama lengkap suami">
                        </div>

                        <!-- Husband NIK -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                NIK Suami
                            </label>
                            <input type="text" wire:model="husband_nik" maxlength="16"
                                class="w-full px-4 py-3 border-2 rounded-lg font-mono text-lg
                                              @error('husband_nik') border-red-500 @else border-gray-300 @enderror
                                              focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                placeholder="1234567890123456">
                            @error('husband_nik')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @else
                                <p class="mt-1 text-xs text-gray-500">16 digit sesuai KTP</p>
                            @enderror
                        </div>

                        <!-- Husband Job -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pekerjaan Suami
                            </label>
                            <input type="text" wire:model="husband_job"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg
                                              focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                placeholder="Contoh: Petani, Wiraswasta, PNS, dll">
                        </div>
                    </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                    <button type="button" wire:click="previousStep" @if ($currentStep === 1) disabled @endif
                        class="inline-flex items-center gap-2 px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold text-gray-700
                                       hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Sebelumnya
                    </button>

                    <div class="text-sm text-gray-500">
                        Langkah {{ $currentStep }} dari {{ $totalSteps }}
                    </div>

                    @if ($currentStep < $totalSteps)
                        <button type="button" wire:click="nextStep"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                            Selanjutnya
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    @else
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors disabled:opacity-50">
                            <span wire:loading.remove>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                            <span wire:loading>
                                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <span wire:loading.remove>Daftar Pasien</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    @endif
                </div>
            </form>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        // Auto-redirect after registration
        Livewire.on('patient-registered', (event) => {
            setTimeout(() => {
                window.location.href = '/patients/' + event.patientId;
            }, 2000);
        });
    </script>
@endpush
