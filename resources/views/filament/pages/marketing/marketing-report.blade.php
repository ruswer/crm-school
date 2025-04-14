<x-filament::page>
    <div class="flex flex-col h-full" x-data="{ showTable: false }">
        <!-- To'lovlar ro'yxati -->
        <div class="flex-1 bg-white rounded-md shadow-sm">
            <!-- To'lovlarni qidirish -->
            <div class="flex items-center justify-between p-4 rounded-md shadow-sm bg-white">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800">Tanlash</span>
                </div>
            </div>

            <!-- Qidiruv filtrlari -->
            <div class="flex items-center justify-between bg-white p-4 rounded-md shadow-sm gap-4 flex-nowrap">
                <!-- Chap tomon: Sana bo'yicha qidirish -->
                <div class="flex flex-col gap-4 w-full lg:w-full lg:min-w-0">
                    <!-- Tepa qator: Filial va Guruh select -->
                    <div class="flex flex-wrap lg:flex-nowrap gap-4">
                        <!-- Sana dan bo'yicha qidirish -->
                        <div class="w-full sm:w-1/2 lg:flex-1">
                            <label for="birth-year" class="block text-sm font-medium text-gray-700">Sana dan</label>
                            <input
                                type="date"
                                id="birth-year"
                                name="birth-year"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <!-- Sana gacha bo'yicha qidirish -->
                        <div class="w-full sm:w-1/2 lg:flex-1">
                            <label for="birth-year" class="block text-sm font-medium text-gray-700">Sana gacha</label>
                            <input
                                type="date"
                                id="birth-year"
                                name="birth-year"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                    </div>
                    <!-- Pastki qator: Qidirish tugmasi o'ngda -->
                    <div class="flex justify-end">
                        <button
                            type="button"
                            @click="showTable = true"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto"
                        >
                            <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Qidirish
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reklama bo'yicha hisobot -->
        <div x-show="showTable" x-transition class="w-full mt-4 bg-white rounded-md shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-4 border-b">Reklama bo'yicha hisobot</h2>
            
            <!-- Ikki ustunli container -->
            <div class="flex flex-row w-full space-x-4"> 
                <!-- Chap ustun (60%) -->
                <div style="width: 60%">
                    <div class="h-full">
                        <div class="p-4">
                            <!-- Jadval -->
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-2 px-4 font-medium text-gray-700 bg-gray-100">Reklama turi</th>
                                        <th class="text-left py-2 px-4 font-medium text-gray-700 bg-gray-100">Soni</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    <tr>
                                        <td class="py-2 px-4">Instagram</td>
                                        <td class="py-2 px-4">24</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">Facebook</td>
                                        <td class="py-2 px-4">18</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">Telegram</td>
                                        <td class="py-2 px-4">15</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">Google Ads</td>
                                        <td class="py-2 px-4">12</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="border-t">
                                        <td class="py-2 px-4 font-medium">Jami:</td>
                                        <td class="py-2 px-4 font-medium">69</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- O'ng ustun (40%) -->
                <div style="width: 40%">
                    <div class="h-full">
                        <div class="p-4">
                            <div class="bg-gray-100 px-2 py-2 border-b">
                                <h3 class="font-medium text-gray-700">Reklama bo'yicha taqsimot</h3>
                            </div>
                            <div class="p-4">
                                <!-- O'ng ustun kontenti -->
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Jins bo'yicha taqsimot -->
        <div x-show="showTable" x-transition class="w-full mt-4 bg-white rounded-md shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-4 border-b">Jins bo'yicha taqsimot</h2>
            
            <!-- Ikki ustunli container -->
            <div class="flex flex-row w-full space-x-4"> 
                <!-- Chap ustun (60%) -->
                <div style="width: 60%">
                    <div class="h-full">
                        <div class="p-4">
                            <h3 class="text-gray-900 mb-4 pb-2">O'quvchilarning umumiy soni: 0</h3>

                            <!-- Jadval -->
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-2 px-4 font-medium text-gray-700 bg-gray-100">Jinsi</th>
                                        <th class="text-left py-2 px-4 font-medium text-gray-700 bg-gray-100">Soni</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    <tr>
                                        <td class="py-2 px-4">Erkak</td>
                                        <td class="py-2 px-4">24</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">Ayol</td>
                                        <td class="py-2 px-4">8</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- O'ng ustun (40%) -->
                <div style="width: 40%">
                    <div class="h-full">
                        <div class="p-4">
                            <div class="bg-gray-100 px-2 py-2 border-b">
                                <h3 class="font-medium text-gray-700">Jins bo'yicha taqsimot</h3>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg width="12" height="12" viewBox="0 0 12 12">
                                    <circle cx="6" cy="6" r="6" fill="#EF4444"/>
                                </svg>
                                <p>Erkak</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg width="12" height="12" viewBox="0 0 12 12">
                                    <circle cx="6" cy="6" r="6" fill="#444ec2"/>
                                </svg>
                                <p>Ayol</p>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>