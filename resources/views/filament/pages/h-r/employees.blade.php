<x-filament::page>
    <div class="flex flex-col h-full">
        <!-- Tepa qism -->
        <div class="flex-1 bg-white rounded-md shadow-sm">
            <div class="flex items-center justify-between p-4 rounded-md shadow-sm bg-white">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800">Tanlash</span>
                </div>
            
                <!-- Qo'shish tugmasi -->
                <button type="button" wire:click="openModal"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Xodim qo'shish
                </button>
            </div>

            <!-- Pastki qism:qidirish -->
            <div class="flex items-center justify-between bg-white p-4 rounded-md shadow-sm gap-4 flex-nowrap">
                <div class="flex flex-col gap-4 w-full">
                    <!-- Tepa qator: Filial va Guruh select -->
                    <div class="flex flex-wrap lg:flex-nowrap gap-4">
                        <!-- Filial bo'yicha qidirish -->
                        <div class="w-full lg:w-1/4">
                            <label for="branch" class="block text-sm font-medium text-gray-700">Filial</label>
                            <select id="branch" name="branch" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha filiallar</option>
                                <option value="1">Filial 1</option>
                                <option value="2">Filial 2</option>
                            </select>
                        </div>
                        <!-- Guruh bo'yicha qidirish -->
                        <div class="w-full lg:w-1/4">
                            <label for="group" class="block text-sm font-medium text-gray-700">Rol</label>
                            <select id="group" name="group" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Tanlash</option>
                                <option value="A">Admin A</option>
                                <option value="B">O'qituvchi</option>
                            </select>
                        </div>
                        <!-- O'qituvchi bo'yicha qidirish -->
                        <div class="w-full lg:w-1/4">
                            <label for="teacher" class="block text-sm font-medium text-gray-700">Bo'lim</label>
                            <select id="teacher" name="teacher" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Tanlash</option>
                                <option value="1">IT</option>
                                <option value="2">Ingliz tili</option>
                            </select>
                        </div>

                        <!--Kalit so'z bilan qidirish -->
                        <div class="w-full lg:w-1/4">
                            <label for="student-name" class="block text-sm font-medium text-gray-700">Kalit so'z buyicha izlash</label>
                            <input
                                type="text"
                                id="student-name"
                                name="student-name"
                                placeholder="Xodim IDsi, Ismi, Roli bo'yicha qidirish"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            />
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
            <h2 class="text-lg font-bold text-gray-800 mb-4 py-4 border-b border-gray-200">Xodim</h2>
            <!-- Xodimlar ro'yxati show icons -->
            <div x-data="{ view: 'grid' }">
                <!-- View Toggle Buttons -->
                <div class="flex space-x-2 mb-6">
                    <!-- Grid View Button -->
                    <div class="group">
                        <button type="button"
                                @click="view = 'grid'"
                                class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 transition-all duration-200 border-b-2"
                                :class="{ 'border-blue-500': view === 'grid', 'border-transparent group-hover:border-blue-500': view !== 'grid' }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Grid
                        </button>
                    </div>
            
                    <!-- List View Button -->
                    <div class="group">
                        <button type="button"
                                @click="view = 'list'"
                                class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 transition-all duration-200 border-b-2"
                                :class="{ 'border-blue-500': view === 'list', 'border-transparent group-hover:border-blue-500': view !== 'list' }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            Ro‘yxat
                        </button>
                    </div>
                </div>
            
                <!-- Employees Content -->
                <div class="mb-6">
                    <!-- Grid View -->
                    <div x-show="view === 'grid'" x-transition class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">name</h3>
                            <p class="text-sm text-gray-600">position</p>
                            <p class="text-sm text-gray-600">email</p>
                            <p class="text-sm text-gray-600">phone</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">name</h3>
                            <p class="text-sm text-gray-600">position</p>
                            <p class="text-sm text-gray-600">email</p>
                            <p class="text-sm text-gray-600">phone</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">name</h3>
                            <p class="text-sm text-gray-600">position</p>
                            <p class="text-sm text-gray-600">email</p>
                            <p class="text-sm text-gray-600">phone</p>
                        </div>
                        
                    </div>
            
                    <!-- List View -->
                    <div x-show="view === 'list'" x-transition class="overflow-x-auto">
                        <!-- Table responsive wrapper -->
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

                            <!-- Table -->
                            <div x-data="sortableTable">
                                <table class="min-w-full text-left text-sm whitespace-nowrap">
                                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 text-gray-800">
                                        <tr>
                                            <th scope="col" class="px-6 py-4">
                                                <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('employeeId')">
                                                    <span>Xodim Stiri</span>
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
                                                <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('employeeRole')">
                                                    <span>Rol</span>
                                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                                        :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-4">
                                                <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('employeeDepartment')">
                                                    <span>Bo'lim</span>
                                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                                        :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-4">
                                                <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('employeePhone')">
                                                    <span>Mobil raqam</span>
                                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                                        :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-4">Amallar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Table rows with data-field attributes -->
                                        <tr class="border-b dark:border-neutral-600">
                                            <td data-field="cabinetNumber" class="px-6 py-4">55</td>
                                            <td data-field="cabinetNumber" class="px-6 py-4">Akbar Sharipov</td>              
                                            <td data-field="cabinetNumber" class="px-6 py-4">O'qituvchi</td>              
                                            <td data-field="cabinetNumber" class="px-6 py-4">Ingliz tili</td>              
                                            <td data-field="cabinetNumber" class="px-6 py-4">998778871</td>              
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <!-- Tahrirlash -->
                                                    <a href="#" class="text-blue-600 hover:text-blue-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                                                            stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" 
                                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L9.38 17.273a4.5 4.5 0 01-1.897 1.13l-2.745.823.823-2.745a4.5 4.5 0 011.13-1.897l10.171-10.17z" />
                                                        </svg>
                                                    </a>
                                                    
                                                    <!-- O'chirish -->
                                                    <a href="#" class="text-red-600 hover:text-red-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                                                            stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" 
                                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </a>
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
                </div>
            
                <!-- Pagination -->
                <div class="mt-4">
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Oldingi
                            </button>
                            <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Keyingi
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Jami <span class="font-medium">20</span> tadan
                                    <span class="font-medium">1</span> dan
                                    <span class="font-medium">10</span> gacha ko'rsatilmoqda
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <button class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        Oldingi
                                    </button>
                                    <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        1
                                    </button>
                                    <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        2
                                    </button>
                                    <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        3
                                    </button>
                                    <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        Keyingi
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>