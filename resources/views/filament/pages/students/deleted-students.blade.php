<x-filament::page>
    <div class="flex flex-col h-full"> <!-- Umumiy orqa fon qo'shildi -->
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
                <!-- Chap tomon: Filial va guruh bo'yicha qidirish (70%) -->
                <div class="flex flex-col gap-4 w-full lg:w-[60%] lg:min-w-0">
                    <!-- Tepa qator: Filial va Guruh select -->
                    <div class="flex flex-wrap lg:flex-nowrap gap-4">
                        <!-- Filial bo'yicha qidirish -->
                        <div class="w-full sm:w-1/2 lg:flex-1">
                            <label for="branch" class="block text-sm font-medium text-gray-700">Filial</label>
                            <select id="branch" name="branch" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha filiallar</option>
                                <option value="1">Filial 1</option>
                                <option value="2">Filial 2</option>
                            </select>
                        </div>
                        <!-- Guruh bo'yicha qidirish -->
                        <div class="w-full sm:w-1/2 lg:flex-1">
                            <label for="group" class="block text-sm font-medium text-gray-700">Guruh</label>
                            <select id="group" name="group" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha guruhlar</option>
                                <option value="A">Guruh A</option>
                                <option value="B">Guruh B</option>
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

                <!-- O'ng tomon: Student nomi bilan qidirish (30%) -->
                <div class="w-full lg:w-[30%] lg:min-w-0">
                    <div class="flex flex-col gap-4">
                        <!-- Tepa qator: Student nomi input -->
                        <div class="w-full">
                            <label for="student-name" class="block text-sm font-medium text-gray-700">Kalit so'z buyicha izlash</label>
                            <input
                                type="text"
                                id="student-name"
                                name="student-name"
                                placeholder="Ism kiriting"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <!-- Pastki qator: Qidirish tugmasi o'ngda -->
                        <div class="flex justify-end">
                            <button
                                type="button"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                <svg class="w-5 h-5 mr-2 text-white" " fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Qidirish
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pastki qism: Kontent -->
        <div class="flex-1 mt-4 bg-white rounded-md shadow-sm p-4">
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
                                'O\'quvchi Ismi' => 'name',
                                'Filial' => 'branch',
                                'Tug\'ilgan kuni' => 'birthday',
                                'Jinsi' => 'gender',
                                'Tel.raqami' => 'phone',
                                'Sabab' => 'reason',
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
                            <th scope="col" class="px-6 py-4">Tahrirlash</th> <!-- Yangi ustun -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b dark:border-neutral-600">
                            <td class="px-6 py-4">
                                <a href="#"
                                    wire:click="showStudentProfile()"
                                    class="text-primary-600 hover:text-primary-900 hover:underline">
                                    Akbaraliyev Akbarbek
                                </a>
                            </td>
                            <td class="px-6 py-4">Chilonzor</td>
                            <td class="px-6 py-4">20-20-2002</td>
                            <td class="px-6 py-4">Erkak</td>
                            <td class="px-6 py-4">+998 90 123 45 67</td>
                            <td class="px-6 py-4">Qimmat</td>
                            <td class="px-6 py-4">
                                <a href="#" 
                                wire:click="editStudent(1)" 
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
                            href="#!">
                                Previous
                            </a>
                        </li>
                        <li>
                            <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            href="#!">
                                1
                            </a>
                        </li>
                        <li aria-current="page">
                            <a class="relative block rounded bg-blue-100 px-3 py-1.5 text-sm font-medium text-blue-700 transition-all duration-300"
                            href="#!">
                                2
                                <span class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">
                                    (current)
                                </span>
                            </a>
                        </li>
                        <li>
                            <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            href="#!">
                                3
                            </a>
                        </li>
                        <li>
                            <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                            href="#!">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</x-filament::page>