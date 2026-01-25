<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
                <div
                    class="bg-gradient-to-r from-emerald-500 via-green-600 to-teal-600 px-6 py-5 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black opacity-5"></div>
                    <div class="relative z-10">
                        <h1 class="text-2xl font-bold text-white flex items-center drop-shadow-lg">
                            <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            KMS Digital - Pencatatan Pertumbuhan Anak
                        </h1>
                        <p class="text-emerald-50 text-sm mt-1 ml-10">Sistem Monitoring Kesehatan dan Gizi Anak</p>
                    </div>
                </div>
                <div class="p-6 bg-gradient-to-br from-gray-50 to-white">
                    <!-- Identity Card -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Informasi Anak
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center">
                                    <span class="w-32 text-gray-600 font-medium">No. RM</span>
                                    <span class="font-semibold text-gray-800">: {{ $child->no_rm }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-32 text-gray-600 font-medium">Nama Anak</span>
                                    <span class="font-semibold text-gray-800">: {{ $child->name }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-32 text-gray-600 font-medium">NIK</span>
                                    <span class="text-gray-700">: {{ $child->nik }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-32 text-gray-600 font-medium">Jenis Kelamin</span>
                                    <span class="text-gray-700">:
                                        {{ $child->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-32 text-gray-600 font-medium">Tanggal Lahir</span>
                                    <span class="text-gray-700">: {{ $child->dob->format('d-m-Y') }}</span>
                                </div>
                                <div class="flex items-center bg-blue-50 -mx-2 px-2 py-2 rounded">
                                    <span class="w-32 text-gray-600 font-medium">Umur Sekarang</span>
                                    <span class="font-bold text-blue-600">: {{ $child->formatted_age }}</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Informasi Kelahiran
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex">
                                    <span class="w-36 text-gray-600">Berat Lahir</span>
                                    <span>: {{ $child->birth_weight ?? '-' }} kg</span>
                                </div>
                                <div class="flex">
                                    <span class="w-36 text-gray-600">Panjang Lahir</span>
                                    <span>: {{ $child->birth_height ?? '-' }} cm</span>
                                </div>
                                <div class="flex">
                                    <span class="w-36 text-gray-600">Tempat Lahir</span>
                                    <span>: {{ $child->pob ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-300">

                    <!-- Main Content: Form + Status -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column: Input Form (2/3 width) -->
                        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Input Data Pengukuran
                            </h3>

                            <form wire:submit.prevent="save" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Tanggal Pengukuran <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            wire:model.live.debounce.500ms="recordDate">
                                        @error('recordDate')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Berat Badan (kg) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" step="0.01"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            wire:model.live.debounce.500ms="weight" placeholder="Contoh: 8.5">
                                        @error('weight')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Tinggi/Panjang Badan (cm) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" step="0.1"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            wire:model.live.debounce.500ms="height" placeholder="Contoh: 72.5">
                                        @error('height')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Metode Pengukuran <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            wire:model.live="measurementMethod">
                                            <option value="Terlentang">Terlentang (&lt; 2 tahun)</option>
                                            <option value="Berdiri">Berdiri (≥ 2 tahun)</option>
                                        </select>
                                        @if ($heightCorrectionApplied)
                                            <p class="text-blue-600 text-xs mt-1">
                                                <svg class="w-3 h-3 inline" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Tinggi terkoreksi: {{ number_format($correctedHeight, 1) }} cm
                                            </p>
                                        @endif
                                        @error('measurementMethod')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Lingkar Kepala (cm)
                                        </label>
                                        <input type="number" step="0.1"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            wire:model="headCircumference" placeholder="Contoh: 45.5">
                                        @error('headCircumference')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4 border-gray-200">

                                <h4 class="text-md font-semibold text-gray-700 mb-3">Intervensi & Catatan</h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Vitamin A</label>
                                        <select
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            wire:model="vitaminA">
                                            <option value="Tidak">Tidak Diberikan</option>
                                            <option value="Biru (6-11 bln)">Kapsul Biru (6-11 bulan) - 100.000 IU
                                            </option>
                                            <option value="Merah (1-5 thn)">Kapsul Merah (1-5 tahun) - 200.000 IU
                                            </option>
                                        </select>
                                    </div>

                                    <div class="md:col-span-2 space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox"
                                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                                wire:model="dewormingMedicine">
                                            <span class="ml-2 text-sm text-gray-700">Diberikan Obat Cacing</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox"
                                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                                wire:model="pmtGiven">
                                            <span class="ml-2 text-sm text-gray-700">Diberikan PMT (Pemberian Makanan
                                                Tambahan)</span>
                                        </label>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                        <textarea
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            rows="3" wire:model="notes" placeholder="Catatan tambahan..."></textarea>
                                        @error('notes')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Nama Bidan/Petugas <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            wire:model="midwifeName">
                                        @error('midwifeName')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex gap-3 pt-4">
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Simpan Data
                                    </button>
                                    <button type="button"
                                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition"
                                        onclick="window.history.back()">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                        Kembali
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="lg:col-span-1">
                            <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Status Gizi Real-time
                            </h3>

                            <div class="relative min-h-[200px]">
                                <!-- Loading Overlay -->
                                <div wire:loading wire:target="calculateRealtime, weight, height, recordDate"
                                    class="absolute inset-0 bg-white bg-opacity-80 z-20 flex items-center justify-center rounded-lg backdrop-blur-sm transition-all duration-300">
                                    <div class="text-center p-4 bg-white rounded-xl shadow-lg border border-blue-100">
                                        <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto mb-2"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <p class="text-xs font-bold text-blue-600 animate-pulse">Mengkalkulasi...</p>
                                    </div>
                                </div>

                                <!-- PERUBAHAN: Tampilkan card meskipun belum ada kalkulasi (untuk estetika), tapi dengan status default -->
                                <div class="space-y-4">
                                    @if ($ageInMonths > 12 && ($statusBBU === null || $statusTBU === null))
                                        <div
                                            class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-lg shadow-sm mb-4">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-amber-600 mr-2 flex-shrink-0 mt-0.5"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-bold text-amber-800 mb-1">Perhatian: Data
                                                        WHO Terbatas</h4>
                                                    <p class="text-xs text-amber-700">Anak berusia
                                                        <strong>{{ $ageInMonths }} bulan</strong>. Sistem saat ini
                                                        hanya memiliki data WHO Standards untuk umur <strong>0-12
                                                            bulan</strong>. Beberapa indikator mungkin tidak tersedia.
                                                        Data yang tersedia tetap dapat disimpan.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- BB/U Status -->
                                    <div class="status-card {{ $this->getStatusColorClass($zscoreBBU) }} p-5 rounded-xl text-white shadow-lg border border-white/20"
                                        title="{{ $statusBBU ?? 'Data WHO tidak tersedia' }}{{ $zscoreBBU !== null ? ' — Z: ' . number_format($zscoreBBU, 2) . ' SD' : '' }}">
                                        <h4
                                            class="text-xs font-bold mb-2 uppercase tracking-wide opacity-90 border-b border-white/20 pb-1">
                                            BB/U (Berat/Umur)</h4>
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <div class="text-2xl font-bold drop-shadow-md">
                                                    @if ($statusBBU)
                                                        {{ $statusBBU }}
                                                    @else
                                                        <span class="text-lg font-semibold text-white/95">Data WHO
                                                            tidak tersedia</span>
                                                    @endif
                                                </div>
                                                @if ($zscoreBBU !== null)
                                                    <p
                                                        class="text-xs bg-black/20 px-2 py-1 rounded inline-block mt-1 font-mono">
                                                        Z: {{ number_format($zscoreBBU, 2) }} SD</p>
                                                @else
                                                    <p class="text-xs opacity-75 mt-1 italic">Isi berat & tinggi</p>
                                                @endif
                                            </div>
                                            <!-- Icon dinamis berdasarkan status -->
                                            <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TB/U Status -->
                                    <div class="status-card {{ $this->getStatusColorClass($zscoreTBU) }} p-5 rounded-xl text-white shadow-lg border border-white/20"
                                        title="{{ $statusTBU ?? 'Data WHO tidak tersedia' }}{{ $zscoreTBU !== null ? ' — Z: ' . number_format($zscoreTBU, 2) . ' SD' : '' }}">
                                        <h4
                                            class="text-xs font-bold mb-2 uppercase tracking-wide opacity-90 border-b border-white/20 pb-1">
                                            TB/U (Tinggi/Umur)</h4>
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <div class="text-2xl font-bold drop-shadow-md">
                                                    @if ($statusTBU)
                                                        {{ $statusTBU }}
                                                    @else
                                                        <span class="text-lg font-semibold text-white/95">Data WHO
                                                            tidak tersedia</span>
                                                    @endif
                                                </div>
                                                @if ($zscoreTBU !== null)
                                                    <p
                                                        class="text-xs bg-black/20 px-2 py-1 rounded inline-block mt-1 font-mono">
                                                        Z: {{ number_format($zscoreTBU, 2) }} SD</p>
                                                @else
                                                    <p class="text-xs opacity-75 mt-1 italic">Isi berat & tinggi</p>
                                                @endif
                                            </div>
                                            <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- BB/TB Status -->
                                    <div class="status-card {{ $this->getStatusColorClass($zscoreBBTB) }} p-5 rounded-xl text-white shadow-lg border border-white/20"
                                        title="{{ $statusBBTB ?? 'Data WHO tidak tersedia' }}{{ $zscoreBBTB !== null ? ' — Z: ' . number_format($zscoreBBTB, 2) . ' SD' : '' }}">
                                        <h4
                                            class="text-xs font-bold mb-2 uppercase tracking-wide opacity-90 border-b border-white/20 pb-1">
                                            BB/TB (Berat/Tinggi)</h4>
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <div class="text-2xl font-bold drop-shadow-md">
                                                    @if ($statusBBTB)
                                                        {{ $statusBBTB }}
                                                    @else
                                                        <span class="text-lg font-semibold text-white/95">Data WHO
                                                            tidak tersedia</span>
                                                    @endif
                                                </div>
                                                @if ($zscoreBBTB !== null)
                                                    <p
                                                        class="text-xs bg-black/20 px-2 py-1 rounded inline-block mt-1 font-mono">
                                                        Z: {{ number_format($zscoreBBTB, 2) }} SD</p>
                                                @else
                                                    <p class="text-xs opacity-75 mt-1 italic">Isi berat & tinggi</p>
                                                @endif
                                            </div>
                                            <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tailwind utilities included to ensure gradient background classes are compiled -->
                                <div class="hidden" aria-hidden="true">
                                    <span class="bg-gradient-to-br from-slate-600 to-slate-800"></span>
                                    <span class="bg-gradient-to-br from-red-700 to-red-900"></span>
                                    <span class="bg-gradient-to-br from-orange-500 to-red-600"></span>
                                    <span class="bg-gradient-to-br from-yellow-500 to-orange-600"></span>
                                    <span class="bg-gradient-to-br from-emerald-500 to-green-700"></span>
                                    <span class="bg-gradient-to-br from-orange-500 to-red-600"></span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200">

                    <!-- Growth Chart -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Grafik Pertumbuhan</h3>
                        <!-- PERBAIKAN: Menambahkan wire:ignore agar chart tidak hilang saat livewire update -->
                        <div wire:ignore>
                            <div id="growthChart" class="bg-white rounded-lg shadow p-4" style="height: 400px;">
                            </div>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200">

                    <!-- History Table -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Riwayat Pertumbuhan (10 Terakhir)</h3>
                        <div class="overflow-x-auto bg-white rounded-lg shadow">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Umur (bulan)</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            BB (kg)</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            TB (cm)</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            BB/U</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            TB/U</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            BB/TB</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Petugas</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($growthHistory as $record)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $record->record_date->format('d-m-Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $record->age_in_months }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($record->weight, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($record->height, 1) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    title="{{ $record->status_bb_u ?? 'Data WHO tidak tersedia' }}{{ $record->zscore_bb_u !== null ? ' — Z: ' . number_format($record->zscore_bb_u, 2) . ' SD' : '' }}"
                                                    class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full {{ $this->getStatusColorClass($record->zscore_bb_u) }} text-white">
                                                    {{ $record->status_bb_u ?? 'Data WHO tidak tersedia' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    title="{{ $record->status_tb_u ?? 'Data WHO tidak tersedia' }}{{ $record->zscore_tb_u !== null ? ' — Z: ' . number_format($record->zscore_tb_u, 2) . ' SD' : '' }}"
                                                    class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full {{ $this->getStatusColorClass($record->zscore_tb_u) }} text-white">
                                                    {{ $record->status_tb_u ?? 'Data WHO tidak tersedia' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    title="{{ $record->status_bb_tb ?? 'Data WHO tidak tersedia' }}{{ $record->zscore_bb_tb !== null ? ' — Z: ' . number_format($record->zscore_bb_tb, 2) . ' SD' : '' }}"
                                                    class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full {{ $this->getStatusColorClass($record->zscore_bb_tb) }} text-white">
                                                    {{ $record->status_bb_tb ?? 'Data WHO tidak tersedia' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $record->midwife_name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                                Belum ada riwayat pertumbuhan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 animate-fade-in">
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 z-50 animate-fade-in">
            <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <style>
        .status-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .status-card:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize ApexCharts
            let chartOptions = {
                series: [{
                    name: 'Berat Badan (kg)',
                    data: []
                }, {
                    name: 'Tinggi Badan (cm)',
                    data: []
                }],
                chart: {
                    type: 'line',
                    height: 400,
                    zoom: {
                        enabled: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                title: {
                    text: 'Grafik Pertumbuhan Anak',
                    align: 'left'
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0.5
                    },
                },
                xaxis: {
                    type: 'datetime',
                    title: {
                        text: 'Tanggal Pengukuran'
                    }
                },
                yaxis: [{
                    title: {
                        text: 'Berat Badan (kg)'
                    },
                    min: 0
                }, {
                    opposite: true,
                    title: {
                        text: 'Tinggi Badan (cm)'
                    },
                    min: 0
                }],
                legend: {
                    position: 'top'
                }
            };

            let chart = new ApexCharts(document.querySelector("#growthChart"), chartOptions);
            chart.render();

            // Initialize chart with server-provided data (fallback)
            let initialRecords = @json($chartData ?? []);
            console.log('Initial chart records:', initialRecords);
            if (Array.isArray(initialRecords) && initialRecords.length > 0) {
                let weightData = initialRecords.map(r => ({
                    x: new Date(r.record_date).getTime(),
                    y: parseFloat(r.weight)
                }));

                let heightData = initialRecords.map(r => ({
                    x: new Date(r.record_date).getTime(),
                    y: parseFloat(r.height)
                }));

                // Debug logs + basic validation
                console.log('Parsed initial weightData:', weightData);
                console.log('Parsed initial heightData:', heightData);
                const invalidWeight = weightData.some(p => Number.isNaN(p.y) || !isFinite(p.y));
                const invalidHeight = heightData.some(p => Number.isNaN(p.y) || !isFinite(p.y));
                const invalidDate = weightData.concat(heightData).some(p => Number.isNaN(p.x));
                if (invalidWeight || invalidHeight || invalidDate) {
                    console.warn('Initial chart data contains invalid entries', {
                        invalidWeight,
                        invalidHeight,
                        invalidDate,
                        initialRecords
                    });
                }

                chart.updateSeries([{
                        name: 'Berat Badan (kg)',
                        data: weightData
                    },
                    {
                        name: 'Tinggi Badan (cm)',
                        data: heightData
                    }
                ]);
            }

            // Listen to browser events if available
            window.addEventListener('chartUpdated', event => {
                console.log('Chart update event received:', event.detail);
                let records = event.detail[0]?.records || event.detail.records || [];

                let weightData = records.map(r => ({
                    x: new Date(r.record_date).getTime(),
                    y: parseFloat(r.weight)
                }));

                let heightData = records.map(r => ({
                    x: new Date(r.record_date).getTime(),
                    y: parseFloat(r.height)
                }));

                // Debug logs + validation
                console.log('Browser event parsed weightData:', weightData);
                console.log('Browser event parsed heightData:', heightData);
                const invalidWeightB = weightData.some(p => Number.isNaN(p.y) || !isFinite(p.y));
                const invalidHeightB = heightData.some(p => Number.isNaN(p.y) || !isFinite(p.y));
                const invalidDateB = weightData.concat(heightData).some(p => Number.isNaN(p.x));
                if (invalidWeightB || invalidHeightB || invalidDateB) {
                    console.warn('Browser event chart data contains invalid entries', {
                        invalidWeightB,
                        invalidHeightB,
                        invalidDateB,
                        records
                    });
                }

                chart.updateSeries([{
                        name: 'Berat Badan (kg)',
                        data: weightData
                    },
                    {
                        name: 'Tinggi Badan (cm)',
                        data: heightData
                    }
                ]);
            });

            // Also listen to Livewire.on for more reliable event handling (if Livewire is present)
            if (typeof Livewire !== 'undefined' && Livewire.on) {
                Livewire.on('chartUpdated', (data) => {
                    console.log('Livewire chartUpdated:', data);
                    let records = data[0]?.records || data.records || [];

                    let weightData = records.map(r => ({
                        x: new Date(r.record_date).getTime(),
                        y: parseFloat(r.weight)
                    }));

                    let heightData = records.map(r => ({
                        x: new Date(r.record_date).getTime(),
                        y: parseFloat(r.height)
                    }));

                    // Debug logs + validation
                    console.log('Livewire parsed weightData:', weightData);
                    console.log('Livewire parsed heightData:', heightData);
                    const invalidWeightL = weightData.some(p => Number.isNaN(p.y) || !isFinite(p.y));
                    const invalidHeightL = heightData.some(p => Number.isNaN(p.y) || !isFinite(p.y));
                    const invalidDateL = weightData.concat(heightData).some(p => Number.isNaN(p.x));
                    if (invalidWeightL || invalidHeightL || invalidDateL) {
                        console.warn('Livewire chart data contains invalid entries', {
                            invalidWeightL,
                            invalidHeightL,
                            invalidDateL,
                            records
                        });
                    }

                    chart.updateSeries([{
                            name: 'Berat Badan (kg)',
                            data: weightData
                        },
                        {
                            name: 'Tinggi Badan (cm)',
                            data: heightData
                        }
                    ]);
                });
            }
        });
    </script>
@endpush
