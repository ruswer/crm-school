<x-filament::page>
    <div class="flex flex-col h-full">
        <!-- Tepa qism (Filtrlar) -->
        <div class="mb-4 bg-white rounded-md shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800">Tanlash</span>
                </div>
                {{-- Reset tugmasi --}}
                @if($selectedBranch || $selectedGroup || $search)
                    <button wire:click="resetFilters" type="button" class="text-sm text-gray-500 hover:text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Tozalash
                    </button>
                @endif
            </div>

            {{-- Pastki qism:qidirish (Eski struktura qaytarildi) --}}
            <div class="flex flex-col lg:flex-row items-start justify-between bg-white p-4 gap-4">
                {{-- Chap tomon: Filial va guruh bo'yicha qidirish --}}
                <div class="flex flex-col gap-4 w-full lg:w-[60%]">
                    {{-- Tepa qator: Filial va Guruh select --}}
                    <div class="flex flex-wrap lg:flex-nowrap gap-4">
                        <div class="w-full sm:w-1/2 lg:flex-1">
                            <label for="branch" class="block text-sm font-medium text-gray-700">Filial</label>
                            <select
                                wire:model.defer="selectedBranch"
                                id="branch"
                                name="branch"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha filiallar</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-1/2 lg:flex-1">
                            <label for="group" class="block text-sm font-medium text-gray-700">Guruh</label>
                            <select
                                wire:model.defer="selectedGroup"
                                id="group"
                                name="group"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha guruhlar</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Pastki qator: Filial/Guruh Qidirish tugmasi --}}
                    <div class="flex justify-end">
                        <button
                            wire:click="searchByFilters" {{-- Komponentda shu nomli metod bo'lishi kerak --}}
                            type="button"
                            wire:loading.attr="disabled" wire:target="searchByFilters"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto"
                        >
                            <svg wire:loading wire:target="searchByFilters" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg wire:loading.remove wire:target="searchByFilters" class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Qidirish (Filtr)
                        </button>
                    </div>
                </div>

                {{-- O'ng tomon: Kalit so'z bilan qidirish --}}
                <div class="w-full lg:w-[30%]">
                    <div class="flex flex-col gap-4">
                        {{-- Tepa qator: Kalit so'z input --}}
                        <div class="w-full">
                            <label for="keyword-search" class="block text-sm font-medium text-gray-700">Kalit so'z bo'yicha izlash</label>
                            <input
                                wire:model.defer="search"
                                type="search"
                                id="keyword-search"
                                name="keyword-search"
                                placeholder="Ism, familiya, telefon..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        {{-- Pastki qator: Kalit so'z Qidirish tugmasi --}}
                        <div class="flex justify-end">
                            <button
                                wire:click="searchByKeyword"
                                type="button"
                                wire:loading.attr="disabled" wire:target="searchByKeyword, search"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto"
                            >
                                <svg wire:loading wire:target="searchByKeyword, search" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg wire:loading.remove wire:target="searchByKeyword, search" class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Qidirish (Kalit so'z)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pastki qism: Kontent (Jadval) -->
        <div class="flex-1 mt-4 bg-white rounded-md shadow-sm p-4">
            <h2 class="text-lg font-bold text-gray-800 mb-4 py-4 border-b border-gray-200">Safdan Chiqarilgan O'quvchilar Hisoboti</h2>
            <div class="overflow-x-auto bg-white dark:bg-neutral-700">
                {{-- Jadval ichidagi qidiruv inputi olib tashlandi --}}
                {{-- <div class="relative m-[2px] mb-3 mr-5 float-left"> ... </div> --}}

                 <!-- Table -->
                 <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 text-gray-800">
                        <tr>
                            {{-- Saralash sarlavhalari --}}
                            @php
                                $columns = [
                                    'O\'quvchi Ismi' => 'name',
                                    'Filial' => 'branch',
                                    'Tug\'ilgan kuni' => 'birth_date',
                                    'Jinsi' => 'gender',
                                    'Tel.raqami' => 'phone',
                                    'Sabab' => 'reason',
                                ];
                            @endphp
                            @foreach ($columns as $label => $column)
                                <th scope="col" class="px-6 py-4">
                                    <button type="button" wire:click="sort('{{ $column }}')" class="flex items-center gap-1 uppercase tracking-wider">
                                        {{ $label }}
                                        {{-- Saralash ikonkalari --}}
                                        @if($sortField === ($column === 'name' ? 'first_name' : $column))
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($sortDirection === 'asc')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                @endif
                                            </svg>
                                        @else
                                             <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                             </svg>
                                        @endif
                                    </button>
                                </th>
                            @endforeach
                            {{-- Yangi "Amallar" ustuni sarlavhasi --}}
                            <th scope="col" class="px-6 py-4 uppercase tracking-wider">Amallar</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Ma'lumotlar Livewire komponentidan keladi ($this->deletedStudents) --}}
                        @forelse($this->deletedStudents as $student)
                            <tr class="border-b dark:border-neutral-600 hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </td>
                                <td class="px-6 py-4">{{ $student->branch?->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d.m.Y') : '-' }}</td>
                                <td class="px-6 py-4">{{ $student->gender == 'male' ? 'Erkak' : ($student->gender == 'female' ? 'Ayol' : '-') }}</td>
                                <td class="px-6 py-4">{{ $student->phone }}</td>
                                <td class="px-6 py-4">{{ $student->removalReason?->name ?? 'Noma\'lum' }}</td>
                                {{-- "Amallar" ustuni --}}
                                <td class="px-6 py-4">
                                    {{-- space-x-2 ni space-x-3 ga o'zgartirdim --}}
                                    <div class="flex items-center space-x-3">
                                        {{-- Qayta tiklash tugmasi --}}
                                        <button type="button"
                                            wire:click="confirmRestoreStudent({{ $student->id }}, '{{ $student->first_name }} {{ $student->last_name }}')" {{-- ID va Ismni uzatish --}}
                                            class="text-green-600 hover:text-green-800" {{-- Rangni yashilga o'zgartirdim --}}
                                            title="Qayta tiklash">
                                            {{-- Qayta tiklash ikonkasini qo'shdim (heroicon-o-arrow-uturn-left) --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                            </svg>
                                        </button>

                                        {{-- O'chirish tugmasi (Butunlay o'chirish uchun) --}}
                                        <button type="button"
                                            wire:click="confirmForceDelete({{ $student->id }}, '{{ $student->first_name }} {{ $student->last_name }}')" {{-- ID va Ismni uzatish --}}
                                            class="text-red-600 hover:text-red-800"
                                            title="Butunlay o'chirish">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            {{-- Ma'lumot topilmaganda ko'rsatiladigan xabar --}}
                            <tr>
                                <td colspan="8"> {{-- Ustunlar soni 8 ga o'zgardi --}}
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Ma'lumot topilmadi</h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Sizning qidiruv yoki filtr so'rovingiz bo'yicha hech qanday safdan chiqarilgan o'quvchi topilmadi.
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

                {{-- Livewire Pagination --}}
                <div class="px-4 py-3 border-t">
                    {{ $this->deletedStudents->links() }}
                </div>
            </div>
        </div>

        {{-- Qayta tiklashni tasdiqlash Modali --}}
        @if($showRestoreConfirmModal)
            <div class="fixed inset-0 z-[1999] overflow-y-auto" aria-labelledby="restore-modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    {{-- Modal foni --}}
                    <div x-data x-show="$wire.showRestoreConfirmModal" x-transition.opacity.duration.300ms
                         class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                         aria-hidden="true" @click="$wire.set('showRestoreConfirmModal', false)"></div>

                    {{-- Modal kontenti --}}
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-data x-show="$wire.showRestoreConfirmModal"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="restore-modal-title">
                                Studentni Qayta Tiklash
                            </h3>
                            <button type="button" @click="$wire.set('showRestoreConfirmModal', false)" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm text-gray-600">
                                Haqiqatan ham **{{ $studentToRestoreName ?? 'tanlangan student' }}**ni qayta tiklamoqchimisiz? U aktiv studentlar ro'yxatiga qaytariladi.
                            </p>
                        </div>

                        <div class="pt-5 mt-6 border-t border-gray-200 sm:flex sm:flex-row-reverse">
                            <button type="button"
                                    wire:click="restoreStudent"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-75 cursor-wait"
                                    class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                                <span wire:loading wire:target="restoreStudent" class="mr-2">
                                    <svg class="w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                <span wire:loading.remove wire:target="restoreStudent">
                                    Ha, Qayta Tiklash
                                </span>
                                 <span wire:loading wire:target="restoreStudent">
                                    Bajarilmoqda...
                                </span>
                            </button>
                            <button type="button"
                                    @click="$wire.set('showRestoreConfirmModal', false)"
                                    wire:loading.attr="disabled"
                                    class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Bekor qilish
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Butunlay o'chirishni tasdiqlash Modali --}}
        @if($showForceDeleteConfirmModal)
            <div class="fixed inset-0 z-[1999] overflow-y-auto" aria-labelledby="force-delete-modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    {{-- Modal foni --}}
                    <div x-data x-show="$wire.showForceDeleteConfirmModal" x-transition.opacity.duration.300ms
                         class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                         aria-hidden="true" @click="$wire.set('showForceDeleteConfirmModal', false)"></div>

                    {{-- Modal kontenti --}}
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-data x-show="$wire.showForceDeleteConfirmModal"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-red-700" id="force-delete-modal-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Studentni Butunlay O'chirish
                            </h3>
                            <button type="button" @click="$wire.set('showForceDeleteConfirmModal', false)" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm text-gray-600">
                                Haqiqatan ham **{{ $studentToForceDeleteName ?? 'tanlangan student' }}**ni bazadan butunlay o'chirmoqchimisiz?
                                <strong class="text-red-600">Bu amalni orqaga qaytarib bo'lmaydi!</strong>
                                Studentga tegishli barcha ma'lumotlar (to'lovlar, davomat va hokazo) o'chib ketadi.
                            </p>
                        </div>

                        <div class="pt-5 mt-6 border-t border-gray-200 sm:flex sm:flex-row-reverse">
                            <button type="button"
                                    wire:click="forceDeleteStudent"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-75 cursor-wait"
                                    class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                                <span wire:loading wire:target="forceDeleteStudent" class="mr-2">
                                    <svg class="w-5 h-5 text-white animate-spin" ...></svg> {{-- Loading SVG --}}
                                </span>
                                <span wire:loading.remove wire:target="forceDeleteStudent">
                                    Ha, Butunlay O'chirish
                                </span>
                                 <span wire:loading wire:target="forceDeleteStudent">
                                    O'chirilmoqda...
                                </span>
                            </button>
                            <button type="button"
                                    @click="$wire.set('showForceDeleteConfirmModal', false)"
                                    wire:loading.attr="disabled"
                                    class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Bekor qilish
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-filament::page>
