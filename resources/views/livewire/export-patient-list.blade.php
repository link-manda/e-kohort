<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Export Data Pasien
        </h2>
        <p class="text-sm text-gray-500 mt-1">Export data master pasien ke format Excel</p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Export Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="space-y-6">
            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Registrasi Mulai
                    </label>
                    <input type="date" wire:model="dateFrom"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('dateFrom')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Registrasi Sampai
                    </label>
                    <input type="date" wire:model="dateTo"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('dateTo')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Pregnancy Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status Kehamilan Terakhir
                </label>
                <select wire:model="pregnancyStatus"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="selesai">Selesai</option>
                    <option value="keguguran">Keguguran</option>
                </select>
            </div>

            <!-- Active Pregnancy Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Memiliki Kehamilan Aktif
                </label>
                <select wire:model="hasActivePregnancy"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">Semua</option>
                    <option value="yes">Ya (Sedang Hamil)</option>
                    <option value="no">Tidak (Tidak Hamil)</option>
                </select>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Data yang akan di-export:</p>
                        <ul class="list-disc list-inside space-y-1 text-blue-700">
                            <li>Data demografis pasien (NIK, No RM, No KK, No BPJS)</li>
                            <li>Informasi kontak (Alamat, No HP)</li>
                            <li>Data suami</li>
                            <li>Status kehamilan terakhir</li>
                            <li>Tanggal registrasi</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('patients.index') }}"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button wire:click="export" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                    <svg wire:loading.remove wire:target="export" class="w-5 h-5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <svg wire:loading wire:target="export" class="animate-spin h-5 w-5" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span wire:loading.remove wire:target="export">Export ke Excel (.xlsx)</span>
                    <span wire:loading wire:target="export">Memproses...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Column Preview -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Preview Kolom Export (21 Kolom)
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                No Urut
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                No RM
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                NIK
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                No KK
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                No BPJS
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                Nama Lengkap
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                Tempat Lahir
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                Tanggal Lahir
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                Umur
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                Pendidikan
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                Pekerjaan
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                Golongan Darah
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                Alamat
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                No HP
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                Nama Suami
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                NIK Suami
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                Pendidikan Suami
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                Pekerjaan Suami
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                Golda Suami
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                Status Kehamilan
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                Tanggal Registrasi
            </div>
        </div>
    </div>
</div>
