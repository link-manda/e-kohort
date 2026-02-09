<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Export Data Gizi & Pertumbuhan</h2>
            <p class="text-gray-500 mt-1">Unduh laporan pemantauan pertumbuhan anak dalam format Excel.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-heroicon-o-check-circle class="h-5 w-5 text-green-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Laporan Bulanan Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <x-heroicon-o-calendar class="w-6 h-6 text-blue-600" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Laporan Bulanan</h3>
                        <p class="text-sm text-gray-500">Rekapitulasi pengukuran semua anak per bulan</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="exportMonthly" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                            <select wire:model="month" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m)->locale('id')->translatedFormat('F') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                            <select wire:model="year" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                @foreach(range(date('Y'), 2020) as $y)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg flex items-center justify-center gap-2 transition-colors">
                        <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
                        <span>Download Excel</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Individual Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <x-heroicon-o-user class="w-6 h-6 text-purple-600" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Riwayat Individual</h3>
                        <p class="text-sm text-gray-500">History lengkap pertumbuhan satu anak</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="exportIndividual" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Anak</label>
                        <select wire:model="child_id" class="w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 select2">
                            <option value="">-- Pilih Anak --</option>
                            @foreach($children as $child)
                                <option value="{{ $child->id }}">
                                    {{ $child->name }} ({{ $child->no_rm }}) - {{ $child->parent_display_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('child_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg flex items-center justify-center gap-2 transition-colors">
                        <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
                        <span>Download Riwayat</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
