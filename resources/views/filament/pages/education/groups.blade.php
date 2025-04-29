<x-filament::page>
    <div class="flex flex-col lg:flex-row lg:items-start gap-4">
        {{-- Chap panel (Guruh qo'shish) --}}
        <div class="w-full lg:w-[30%] bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 flex-shrink-0">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b dark:border-gray-700">Guruh qo'shish</h2>
            {{-- Forma --}}
            <form wire:submit.prevent="createGroup" class="space-y-4">
                {{ $this->createGroupForm }}

                {{-- Saqlash tugmasi --}}
                <div class="flex justify-end pt-4">
                    <x-filament::button type="submit" wire:loading.attr="disabled">
                         <svg wire:loading wire:target="createGroup" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saqlash
                    </x-filament::button>
                </div>
            </form>
        </div>

        {{-- O'ng panel (Guruhlar ro'yxati) --}}
        <div class="w-full lg:w-[70%] bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b dark:border-gray-700">Guruhlar ro'yxati</h2>

            {{-- Status filterlari --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                {{-- Status filterlari --}}
                <div class="inline-flex rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-1" x-data="{ selected: @entangle('selectedStatus') }">
                    <button type="button"
                            wire:click="filterByStatus('active')"
                            :class="{
                                'bg-green-600 shadow-sm text-white': selected === 'active',
                                'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300': selected !== 'active'
                            }"
                            class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Faol
                    </button>

                    <button type="button"
                            wire:click="filterByStatus('waiting')"
                            :class="{
                                'bg-gray-500 shadow-sm text-white': selected === 'waiting',
                                'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300': selected !== 'waiting'
                            }"
                            class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Kutilmoqda
                    </button>

                    <button type="button"
                            wire:click="filterByStatus('deleted')"
                            :class="{
                                'bg-red-600 shadow-sm text-white': selected === 'deleted',
                                'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300': selected !== 'deleted'
                            }"
                            class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        O'chirilgan
                    </button>
                </div>
            </div>

            {{-- Jadval --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                        <tr>
                            {{-- Guruh ustuni --}}
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('name')">
                                <div class="flex items-center gap-x-1">
                                    <span>Guruh</span>
                                    <x-heroicon-s-chevron-up-down class="w-4 h-4 {{ $sortField === 'name' ? '' : 'text-gray-400' }}" />
                                </div>
                            </th>
                            {{-- O'qituvchi ustuni --}}
                             <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('teacher_id')">
                                <div class="flex items-center gap-x-1">
                                    <span>O'qituvchi</span>
                                     <x-heroicon-s-chevron-up-down class="w-4 h-4 {{ $sortField === 'teacher_id' ? '' : 'text-gray-400' }}" />
                                </div>
                            </th>
                            {{-- O'quvchilar soni ustuni --}}
                             <th scope="col" class="px-6 py-3">
                                <span>O'quvchilar soni</span>
                            </th>
                            {{-- Amallar ustuni --}}
                            <th scope="col" class="px-6 py-3 text-right">Amallar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->groups as $group)
                            <tr class="border-b dark:border-neutral-600 hover:bg-gray-50 dark:hover:bg-gray-700" wire:key="group-{{ $group->id }}">
                                {{-- Guruh nomi --}}
                                <td class="px-6 py-4">{{ $group->name }}</td>
                                {{-- O'qituvchi nomi --}}
                                <td class="px-6 py-4">{{ $group->teacher->fullName ?? 'N/A' }}</td>
                                {{-- O'quvchilar soni --}}
                                <td class="px-6 py-4">{{ $group->students_count ?? $group->students()->count() }}</td>
                                {{-- Amallar --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        @if($selectedStatus === 'deleted')
                                            {{-- Tiklash tugmasi --}}
                                            <button type="button" wire:click="restoreGroup({{ $group->id }})" title="Tiklash"
                                                    class="text-yellow-500 hover:text-yellow-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                </svg>
                                            </button>
                                            {{-- Butunlay o'chirish tugmasi --}}
                                            <button type="button" wire:click="deleteGroup({{ $group->id }})"
                                                    wire:confirm="Haqiqatan ham bu guruhni butunlay o'chirmoqchimisiz? Bu amalni ortga qaytarib bo'lmaydi!"
                                                    title="Butunlay o'chirish" class="text-red-600 hover:text-red-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        @else
                                            {{-- Tahrirlash tugmasi --}}
                                            <button type="button" wire:click="editGroup({{ $group->id }})" title="Tahrirlash"
                                                    class="text-blue-600 hover:text-blue-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.796a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </button>
                                            {{-- O'chirish (arxivlash) tugmasi --}}
                                            <button type="button" wire:click="deleteGroup({{ $group->id }})"
                                                    wire:confirm="Haqiqatan ham bu guruhni o'chirmoqchimisiz (arxivlamoqchimisiz)?"
                                                    title="O'chirish (Arxiv)" class="text-red-600 hover:text-red-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                {{-- Colspan 4 ga o'zgartirildi --}}
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ $selectedStatus === 'deleted' ? 'O\'chirilgan guruhlar topilmadi.' : ($selectedStatus === 'waiting' ? 'Kutilayotgan guruhlar topilmadi.' : 'Faol guruhlar topilmadi.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginatsiya --}}
            <div class="mt-4">
                {{ $this->groups->links() }}
            </div>

        </div>
    </div>

    {{-- Tahrirlash uchun Modal --}}
    {{-- ... --}}

</x-filament::page>
