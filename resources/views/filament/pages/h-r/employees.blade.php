<x-filament::page>
    <div class="flex flex-col h-full" x-data>

        <!-- ================== Tepa qism: Filtrlar (Standart HTML + Tailwind + Livewire) ================== -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6">
            {{-- Sarlavha va "Xodim qo'shish" tugmasi --}}
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <div class="flex items-center gap-2">
                    {{-- Users Icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.01A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">Tanlash</span>
                </div>
                {{-- "Xodim qo'shish" tugmasi (Standart HTML 'a' tegi) --}}
                {{-- TODO: 'href' manzilini to'g'rilang. Masalan: {{ route('filament.admin.pages.hr.employee.create') }} --}}
                <a href="{{ \App\Filament\Pages\HR\CreateEmployeePage::getUrl() }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-transparent bg-warning-600 px-3 py-1.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-warning-500 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{-- Plus Icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 -ml-0.5">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Xodim qo'shish
                </a>
            </div>

            {{-- Filtrlar formasi --}}
            <div class="p-4">
                <div class="flex flex-col gap-4 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Filial filtri (Standart Select) --}}
                        <div>
                            <label for="branch" class="block text-sm font-medium text-gray-700">Filial</label>
                            {{-- wire:model.live - lavozimlarni yangilash uchun --}}
                            <select wire:model.live="branchFilter" id="branch"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-primary-500 dark:focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha filiallar</option>
                                @foreach($this->branches as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Lavozim filtri (Standart Select) --}}
                        <div>
                            <label for="positionFilter" class="block text-sm font-medium text-gray-700">Lavozim</label>
                            {{-- wire:model.defer - faqat tugma bosilganda qo'llaniladi --}}
                            <select wire:model.defer="positionFilter" id="positionFilter"
                                    @disabled(!$this->branchFilter || ($this->branchFilter && $this->positions->isEmpty()))
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                <option value="">
                                    @if(!$this->branchFilter)
                                        Avval filialni tanlang
                                    @elseif($this->positions->isEmpty())
                                        Bu filialda lavozim yo'q
                                    @else
                                        Lavozimni tanlang
                                    @endif
                                </option>
                                @if($this->branchFilter && !$this->positions->isEmpty())
                                    @foreach($this->positions as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Bo'lim filtri (Standart Select) --}}
                        <div>
                            <label for="departmentFilter" class="block text-sm font-medium text-gray-700">Bolim</label>
                             {{-- wire:model.defer - faqat tugma bosilganda qo'llaniladi --}}
                             <select wire:model.defer="departmentFilter" id="departmentFilter"
                                     class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm">
                                 <option value="">Barcha bo'limlar</option>
                                 @foreach($this->departments as $id => $name)
                                     <option value="{{ $id }}">{{ $name }}</option>
                                 @endforeach
                             </select>
                        </div>

                        {{-- Qidiruv maydoni (Standart Input) --}}
                        <div class="relative">
                            <label for="search-keyword" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                            <div class="relative rounded-lg shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-gray-400 dark:text-gray-500">
                                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                {{-- wire:model.live - real vaqtda qidirish uchun --}}
                                <input type="search"
                                       wire:model.live.debounce.500ms="search"
                                       id="search-keyword" {{-- ID label'ning 'for' atributiga mos kelishi kerak --}}
                                       placeholder="ID, Ism, Lavozim bo'yicha..."
                                       class="block w-full rounded-lg border-gray-300 pl-10 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm"
                                />
                            </div>
                        </div>
                    </div>

                    {{-- "Qidirish" tugmasi (Standart Button) --}}
                    <div class="flex justify-end">
                        <button type="button"
                                wire:click="search"
                                {{-- Loading direktivalari olib tashlandi --}}
                                class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-transparent bg-blue-600 px-3 py-1.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-70 dark:focus:ring-offset-gray-800">
                            {{-- Magnifying Glass Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 -ml-0.5 mr-0.5">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                            <span>Qidirish</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ================== End Tepa qism ================== -->

        <!-- ================== Pastki qism: Kontent (Grid/List) ================== -->
        <!-- Asosiy kontent -->
        <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
            <!-- Sarlavha -->
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-6">Xodimlar</h2>

            <!-- View Toggle Buttons -->
            <div class="flex justify-start mb-6 border-b dark:border-gray-700 pb-4">
                <div class="flex space-x-1 border dark:border-gray-600 rounded-lg p-0.5">
                    {{-- Grid View Button --}}
                    <button
                        type="button"
                        wire:click="toggleView('grid')"
                        title="Grid ko'rinishi"
                        class="{{ $this->displayMode === 'grid' ? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50' }} p-2 rounded-md transition-colors duration-200"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                    {{-- List View Button --}}
                    <button
                        type="button"
                        wire:click="toggleView('list')"
                        title="Ro'yxat ko'rinishi"
                        class="{{ $this->displayMode === 'list' ? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50' }} p-2 rounded-md transition-colors duration-200"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Employees Content -->
            <div>
                <!-- Grid View -->
                <div x-show="$wire.displayMode === 'grid'" style="display: none;" x-transition.opacity>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($this->staffList as $staff)
                            <div class="bg-white dark:bg-gray-800/50 rounded-lg shadow-sm border dark:border-gray-700 flex flex-col transition hover:shadow-md overflow-hidden">
                                {{-- Rasm va Ma'lumotlar qismi --}}
                                <div class="p-5 flex-1">
                                    {{-- Rasm uchun joy (Placeholder) --}}
                                    <div class="flex justify-center mb-4">
                                        <div class="h-16 w-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400 dark:text-gray-500">
                                            {{-- Placeholder Icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                            </svg>
                                            {{-- Agar rasm bo'lsa: <img src="{{ $staff->profile_photo_url }}" alt="{{ $staff->full_name }}" class="h-16 w-16 rounded-full object-cover"> --}}
                                        </div>
                                    </div>
                                    {{-- TODO: Profil sahifasiga linkni to'g'rilang --}}
                                    <a href="#"
                                        wire:click.prevent="showEmployeeProfile({{ $staff->id }})"    
                                        class="hover:text-primary-600 dark:hover:text-primary-400 font-medium">
                                        <h3 class="text-lg font-semibold text-center text-gray-900 hover:text-primary-600 dark:hover:text-primary-400 truncate mb-1">
                                            {{ $staff->full_name }}
                                        </h3>
                                    </a>
                                    <p class="text-sm text-center text-gray-600 dark:text-gray-400">{{ $staff->position?->name ?? 'Lavozim yo\'q' }}</p>
                                </div>
                                <div class="mt-4 pt-4 border-t dark:border-gray-700 flex justify-end space-x-1">
                                    {{-- SMS Button (disabled) --}}
                                    <button type="button" title="SMS Yuborish" disabled
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-full text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 cursor-not-allowed">
                                        <span class="sr-only">SMS Yuborish</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-3.04 8.25-6.75 8.25a8.25 8.25 0 01-8.25-8.25S3 7.444 3 12c0-1.103.268-2.134.743-3.099.446-.91.99-1.748 1.6-2.487V5.25c0-.938.762-1.75 1.75-1.75h8.5c.988 0 1.75.813 1.75 1.75v1.164c.61.74 1.154 1.576 1.6 2.487.475.965.743 1.996.743 3.1z" />
                                        </svg>
                                    </button>
                                    {{-- Edit Button --}}
                                    {{-- TODO: Profil sahifasiga linkni to'g'rilang yoki Livewire action qo'shing --}}
                                    <a href="#" {{-- Misol: wire:click="goToStaffProfile({{ $staff->id }})" yoki href="..." --}}
                                        title="Profilni ko'rish/Tahrirlash"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-full text-info-600 hover:bg-info-500/10 focus:outline-none dark:text-info-400 transition-colors">
                                         <span class="sr-only">Tahrirlash</span>
                                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.796a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                         </svg>
                                     </a>
                                    {{-- Delete Button --}}
                                    <button type="button"
                                            wire:click="confirmDeleteStaff({{ $staff->id }}, '{{ addslashes($staff->full_name) }}')"
                                            title="Safdan chiqarish"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-full text-danger-600 hover:bg-danger-500/10 focus:outline-none dark:text-danger-400 transition-colors">
                                        <span class="sr-only">Safdan chiqarish</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.01A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">Xodimlar topilmadi</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Qidiruv yoki filtr parametrlarini o'zgartirib ko'ring.</p>
                            </div>
                        @endforelse
                    </div>
                    {{-- Pagination --}}
                    @if ($this->staffList->hasPages())
                        <div class="mt-6 px-2">
                            {{ $this->staffList->links() }}
                        </div>
                    @endif
                </div>

                <!-- List View (Standart Table) -->
                <div x-show="$wire.displayMode === 'list'" style="display: none;" x-transition.opacity>
                    <div class="overflow-x-auto rounded-lg border dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    {{-- Sortable Header Cell Function --}}
                                    @php
                                    $headerCell = function ($field, $label) use ($sortField, $sortDirection) {
                                        $isSorted = $sortField === $field;
                                        $directionClass = $isSorted ? ($sortDirection === 'asc' ? 'sort-asc' : 'sort-desc') : '';
                                        $arrowSvg = $isSorted ? ($sortDirection === 'asc' ? '<svg class="w-3 h-3 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" /></svg>' : '<svg class="w-3 h-3 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>') : '<svg class="w-3 h-3 ml-1 opacity-25 group-hover:opacity-100 transition-opacity" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>';
                                        return '<th scope="col" wire:click="sortBy(\'' . $field . '\')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer group ' . $directionClass . '"><div class="flex items-center">' . $label . $arrowSvg . '</div></th>';
                                    };
                                    @endphp

                                    {!! $headerCell('id', 'ID') !!}
                                    {!! $headerCell('first_name', 'Xodim Nomi') !!}
                                    {!! $headerCell('position_id', 'Lavozim') !!}
                                    {!! $headerCell('department_id', 'Bo\'lim') !!}
                                    {!! $headerCell('phone', 'Mobil raqam') !!}
                                    {!! $headerCell('branch_id', 'Filial') !!}
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amallar</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @forelse($this->staffList as $staff)
                                    {{-- wire:loading.class olib tashlandi --}}
                                    <tr wire:key="staff-row-{{ $staff->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">{{ $staff->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            {{-- TODO: Profil sahifasiga linkni to'g'rilang --}}
                                            <a href="#"
                                                wire:click.prevent="showEmployeeProfile({{ $staff->id }})"    
                                                class="hover:text-primary-600 dark:hover:text-primary-400 font-medium">
                                                {{ $staff->full_name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $staff->position?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $staff->department?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $staff->phone ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $staff->branch?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-1">
                                                {{-- SMS Button (disabled) --}}
                                                <button type="button" title="SMS Yuborish" disabled
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-full text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 cursor-not-allowed">
                                                    <span class="sr-only">SMS Yuborish</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-3.04 8.25-6.75 8.25a8.25 8.25 0 01-8.25-8.25S3 7.444 3 12c0-1.103.268-2.134.743-3.099.446-.91.99-1.748 1.6-2.487V5.25c0-.938.762-1.75 1.75-1.75h8.5c.988 0 1.75.813 1.75 1.75v1.164c.61.74 1.154 1.576 1.6 2.487.475.965.743 1.996.743 3.1z" />
                                                    </svg>
                                                </button>
                                                {{-- Edit Button --}}
                                                {{-- TODO: Profil sahifasiga linkni to'g'rilang yoki Livewire action qo'shing --}}
                                                <a href="#" {{-- Misol: wire:click="goToStaffProfile({{ $staff->id }})" yoki href="..." --}}
                                                   title="Profilni ko'rish/Tahrirlash"
                                                   class="inline-flex items-center justify-center h-8 w-8 rounded-full text-info-600 hover:bg-info-500/10 focus:outline-none dark:text-info-400 transition-colors">
                                                    <span class="sr-only">Tahrirlash</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.796a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </a>
                                                {{-- Delete Button --}}
                                                <button type="button"
                                                        wire:click="confirmDeleteStaff({{ $staff->id }}, '{{ addslashes($staff->full_name) }}')"
                                                        title="Safdan chiqarish"
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-full text-danger-600 hover:bg-danger-500/10 focus:outline-none dark:text-danger-400 transition-colors">
                                                    <span class="sr-only">Safdan chiqarish</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-12 px-6">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                 <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.01A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">Xodimlar topilmadi</h3>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    @if ($this->staffList->hasPages())
                        <div class="mt-6 px-2">
                            {{ $this->staffList->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- ================== End Pastki qism ================== -->
    </div>

    <!-- Modal Oyna (Standart HTML + Livewire + Alpine) -->
    @if ($showDeleteConfirmModal)
        <div
            x-data="{ show: @entangle('showDeleteConfirmModal') }" {{-- .defer qo'shildi --}}
            x-show="show"
            x-on:keydown.escape.window="show = false"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4 py-6"
            style="display: none;"
        >
            {{-- Modal Orqa Fon --}}
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity"
                wire:click="cancelDeleteStaff"
            ></div>

            {{-- Modal Kontenti --}}
            <div
                x-show="show"
                x-trap.inert.noscroll="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative w-full max-w-md transform overflow-hidden rounded-xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-800"
            >
                {{-- Modal Sarlavhasi va Ikona --}}
                <div class="flex items-start space-x-3 rtl:space-x-reverse">
                    <div class="flex-shrink-0 flex items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20 h-10 w-10">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">
                            Xodimni o'chirish
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ $staffToDeleteName ? 'Rostdan ham ' . $staffToDeleteName . 'ni o\'chirmoqchimisiz?' : 'Xodimni o\'chirishni istaysizmi?' }}
                        </p>
                    </div>
                </div>

                {{-- Modal Asosiy Qismi --}}
                <div class="mt-4 space-y-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Bu amalni ortga qaytarib bo'lmaydi.
                    </p>
                </div>

                {{-- Modal Footer (Tugmalar) --}}
                <div class="mt-6 flex justify-end gap-x-3">
                    {{-- Bekor qilish tugmasi --}}
                    <button
                        type="button"
                        wire:click="cancelDeleteStaff"
                        x-on:click="show = false"
                        class="inline-flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800"
                    >
                        Bekor qilish
                    </button>

                    {{-- O'chirish tugmasi --}}
                    <button
                        type="button"
                        wire:click="deleteStaff"
                        class="inline-flex justify-center items-center rounded-lg border border-transparent bg-danger-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-danger-700 focus:outline-none focus:ring-2 focus:ring-danger-500 focus:ring-offset-2 disabled:opacity-50 dark:focus:ring-offset-gray-800"
                    >
                        <span>O'chirish</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</x-filament::page>
