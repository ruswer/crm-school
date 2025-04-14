<x-filament::page>
    <div class="flex flex-col lg:flex-row gap-4">
        <!-- Chap panel (30%) -->
        <div class="w-full lg:w-[30%] bg-white rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-4 border-b">Kabinet qo'shish</h2>
            <form class="space-y-4">
                <!-- Kabinet raqami -->
                <div>
                    <label for="room_number" class="block text-sm font-medium text-gray-700">Kabinet raqami</label>
                    <input type="text" id="room_number" name="room_number" 
                           placeholder="Kabinet raqamini kiriting"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Saqlash tugmasi -->
                <div class="flex justify-end pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Saqlash
                    </button>
                </div>
            </form>
        </div>

        <!-- O'ng panel (70%) -->
        <div class="w-full lg:w-[70%] bg-white rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-4 border-b">Kabinetlar ro'yxati</h2>
            
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
                                    <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('cabinetNumber')">
                                        <span>Kabinet</span>
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
                                <td data-field="cabinetNumber" class="px-6 py-4">Ingliz tili 1-xona</td>              
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
                            <tr class="border-b dark:border-neutral-600">
                                <td data-field="cabinetNumber" class="px-6 py-4">Ona tili 2-xona</td>
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
</x-filament::page>