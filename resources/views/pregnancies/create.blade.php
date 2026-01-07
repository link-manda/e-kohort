<x-dashboard-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Daftarkan Kehamilan Baru</h1>
            <p class="text-gray-600 mt-2">Formulir pendaftaran kehamilan dengan kalkulasi otomatis</p>
        </div>

        <!-- Livewire Component -->
        @livewire('pregnancy-registration', ['patient_id' => $patient_id])
    </div>
</x-dashboard-layout>
