<x-filament::page>
    {{-- Bitta asosiy o'rab turuvchi div --}}
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- Chap panel (30%) - Chiqim qo'shish --}}
        <div class="w-full lg:w-[30%] bg-white rounded-lg shadow-sm p-4 dark:bg-gray-800 self-start">
           {{-- ... Forma kodi (o'zgarishsiz) ... --}}
           <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                Chiqimni qo'shish
            </h2>
            <form wire:submit.prevent="saveExpense" class="space-y-4">
                {{-- Chiqim kategoriyasi --}}
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chiqim kategoriyasi</label>
                    <select wire:model.defer="category_id" id="category" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('category_id') border-red-500 @enderror">
                        <option value="">Kategoriyani tanlang</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Filial --}}
                <div>
                    <label for="branch" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filial</label>
                    <select wire:model.defer="branch_id" id="branch"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('branch_id') border-red-500 @enderror">
                        <option value="">Filialni tanlang (Ixtiyoriy)</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Chiqim nomi --}}
                <div>
                    <label for="expense_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chiqim nomi</label>
                    <input wire:model.defer="expense_name" type="text" id="expense_name" required
                           placeholder="Chiqim nomini kiriting"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('expense_name') border-red-500 @enderror">
                    @error('expense_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Sana --}}
                <div>
                    <label for="expense_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sana</label>
                    <input wire:model.defer="expense_date" type="date" id="expense_date" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('expense_date') border-red-500 @enderror">
                    @error('expense_date') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Narxi --}}
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Narxi</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input wire:model.defer="amount" type="number" step="any" id="amount" required
                               placeholder="0.00"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm pl-3 pr-12 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('amount') border-red-500 @enderror">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">UZS</span>
                        </div>
                    </div>
                     @error('amount') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- To'lov shakli --}}
                <div>
                    <label for="payment_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">To'lov shakli</label>
                    <select wire:model.defer="payment_type" id="payment_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('payment_type') border-red-500 @enderror">
                        <option value="">To'lov shaklini tanlang</option>
                        <option value="cash">Naqd</option>
                        <option value="card">Plastik karta</option>
                        <option value="transfer">Pul o'tkazma</option>
                    </select>
                    @error('payment_type') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Tavsifi --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tavsifi</label>
                    <textarea wire:model.defer="description" id="description" rows="3"
                              placeholder="Qo'shimcha ma'lumot kiriting"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('description') border-red-500 @enderror"></textarea>
                    @error('description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Saqlash tugmasi --}}
                <div class="flex justify-end pt-4">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg wire:loading wire:target="saveExpense" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Saqlash</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- O'ng panel (70%) - Chiqimlar jadvali --}}
        <div class="w-full lg:w-[70%] bg-white rounded-lg shadow-sm p-4 dark:bg-gray-800 lg:self-start">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">Chiqimlar ro'yxati</h2>
            {{-- Jadval --}}
            <div class="overflow-x-auto">
                {{-- Qidiruv inputi --}}
                <div class="relative mb-3 float-left mr-4">
                    <label for="searchQueryTable" class="sr-only">Qidirish</label> {{-- ID o'zgartirildi --}}
                    <input wire:model.live.debounce.300ms="searchQuery" id="searchQueryTable" type="search" placeholder="Qidirish..." class="block w-64 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2 pl-10 pr-4 text-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white" />
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-gray-400 dark:text-gray-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                </div>

                <table class="min-w-full text-left text-sm whitespace-nowrap clear-left">
                    {{-- Jadval boshi (Saralash bilan) --}}
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                        <tr>
                            {{-- Saralash uchun ustun nomlari --}}
                            @php
                                $columns = [
                                    'Nomi' => 'name',
                                    'Sana' => 'date', // PHP da 'expense_date' ga moslanadi
                                    'Kategoriya' => 'category', // PHP da 'category_id' ga moslanadi
                                    'Filial' => 'branch', // PHP da 'branch_id' ga moslanadi
                                    'Summa' => 'amount',
                                    'To\'lov shakli' => 'payment_type',
                                ];
                            @endphp
                            @foreach ($columns as $label => $columnKey)
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sort('{{ $columnKey }}')">
                                    <div class="flex items-center gap-1">
                                        <span>{{ $label }}</span>
                                        {{-- Saralash ikonkalari --}}
                                        @php
                                            // PHP dagi haqiqiy saralash maydonini olish
                                            $actualSortField = match ($columnKey) {
                                                'name' => 'name',
                                                'date' => 'expense_date',
                                                'category' => 'category_id',
                                                'branch' => 'branch_id',
                                                'amount' => 'amount',
                                                'payment_type' => 'payment_type',
                                                default => ''
                                            };
                                        @endphp
                                        @if($sortField === $actualSortField)
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($sortDirection === 'asc') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /> @else <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /> @endif
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /> </svg>
                                        @endif
                                    </div>
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-right">Amallar</th>
                        </tr>
                    </thead>
                    {{-- Jadval tanasi (Dinamik) --}}
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($expenses as $expense) {{-- PHP dan kelgan $expenses --}}
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 dark:text-gray-200">{{ $expense->name }}</td>
                                <td class="px-6 py-4 dark:text-gray-300">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d.m.Y') }}</td>
                                <td class="px-6 py-4 dark:text-gray-300">{{ $expense->category?->name ?? '-' }}</td>
                                <td class="px-6 py-4 dark:text-gray-300">{{ $expense->branch?->name ?? '-' }}</td>
                                <td class="px-6 py-4 dark:text-gray-200">{{ number_format($expense->amount, 0, ',', ' ') }} UZS</td>
                                <td class="px-6 py-4 dark:text-gray-300">
                                    {{ match($expense->payment_type) { 'cash' => 'Naqd', 'card' => 'Karta', 'transfer' => 'O\'tkazma', default => $expense->payment_type } }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    {{-- Tahrirlash/O'chirish tugmalari (keyinroq qo'shiladi) --}}
                                    <button type="button" title="Tahrirlash" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L9.38 17.273a4.5 4.5 0 01-1.897 1.13l-2.745.823.823-2.745a4.5 4.5 0 011.13-1.897l10.171-10.17z" />
                                        </svg>
                                    </button>
                                    {{-- <button type="button" title="O'chirish" class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400 focus:outline-none ml-2">...</button> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
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
                    {{ $expenses->links() }} {{-- PHP dan kelgan $expenses --}}
                </div>
            </div>
        </div>

    </div> {{-- Asosiy o'rab turuvchi div yopilishi --}}
</x-filament::page>
