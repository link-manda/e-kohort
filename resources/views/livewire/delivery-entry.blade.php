<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('patients.show', $pregnancy->patient_id) }}"
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">📝 Data Persalinan</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $pregnancy->patient->name }} - {{ $pregnancy->gravida }}</p>
            </div>
        </div>
    </div>

    @if ($showSuccess)
        <!-- Success Message -->
        <div class="bg-green-50 border-2 border-green-500 rounded-xl p-6 mb-6 text-center" x-data
            x-init="setTimeout(() => window.location.href = '{{ route('patients.show', $pregnancy->patient_id) }}', 2000)">
            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-green-900 mb-2">Data Persalinan Tersimpan!</h3>
            <p class="text-green-700 mb-4">Status kehamilan telah diupdate ke "Lahir"</p>
            <p class="text-sm text-green-600">Anda dapat melanjutkan ke kunjungan nifas...</p>
        </div>
    @else
        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <form wire:submit.prevent="save">
                <div class="space-y-6">
                    <!-- Alert Info -->
                    <div class="bg-blue-50 border-2 border-blue-500 rounded-xl p-4 flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-blue-900">Informasi Penting</p>
                            <p class="text-sm text-blue-700 mt-1">
                                Form ini digunakan untuk mencatat data persalinan dan menutup status kehamilan.
                                Setelah data tersimpan, status kehamilan akan berubah menjadi <strong>"Lahir"</strong>.
                            </p>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Waktu Persalinan
                    </h3>

                    <!-- Tanggal dan Waktu Lahir -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" wire:model="delivery_date"
                                class="w-full px-4 py-3 border-2 rounded-lg
                                      @error('delivery_date') border-red-500 @else border-gray-300 @enderror
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                            @error('delivery_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Waktu Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="time" wire:model="delivery_time"
                                class="w-full px-4 py-3 border-2 rounded-lg
                                      @error('delivery_time') border-red-500 @else border-gray-300 @enderror
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                            @error('delivery_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Cara Persalinan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Cara Persalinan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label
                                class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all
                                         {{ $delivery_method === 'Normal' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-blue-300' }}">
                                <input type="radio" wire:model="delivery_method" value="Normal"
                                    class="w-5 h-5 text-blue-600">
                                <span class="font-medium">Normal</span>
                            </label>
                            <label
                                class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all
                                         {{ $delivery_method === 'Caesar/Sectio' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-blue-300' }}">
                                <input type="radio" wire:model="delivery_method" value="Caesar/Sectio"
                                    class="w-5 h-5 text-blue-600">
                                <span class="font-medium">Caesar/SC</span>
                            </label>
                            <label
                                class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all
                                         {{ $delivery_method === 'Vakum' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-blue-300' }}">
                                <input type="radio" wire:model="delivery_method" value="Vakum"
                                    class="w-5 h-5 text-blue-600">
                                <span class="font-medium">Vakum</span>
                            </label>
                        </div>
                        @error('delivery_method')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penolong Persalinan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Penolong Persalinan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="birth_attendant"
                            class="w-full px-4 py-3 border-2 rounded-lg
                                  @error('birth_attendant') border-red-500 @else border-gray-300 @enderror
                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                            placeholder="Contoh: Bidan Siti atau dr. Ahmad, SpOG">
                        @error('birth_attendant')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="place_of_birth"
                            class="w-full px-4 py-3 border-2 rounded-lg
                                  @error('place_of_birth') border-red-500 @else border-gray-300 @enderror
                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                            placeholder="Contoh: RSUD Wangaya, PMB Bidan Ani, atau Rumah">
                        @error('place_of_birth')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kondisi Bayi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Kondisi Bayi <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label
                                class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all
                                         {{ $outcome === 'Hidup' ? 'border-green-500 bg-green-50' : 'border-gray-300 hover:border-green-300' }}">
                                <input type="radio" wire:model.live="outcome" value="Hidup"
                                    class="w-5 h-5 text-green-600">
                                <span class="font-medium">Hidup</span>
                            </label>
                            <label
                                class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all
                                         {{ $outcome === 'Meninggal' ? 'border-red-500 bg-red-50' : 'border-gray-300 hover:border-red-300' }}">
                                <input type="radio" wire:model.live="outcome" value="Meninggal"
                                    class="w-5 h-5 text-red-600">
                                <span class="font-medium">Meninggal</span>
                            </label>
                            <label
                                class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all
                                         {{ $outcome === 'Abortus' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300 hover:border-yellow-300' }}">
                                <input type="radio" wire:model.live="outcome" value="Abortus"
                                    class="w-5 h-5 text-yellow-600">
                                <span class="font-medium">Abortus</span>
                            </label>
                        </div>
                        @error('outcome')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin (only if outcome is Hidup) -->
                    @if ($outcome === 'Hidup')
                        <div x-data x-init="$el.scrollIntoView({ behavior: 'smooth', block: 'center' })">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jenis Kelamin Bayi <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label
                                    class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all
                                             {{ $baby_gender === 'L' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-blue-300' }}">
                                    <input type="radio" wire:model="baby_gender" value="L"
                                        class="w-5 h-5 text-blue-600">
                                    <span class="font-medium">👦 Laki-laki</span>
                                </label>
                                <label
                                    class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all
                                             {{ $baby_gender === 'P' ? 'border-pink-500 bg-pink-50' : 'border-gray-300 hover:border-pink-300' }}">
                                    <input type="radio" wire:model="baby_gender" value="P"
                                        class="w-5 h-5 text-pink-600">
                                    <span class="font-medium">👧 Perempuan</span>
                                </label>
                            </div>
                            @error('baby_gender')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Komplikasi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Komplikasi Persalinan (Opsional)
                        </label>
                        <textarea wire:model="complications" rows="3"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg
                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                            placeholder="Tuliskan jika ada komplikasi seperti pendarahan, preeklampsia, dll."></textarea>
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada komplikasi</p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 pt-6 border-t">
                        <a href="{{ route('patients.show', $pregnancy->patient_id) }}"
                            class="px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold text-gray-700
                                  hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold
                                   hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Data Persalinan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>
