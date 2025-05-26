<x-filament::page>
    <div class="flex flex-col h-full" x-data="{ showPaymentModal: @entangle('showPaymentModal') }">
        <!-- Filtrlar -->
        <div class="flex-1 bg-white dark:bg-gray-800 rounded-md shadow-sm mb-6">
            <div class="flex items-center justify-between p-4 rounded-md shadow-sm bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">Tanlash</span>
                </div>
            </div>

            <div class="p-4">
                <div class="flex flex-col gap-4 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Filial filtri -->
                        <div class="w-full">
                            <label for="branchFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filial</label>
                            <select wire:model.defer="selectedBranchId" id="branchFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Barcha filiallar</option>
                                @foreach($this->branches as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Oy filtri -->
                        <div class="w-full">
                            <label for="monthFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Oy</label>
                            <select wire:model.defer="selectedMonth" id="monthFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Tanlang</option>
                                @foreach($this->months as $num => $name)
                                    <option value="{{ $num }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Yil filtri -->
                        <div class="w-full">
                            <label for="yearFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Yil</label>
                            <select wire:model.defer="selectedYear" id="yearFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Tanlang</option>
                                @foreach($this->years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Qidirish tugmasi -->
                    <div class="mt-4 flex justify-end">
                        <button
                            wire:click="searchPayrolls"
                            wire:loading.attr="disabled"
                            wire:target="searchPayrolls"
                            type="button"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto disabled:opacity-50"
                        >
                            <svg wire:loading.remove wire:target="searchPayrolls" class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <svg wire:loading wire:target="searchPayrolls" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Qidirish
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ish haqi jadvali -->
        <div class="fi-ta rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b dark:border-gray-700">
                Xodimlar ro'yxati ({{ $this->selectedMonth ? $this->months[$this->selectedMonth] : '' }} {{ $this->selectedYear }})
            </h2>

            <div class="fi-ta-content relative overflow-x-auto">
                <!-- Search input -->
                <div class="relative m-[2px] mb-3 mr-5 float-left">
                    <label for="inputSearch" class="sr-only">Qidirish</label>
                    <input wire:model.live.debounce.500ms="search"
                           id="inputSearch"
                           type="text"
                           placeholder="Xodim bo'yicha qidirish..."
                           class="block w-64 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400 dark:text-white" />
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-neutral-500 dark:text-neutral-200">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                </div>
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300">
                        <tr>
                            @php
                                $headerCell = function ($field, $label) use ($sortField, $sortDirection) {
                                    $actualSortField = [
                                        'employeeName' => 'staff.first_name',
                                        'salaryAll' => 'final_salary',
                                        'paidSalary' => 'total_paid',
                                        'notPaidSalary' => 'debt',
                                    ][$field] ?? $field;
                                    $isSorted = $sortField === $actualSortField;
                                    $arrowSvg = $isSorted
                                        ? ($sortDirection === 'asc'
                                            ? '<svg class="w-3 h-3 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" /></svg>'
                                            : '<svg class="w-3 h-3 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>')
                                        : '<svg class="w-3 h-3 ml-1 opacity-25 group-hover:opacity-100 transition-opacity" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>';
                                    return '<th scope="col" wire:click="sortBy(\'' . $field . '\')" class="px-6 py-4 cursor-pointer group"><div class="flex items-center">' . $label . $arrowSvg . '</div></th>';
                                };
                            @endphp
                            {!! $headerCell('employeeName', 'Xodim Nomi') !!}
                            {!! $headerCell('salaryAll', 'Jami') !!}
                            {!! $headerCell('paidSalary', 'To\'langan') !!}
                            {!! $headerCell('notPaidSalary', 'Qarz') !!}
                            <th scope="col" class="px-6 py-4">Amallar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($this->payrollData as $record)
                        <tr wire:key="payroll-{{ $record->id }}" class="border-b dark:border-neutral-600 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td data-field="employeeName" class="px-6 py-4 dark:text-white">{{ $record->full_name }}</td>
                            <td data-field="salaryAll" class="px-6 py-4">
                                <input
                                    wire:model.defer="payrollDataInput.{{ $record->id }}"
                                    type="number"
                                    name="salaryAll_{{ $record->id }}"
                                    placeholder="0"
                                    onfocus="if(this.value === '0' || this.value === '0.00') this.value = '';"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                            </td>
                            <td data-field="paidSalary" class="px-6 py-4">
                                <div class="fi-ta-text px-3 py-4 text-sm text-green-600 dark:text-green-400">
                                    {{ number_format($record->total_paid ?? 0, 0, '.', ' ') }}
                                </div>
                            </td>
                            <td data-field="notPaidSalary" class="px-6 py-4">
                                @php
                                    $debt = ($record->final_salary ?? 0) - ($record->total_paid ?? 0);
                                @endphp
                                <div class="fi-ta-text px-3 py-4 text-sm {{ $debt > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400' }}">
                                    {{ number_format($debt, 0, '.', ' ') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-1">
                                    <button type="button"
                                        wire:click="saveSalary({{ $record->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="saveSalary({{ $record->id }})"
                                        class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
                                        title="Saqlash"
                                    >
                                        <svg wire:loading.remove wire:target="saveSalary({{ $record->id }})" xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-5 h-5"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859M12 3v8.25m0 0l-3-3m3 3l3-3" />
                                        </svg>
                                        <svg wire:loading wire:target="saveSalary({{ $record->id }})" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{-- Saqlash --}}
                                    </button>

                                    <button type="button"
                                        wire:click="openPaymentModal({{ $record->id }}, {{ $record->payroll_id ?? 'null' }})"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-full text-blue-600 hover:bg-blue-500/10 focus:outline-none dark:text-blue-400 transition-colors"
                                        title="To'lov qo'shish"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 px-6 text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.166 1.318m0 0A1.5 1.5 0 0 1 5.536 17.818a1.5 1.5 0 0 1-1.8-1.8A1.5 1.5 0 0 1 5.536 14.22m6.48 2.098a1.5 1.5 0 0 1 1.8 1.8a1.5 1.5 0 0 1-1.8 1.8a1.5 1.5 0 0 1-1.8-1.8a1.5 1.5 0 0 1 1.8-1.8Zm4.49-2.097a1.5 1.5 0 0 1 1.8 1.8a1.5 1.5 0 0 1-1.8 1.8a1.5 1.5 0 0 1-1.8-1.8a1.5 1.5 0 0 1 1.8-1.8Z" />
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 4.875 4.875 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.092 1.21-.138 2.43-.138 3.662 0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 4.875 4.875 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.092-1.21.138-2.43.138-3.662Z" />
                                        </svg>
                                        <span class="font-medium">Xodimlar topilmadi</span>
                                        <p class="text-sm text-gray-400">Filtr parametrlarini o'zgartirib ko'ring.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if ($this->payrollData->hasPages())
                <div class="border-t border-gray-200 dark:border-white/10 p-4 mt-4">
                    {{ $this->payrollData->links() }}
                </div>
            @endif
        </div>

        {{-- To'lov qo'shish uchun Modal (Alpine.js + Tailwind) --}}
        <div
            x-show="showPaymentModal"
            x-on:keydown.escape.window="showPaymentModal = false"
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
                x-show="showPaymentModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity"
                wire:click="closePaymentModal" {{-- Close on overlay click --}}
            ></div>

            {{-- Modal Kontenti --}}
            <div
                x-show="showPaymentModal"
                x-trap.inert.noscroll="showPaymentModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative w-full max-w-md transform overflow-hidden rounded-xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-800"
            >
                {{-- Modal Sarlavhasi --}}
                <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                        To'lov qo'shish ({{ $staffForPayment?->full_name }})
                    </h3>
                    <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Asosiy Qismi (Forma) --}}
                <form wire:submit.prevent="savePayment" class="mt-4 space-y-4">
                    <div>
                        <label for="paymentAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Summa</label>
                        <input wire:model.defer="paymentAmount" type="number" step="any" id="paymentAmount" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('paymentAmount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="paymentDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sana</label>
                        <input wire:model.defer="paymentDate" type="date" id="paymentDate" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               onclick="this.showPicker();">
                        @error('paymentDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="paymentComment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Izoh</label>
                        <textarea wire:model.defer="paymentComment" id="paymentComment" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                        @error('paymentComment') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Modal Footer (Tugmalar) --}}
                    <div class="mt-6 flex justify-end gap-x-3">
                        <button
                            type="button"
                            wire:click="closePaymentModal"
                            class="inline-flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800"
                        >
                            Bekor qilish
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="savePayment"
                            class="inline-flex items-center justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 dark:focus:ring-offset-gray-800"
                        >
                            <svg wire:loading wire:target="savePayment" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="savePayment">Saqlash</span>
                            <span wire:loading wire:target="savePayment">Saqlanmoqda...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-filament::page>
