<x-filament::page>
    {{-- Bitta asosiy o'rab turuvchi div --}}
    <div class="flex flex-col gap-6">

        {{-- Filtrlar Bo'limi (Manual HTML) --}}
        <div class="bg-white rounded-md shadow-sm dark:bg-gray-800">
            {{-- Sarlavha --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">Filtrlar</span>
                </div>
                {{-- Bu yerga boshqa tugmalar qo'shish mumkin --}}
            </div>

            {{-- Filtr Inputlari --}}
            <div class="p-4">
                <div class="flex flex-col gap-4 w-full">
                    {{-- Birinchi qator: Filial, Guruh, Oy, Yil --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Filial --}}
                        <div>
                            <label for="branch_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filial</label>
                            {{-- Filial o'zgarganda guruhlarni yangilash uchun .live --}}
                            <select wire:model.live="selectedBranch" id="branch_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Barcha filiallar</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Guruh --}}
                        <div>
                            <label for="group_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guruh</label>
                            <select wire:model.defer="selectedGroup" id="group_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" @if(empty($groups)) disabled @endif>
                                <option value="">Barcha guruhlar</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Oy (Hozircha filtr logikasida ishlatilmayapti) --}}
                        <div>
                            <label for="month_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Oy</label>
                            <select wire:model.defer="selectedMonth" id="month_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Barcha Oylar</option>
                                @foreach($months as $monthNumber => $monthName)
                                    <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Yil (Hozircha filtr logikasida ishlatilmayapti) --}}
                        <div>
                            <label for="year_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Yil</label>
                            <select wire:model.defer="selectedYear" id="year_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Barcha Yillar</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Ikkinchi qator: Qidiruv va Tugma --}}
                    <div class="flex justify-between items-end pt-4 gap-4"> {{-- Qidiruv va tugma bir qatorda --}}
                        {{-- Qidiruv --}}
                        <div class="flex-1"> {{-- Qidiruv maydoni --}}
                            <label for="searchQuery" class="block text-sm font-medium text-gray-700">O'quvchi (Ism, Tel)</label>
                            <input wire:model.defer="searchQuery" id="searchQuery" type="search" placeholder="Qidirish..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                        </div>

                        {{-- Qidirish Tugmasi --}}
                        <div class="flex justify-end"> {{-- Tugma oxirgi ustunda o'ngga suriladi --}}
                            <button
                                wire:click="filterDebtors" {{-- Filtr metodini chaqirish --}}
                                type="button"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto"
                                >
                                {{-- Loading ikonkasi --}}
                                <svg wire:loading wire:target="filterDebtors" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{-- Qidiruv ikonkasi --}}
                                <svg wire:loading.remove wire:target="filterDebtors" class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Qidirish
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Qarzdorlar Jadvali (Avvalgi javobdagi kabi qoladi) --}}
        <x-filament::card>
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 py-4 border-b border-gray-200 dark:border-gray-700">
                Qarzdorlar Ro'yxati
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    {{-- Jadval Boshi --}}
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                        <tr>
                            {{-- Ustun nomlari va saralash --}}
                            @php
                                $columns = [
                                    'O\'quvchi' => 'student_name',
                                    'Guruh(lar)' => null,
                                    'Qarz miqdori' => 'total_debt',
                                    'Oxirgi to\'lov' => 'last_payment_date',
                                    'To\'lov muddati' => 'next_due_date',
                                ];
                            @endphp

                            @foreach ($columns as $label => $columnKey)
                                <th scope="col" class="px-6 py-3">
                                    @if($columnKey)
                                        <button wire:click="sort('{{ $columnKey }}')" class="flex items-center gap-1 uppercase font-semibold">
                                            <span>{{ $label }}</span>
                                            @if($sortField === $columnKey || ($columnKey === 'student_name' && $sortField === 'first_name'))
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($sortDirection === 'asc') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /> @else <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /> @endif
                                                </svg>
                                            @else
                                                 <svg class="w-3 h-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /> </svg>
                                            @endif
                                        </button>
                                    @else
                                        <span class="uppercase font-semibold">{{ $label }}</span>
                                    @endif
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-right uppercase font-semibold">Amallar</th>
                        </tr>
                    </thead>
                    {{-- Jadval Tanasi --}}
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($debtors as $debtor)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                {{-- O'quvchi --}}
                                <td class="px-6 py-4">
                                    <a href="#"
                                        wire:click="showStudentProfile({{ $debtor->id }})"
                                        class="text-primary-600 hover:text-primary-900 hover:underline">
                                        {{ $debtor->first_name }} {{ $debtor->last_name }}
                                    </a>
                                </td>
                                {{-- Guruh(lar) --}}
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                    {{ $debtor->groups->pluck('name')->implode(', ') ?: '-' }}
                                </td>
                                {{-- Qarz miqdori --}}
                                <td class="px-6 py-4 font-semibold text-red-600 dark:text-red-500">
                                    {{ number_format($debtor->total_debt ?? 0, 0, ',', ' ') }} UZS
                                </td>
                                {{-- Oxirgi to'lov sanasi --}}
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                    {{ $debtor->last_payment_date ? \Carbon\Carbon::parse($debtor->last_payment_date)->format('d.m.Y') : '-' }}
                                </td>
                                {{-- To'lov muddati (eng yaqini) --}}
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                    {{ $debtor->next_due_date ? \Carbon\Carbon::parse($debtor->next_due_date)->format('d.m.Y') : '-' }}
                                </td>
                                {{-- Amallar --}}
                                <td class="px-6 py-4 text-right">
                                    <x-filament::button
                                        wire:click="sendReminder({{ $debtor->id }})"
                                        color="warning"
                                        size="sm"
                                        icon="heroicon-o-bell-alert"
                                        tooltip="Eslatma yuborish"
                                    >
                                        Eslatma
                                    </x-filament::button>
                                </td>
                            </tr>
                        @empty
                            {{-- Ma'lumot topilmaganda --}}
                            <tr>
                                <td colspan="6">
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
            </div>
            {{-- Pagination --}}
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $debtors->links() }}
            </div>
        </x-filament::card>

    </div> {{-- Asosiy o'rab turuvchi div yopilishi --}}
</x-filament::page>
