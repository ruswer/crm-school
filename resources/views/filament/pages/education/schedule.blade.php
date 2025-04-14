<x-filament::page>
    <div class="flex flex-col h-full">
        <!-- Tepa qism -->
        <div class="flex-1 bg-white rounded-md shadow-sm">
            <div class="flex items-center justify-between p-4 rounded-md shadow-sm bg-white">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800">Tanlash</span>
                </div>
            
                <!-- Qo'shish tugmasi -->
                <button type="button" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Qo'shish
                </button>
            </div>

            <!-- Pastki qism:qidirish -->
            <div class="flex items-center justify-between bg-white p-4 rounded-md shadow-sm gap-4 flex-nowrap">
                <div class="flex flex-col gap-4 w-full">
                    <!-- Tepa qator: Filial va Guruh select -->
                    <div class="flex flex-wrap lg:flex-nowrap gap-4">
                        <!-- Filial bo'yicha qidirish -->
                        <div class="w-full lg:w-1/3">
                            <label for="branch" class="block text-sm font-medium text-gray-700">Filial</label>
                            <select id="branch" name="branch" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha filiallar</option>
                                <option value="1">Filial 1</option>
                                <option value="2">Filial 2</option>
                            </select>
                        </div>
                        <!-- Guruh bo'yicha qidirish -->
                        <div class="w-full lg:w-1/3">
                            <label for="group" class="block text-sm font-medium text-gray-700">Guruh</label>
                            <select id="group" name="group" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha guruhlar</option>
                                <option value="A">Guruh A</option>
                                <option value="B">Guruh B</option>
                            </select>
                        </div>
                        <!-- O'qituvchi bo'yicha qidirish -->
                        <div class="w-full lg:w-1/3">
                            <label for="teacher" class="block text-sm font-medium text-gray-700">O'qituvchi</label>
                            <select id="teacher" name="teacher" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha o'qituvchilar</option>
                                <option value="1">O'qituvchi 1</option>
                                <option value="2">O'qituvchi 2</option>
                            </select>
                        </div>
                    </div>
                    <!-- Pastki qator: Qidirish tugmasi o'ngda -->
                    <div class="flex justify-end">
                        <button
                            type="button"
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

        <!-- Pastki qism: Kontent -->
        <div class="flex-1 mt-4 bg-white rounded-md shadow-sm p-4">
            <h2 class="text-lg font-bold text-gray-800 mb-4 py-4 border-b border-gray-200">Darslar jadvali</h2>
            
        </div>
    </div>
</x-filament::page>