<x-filament::page>
    <div class="flex flex-col lg:flex-row gap-4">
        <!-- Chap panel (30%) - Kategoriya qo'shish/tahrirlash -->
        <div class="w-full lg:w-[30%] bg-white rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-4 border-b">Reklama turi</h2>
            <form class="space-y-4">
                <!-- Kategoriya nomi -->
                <div>
                    <label for="category_name" class="block text-sm font-medium text-gray-700">Reklama turi</label>
                    <input type="text" id="category_name" name="category_name" 
                           placeholder="Reklama nomini kiriting"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Tavsifi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Tavsifi</label>
                    <textarea id="description" name="description" rows="3" 
                              placeholder="Reklama haqida qo'shimcha ma'lumot"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                </div>

                <!-- Saqlash tugmasi -->
                <div class="flex justify-end pt-4">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Saqlash
                    </button>
                </div>
            </form>
        </div>

        <!-- O'ng panel (70%) -->
        <div class="w-full lg:w-[70%] bg-white rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-4 border-b">Reklama turi ro'yhati</h2>
            <!-- Table responsive wrapper -->
            <div class="overflow-x-auto bg-white dark:bg-neutral-700">
                <!-- Search input -->
                <div class="relative m-[2px] mb-3 mr-5 float-left">
                    <label for="inputSearch" class="sr-only">Search </label>
                    <input id="inputSearch" type="text" placeholder="Search..." class="block w-64 rounded-lg border dark:border-none dark:bg-neutral-600 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400" />
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-neutral-500 dark:text-neutral-200">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                </div>
            
                <!-- Table -->
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <!-- Table head -->
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 text-gray-800">
                        <tr>
                            @foreach ([
                                'Reklama turi' => 'advertisementType',
                            ] as $label => $column)
                                <th scope="col" class="px-6 py-4">
                                    {{ $label }}
                                    <a href="#" wire:click="sort('{{ $column }}')" class="inline">
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                            viewBox="0 0 320 512" 
                                            class="w-[0.75rem] h-[0.75rem] inline ml-1 text-neutral-500 dark:text-neutral-200 mb-[1px] 
                                                    {{ isset($sortField) && $sortField === $column ? ($sortDirection === 'asc' ? 'rotate-180' : '') : '' }}"
                                            fill="currentColor">
                                            <path d="M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8H32c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128z" />
                                        </svg>
                                    </a>
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-4">Tahrirlash</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b dark:border-neutral-600">
                            <td class="px-6 py-4">Telegram</td>
                            <td class="px-6 py-4">
                                <a href="#" 
                                wire:click="editExpense(1)" 
                                class="text-blue-600 hover:text-blue-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                                        stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L9.38 17.273a4.5 4.5 0 01-1.897 1.13l-2.745.823.823-2.745a4.5 4.5 0 011.13-1.897l10.171-10.17z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
        
                <nav class="mt-5 flex items-center justify-center text-sm" aria-label="Page navigation example">
                    <ul class="list-style-none flex gap-1">
                        <li>
                            <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            href="#!">Previous</a>
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
                            href="#!">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</x-filament::page>