<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" x-data="{ activeTab: 'anamnesa' }">
    <!-- Global Error Messages -->
    @if ($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex items-start">
                <x-heroicon-o-exclamation-circle class="h-6 w-6 text-red-600 flex-shrink-0 mt-0.5" />
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">Terdapat {{ $errors->count() }} kesalahan dalam form:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Header: Patient Identity -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center space-x-3">
                        <h3 class="text-xl leading-6 font-bold text-gray-900">📋 Poli Umum - Input Kunjungan</h3>
                        @if ($is_emergency)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 animate-pulse">
                                🚨 GAWAT DARURAT
                            </span>
                        @endif
                    </div>
                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
                        <span class="font-semibold">{{ $visitorName }}</span>
                        <span>|</span>
                        <span>{{ $visitor->gender == 'L' ? '👨 Laki-laki' : '👩 Perempuan' }}</span>
                        <span>|</span>
                        <span>{{ $visitorAge }}</span>
                        <span>|</span>
                        <span class="font-mono">RM: {{ $visitorNoRm }}</span>
                        @if($visitorType === 'child' && $visitorParentName)
                            <span>|</span>
                            <span class="text-green-700">👤 Orang Tua: {{ $visitorParentName }}</span>
                        @endif
                    </div>
                    @if ($allergies)
                        <div class="mt-2 flex items-start space-x-2 bg-red-50 border-l-4 border-red-400 p-2 rounded">
                            <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" />
                            <div>
                                <p class="text-xs font-bold text-red-800">PERHATIAN - RIWAYAT ALERGI:</p>
                                <p class="text-sm text-red-700">{{ $allergies }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="text-right space-y-2">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $payment_method == 'BPJS' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $payment_method }}
                    </span>
                    @if ($bmi)
                        <div class="text-xs">
                            <span class="font-semibold">IMT:</span>
                            <span
                                class="font-mono {{ $bmi < 18.5 ? 'text-yellow-600' : ($bmi >= 25 ? 'text-red-600' : 'text-green-600') }}">
                                {{ $bmi }} ({{ $this->getBMICategory() }})
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button @click="activeTab = 'anamnesa'"
                    :class="activeTab === 'anamnesa' ? 'border-blue-500 text-blue-600 bg-blue-50' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200">
                    <div class="flex items-center justify-center space-x-2">
                        <x-heroicon-o-clipboard-document-list class="h-5 w-5" />
                        <span>1. Anamnesa (Subjective)</span>
                    </div>
                </button>
                <button @click="activeTab = 'objektif'"
                    :class="activeTab === 'objektif' ? 'border-blue-500 text-blue-600 bg-blue-50' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200">
                    <div class="flex items-center justify-center space-x-2">
                        <x-heroicon-o-chart-bar class="h-5 w-5" />
                        <span>2. Objektif (Pemeriksaan)</span>
                    </div>
                </button>
                <button @click="activeTab = 'diagnosa'"
                    :class="activeTab === 'diagnosa' ? 'border-blue-500 text-blue-600 bg-blue-50' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200">
                    <div class="flex items-center justify-center space-x-2">
                        <x-heroicon-o-clipboard-document-check class="h-5 w-5" />
                        <span>3. Assessment & Plan</span>
                    </div>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="px-4 py-6 sm:p-6">
            <!-- TAB 1: ANAMNESA (SUBJECTIVE) -->
            <div x-show="activeTab === 'anamnesa'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100">
                @include('livewire.general-visit-entry.tab-anamnesa')
            </div>

            <!-- TAB 2: OBJEKTIF (OBJECTIVE) -->
            <div x-show="activeTab === 'objektif'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100" x-cloak>
                @include('livewire.general-visit-entry.tab-objektif')
            </div>

            <!-- TAB 3: ASSESSMENT & PLAN -->
            <div x-show="activeTab === 'diagnosa'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100" x-cloak>
                @include('livewire.general-visit-entry.tab-diagnosa')
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="px-4 py-4 bg-gray-50 border-t border-gray-200 sm:px-6">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600 space-y-1">
                    <div class="flex items-center gap-4">
                        <div>
                            <span class="font-semibold">Biaya Jasa:</span>
                            <span class="ml-2 text-lg font-bold text-blue-600">Rp
                                {{ number_format($service_fee ?: 0, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">Biaya Obat:</span>
                            <span class="ml-2 text-lg font-bold text-green-600">Rp
                                {{ number_format($this->getTotalPrescriptionCost(), 0, ',', '.') }}</span>
                        </div>
                        <div class="pl-4 border-l-2 border-gray-300">
                            <span class="font-semibold">TOTAL:</span>
                            <span class="ml-2 text-2xl font-bold text-red-600">Rp
                                {{ number_format(($service_fee ?: 0) + $this->getTotalPrescriptionCost(), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
