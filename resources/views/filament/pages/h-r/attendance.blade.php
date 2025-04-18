<x-filament::page>
    <div class="flex flex-col h-full" x-data="{ showTable: false }">
        <!-- Tepa qism -->
        <div class="flex-1 bg-white rounded-md shadow-sm">
            <div class="flex items-center justify-between p-4 rounded-md shadow-sm bg-white">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800">Tanlash</span>
                </div>
            </div>

            <!-- Pastki qism:qidirish -->
            <div class="flex items-center justify-between bg-white p-4 rounded-md shadow-sm gap-4 flex-nowrap">
                <div class="flex flex-col gap-4 w-full">
                    <!-- Tepa qator: Filial va Guruh select -->
                    <div class="flex flex-wrap lg:flex-nowrap gap-4">
                        <!-- Filial bo'yicha qidirish -->
                        <div class="w-full lg:w-1/2">
                            <label for="branch" class="block text-sm font-medium text-gray-700">Filial</label>
                            <select id="branch" name="branch" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha filiallar</option>
                                <option value="1">Filial 1</option>
                                <option value="2">Filial 2</option>
                            </select>
                        </div>
                        <!-- Sana bo'yicha qidirish -->
                        <div class="w-full lg:w-1/2">
                            <label for="birth-year" class="block text-sm font-medium text-gray-700">Sana</label>
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

        <!-- Pastki qism: Content -->
        <div x-show="showTable" x-transition class="w-full bg-white rounded-lg shadow-sm p-4 mt-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-4 border-b">Xodimlar ro'yxati</h2>
            <button
                type="button"
                class="inline-flex items-center px-4 py-2 mb-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Saqlash
            </button>
            <!-- Table responsive wrapper -->
            <div class="overflow-x-auto bg-white dark:bg-neutral-700">
                <!-- Table -->
                <div x-data="sortableTable">
                    <table class="min-w-full text-left text-sm whitespace-nowrap">
                        <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 text-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-4">
                                    <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('id')">
                                        <span>#</span>
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                             :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4">
                                    <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('teacherName')">
                                        <span>Nomi</span>
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                             :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4">
                                    <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('attendance')">
                                        <span>Davomat</span>
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                             :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4">
                                    <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('attendanceNote')">
                                        <span>Izoh</span>
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                             :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table rows with data-field attributes -->
                            <tr class="border-b dark:border-neutral-600">
                                <td data-field="id" class="px-6 py-4">1</td>              
                                <td data-field="teacherName" class="px-6 py-4">Kazim</td> 
                                <td data-field="attendance" class="px-6 py-4">
                                    <div class="flex flex-col space-y-2">
                                        <!-- Ish kuni emas -->
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                name="attendance_" 
                                                value="not_working"
                                                class="form-radio h-4 w-4 text-gray-600 border-gray-300 focus:ring-gray-500 !rounded-none"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">Ish kuni emas</span>
                                        </label>

                                        <!-- Ishtirok etmoqda -->
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                name="attendance_" 
                                                value="present"
                                                class="form-radio h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500 !rounded-none"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">Ishtirok etmoqda</span>
                                        </label>
                                        
                                        <!-- Qatnashmayapti -->
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                name="attendance_" 
                                                value="absent"
                                                class="form-radio h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500 !rounded-none"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">Qatnashmayapti</span>
                                        </label>
                                
                                    </div>
                                </td>
                                <td data-field="attendanceNote" class="px-6 py-4">
                                    <input 
                                        type="text" 
                                        name="note_" 
                                        placeholder="Izoh kiriting..."
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    >
                                </td>
                            </tr>
                            <tr class="border-b dark:border-neutral-600">
                                <td data-field="id" class="px-6 py-4">2</td>
                                <td data-field="teacherName" class="px-6 py-4">Akbar</td> 
                                <td data-field="attendance" class="px-6 py-4">
                                    <div class="flex flex-col space-y-2">
                                        <!-- Ish kuni emas -->
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                name="attendance_" 
                                                value="not_working"
                                                class="form-radio h-4 w-4 text-gray-600 border-gray-300 focus:ring-gray-500 !rounded-none"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">Ish kuni emas</span>
                                        </label>
                                        
                                        <!-- Ishtirok etmoqda -->
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                name="attendance_" 
                                                value="present"
                                                class="form-radio h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500 !rounded-none"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">Ishtirok etmoqda</span>
                                        </label>
                                        
                                        <!-- Qatnashmayapti -->
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                name="attendance_" 
                                                value="absent"
                                                class="form-radio h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500 !rounded-none"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">Qatnashmayapti</span>
                                        </label>
                                
                                    </div>
                                </td> 
                                <td data-field="attendanceNote" class="px-6 py-4">
                                    <input 
                                        type="text" 
                                        name="note_" 
                                        placeholder="Izoh kiriting..."
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav class="mt-5 flex items-center justify-center text-sm">
                    <ul class="list-style-none flex gap-1">
                        <li>
                            <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            href="#!">Oldingi</a>
                        </li>
                        <li>
                            <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            href="#!">1</a>
                        </li>
                        <li aria-current="page">
                            <a class="relative block rounded bg-blue-100 px-3 py-1.5 text-sm font-medium text-blue-700 transition-all duration-300"
                            href="#!">2</a>
                        </li>
                        <li>
                            <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            href="#!">3</a>
                        </li>
                        <li>
                            <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            href="#!">Keyingi</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</x-filament::page>