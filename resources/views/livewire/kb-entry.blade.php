<div class="container mx-auto p-6 max-w-6xl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Entry KB (Keluarga Berencana)</h1>
        <p class="text-gray-600 mt-1">Form pendaftaran dan kunjungan KB dengan validasi medis</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Section 1: Identitas Pasien & Screening -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-50 px-6 py-3 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Identitas Pasien & Pemeriksaan Awal</h2>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if (!$patient)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Pasien *</label>

                            <div class="relative">
                                <input type="text" wire:model.live.debounce.300ms="patient_search"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Cari nama atau RM...">

                                @if ($patient_search && $patientResults->count())
                                    <div
                                        class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded shadow-lg max-h-60 overflow-auto">
                                        @foreach ($patientResults as $p)
                                            <button type="button" wire:click="selectPatient({{ $p->id }})"
                                                class="w-full text-left px-4 py-2 hover:bg-gray-50 transition-colors">
                                                <div class="text-sm font-medium text-gray-900">{{ $p->name }} <span
                                                        class="text-xs text-gray-500">(RM: {{ $p->no_rm }})</span></div>
                                                <div class="text-xs text-gray-400">{{ $p->phone }}</div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            @error('patient_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if ($patient)
                        <div class="md:col-span-2 bg-gray-50 p-4 rounded-md border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-semibold text-gray-700">Pasien Terpilih:</h3>
                                @if (!request()->query('patient_id'))
                                    <button type="button" wire:click="resetPatientSelection"
                                        class="text-xs text-red-600 hover:text-red-800 font-medium">
                                        Ganti Pasien
                                    </button>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                <div><span class="font-medium">Nama:</span> {{ $patient->name }}</div>
                                <div><span class="font-medium">No RM:</span> {{ $patient->no_rm }}</div>
                                <div><span class="font-medium">NIK:</span> {{ $patient->nik ?? '-' }}</div>
                                <div><span class="font-medium">Telepon:</span> {{ $patient->phone }}</div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kunjungan *</label>
                        <input type="datetime-local" wire:model.live="visit_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        @error('visit_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kunjungan *</label>
                        <select wire:model.live="visit_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            <option value="Peserta Baru">Peserta Baru</option>
                            <option value="Peserta Lama">Peserta Lama</option>
                            <option value="Ganti Cara">Ganti Cara</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pembayaran *</label>
                        <select wire:model="payment_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            <option value="Umum">Umum</option>
                            <option value="BPJS">BPJS</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Berat Badan (kg)</label>
                        <input type="number" step="0.1" wire:model="weight" placeholder="50.5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tekanan Darah (mmHg)</label>
                        <div class="flex items-center space-x-3">
                            <input type="number" wire:model.live="blood_pressure_systolic" placeholder="120"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            <span class="text-gray-500 font-medium">/</span>
                            <input type="number" wire:model.live="blood_pressure_diastolic" placeholder="80"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    @if ($isHypertensive)
                        <div class="md:col-span-2 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-red-700 font-medium">{{ $hypertensionWarning }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Pemeriksaan Fisik</label>
                        <textarea wire:model="physical_exam_notes" rows="2" placeholder="Contoh: Hasil periksa dalam normal (untuk IUD)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Pemilihan Metode Kontrasepsi -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-50 px-6 py-3 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Metode Kontrasepsi</h2>
            </div>

            <div class="p-6">
                @if (count($availableMethods) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($availableMethods as $category => $methods)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                <h3 class="font-semibold text-gray-800 mb-3 pb-2 border-b">{{ $category }}</h3>
                                <div class="space-y-2">
                                    @foreach ($methods as $method)
                                        <label class="flex items-start cursor-pointer hover:bg-gray-50 p-2 rounded">
                                            <input type="radio" wire:model.live="kb_method_id"
                                                value="{{ $method->id }}"
                                                class="mt-1 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-3 text-sm text-gray-700">{{ $method->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>Tidak ada metode kontrasepsi yang tersedia untuk kondisi ini</p>
                    </div>
                @endif

                @error('kb_method_id')
                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if ($selectedMethod)
                    <div class="mt-4 pt-4 border-t">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Merek/Brand (jika ada)</label>
                        <input type="text" wire:model="contraception_brand"
                            placeholder="Contoh: Cyclofem, Triclofem"
                            class="w-full max-w-md px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 3: Tindakan & Diagnosa -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-50 px-6 py-3 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Tindakan Medis & Diagnosa</h2>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode ICD-10</label>

                        <div class="relative" x-data="{ open: @entangle('show_icd_dropdown') }">
                            <div class="relative">
                                <input type="text" wire:model.live="icd_search" @focus="open = true"
                                    class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                    placeholder="Ketik kode atau nama ICD-10 (mis. Z30.5, Kontrasepsi)">

                                @if ($icd_code)
                                    <button type="button" wire:click="clearIcd10"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>

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
                                                    <p class="font-semibold text-gray-800 text-sm">
                                                        {{ $result['name'] }}</p>
                                                    <p class="text-xs text-gray-600 mt-1">{{ $result['description'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                            @if ($icd_code)
                                <div class="mt-3 p-3 bg-green-50 border border-green-300 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs text-green-600 font-semibold">Kode Dipilih:</p>
                                            <p class="font-bold text-green-800">{{ $icd_code }}</p>
                                            <p class="text-sm text-gray-700 mt-1">{{ $diagnosis }}</p>
                                        </div>
                                        <button type="button" wire:click="clearIcd10"
                                            class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosa</label>
                        <input type="text" wire:model="diagnosis" placeholder="Pemilihan kontrasepsi"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bidan *</label>
                        <input type="text" wire:model="midwife_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        @error('midwife_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali (Otomatis)</label>
                        <input type="date" wire:model="next_visit_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                        <p class="mt-1 text-xs text-gray-500">Dihitung otomatis, dapat diubah manual</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keluhan/Efek Samping</label>
                        <textarea wire:model="side_effects" rows="2" placeholder="Jika ada keluhan atau efek samping"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Komplikasi</label>
                        <textarea wire:model="complications" rows="2" placeholder="Jika ada komplikasi"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <!-- Biaya Layanan -->
                    <div class="md:col-span-2 border-t pt-4 mt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Biaya Layanan (Rp)
                        </label>
                        <input type="number" wire:model="service_fee" min="0" step="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                            placeholder="Biaya jasa pelayanan KB">
                        @error('service_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if ($selectedMethod && in_array($selectedMethod->category, ['IUD', 'IMPLANT']))
                    <div class="mt-4 pt-4 border-t">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="informed_consent"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-gray-700">
                                ☑ Informed Consent ditandatangani
                                <span class="text-red-600">*</span>
                            </span>
                        </label>
                        @error('informed_consent')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 ml-7 text-xs text-gray-500">Wajib untuk metode invasif (IUD/Implant)</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
            <button type="button" wire:click="resetForm"
                class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                Reset
            </button>
            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm">
                Simpan Data KB
            </button>
        </div>
    </form>
</div>
