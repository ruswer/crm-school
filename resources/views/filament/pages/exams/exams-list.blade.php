<x-filament::page>
    <div class="flex flex-col h-full">
        {{-- Tepa qism: Filterlar --}}
        <div class="mb-4 bg-white dark:bg-gray-800 rounded-md shadow-sm">
            {{-- ... Filterlar qismi (o'zgarishsiz) ... --}}
             {{-- Sarlavha qismi --}}
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">Tanlash</span>
                </div>
                {{-- Filtrlarni tozalash tugmasi --}}
                <button
                    wire:click="resetFilters"
                    type="button"
                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Tozalash
                </button>
            </div>

            {{-- Filterlar qismi --}}
            <div class="p-4">
                <div class="flex flex-col gap-4 w-full">
                    {{-- Tepa qator: Filial va Guruh select --}}
                    <div class="flex flex-wrap lg:flex-nowrap gap-4">
                        {{-- Filial bo'yicha qidirish --}}
                        <div class="w-full lg:w-1/2">
                            <label for="branch" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filial</label>
                            <select id="branch"
                                    wire:model.defer="selectedBranch"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha filiallar</option>
                                {{-- Filiallarni dinamik yuklash (pluck natijasi) --}}
                                @foreach($this->branches as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Guruh bo'yicha qidirish --}}
                        <div class="w-full lg:w-1/2">
                            <label for="group" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guruh</label>
                            <select id="group"
                                    wire:model.defer="selectedGroup"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha guruhlar</option>
                                {{-- Guruhlarni dinamik yuklash (pluck natijasi) --}}
                                @foreach($this->groups as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Pastki qator: Qidirish tugmasi --}}
                    <div class="flex justify-end">
                        {{-- Qidirish tugmasi --}}
                        <button
                            type="button"
                            wire:click="applyFilters"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-auto" {{-- w-full olib tashlandi --}}
                        >
                            <svg wire:loading wire:target="applyFilters" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg wire:loading.remove wire:target="applyFilters" class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Qidirish
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pastki qism: Kontent (Jadval) --}}
        <div class="flex-1 bg-white dark:bg-gray-800 rounded-md shadow-sm p-4">
            {{-- Sarlavha va tugma --}}
            <div class="flex items-center justify-between mb-4 pb-4 border-b dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">Imtihonlar ro'yxati</h2>

                {{-- Mavjud Qo'shish tugmasiga wire:click qo'shildi --}}
                <button type="button"
                        wire:click="openCreateModal"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Qo'shish
                </button>
            </div>

            {{-- Jadval va qolgan qismlar --}}
            <div class="overflow-x-auto">
                {{-- ... Jadval qidiruvi ... --}}
                 <div class="relative m-[2px] mb-3 mr-5 float-left">
                    <label for="tableSearch" class="sr-only">Qidirish</label>
                    <input id="tableSearch" type="text" placeholder="Jadvaldan qidirish..."
                           wire:model.live.debounce.300ms="search" {{-- Livewire real-time qidiruv --}}
                           class="block w-64 rounded-lg border dark:border-gray-600 dark:bg-gray-700 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400 dark:text-gray-200" />
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-neutral-500 dark:text-neutral-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                </div>

                {{-- Table --}}
                <table class="min-w-full text-left text-sm whitespace-nowrap clear-left">
                     <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                        <tr>
                            {{-- Ustun nomlari va saralash --}}
                            @php
                                $columns = [
                                    'Filial' => 'branch',
                                    'Guruh' => 'group',
                                    'Imtihon nomi' => 'name', // Yangi ustun
                                    'Imtihon sanasi' => 'examDate',
                                    'Imtihon vaqti' => 'examTime',
                                    'Holati' => 'status', // Yangi ustun
                                    // 'Tavsifi' => 'Izoh', // Agar kerak bo'lsa
                                ];
                            @endphp
                            @foreach ($columns as $label => $columnKey)
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('{{ $columnKey }}')">
                                    <div class="flex items-center gap-1">
                                        <span>{{ $label }}</span>
                                        {{-- Saralash ikonkalari --}}
                                        @php
                                            $actualSortField = match ($columnKey) {
                                                'branch' => 'branch_id',
                                                'group' => 'group_id',
                                                'examDate' => 'exam_date',
                                                'examTime' => 'exam_time',
                                                'Izoh' => 'description',
                                                'name' => 'name',
                                                'status' => 'status',
                                                default => $columnKey,
                                            };
                                        @endphp
                                        @if ($sortField === $actualSortField)
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if ($sortDirection === 'asc') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /> @else <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /> @endif
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /> </svg>
                                        @endif
                                    </div>
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-3">Amallar</th>
                        </tr>
                    </thead>
                    <tbody class="dark:text-gray-200">
                        @forelse ($this->exams as $exam)
                            <tr class="border-b dark:border-neutral-600 hover:bg-gray-50 dark:hover:bg-gray-700" wire:key="exam-{{ $exam->id }}">
                                <td class="px-6 py-4">{{ $exam->branch?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $exam->group?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $exam->name }}</td> {{-- Yangi ustun --}}
                                <td class="px-6 py-4">{{ $exam->exam_date?->format('Y-m-d') ?? $exam->exam_date }}</td>
                                <td class="px-6 py-4">{{ $exam->exam_time }}</td>
                                <td class="px-6 py-4">{{ $exam->status }}</td> {{-- Yangi ustun --}}
                                {{-- <td class="px-6 py-4">{{ $exam->description }}</td> --}}
                                <td class="px-6 py-4">
                                    <div class="flex space-x-3">
                                        <button type="button"
                                                wire:click="openMarkingModal({{ $exam->id }})" {{-- wire:click izohdan chiqarildi va metod nomi o'zgartirildi --}}
                                                title="Imtihonni baholash"
                                                class="text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                         <button type="button"
                                            wire:click="editExam({{ $exam->id }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L9.38 17.273a4.5 4.5 0 01-1.897 1.13l-2.745.823.823-2.745a4.5 4.5 0 011.13-1.897l10.171-10.17z" />
                                            </svg>
                                        </button>
                                        <button type="button"
                                            wire:click="deleteExam({{ $exam->id }})"
                                            wire:confirm="Haqiqatan ham bu imtihonni o'chirmoqchimisiz?" {{-- O'chirishni tasdiqlash --}}
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                           <tr>
                                <td colspan="{{ count($columns) + 1 }}"> {{-- colspan to'g'rilandi --}}
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Ma'lumot topilmadi</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Tanlangan filterlar bo'yicha imtihonlar topilmadi.
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
                {{-- ... Pagination ... --}}
                 <div class="mt-4">
                    {{ $this->exams->links() }}
                </div>
            </div>
        </div>

        {{-- Qo'shish uchun Modal Oyna --}}
        @if($showCreateExamModal)
            {{-- Tashqi konteyner: Markazlash va padding uchun. overflow-y-auto olib tashlandi --}}
            <div class="fixed inset-0 z-40 flex items-start sm:items-center justify-center p-4" {{-- overflow-y-auto olib tashlandi, padding qoldi --}}
                aria-labelledby="modal-title" role="dialog" aria-modal="true"
                x-data="{ show: @entangle('showCreateExamModal') }"
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                style="display: none;"
                >
                {{-- Modal foni --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="show = false"></div>

                {{-- Modal paneli: Styling, max-width, flex-col va max-height qo'shildi --}}
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full flex flex-col max-h-[calc(100vh-2rem)]" {{-- flex, flex-col, max-h qo'shildi (4rem o'rniga 2rem sinab ko'ramiz) --}}
                    x-show="show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.away="show = false"
                    >

                    {{-- 1. Modal Sarlavhasi (Header) - padding, border-b --}}
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200 dark:border-gray-700 flex-shrink-0"> {{-- border-b va flex-shrink-0 qo'shildi --}}
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Yangi Imtihon Qo'shish
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Modal Asosiy Qismi (Body) - flex-1 va overflow-y-auto qo'shildi --}}
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 flex-1 overflow-y-auto"> {{-- flex-1 va overflow-y-auto qo'shildi --}}
                        {{-- Forma endi shu div ichida scroll bo'ladi --}}
                        <form wire:submit.prevent="createExam">
                            {{ $this->createExamForm }}
                        </form>
                    </div>

                    {{-- 3. Modal Pastki Qismi (Footer) - padding, border-t --}}
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-600 flex-shrink-0"> {{-- border-t va flex-shrink-0 qo'shildi --}}
                        <button type="button"
                                wire:click="createExam"
                                wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <span wire:loading wire:target="createExam" class="mr-2">
                                {{-- ... loading svg ... --}}
                            </span>
                            Saqlash
                        </button>
                        <button type="button"
                                wire:click="closeCreateModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Bekor qilish
                        </button>
                    </div>

                </div>
            </div>
        @endif
        {{-- /Qo'shish uchun Modal Oyna --}}

        {{-- Baholash uchun Modal Oyna --}}
        @if($showMarkingModal && $examToMark)
            <div class="fixed inset-0 z-50 flex items-start sm:items-center justify-center p-4" {{-- z-index oshirildi --}}
                aria-labelledby="marking-modal-title" role="dialog" aria-modal="true"
                x-data="{ showMarking: @entangle('showMarkingModal') }"
                x-show="showMarking"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                style="display: none;">
                {{-- Modal foni --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showMarking = false"></div>

                {{-- Modal paneli --}}
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full flex flex-col max-h-[calc(100vh-4rem)]" {{-- max-width o'zgartirildi --}}
                    x-show="showMarking"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.away="showMarking = false">

                    {{-- 1. Modal Sarlavhasi --}}
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                                {{-- Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-600 dark:text-green-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="marking-modal-title">
                                    "{{ $examToMark->name }}" imtihonini baholash
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Guruh: {{ $examToMark->group?->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Modal Asosiy Qismi (O'quvchilar ro'yxati) --}}
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 flex-1 overflow-y-auto">
                        <form wire:submit.prevent="saveMarks">
                            <div class="space-y-4">
                                @if(count($studentsToMark) > 0)
                                    @foreach($studentsToMark as $student)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md" wire:key="student-mark-{{ $student->id }}">
                                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </span>
                                            <div>
                                                <label for="mark-{{ $student->id }}" class="sr-only">Baho</label>
                                                <input type="number"
                                                    id="mark-{{ $student->id }}"
                                                    wire:model.defer="marks.{{ $student->id }}"
                                                    placeholder="Baho"
                                                    min="0"
                                                    max="6" {{-- Maksimal bahoni 6 ga o'zgartirdim --}}
                                                    class="w-20 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-center
                                                    @error('marks.' . $student->id) border-red-500 dark:border-red-400 @enderror"> {{-- Xato bo'lsa border qizil bo'lishi --}}

                                                {{-- Xatolikni ko'rsatish --}}
                                                @error('marks.' . $student->id)
                                                    <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</span>
                                                @enderror
                                                {{-- /Xatolikni ko'rsatish --}}

                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">Bu guruhda o'quvchilar topilmadi.</p>
                                @endif
                            </div>
                            {{-- Forma submit tugmasi footerda bo'ladi --}}
                        </form>
                    </div>

                    {{-- 3. Modal Pastki Qismi (Footer) --}}
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-600 flex-shrink-0">
                        <button type="button" {{-- Formadan tashqarida, lekin wire:click bilan ishlaydi --}}
                                wire:click="saveMarks"
                                wire:loading.attr="disabled"
                                wire:target="saveMarks" {{-- Loading state uchun target --}}
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <svg wire:loading wire:target="saveMarks" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Baholarni Saqlash
                        </button>
                        <button type="button"
                                wire:click="closeMarkingModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Bekor qilish
                        </button>
                    </div>

                </div>
            </div>
        @endif
        {{-- /Baholash uchun Modal Oyna --}}
    </div>
</x-filament::page>
