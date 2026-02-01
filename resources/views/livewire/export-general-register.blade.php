<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Export Register Poli Umum
            </h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
                <p>
                    Unduh laporan register kunjungan poli umum dalam format Excel.
                </p>
            </div>
            <div class="mt-5 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                <div>
                    <label for="dateFrom" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <div class="mt-1">
                        <input type="date" wire:model="dateFrom" id="dateFrom" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                <div>
                    <label for="dateTo" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <div class="mt-1">
                        <input type="date" wire:model="dateTo" id="dateTo" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
            </div>
            <div class="mt-5">
                <button type="button" wire:click="export" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                    Export Excel
                </button>
            </div>
        </div>
    </div>
</div>
