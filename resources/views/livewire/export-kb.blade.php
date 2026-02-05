<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Export Laporan KB
        </h1>
        <p class="text-sm text-gray-500 mt-1">Download laporan harian KB dalam format Excel</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form wire:submit.prevent="export">
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Date Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="start_date" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="end_date" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('end_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Filters -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kunjungan (Filter)</label>
                        <select wire:model="visit_type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Jenis</option>
                            <option value="Peserta Baru">Peserta Baru</option>
                            <option value="Peserta Lama">Peserta Lama</option>
                            <option value="Ganti Cara">Ganti Cara</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pembayaran (Filter)</label>
                        <select wire:model="payment_type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Pembayaran</option>
                            <option value="Umum">Umum</option>
                            <option value="BPJS">BPJS</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode KB (Filter)</label>
                        <select wire:model="kb_method_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Metode</option>
                            @foreach ($kbMethods->groupBy('category') as $category => $methods)
                                <optgroup label="{{ $category }}">
                                    @foreach ($methods as $method)
                                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Statistics Preview -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Laporan akan mencakup:</h3>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>✓ Data kunjungan KB dari {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} s/d
                            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</li>
                        <li>✓ Filter: {{ $visit_type ?: 'Semua jenis kunjungan' }}</li>
                        <li>✓ Pembayaran: {{ $payment_type ?: 'Umum & BPJS' }}</li>
                        <li>✓ Format: Excel (.xlsx) dengan styling</li>
                    </ul>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="window.location.href='{{ route('dashboard') }}'"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download Excel
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Help Section -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span>💡</span>
            Tips Laporan
        </h3>
        <ul class="text-sm text-gray-700 space-y-1">
            <li>• <strong>Peserta Baru:</strong> Akseptor KB pertama kali</li>
            <li>• <strong>Peserta Lama:</strong> Kunjungan ulang/kontrol</li>
            <li>• <strong>Ganti Cara:</strong> Berganti metode kontrasepsi</li>
            <li>• Filter metode berguna untuk laporan spesifik seperti "Akseptor IUD Silverline"</li>
        </ul>
    </div>
</div>
