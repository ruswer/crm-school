<x-filament::page>
    {{-- Bitta asosiy o'rab turuvchi div --}}
    <div class="flex flex-col lg:flex-row gap-6"> {{-- gap-6 qo'shildi --}}

        {{-- Chap panel (30%) - Kategoriya qo'shish/tahrirlash --}}
        <div class="w-full lg:w-[30%] bg-white rounded-lg shadow-sm p-4 dark:bg-gray-800 self-start"> {{-- self-start qo'shildi --}}
            {{-- Forma sarlavhasi --}}
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                @if($editingCategoryId)
                    Kategoriyani Tahrirlash
                @else
                    Yangi Kategoriya Qo'shish
                @endif
            </h2>

            {{-- Forma --}}
            <form wire:submit.prevent="saveCategory" class="space-y-4">
                {{-- Kategoriya nomi --}}
                <div>
                    <label for="category_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategoriya nomi</label>
                    <input wire:model.defer="name" type="text" id="category_name"
                           placeholder="Kategoriya nomini kiriting" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror">
                    @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Tavsifi --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tavsifi</label>
                    <textarea wire:model.defer="description" id="description" rows="3"
                              placeholder="Kategoriya haqida qo'shimcha ma'lumot"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('description') border-red-500 @enderror"></textarea>
                    @error('description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.defer="status" id="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('status') border-red-500 @enderror">
                        <option value="active">Faol</option>
                        <option value="inactive">Faol emas</option>
                    </select>
                    @error('status') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Saqlash/Yangilash tugmasi --}}
                <div class="flex justify-between items-center pt-4">
                    {{-- Bekor qilish tugmasi --}}
                    @if($editingCategoryId)
                        <button type="button" wire:click="resetForm"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                            Bekor qilish
                        </button>
                    @else
                        <div></div> {{-- Joyni egallash uchun --}}
                    @endif

                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg wire:loading wire:target="saveCategory" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>
                            @if($editingCategoryId) Yangilash @else Saqlash @endif
                        </span>
                    </button>
                </div>
            </form>
        </div>

        {{-- O'ng panel (70%) - Kategoriyalar jadvali --}}
        <div class="w-full lg:w-[70%] bg-white rounded-lg shadow-sm p-4 dark:bg-gray-800 lg:self-start">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">Kategoriyalar ro'yxati</h2>
            {{-- Jadval --}}
            <div class="overflow-x-auto">
                {{-- Qidiruv inputi --}}
                <div class="relative mb-3 float-left mr-4"> {{-- float-left va mr-4 qo'shildi --}}
                    <label for="inputSearch" class="sr-only">Qidirish</label>
                    <input wire:model.live.debounce.300ms="searchQuery" id="inputSearch" type="search" placeholder="Qidirish..." class="block w-64 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2 pl-10 pr-4 text-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white" />
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-gray-400 dark:text-gray-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                </div>

                <table class="min-w-full text-left text-sm whitespace-nowrap clear-left"> {{-- clear-left qo'shildi --}}
                    {{-- Jadval boshi --}}
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                        <tr>
                            {{-- Saralash uchun ustun nomlari --}}
                            @php
                                $columns = [
                                    'Chiqim kategoriyasi' => 'category', // PHP da 'name' ga moslanadi
                                    'Tavsifi' => 'note', // PHP da 'description' ga moslanadi
                                    'Holati' => 'status',
                                ];
                            @endphp
                            @foreach ($columns as $label => $columnKey)
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sort('{{ $columnKey }}')">
                                    <div class="flex items-center gap-1">
                                        <span>{{ $label }}</span>
                                        {{-- Saralash ikonkalari --}}
                                        @if($sortField === match($columnKey){'category'=>'name','note'=>'description','status'=>'status',default=>''})
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($sortDirection === 'asc') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /> @else <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /> @endif
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /> </svg>
                                        @endif
                                    </div>
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-right">Amallar</th> {{-- Tahrirlash o'rniga Amallar --}}
                        </tr>
                    </thead>
                    {{-- Jadval tanasi --}}
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 dark:text-gray-200">{{ $category->name }}</td>
                                <td class="px-6 py-4 dark:text-gray-300">{{ Str::limit($category->description, 50) }}</td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100' => $category->status === 'active',
                                        'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100' => $category->status === 'inactive',
                                    ])>
                                        {{ $category->status === 'active' ? 'Faol' : 'Faol emas' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    {{-- Tahrirlash tugmasi --}}
                                    <button type="button"
                                            wire:click="editCategory({{ $category->id }})"
                                            class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L9.38 17.273a4.5 4.5 0 01-1.897 1.13l-2.745.823.823-2.745a4.5 4.5 0 011.13-1.897l10.171-10.17z" />
                                        </svg>
                                    </button>
                                    {{-- O'chirish tugmasini ham qo'shish mumkin (masalan, modal bilan) --}}
                                    {{-- <button type="button" wire:click="confirmDelete({{ $category->id }})" class="text-red-600 hover:text-red-900 ml-2">...</button> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Ma'lumot topilmadi</h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Tanlangan filial yoki guruh bo'yicha ma'lumot topilmadi.
                                        </p>
                                        <div class="mt-6">
                                            <button
                                                wire:click="resetFilters"
                                                type="button"
                                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            >
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Filterni tozalash
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-4 px-2">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>

    </div> {{-- Asosiy o'rab turuvchi div yopilishi --}}
</x-filament::page>
