{{-- TAB 2: OBJEKTIF (OBJECTIVE) - Pemeriksaan Fisik & Vital Signs --}}
<div class="space-y-6">
    <!-- Vital Signs Card -->
    <div class="bg-gradient-to-r from-green-50 to-teal-50 border border-green-200 rounded-lg p-5">
        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-heart class="h-6 w-6 mr-2 text-green-600" />
            Tanda Vital (Vital Signs)
        </h4>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- Tekanan Darah -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    🩺 Tekanan Darah (mmHg)
                </label>
                <div class="flex items-center space-x-2">
                    <input type="number" wire:model="systolic" placeholder="Sistole"
                        class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    <span class="text-gray-500 font-bold">/</span>
                    <input type="number" wire:model="diastolic" placeholder="Diastole"
                        class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                </div>
                <p class="mt-1 text-xs text-gray-500">Contoh: 120 / 80</p>
            </div>

            <!-- Suhu -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">🌡️ Suhu (°C)</label>
                <input type="number" step="0.1" wire:model="temperature"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
            </div>

            <!-- Respiratory Rate -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">🫁 RR (/menit)</label>
                <input type="number" wire:model="respiratory_rate" placeholder="20"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                <p class="mt-1 text-xs text-gray-500">Nafas</p>
            </div>

            <!-- Heart Rate / Nadi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">❤️ Nadi (/menit)</label>
                <input type="number" wire:model="heart_rate" placeholder="80"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
            </div>

            <!-- Berat Badan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">⚖️ BB (kg)</label>
                <input type="number" step="0.1" wire:model.blur="weight"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
            </div>

            <!-- Tinggi Badan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">📏 TB (cm)</label>
                <input type="number" step="0.1" wire:model.blur="height"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
            </div>

            <!-- Lingkar Perut -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">📐 LP (cm)</label>
                <input type="number" step="0.1" wire:model="waist_circumference"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                <p class="mt-1 text-xs text-gray-500">Lingkar Perut</p>
            </div>

            <!-- IMT (Auto Calculate) -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">📊 IMT (Body Mass Index)</label>
                <div
                    class="mt-1 p-3 bg-white border-2 {{ $bmi ? ($bmi < 18.5 ? 'border-yellow-400 bg-yellow-50' : ($bmi >= 25 ? 'border-red-400 bg-red-50' : 'border-green-400 bg-green-50')) : 'border-gray-200' }} rounded-md">
                    @if ($bmi)
                        <p
                            class="text-2xl font-bold {{ $bmi < 18.5 ? 'text-yellow-700' : ($bmi >= 25 ? 'text-red-700' : 'text-green-700') }}">
                            {{ $bmi }}
                        </p>
                        <p
                            class="text-sm {{ $bmi < 18.5 ? 'text-yellow-600' : ($bmi >= 25 ? 'text-red-600' : 'text-green-600') }}">
                            {{ $this->getBMICategory() }}
                        </p>
                    @else
                        <p class="text-sm text-gray-400 italic">Masukkan BB & TB</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pemeriksaan Fisik Head-to-Toe -->
    <div class="bg-purple-50 border border-purple-200 rounded-lg p-5">
        <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
            <x-heroicon-o-clipboard-document-check class="h-6 w-6 mr-2 text-purple-600" />
            Pemeriksaan Fisik Sistemik (Head-to-Toe)
        </h4>
        <p class="text-sm text-gray-600 mb-4">
            Default value sudah diisi "Normal". Edit hanya jika ada kelainan atau temuan khusus.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Kepala -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">👤 Kepala</label>
                <input type="text" wire:model="head_to_toe.kepala"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
            </div>

            <!-- Mata -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">👁️ Mata</label>
                <input type="text" wire:model="head_to_toe.mata"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
            </div>

            <!-- Telinga -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">👂 Telinga</label>
                <input type="text" wire:model="head_to_toe.telinga"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
            </div>

            <!-- Leher -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">🦴 Leher</label>
                <input type="text" wire:model="head_to_toe.leher"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
            </div>

            <!-- Thorax Jantung -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">❤️ Thorax (Jantung)</label>
                <input type="text" wire:model="head_to_toe.thorax_jantung"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
            </div>

            <!-- Thorax Paru -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">🫁 Thorax (Paru)</label>
                <input type="text" wire:model="head_to_toe.thorax_paru"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
            </div>

            <!-- Abdomen -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">🫄 Abdomen</label>
                <input type="text" wire:model="head_to_toe.abdomen"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
            </div>

            <!-- Ekstremitas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">🖐️ Ekstremitas</label>
                <input type="text" wire:model="head_to_toe.ekstremitas"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
            </div>

            <!-- Genitalia -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">🩺 Genitalia</label>
                <input type="text" wire:model="head_to_toe.genitalia"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
            </div>
        </div>
    </div>

    <!-- Catatan Pemeriksaan Fisik Umum (Optional) -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            📝 Catatan Pemeriksaan Fisik Tambahan (Optional)
        </label>
        <textarea wire:model="physical_exam" rows="3"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            placeholder="Tambahan catatan pemeriksaan fisik yang tidak tercakup di checklist di atas..."></textarea>
        <p class="mt-1 text-xs text-gray-500">Gunakan field ini untuk detail tambahan yang spesifik</p>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between">
        <button @click="activeTab = 'anamnesa'" type="button"
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <x-heroicon-o-arrow-left class="mr-2 h-4 w-4" />
            Kembali ke Anamnesa
        </button>
        <button @click="activeTab = 'diagnosa'" type="button"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Lanjut ke Diagnosa & Resep
            <x-heroicon-o-arrow-right class="ml-2 h-4 w-4" />
        </button>
    </div>
</div>
