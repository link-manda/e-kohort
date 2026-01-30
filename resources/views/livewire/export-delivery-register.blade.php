<div class="container mx-auto p-6 max-w-4xl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Export Register Persalinan</h1>
        <p class="text-gray-600 mt-1">Download Register Persalinan dalam format Excel sesuai standar Register Bidan</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form wire:submit.prevent="export">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Month Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulan *</label>
                    <select wire:model="month" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                    @error('month')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun *</label>
                    <select wire:model="year" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                        @for ($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                    @error('year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Statistics Preview -->
            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-800 mb-2">Laporan akan mencakup:</h3>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>✓ Data persalinan bulan
                        {{ \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}</li>
                    <li>✓ Format: Excel (.xlsx) dengan header bertingkat (27 kolom)</li>
                    <li>✓ Data Ibu (5 field): Nama, NIK, TTL, Umur, HP</li>
                    <li>✓ Data Suami (5 field): Nama, NIK, TTL, Umur, HP</li>
                    <li>✓ Informasi Persalinan: Tanggal/Jam, Jenis, Penyulit, Episiotomi</li>
                    <li>✓ Manajemen Aktif Kala 3 (AMTSL): Oksitosin, PTT, Masase Uterus</li>
                    <li>✓ Pemantauan 2 Jam Post Partum & Keterangan Bayi</li>
                </ul>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="window.location.href='{{ route('dashboard') }}'"
                    class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Kembali
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:target="export"
                    class="px-6 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 transition shadow-sm flex items-center disabled:opacity-50">
                    <svg wire:loading.remove wire:target="export" class="w-5 h-5 mr-2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <svg wire:loading wire:target="export" class="animate-spin h-5 w-5 mr-2 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span wire:loading.remove wire:target="export">Download Excel</span>
                    <span wire:loading wire:target="export">Memproses...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Help Section -->
    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
        <h3 class="font-semibold text-gray-800 mb-2">📋 Informasi Register:</h3>
        <ul class="text-sm text-gray-700 space-y-1">
            <li>• <strong>Header Bertingkat:</strong> IBU, SUAMI, dan MANAJEMEN AKTIF KALA 3 memiliki sub-kolom</li>
            <li>• <strong>Total 27 Kolom:</strong> NO, Tanggal, NO KK, + 5 kolom Ibu + 5 kolom Suami + Data Persalinan +
                AMTSL + Pemantauan</li>
            <li>• <strong>AMTSL (Manajemen Aktif Kala Tiga):</strong> Checklist Oksitosin, PTT, dan Masase Uterus</li>
            <li>• <strong>Standar Register Bidan:</strong> Format sesuai dengan buku register persalinan nasional</li>
            <li>• <strong>File Name:</strong> Register_Persalinan_[Bulan]_[Tahun].xlsx</li>
        </ul>
    </div>
</div>
