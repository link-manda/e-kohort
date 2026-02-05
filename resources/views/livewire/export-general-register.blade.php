<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Export Register Poli Umum
        </h2>
        <p class="text-sm text-gray-500 mt-1">Unduh laporan register kunjungan poli umum dalam format Excel</p>
    </div>

    <!-- Export Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="dateFrom" class="block text-sm font-medium text-gray-700 mb-2">
                        Dari Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model="dateFrom" id="dateFrom"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="dateTo" class="block text-sm font-medium text-gray-700 mb-2">
                        Sampai Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model="dateTo" id="dateTo"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Informasi Export</p>
                        <ul class="list-disc list-inside space-y-1 text-blue-700">
                            <li>File akan mencakup semua data kunjungan poli umum sesuai rentang tanggal</li>
                            <li>Format Excel (.xlsx)</li>
                            <li>Nama file otomatis dengan timestamp</li>
                            <li>Data termasuk informasi pasien dan keluhan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('dashboard') }}"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="button" wire:click="export"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export Excel
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
            Kolom yang Akan Di-export
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm text-gray-700">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                Data Identitas Pasien
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                Tanggal Kunjungan
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                Keluhan & Diagnosa
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                Tindakan
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                Obat yang Diberikan
            </div>
        </div>
    </div>
</div>
