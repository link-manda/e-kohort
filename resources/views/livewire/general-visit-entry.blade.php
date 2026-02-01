<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Header: Patient Identity -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Poli Umum - Input Kunjungan</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ $patient->name }} | {{ $patient->gender == 'L' ? 'Laki-laki' : 'Perempuan' }} | {{ $patient->age }} Tahun | RM: {{ $patient->no_rm }}
                </p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium {{ $payment_method == 'BPJS' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $payment_method }}
                </span>
            </div>
        </div>

        <div class="px-4 py-5 sm:p-6 space-y-6">
            <!-- Visit Date & Payment -->
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="visit_date" class="block text-sm font-medium text-gray-700">Tanggal Kunjungan</label>
                    <div class="mt-1">
                        <input type="datetime-local" wire:model="visit_date" id="visit_date" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    @error('visit_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                    <div class="mt-1">
                        <select id="payment_method" wire:model="payment_method" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="Umum">Umum / Tunai</option>
                            <option value="BPJS">BPJS Kesehatan</option>
                        </select>
                    </div>
                    @error('payment_method') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">1. Tanda Vital (Objective)</h4>
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <label for="systolic" class="block text-sm font-medium text-gray-700">Tekanan Darah (mmHg)</label>
                        <div class="mt-1 flex items-center space-x-2">
                            <input type="number" wire:model="systolic" placeholder="Sistole" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <span class="text-gray-500">/</span>
                            <input type="number" wire:model="diastolic" placeholder="Diastole" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="temperature" class="block text-sm font-medium text-gray-700">Suhu (°C)</label>
                        <div class="mt-1">
                            <input type="number" step="0.1" wire:model="temperature" id="temperature" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="weight" class="block text-sm font-medium text-gray-700">Berat (kg)</label>
                        <div class="mt-1">
                            <input type="number" step="0.1" wire:model="weight" id="weight" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="height" class="block text-sm font-medium text-gray-700">Tinggi (cm)</label>
                        <div class="mt-1">
                            <input type="number" step="0.1" wire:model="height" id="height" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">2. Anamnesa & Pemeriksaan Fisik</h4>
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="complaint" class="block text-sm font-medium text-gray-700">Keluhan Utama (Subjective)</label>
                        <div class="mt-1">
                            <textarea id="complaint" wire:model="complaint" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Contoh: Demam sejak 2 hari lalu, pusing, mual..."></textarea>
                        </div>
                        @error('complaint') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="physical_exam" class="block text-sm font-medium text-gray-700">Pemeriksaan Fisik (Objective)</label>
                        <div class="mt-1">
                            <textarea id="physical_exam" wire:model="physical_exam" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Contoh: Kepala normocephal, mata konjungtiva anemis (-/-), thorax..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">3. Diagnosa & Terapi</h4>
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                     <div class="sm:col-span-3">
                        <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnosa (Assessment)</label>
                        <div class="mt-1">
                            <input type="text" wire:model="diagnosis" id="diagnosis" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('diagnosis') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3 relative">
                        <label for="icd_search" class="block text-sm font-medium text-gray-700">Kode ICD-10 (Optional)</label>
                        <div class="mt-1">
                            <input type="text" wire:model.live.debounce.300ms="icd_search" id="icd_search" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Ketik Kode atau Nama Penyakit...">
                        </div>
                         @if($show_icd_dropdown)
                            <div class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                @foreach($icd_results as $icd)
                                    <div wire:click="selectIcd10('{{ $icd['code'] }}', '{{ $icd['name'] }}')" class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-100">
                                        <div class="flex items-center">
                                            <span class="font-semibold block truncate text-blue-800 w-16">{{ $icd['code'] }}</span>
                                            <span class="font-normal block truncate ml-2">{{ $icd['name'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if($icd10_code)
                            <p class="mt-1 text-sm text-green-600">Terpilih: {{ $icd10_code }}</p>
                        @endif
                    </div>

                    <div class="sm:col-span-6">
                        <label for="therapy" class="block text-sm font-medium text-gray-700">Terapi / Tindakan / Resep (Plan)</label>
                        <div class="mt-1">
                            <textarea id="therapy" wire:model="therapy" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Tuliskan resep obat, edukasi, atau tindakan yang dilakukan..."></textarea>
                        </div>
                        @error('therapy') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status Keluar</label>
                        <div class="mt-1">
                            <select id="status" wire:model="status" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="Pulang">Pulang (Sembuh/Berobat Jalan)</option>
                                <option value="Rujuk">Rujuk ke RS</option>
                                <option value="Rawat Inap">Rawat Inap</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <a href="{{ route('patients.show', $patient->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                Batal
            </a>
            <button wire:click="save" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Simpan Kunjungan
            </button>
        </div>
    </div>
</div>
