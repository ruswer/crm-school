<x-filament::page>
    <div class="flex flex-col h-full"> <!-- Umumiy orqa fon qo'shildi -->
        <!-- Tepa qism -->
        <div class="flex-1 bg-white rounded-md shadow-sm">
            <div class="flex items-center justify-between p-4 rounded-md shadow-sm bg-white">
                <!-- Chap tomon: Tanlash sarlavhasi va search ikonka -->
                <div class="flex items-center">
                    <button
                        type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                        O'quvchi Import qilish
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </button>
                </div>
                <!-- O'ng tomon: O'quvchi Qo'shish tugmasi -->
                <div>
                    <x-filament::button
                        tag="a"
                        href="{{ url('/admin/add-student') }}"
                        color="primary"
                        icon="heroicon-o-plus"
                    >
                        O'quvchi qo'shish
                    </x-filament::button>
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
                            <select 
                                wire:model="selectedBranch"
                                id="branch" 
                                name="branch" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="">Barcha filiallar</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Guruh bo'yicha qidirish -->
                        <div class="w-full sm:w-1/2 lg:flex-1">
                            <label for="group" class="block text-sm font-medium text-gray-700">Guruh</label>
                            <select 
                                wire:model="selectedGroup"
                                id="group" 
                                name="group" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="">Barcha guruhlar</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                   <!-- Filial va guruh qidiruv tugmasi -->
                    <div class="flex justify-end">
                        <button
                            wire:click="searchByFilters"
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
                                wire:model.debounce.300ms="search"
                                type="text"
                                id="student-name"
                                name="student-name"
                                placeholder="Ism, telefon yoki ID kiriting"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <!-- Kalit so'z qidiruv tugmasi -->
                        <div class="flex justify-end">
                            <button
                                wire:click="searchByKeyword"
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
        </div>
        <!-- Pastki qism: Kontent -->
        <div class="flex-1 mt-4 bg-white rounded-md shadow-sm p-4">
            <!-- Table responsive wrapper -->
            <div class="overflow-x-auto bg-white">
                <!-- Table -->
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <!-- Table head -->
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 text-gray-800">
                        <tr>
                            @foreach ([
                                'O\'quvchi Ismi' => 'name',
                                'Filial' => 'branch',
                                'Holati' => 'status',
                                'Kurs' => 'course',
                                'Guruh' => 'group',
                                'Tel.raqami' => 'phone',
                                'Til' => 'language',
                                'Darajasi' => 'level',
                                'Dars kunlari' => 'study_days'
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
                        @if($this->students->total() === 0 && !$this->isFilterActive && !$this->isSearchActive)
                            <!-- Database Empty state - faqat bazada umuman ma'lumot yo'q bo'lganda -->
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">O'quvchilar mavjud emas</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Hozircha tizimda birorta ham o'quvchi ro'yxatdan o'tkazilmagan.
                                </p>
                                <div class="mt-6">
                                    <x-filament::button
                                        tag="a"
                                        href="{{ url('/admin/add-student') }}"
                                        color="primary"
                                        icon="heroicon-o-plus"
                                    >
                                        O'quvchi qo'shish
                                    </x-filament::button>
                                </div>
                            </div>
                        @elseif($this->students->isEmpty() && ($this->isFilterActive || $this->isSearchActive))
                            <!-- Search Empty state - qidiruv natijasi bo'sh bo'lganda -->
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Ma'lumot topilmadi</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Sizning so'rovingiz bo'yicha hech qanday ma'lumot topilmadi.
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
                        @else
                            @foreach($this->students as $student)
                                <tr class="border-b dark:border-neutral-600">
                                    <td class="px-6 py-4">
                                        <a href="#"
                                            wire:click="showStudentProfile({{ $student->id }})"
                                            class="text-primary-600 hover:text-primary-900 hover:underline">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">{{ $student->branch->name }}</td>
                                    <td class="px-6 py-4">{{ $student->status->name }}</td>
                                    <td class="px-6 py-4">{{ $student->courses->first()->name ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @foreach($student->groups as $group)
                                            {{ $group->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">{{ $student->phone }}</td>
                                    <td class="px-6 py-4">
                                        @if($student->studyLanguagesStudents && $student->studyLanguagesStudents->count() > 0)
                                            @foreach($student->studyLanguagesStudents as $language)
                                                {{ $language->language }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $student->knowledgeLevel->name ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @foreach($student->studyDays as $day)
                                            {{ $day->day }}{{ !$loop->last ? '/' : '' }}
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="#" 
                                        wire:click="editStudent({{ $student->id }})" 
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
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <div class="mt-4 px-4 py-3 bg-white border-t">
                    @if($this->students->hasPages())
                        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                            <div class="flex justify-between flex-1 sm:hidden">
                                <span>
                                    @if ($this->students->onFirstPage())
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                            {!! __('pagination.previous') !!}
                                        </span>
                                    @else
                                        <button wire:click="previousPage" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                            {!! __('pagination.previous') !!}
                                        </button>
                                    @endif
                                </span>
                
                                <span>
                                    @if ($this->students->hasMorePages())
                                        <button wire:click="nextPage" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                            {!! __('pagination.next') !!}
                                        </button>
                                    @else
                                        <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                            {!! __('pagination.next') !!}
                                        </span>
                                    @endif
                                </span>
                            </div>
                
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700 leading-5">
                                        {!! __('Showing') !!}
                                        <span class="font-medium">{{ $this->students->firstItem() }}</span>
                                        {!! __('to') !!}
                                        <span class="font-medium">{{ $this->students->lastItem() }}</span>
                                        {!! __('of') !!}
                                        <span class="font-medium">{{ $this->students->total() }}</span>
                                        {!! __('results') !!}
                                    </p>
                                </div>
                
                                <div>
                                    <span class="relative z-0 inline-flex rounded-md shadow-sm">
                                        {{-- Previous Page Link --}}
                                        @if ($this->students->onFirstPage())
                                            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                                <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md leading-5" aria-hidden="true">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </span>
                                        @else
                                            <button wire:click="previousPage" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        @endif
                
                                        {{-- Pagination Elements --}}
                                        @foreach ($this->students->getUrlRange(1, $this->students->lastPage()) as $page => $url)
                                            @if ($page == $this->students->currentPage())
                                                <span aria-current="page">
                                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-blue-50 border border-gray-300 cursor-default leading-5">{{ $page }}</span>
                                                </span>
                                            @else
                                                <button wire:click="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                    {{ $page }}
                                                </button>
                                            @endif
                                        @endforeach
                
                                        {{-- Next Page Link --}}
                                        @if ($this->students->hasMorePages())
                                            <button wire:click="nextPage" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        @else
                                            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                                <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md leading-5" aria-hidden="true">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </nav>
                    @endif
                </div>   
            </div>
        </div>
    </div>
</x-filament::page>