<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">📏 Poli Gizi & Pertumbuhan</h2>
            <p class="text-sm text-gray-500 mt-1">Pemantauan tumbuh kembang anak, status gizi, dan deteksi dini stunting.</p>
        </div>
        <div class="flex gap-2">
            <!-- Add future export buttons here -->
        </div>
    </div>

    <!-- Stats Overview (Optional, can be added later) -->

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Cari nama anak, No. RM, atau nama ibu...">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                </div>
            </div>
            <div class="w-full md:w-64">
                <select wire:model.live="statusFilter"
                    class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">Semua Status Gizi</option>
                    <option value="normal">Gizi Baik (Normal)</option>
                    <option value="stunting">Stunting (TB/U Pendek)</option>
                    <option value="wasting">Wasting (Gizi Kurang)</option>
                    <option value="underweight">Underweight (BB Kurang)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengukuran Terakhir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Gizi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($children as $child)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold">
                                            {{ substr($child->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $child->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $child->no_rm }} | {{ $child->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $child->formatted_age }}</div>
                                <div class="text-xs text-gray-500">Lahir: {{ $child->dob->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($child->latestGrowthRecord)
                                    <div class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $child->latestGrowthRecord->weight }} kg</span> /
                                        <span class="font-medium">{{ $child->latestGrowthRecord->height }} cm</span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($child->latestGrowthRecord->record_date)->format('d M Y') }}
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 italic">Belum ada data</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($child->latestGrowthRecord)
                                    <div class="flex flex-col gap-1">
                                        <!-- BB/U -->
                                        @if($child->latestGrowthRecord->status_bb_u)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{
                                                $child->latestGrowthRecord->status_bb_u == 'Baik' ? 'bg-green-100 text-green-800' :
                                                ($child->latestGrowthRecord->status_bb_u == 'Kurang' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')
                                            }}">
                                                BB/U: {{ $child->latestGrowthRecord->status_bb_u }}
                                            </span>
                                        @endif

                                        <!-- TB/U -->
                                        @if($child->latestGrowthRecord->status_tb_u)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{
                                                $child->latestGrowthRecord->status_tb_u == 'Normal' ? 'bg-green-100 text-green-800' :
                                                ($child->latestGrowthRecord->status_tb_u == 'Tinggi' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')
                                            }}">
                                                TB/U: {{ $child->latestGrowthRecord->status_tb_u }}
                                            </span>
                                        @endif

                                        <!-- BB/TB -->
                                        @if($child->latestGrowthRecord->status_bb_tb)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{
                                                $child->latestGrowthRecord->status_bb_tb == 'Baik' ? 'bg-green-100 text-green-800' :
                                                ($child->latestGrowthRecord->status_bb_tb == 'Gizi Lebih' || $child->latestGrowthRecord->status_bb_tb == 'Obesitas' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800')
                                            }}">
                                                BB/TB: {{ $child->latestGrowthRecord->status_bb_tb }}
                                            </span>
                                        @endif


                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('children.growth', $child->id) }}"
                                   class="text-purple-600 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 px-3 py-2 rounded-lg transition-colors inline-flex items-center gap-1">
                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    Input Gizi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-face-frown class="mx-auto h-12 w-12 text-gray-300" />
                                <p class="mt-2 text-base font-medium">Belum ada data anak</p>
                                <p class="text-sm">Silakan daftarkan anak melalui menu Pendaftaran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $children->links() }}
        </div>
    </div>
</div>
