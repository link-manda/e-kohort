{{-- TAB 3: ASSESSMENT & PLAN - Diagnosa & Resep Obat --}}
<div class="space-y-6">
    <!-- Diagnosa Card -->
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-5">
        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-document-text class="h-6 w-6 mr-2 text-orange-600" />
            Diagnosa (Assessment)
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Diagnosa Utama -->
            <div>
                <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-1">
                    Diagnosa Utama <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="diagnosis" id="diagnosis"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500"
                    placeholder="Contoh: ISPA (Infeksi Saluran Pernafasan Akut)">
                @error('diagnosis')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- ICD-10 Code Search -->
            <div class="relative">
                <label for="icd_search" class="block text-sm font-medium text-gray-700 mb-1">
                    Kode ICD-10 (Optional)
                </label>
                <input type="text" wire:model.live.debounce.300ms="icd_search" id="icd_search"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500"
                    placeholder="Ketik kode atau nama penyakit...">

                @if ($show_icd_dropdown)
                    <div
                        class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                        @forelse($icd_results as $icd)
                            <div wire:click="selectIcd10('{{ $icd['code'] }}', '{{ addslashes($icd['name']) }}')"
                                class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-orange-100">
                                <div class="flex items-center">
                                    <span
                                        class="font-semibold block truncate text-orange-800 w-20">{{ $icd['code'] }}</span>
                                    <span
                                        class="font-normal block truncate ml-2 text-gray-700">{{ $icd['name'] }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-2 pl-3 pr-9 text-gray-500 text-sm">Tidak ada hasil</div>
                        @endforelse
                    </div>
                @endif

                @if ($icd10_code)
                    <p class="mt-1 text-sm text-green-600 flex items-center">
                        <x-heroicon-o-check-circle class="h-4 w-4 mr-1" />
                        Terpilih: <span class="font-mono ml-1">{{ $icd10_code }}</span>
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Terapi/Tindakan (Optional) -->
    <div>
        <label for="therapy" class="block text-sm font-medium text-gray-700 mb-1">
            <x-heroicon-o-beaker class="h-4 w-4 inline mr-1" />
            Terapi / Tindakan / Edukasi (Optional)
        </label>
        <textarea wire:model="therapy" id="therapy" rows="3"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            placeholder="Contoh: Istirahat cukup, minum air hangat, kompres dingin jika demam tinggi..."></textarea>
        <p class="mt-1 text-xs text-gray-500">Kosongkan jika hanya memberikan resep obat tanpa tindakan tambahan</p>
    </div>

    <!-- Resep Obat (Prescriptions Repeater) -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-lg p-5">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                <x-heroicon-o-clipboard-document-list class="h-6 w-6 mr-2 text-green-600" />
                Resep Obat ({{ count($prescriptions) }} item)
            </h4>
            <button wire:click="addPrescription" type="button"
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <x-heroicon-o-plus-circle class="h-5 w-5 mr-1" />
                Tambah Obat
            </button>
        </div>

        <div class="space-y-4">
            @foreach ($prescriptions as $index => $prescription)
                <div class="bg-white border border-gray-200 rounded-lg p-4 relative"
                    wire:key="prescription-{{ $index }}">
                    @if (count($prescriptions) > 1)
                        <button wire:click="removePrescription({{ $index }})" type="button"
                            class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                            <x-heroicon-o-x-circle class="h-5 w-5" />
                        </button>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <!-- Nama Obat -->
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Nama Obat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="prescriptions.{{ $index }}.medicine_name"
                                class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="Contoh: Paracetamol">
                            @error("prescriptions.$index.medicine_name")
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dosis (Optional) -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Dosis</label>
                            <input type="text" wire:model="prescriptions.{{ $index }}.dosage"
                                class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="500mg">
                        </div>

                        <!-- Quantity -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="prescriptions.{{ $index }}.quantity"
                                class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="10 tablet">
                            @error("prescriptions.$index.quantity")
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity Number (for calculation) -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Qty (angka) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model.blur="prescriptions.{{ $index }}.quantity_number"
                                class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="10">
                        </div>

                        <!-- Unit Price -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Harga (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model.blur="prescriptions.{{ $index }}.unit_price"
                                class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="5000">
                        </div>

                        <!-- Signa (Aturan Pakai) -->
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Signa (Aturan Pakai) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="prescriptions.{{ $index }}.signa"
                                class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="3 x 1 tablet sesudah makan">
                            @error("prescriptions.$index.signa")
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Frequency -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Frekuensi</label>
                            <input type="text" wire:model="prescriptions.{{ $index }}.frequency"
                                class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="3x sehari">
                        </div>

                        <!-- Duration -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Durasi</label>
                            <input type="text" wire:model="prescriptions.{{ $index }}.duration"
                                class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="5 hari">
                        </div>

                        <!-- Total Harga -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Total</label>
                            <div class="mt-1 p-2 bg-green-100 border border-green-300 rounded-md text-center">
                                <p class="text-sm font-bold text-green-800">
                                    Rp {{ number_format($this->calculatePrescriptionTotal($index), 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Catatan untuk
                            Apoteker/Pasien</label>
                        <textarea wire:model="prescriptions.{{ $index }}.notes" rows="1"
                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            placeholder="Catatan tambahan jika diperlukan..."></textarea>
                    </div>
                </div>
            @endforeach
        </div>

        @if (count($prescriptions) === 0)
            <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <x-heroicon-o-clipboard-document-list class="h-12 w-12 mx-auto text-gray-400" />
                <p class="mt-2 text-sm text-gray-500">Belum ada resep obat</p>
                <button wire:click="addPrescription" type="button"
                    class="mt-2 inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <x-heroicon-o-plus-circle class="h-4 w-4 mr-1" />
                    Tambah Obat Pertama
                </button>
            </div>
        @endif
    </div>

    <!-- Status Keluar & Payment -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                <x-heroicon-o-arrow-right-on-rectangle class="h-4 w-4 inline mr-1" />
                Status Keluar <span class="text-red-500">*</span>
            </label>
            <select wire:model="status" id="status"
                class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                <option value="Pulang">✅ Pulang (Sembuh/Berobat Jalan)</option>
                <option value="Rujuk">🏥 Rujuk ke Rumah Sakit</option>
                <option value="Rawat Inap">🛏️ Rawat Inap</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                💰 Total Biaya Obat
            </label>
            <div
                class="mt-1 p-3 bg-gradient-to-r from-green-100 to-emerald-100 border-2 border-green-400 rounded-md text-center">
                <p class="text-2xl font-bold text-green-700">
                    Rp {{ number_format($this->getTotalPrescriptionCost(), 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between pt-4 border-t border-gray-200">
        <button @click="activeTab = 'objektif'" type="button"
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <x-heroicon-o-arrow-left class="mr-2 h-4 w-4" />
            Kembali ke Pemeriksaan
        </button>
        <button wire:click="save" type="button"
            class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <x-heroicon-o-check-circle class="mr-2 h-5 w-5" />
            Simpan Kunjungan
        </button>
    </div>
</div>
