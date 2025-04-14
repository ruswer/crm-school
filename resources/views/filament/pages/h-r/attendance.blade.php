<x-filament::page>
    <div class="flex flex-col h-full">
        <!-- Tepa qism -->
        <div class="flex-1 bg-white rounded-md shadow-sm">
            <div class="flex items-center justify-between p-4 rounded-md shadow-sm bg-white">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800">Xodimlar davomati</span>
                </div>
            
                <!-- Davomat qo'shish tugmasi -->
                <button type="button" wire:click="openModal"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Davomat qo'shish
                </button>
            </div>

            <!-- Filterlar -->
            <div class="p-4">
                <div class="flex flex-wrap gap-4">
                    <div class="w-full md:w-48">
                        <label for="date" class="block text-sm font-medium text-gray-700">Sana</label>
                        <input type="date" wire:model="date" id="date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <div class="w-full md:w-48">
                        <label for="department" class="block text-sm font-medium text-gray-700">Bo'lim</label>
                        <select id="department" wire:model="department" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Barcha bo'limlar</option>
                            <option value="it">IT bo'limi</option>
                            <option value="hr">HR bo'limi</option>
                            <option value="finance">Moliya bo'limi</option>
                        </select>
                    </div>

                    <div class="w-full md:w-48">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" wire:model="status" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Barchasi</option>
                            <option value="present">Keldi</option>
                            <option value="absent">Kelmadi</option>
                            <option value="late">Kechikdi</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Davomat jadvali -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                F.I.O
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bo'lim
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kelgan vaqt
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ketgan vaqt
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Izoh
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Amallar</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Davomat ma'lumotlari -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament::page>