<x-dashboard-layout>
    <div class="max-w-5xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Kunjungan ANC</h1>
            <p class="text-gray-600 mt-2">Perbarui data kunjungan dengan wizard 4 langkah</p>
        </div>

        <!-- Livewire Component -->
        @livewire('anc-visit-wizard', ['pregnancy_id' => $pregnancy_id, 'visit_id' => $visit_id])
    </div>
</x-dashboard-layout>
