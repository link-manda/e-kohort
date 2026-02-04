{{-- TAB 1: ANAMNESA (SUBJECTIVE) --}}
<div class="space-y-6">
    <!-- Visit Date & Payment -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
        <div>
            <label for="visit_date" class="block text-sm font-medium text-gray-700">
                <x-heroicon-o-calendar class="h-4 w-4 inline mr-1" />
                Tanggal & Waktu Kunjungan
            </label>
            <input type="datetime-local" wire:model="visit_date" id="visit_date"
                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            @error('visit_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="payment_method" class="block text-sm font-medium text-gray-700">
                <x-heroicon-o-credit-card class="h-4 w-4 inline mr-1" />
                Metode Pembayaran
            </label>
            <select wire:model.live="payment_method" id="payment_method"
                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                <option value="Umum">💵 Umum / Tunai</option>
                <option value="BPJS">🏥 BPJS Kesehatan</option>
            </select>
        </div>

        <div>
            <label for="consciousness" class="block text-sm font-medium text-gray-700">
                <x-heroicon-o-user class="h-4 w-4 inline mr-1" />
                Kesadaran
            </label>
            <select wire:model="consciousness" id="consciousness"
                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                <option value="Compos Mentis">Compos Mentis (Sadar Penuh)</option>
                <option value="Somnolen">Somnolen (Mengantuk)</option>
                <option value="Sopor">Sopor (Setengah Sadar)</option>
                <option value="Koma">Koma (Tidak Sadar)</option>
            </select>
        </div>
    </div>

    <!-- Emergency Status -->
    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input wire:model.live="is_emergency" id="is_emergency" type="checkbox"
                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
        </div>
        <div class="ml-3 text-sm">
            <label for="is_emergency" class="font-medium text-gray-700">
                Status Gawat Darurat / Emergency
            </label>
            <p class="text-gray-500">Centang jika pasien memerlukan penanganan segera</p>
        </div>
    </div>

    <!-- Keluhan Utama -->
    <div>
        <label for="complaint" class="block text-sm font-medium text-gray-700 mb-1">
            <x-heroicon-o-chat-bubble-bottom-center-text class="h-4 w-4 inline mr-1" />
            Keluhan Utama <span class="text-red-500">*</span>
        </label>
        <textarea wire:model="complaint" id="complaint" rows="4"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            placeholder="Contoh: Demam sejak 3 hari yang lalu disertai batuk dan pilek. Pasien mengeluh sakit kepala dan badan terasa lemas..."></textarea>
        @error('complaint')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Riwayat Kesehatan Card -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
            <x-heroicon-o-exclamation-triangle class="h-5 w-5 mr-2 text-yellow-600" />
            Riwayat Kesehatan
        </h4>

        <div class="space-y-4">
            <div>
                <label for="allergies" class="block text-sm font-medium text-gray-700 mb-1">
                    🚨 Riwayat Alergi (Obat/Makanan/Lainnya)
                </label>
                <input type="text" wire:model.blur="allergies" id="allergies"
                    class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    placeholder="Contoh: Amoxicillin, Seafood, Debu">
                <p class="mt-1 text-xs text-gray-500">Akan muncul sebagai alert merah di header jika diisi</p>
            </div>

            <div>
                <label for="medical_history" class="block text-sm font-medium text-gray-700 mb-1">
                    📋 Riwayat Penyakit Dahulu
                </label>
                <textarea wire:model="medical_history" id="medical_history" rows="2"
                    class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    placeholder="Contoh: Hipertensi sejak 5 tahun lalu, Diabetes Mellitus Tipe 2"></textarea>
            </div>
        </div>
    </div>

    <!-- Gaya Hidup (Skrining PTM) Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
            <x-heroicon-o-heart class="h-5 w-5 mr-2 text-blue-600" />
            Skrining PTM (Penyakit Tidak Menular)
        </h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">🚬 Kebiasaan Merokok</label>
                <div class="space-y-2">
                    <label class="inline-flex items-center mr-4">
                        <input type="radio" wire:model="lifestyle_smoking" value="Tidak"
                            class="form-radio text-blue-600">
                        <span class="ml-2 text-sm">Tidak</span>
                    </label>
                    <label class="inline-flex items-center mr-4">
                        <input type="radio" wire:model="lifestyle_smoking" value="Ya"
                            class="form-radio text-blue-600">
                        <span class="ml-2 text-sm">Ya</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="lifestyle_smoking" value="Jarang"
                            class="form-radio text-blue-600">
                        <span class="ml-2 text-sm">Jarang</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">🍺 Konsumsi Alkohol</label>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="lifestyle_alcohol" id="lifestyle_alcohol"
                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <label for="lifestyle_alcohol" class="ml-2 text-sm text-gray-700">
                        Ya, konsumsi alkohol
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">🏃 Aktivitas Fisik/Olahraga</label>
                <div class="space-y-2">
                    <label class="inline-flex items-center mr-4">
                        <input type="radio" wire:model="lifestyle_activity" value="Aktif"
                            class="form-radio text-blue-600">
                        <span class="ml-2 text-sm">Aktif (≥ 30 menit/hari)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="lifestyle_activity" value="Kurang Olahraga"
                            class="form-radio text-blue-600">
                        <span class="ml-2 text-sm">Kurang Olahraga</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">🍎 Pola Makan</label>
                <select wire:model="lifestyle_diet"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="Sehat">Sehat (Cukup Sayur & Buah)</option>
                    <option value="Kurang Sayur/Buah">Kurang Sayur/Buah</option>
                    <option value="Tinggi Gula/Garam/Lemak">Tinggi Gula/Garam/Lemak</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Navigation Button -->
    <div class="flex justify-end">
        <button @click="activeTab = 'objektif'" type="button"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Lanjut ke Pemeriksaan Objektif
            <x-heroicon-o-arrow-right class="ml-2 h-4 w-4" />
        </button>
    </div>
</div>
