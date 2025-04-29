<x-filament::page>
    {{-- lg:items-start qo'shildi, panellar balandligi moslashmasligi uchun --}}
    <div class="flex flex-col lg:flex-row lg:items-start gap-4">
        {{-- Chap panel (Filial qo'shish) --}}
        <div class="w-full lg:w-[30%] bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 flex-shrink-0">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b dark:border-gray-700">Filial qo'shish</h2>
            {{-- Forma --}}
            <form wire:submit.prevent="createBranch" class="space-y-4">
                {{ $this->createForm }} {{-- Filament formasini chiqarish --}}

                {{-- Saqlash tugmasi --}}
                <div class="flex justify-end pt-4">
                    <x-filament::button type="submit" wire:loading.attr="disabled">
                         <svg wire:loading wire:target="createBranch" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saqlash
                    </x-filament::button>
                </div>
            </form>
        </div>

        {{-- O'ng panel (Filiallar ro'yxati) --}}
        <div class="w-full lg:w-[70%] bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b dark:border-gray-700">Filiallar ro'yxati</h2>

            {{-- Qidiruv --}}
            <div class="mb-4">
                 <label for="table-search" class="sr-only">Qidirish</label>
                 <div class="relative">
                     <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                          <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                     </div>
                     {{-- wire:model.live yoki wire:model.debounce ishlatiladi --}}
                     <input type="text" id="table-search" wire:model.live.debounce.500ms="search"
                            class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-full sm:w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Filial, manzil, telefon bo'yicha...">
                 </div>
            </div>

            {{-- Jadval --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                        <tr>
                            {{-- Saralash uchun wire:click qo'shilgan --}}
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('name')">
                                <div class="flex items-center gap-x-1">
                                    <span>Filial nomi</span>
                                    {{-- Saralash ikonkasini ko'rsatish (ixtiyoriy) --}}
                                    <x-heroicon-s-chevron-up-down class="w-4 h-4 {{ $sortField === 'name' ? '' : 'text-gray-400' }}" />
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('address')">
                                <div class="flex items-center gap-x-1">
                                    <span>Manzil</span>
                                    <x-heroicon-s-chevron-up-down class="w-4 h-4 {{ $sortField === 'address' ? '' : 'text-gray-400' }}" />
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('phone')">
                                <div class="flex items-center gap-x-1">
                                    <span>Telefon</span>
                                    <x-heroicon-s-chevron-up-down class="w-4 h-4 {{ $sortField === 'phone' ? '' : 'text-gray-400' }}" />
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-right">Amallar</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Ma'lumotlarni chiqarish --}}
                        @forelse($this->branches as $branch)
                            <tr class="border-b dark:border-neutral-600 hover:bg-gray-50 dark:hover:bg-gray-700" wire:key="branch-{{ $branch->id }}">
                                <td class="px-6 py-4">{{ $branch->name }}</td>
                                <td class="px-6 py-4">{{ $branch->address }}</td>
                                <td class="px-6 py-4">{{ $branch->phone }}</td>
                                <td class="px-6 py-4 text-right">
                                    {{-- Amallar tugmalari --}}
                                    <div class="flex justify-end items-center gap-2">
                                        {{-- Tahrirlash tugmasi --}}
                                        <button type="button" wire:click="editBranch({{ $branch->id }})" title="Tahrirlash"
                                                class="text-blue-600 hover:text-blue-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.796a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </button>
                                        {{-- O'chirish tugmasi --}}
                                        <button type="button" wire:click="openDeleteModal({{ $branch->id }})"
                                                title="O'chirish" class="text-red-600 hover:text-red-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Filiallar topilmadi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginatsiya --}}
            <div class="mt-4">
                {{ $this->branches->links() }}
            </div>

        </div>
    </div>

    {{-- Tahrirlash uchun Modal --}}
    @if($showEditModal && $editingBranch)
        {{-- x-data va x-show qo'shildi, wire:model.defer olib tashlandi --}}
        <div x-data="{ show: @entangle('showEditModal') }"
            x-show="show"
            x-on:keydown.escape.window="show = false" {{-- Esc bosilganda yopish --}}
            class="fixed inset-0 z-40 flex items-center justify-center overflow-y-auto"
            aria-labelledby="edit-branch-modal-title"
            role="dialog"
            aria-modal="true"
            style="display: none;" {{-- Boshida yashirin turishi uchun --}}
            >
            {{-- Modal foni --}}
            <div x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/75 transition-opacity"
                @click="show = false" {{-- Fon bosilganda yopish --}}
                aria-hidden="true"></div>

            {{-- Modal paneli --}}
            <div x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                @click.away="show = false" {{-- Modal tashqarisi bosilganda yopish --}}
                >
                {{-- Filament Modal komponentini ishlatish o'rniga, standart modal strukturasi --}}
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="edit-branch-modal-title">
                                Filialni Tahrirlash: {{ $editingBranch->name }}
                            </h3>
                            <div class="mt-4">
                                {{-- Tahrirlash formasi --}}
                                <form wire:submit.prevent="updateBranch">
                                    {{ $this->editForm }}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    {{-- O'zgarishlarni Saqlash tugmasi --}}
                    <button
                        type="button"
                        wire:click="updateBranch"
                        wire:loading.attr="disabled"
                        wire:target="updateBranch" {{-- Loading state uchun target --}}
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 disabled:opacity-75"
                        >
                        {{-- Loading SVG --}}
                        <svg wire:loading wire:target="updateBranch" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                        {{-- Tugma matni --}}
                        <span wire:loading.remove wire:target="updateBranch">O'zgarishlarni Saqlash</span>
                        <span wire:loading wire:target="updateBranch">Saqlanmoqda...</span>
                    </button>

                    {{-- Bekor qilish tugmasi --}}
                    <button
                        type="button"
                        wire:click="$set('showEditModal', false)"
                        class="mr-2 inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-black bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-black dark:border-gray-600 dark:hover:bg-gray-600"
                        >
                        Bekor qilish
                    </button>
                </div>   
            </div>
        </div>
    @endif

     {{-- O'chirishni tasdiqlash Modali --}}
    @if($showDeleteModal && $deletingBranchId)
        <div x-data="{ show: @entangle('showDeleteModal') }"
            x-show="show"
            x-on:keydown.escape.window="show = false"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"
            aria-labelledby="delete-branch-modal-title"
            role="dialog"
            aria-modal="true"
            style="display: none;"
            >
            {{-- Modal foni --}}
            <div x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/75 transition-opacity"
                @click="show = false"
                aria-hidden="true"></div>

            {{-- Modal paneli --}}
            <div x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                @click.away="show = false"
                >
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        {{-- Ikonka --}}
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100" id="delete-branch-modal-title">
                                Filialni o'chirishni tasdiqlang
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Haqiqatan ham "<strong>{{ $deletingBranchName }}</strong>" nomli filialni o'chirmoqchimisiz? Bu amalni ortga qaytarib bo'lmaydi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    {{-- O'chirishni tasdiqlash tugmasi --}}
                    <button type="button"
                            wire:click="confirmDeleteBranch"
                            wire:loading.attr="disabled"
                            wire:target="confirmDeleteBranch"
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto disabled:opacity-75">
                        <svg wire:loading wire:target="confirmDeleteBranch" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Ha, o'chirish
                    </button>
                    {{-- Bekor qilish tugmasi --}}
                    <button type="button"
                            wire:click="$set('showDeleteModal', false)"
                            wire:loading.attr="disabled"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto dark:bg-gray-700 dark:text-gray-200 dark:ring-gray-600 dark:hover:bg-gray-600">
                        Yo'q, bekor qilish
                    </button>
                </div>
            </div>
        </div>
    @endif

</x-filament::page>