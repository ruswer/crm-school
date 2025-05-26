<x-filament::page>
    <div class="space-y-6" x-data="{ showAbsentDatesModal: @entangle('showAbsentDatesModal') }">
        {{-- Hisobot filtrlari --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">Tanlash</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Filial filtri --}}
                <div>
                    <label for="branchFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filial</label>
                    <select wire:model.defer="selectedBranchId" id="branchFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Barcha filiallar</option>
                        @foreach($this->branches as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Oy filtri --}}
                <div>
                    <label for="monthFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Oy</label>
                    <select wire:model.defer="selectedMonth" id="monthFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Tanlang</option>
                        @foreach($this->months as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Yil filtri --}}
                <div>
                    <label for="yearFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Yil</label>
                    <select wire:model.defer="selectedYear" id="yearFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Tanlang</option>
                        @foreach($this->years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Hisobotni shakllantirish tugmasi --}}
            <div class="mt-6 flex justify-end">
                <button
                    wire:click="generateReport"
                    wire:loading.attr="disabled"
                    wire:target="generateReport"
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                >
                    {{-- Loading Spinner --}}
                    <svg wire:loading wire:target="generateReport" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{-- Default Icon --}}
                    <svg wire:loading.remove wire:target="generateReport" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span wire:loading.remove wire:target="generateReport">Hisobotni shakllantirish</span>
                    <span wire:loading wire:target="generateReport">Shakllantirilmoqda...</span>
                </button>
            </div>
        </div>

        {{-- Hisobot jadvali --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
             {{-- Jadval sarlavhasi va Qidiruv --}}
             <div class="border-b border-gray-200 dark:border-white/10">
                 <div class="p-4 space-y-2"> {{-- Sarlavha va qidiruvni alohida qatorga joylash --}}
                    <h2 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Xodimlar davomati hisoboti ({{ $this->selectedMonth ? $this->months[$this->selectedMonth] : '' }} {{ $this->selectedYear }})
                    </h2>
                    {{-- Live Search Input --}}
                    <div class="relative max-w-xs"> {{-- Kenglikni cheklash --}}
                        <label for="tableSearch" class="sr-only">Qidirish</label>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                             <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input
                            wire:model.live.debounce.500ms="search"
                            id="tableSearch"
                            type="text"
                            placeholder="Xodim bo'yicha qidirish..."
                            class="block w-full rounded-lg border-gray-300 shadow-sm pl-10 py-1.5 sm:text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    </div>
                 </div>
             </div>

            {{-- Jadval Kontenti --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm whitespace-nowrap divide-y divide-gray-200 dark:divide-white/10">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            {{-- Saralash uchun Helper Funksiya --}}
                            @php
                                $headerCell = function ($field, $label) use ($sortField, $sortDirection) {
                                    $isSorted = $sortField === $field;
                                    $directionClass = $isSorted ? ($sortDirection === 'asc' ? 'sort-asc' : 'sort-desc') : '';
                                    $arrowSvg = $isSorted
                                        ? ($sortDirection === 'asc'
                                            ? '<svg class="w-3 h-3 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" /></svg>'
                                            : '<svg class="w-3 h-3 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>')
                                        : '<svg class="w-3 h-3 ml-1 opacity-25 group-hover:opacity-100 transition-opacity" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>';
                                        return '<th scope="col" wire:click="sortBy(\'' . $field . '\')" class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider cursor-pointer group ' . $directionClass . '"><div class="flex items-center">' . $label . $arrowSvg . '</div></th>';
                                    };
                            @endphp

                            {!! $headerCell('staff.id', '№') !!}
                            {!! $headerCell('staff.first_name', 'Xodim Nomi') !!}
                            {!! $headerCell('staff.department_id', 'Bo\'lim') !!} {{-- Yoki branch_id --}}
                            {!! $headerCell('present_count', 'Ishtirok etgan') !!}
                            {!! $headerCell('absent_count', 'Qatnashmagan') !!}
                            {{-- {!! $headerCell('not_working_count', 'Ishlamagan') !!} --}}
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                Amallar
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                        @forelse($this->reportData as $record)
                            <tr wire:key="report-row-{{ $record->id }}" class="hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="px-6 py-4 text-gray-900 dark:text-white">{{ $record->id }}</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white">{{ $record->full_name }}</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white">{{ $record->department?->name ?? $record->branch?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-green-600 dark:text-green-400">{{ $record->present_count ?? 0 }}</td>
                                <td class="px-6 py-4 text-red-600 dark:text-red-400">{{ $record->absent_count ?? 0 }}</td>
                                {{-- <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $record->not_working_count ?? 0 }}</td> --}}
                                <td class="px-6 py-4">
                                    @if(($record->absent_count ?? 0) > 0)
                                        <button
                                            type="button"
                                            wire:click="viewAbsentDates({{ $record->id }})"
                                            title="Qatnashmagan kunlarni ko'rish"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 focus:outline-none"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        <p class="mt-2 text-sm font-medium">Ma'lumot topilmadi</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">Filtrlarni o'zgartirib ko'ring.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($this->reportData->hasPages())
                <div class="border-t border-gray-200 dark:border-white/10 p-4">
                    {{ $this->reportData->links() }}
                </div>
            @endif
        </div>

        {{-- Qatnashmagan kunlar uchun Modal (Alpine.js + Tailwind) --}}
        <div
            x-show="showAbsentDatesModal"
            x-on:keydown.escape.window="showAbsentDatesModal = false"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4 py-6"
            style="display: none;" {{-- Initially hidden --}}
        >
            {{-- Modal Orqa Fon --}}
            <div
                x-show="showAbsentDatesModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity"
                @click="showAbsentDatesModal = false" {{-- Close on overlay click --}}
            ></div>

            {{-- Modal Kontenti --}}
            <div
                x-show="showAbsentDatesModal"
                x-trap.inert.noscroll="showAbsentDatesModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative w-full max-w-lg transform overflow-hidden rounded-xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-800"
            >
                {{-- Modal Sarlavhasi --}}
                <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                        @if($staffForModal)
                            {{ $staffForModal->full_name }} - Qatnashmagan kunlar
                            ({{ $this->selectedMonth ? $this->months[$this->selectedMonth] : '' }} {{ $this->selectedYear }})
                        @else
                            Qatnashmagan kunlar
                        @endif
                    </h3>
                    <button @click="showAbsentDatesModal = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Asosiy Qismi --}}
                <div class="mt-4">
                    @if($absentDatesForModal->isNotEmpty())
                        <div class="space-y-4 max-h-80 overflow-y-auto pr-2">
                            <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($absentDatesForModal as $absence)
                                    <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($absence->date)->format('d.m.Y') }}
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200 sm:col-span-2 sm:mt-0">{{ $absence->comment ?: '-' }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Tanlangan davr uchun qatnashmagan kunlar topilmadi.
                        </p>
                    @endif
                </div>

                {{-- Modal Footer (Tugmalar) --}}
                <div class="mt-6 flex justify-end">
                    <button
                        type="button"
                        @click="showAbsentDatesModal = false"
                        class="inline-flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800"
                    >
                        Yopish
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-filament::page>