<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Export Imunisasi
        </h2>
        <p class="text-sm text-gray-500 mt-1">Export data imunisasi (laporan bulanan & riwayat individual)</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Monthly Export -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-lg">📅 Export Bulanan</h3>
            <form wire:submit.prevent="exportMonthly">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bulan <span class="text-red-500">*</span></label>
                        <select wire:model="month" class="w-full px-4 py-2 border rounded-lg">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="year" min="2000" max="2100" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-4 text-sm">
                    Export bulanan akan mengeluarkan satu file Excel berisi semua kunjungan imunisasi pada bulan yang dipilih.
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Export Excel</button>
                </div>
            </form>
        </div>

        <!-- Individual Export -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-lg">👤 Export Riwayat Individual</h3>
            <form wire:submit.prevent="exportIndividual">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Anak <span class="text-red-500">*</span></label>
                    <select wire:model="child_id" class="w-full px-4 py-2 border rounded-lg">
                        <option value="">-- Pilih Anak --</option>
                        @foreach ($children as $ch)
                            <option value="{{ $ch->id }}">{{ $ch->name }} — Ibu: {{ $ch->patient->name }}</option>
                        @endforeach
                    </select>
                    @error('child_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-4 text-sm">
                    Export individual akan menghasilkan riwayat semua kunjungan imunisasi untuk anak terpilih.
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Export Excel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 mt-6">
        <h3 class="font-bold text-gray-900 mb-3">Kolom yang akan di-export</h3>
        <p class="text-sm text-gray-700">Laporan bulanan akan berisi kolom sesuai template klien: identitas anak & ibu, BB, PB, Lingkar Kepala, Status Gizi, daftar vaksin (kolom per vaksin), obat yang diberikan dan keterangan.</p>
    </div>
</div>
