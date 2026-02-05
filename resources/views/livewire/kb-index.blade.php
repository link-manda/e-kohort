<div class="space-y-6">
    <livewire:patient-preview />

    <x-breadcrumb :items="[['label' => 'Kunjungan KB']]" />

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Semua Kunjungan KB
            </h1>
            <p class="text-sm text-gray-500 mt-1">Daftar lengkap seluruh kunjungan KB</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div wire:click="$set('')"
            class="bg-white rounded-xl shadow-sm border-2 border-gray-200 p-6 cursor-pointer hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Kunjungan</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div wire:click="$set('')"
            class="bg-white rounded-xl shadow-sm border-2 {{ 'border-red-500' }} p-6 cursor-pointer hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Hipertensi</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['hypertensive'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div wire:click="$set('')"
            class="bg-white rounded-xl shadow-sm border-2 {{ 'border-orange-500' }} p-6 cursor-pointer hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Normal</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['normal'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pasien atau Catatan</label>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    placeholder="Nama pasien, RM, atau diagnosis...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kunjungan</label>
                <select wire:model.live="visit_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500">
                    <option value="">Semua</option>
                    <option>Peserta Baru</option>
                    <option>Peserta Lama</option>
                    <option>Ganti Cara</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tampilkan</label>
                <select wire:model.live="perPage"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500">
                    <option value="15">15 per halaman</option>
                    <option value="25">25 per halaman</option>
                    <option value="50">50 per halaman</option>
                </select>
            </div>
        </div>

        @if ($search || $visit_type || $kb_method_id)
            <div class="mt-3 flex items-center justify-between">
                <p class="text-sm text-gray-600">Filter aktif:
                    @if ($visit_type)
                        <span class="font-medium">{{ $visit_type }}</span>
                    @endif
                    @if ($kb_method_id)
                        <span class="font-medium">Metode terpilih</span>
                    @endif
                    @if ($search)
                        <span class="font-medium">"{{ $search }}"</span>
                    @endif
                </p>
                <button wire:click="resetFilters" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Reset
                    Filter</button>
            </div>
        @endif
    </div>

    <!-- Visits Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if ($visits->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Pembayaran
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tensi</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($visits as $v)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ optional($v->visit_date)->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ optional($v->visit_date)->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $v->patient->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $v->patient->no_rm ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium">{{ $v->kbMethod->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm">{{ $v->payment_type }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm">{{ $v->blood_pressure_systolic ?? '-' }}/{{ $v->blood_pressure_diastolic ?? '-' }}</span>
                                    @if ($v->blood_pressure_systolic && $v->blood_pressure_diastolic)
                                        @if ($v->blood_pressure_systolic >= 140 || $v->blood_pressure_diastolic >= 90)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 ml-2">Hipertensi</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 ml-2">Normal</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('kb.entry') }}?patient={{ $v->patient_id }}"
                                            class="p-1.5 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded transition-colors"
                                            title="Buat Kunjungan Baru">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </a>
                                        <button wire:click="openPatientPreview({{ $v->patient_id }})"
                                            class="p-1.5 text-green-600 hover:text-green-700 hover:bg-green-50 rounded transition-colors"
                                            title="Preview Pasien">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $visits->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada kunjungan KB</h3>
                <p class="text-gray-600 mb-4">Belum ada kunjungan KB yang sesuai dengan filter.</p>
                <button wire:click="resetFilters"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">Reset
                    Filter</button>
            </div>
        @endif
    </div>
</div>
