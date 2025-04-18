<x-filament::page>
    <div class="space-y-6">
        <!-- Hisobot filtrlari -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between p-4 rounded-md shadow-sm bg-white">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800">Tanlash</span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Filial filtri -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Filial</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tanlang</option>
                        <option value="1">Chilonzor</option>
                        <option value="2">Namangan</option>
                        <option value="3">Pop</option>
                    </select>
                </div>
                <!-- Sana oralig'i -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Oy</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tanlang</option>
                        <option value="1">Yanvar</option>
                        <option value="2">Fevral</option>
                        <option value="3">Mart</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Yil</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tanlang</option>
                        <option value="1">2024</option>
                        <option value="2">2025</option>
                    </select>
                </div>
            </div>

            <!-- Qidiruv tugmasi -->
            <div class="mt-4 flex justify-end">
                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Hisobotni shakllantirish
                </button>
            </div>
        </div>

        <!-- Hisobot jadvali -->
        
        <div class="flex-1 mt-4 bg-white rounded-md shadow-sm p-4">
            <div class="flex items-center justify-between mb-4 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">Xodimlar davomati hisoboti</h2>
            </div>
            <div class="overflow-x-auto bg-white dark:bg-neutral-700">
                <!-- Search input -->
                <div class="relative m-[2px] mb-3 mr-5 float-left">
                    <label for="inputSearch" class="sr-only">Qidirish</label>
                    <input id="inputSearch" type="text" placeholder="Qidirish..." class="block w-64 rounded-lg border dark:border-none dark:bg-neutral-600 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400" />
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-neutral-500 dark:text-neutral-200">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                </div>
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 text-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-4">
                                <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('employeeId')">
                                    <span>№</span>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                        :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4">
                                <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('employeeName')">
                                    <span>Xodim Nomi</span>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                        :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4">
                                <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('department')">
                                    <span>Bo'lim</span>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                        :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4">
                                <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('onWork')">
                                    <span>Ishtirok etgan</span>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                        :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4">
                                <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('notOnWork')">
                                    <span>Qatnashmagan</span>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                        :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4">
                                <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('onHoliday')">
                                    <span>Bayram</span>
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
                            <td data-field="employeeId" class="px-6 py-4">55</td>
                            <td data-field="employeeName" class="px-6 py-4">Akbar Sharipov</td>              
                            <td data-field="department" class="px-6 py-4">Chilonzor</td>              
                            <td data-field="onWork" class="px-6 py-4">
                                <div class="fi-ta-text px-3 py-4 text-sm text-green-600 dark:text-green-400">
                                    15
                                </div>
                            </td>              
                            <td data-field="notOnWork" class="px-6 py-4">
                                <div class="fi-ta-text px-3 py-4 text-sm text-red-600 dark:text-red-400">
                                    2
                                </div>    
                            </td> 
                            <td data-field="onHoliday" class="px-6 py-4">
                                <div class="fi-ta-text px-3 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    4
                                </div>    
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
</x-filament::page>