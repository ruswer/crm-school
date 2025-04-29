<x-filament::page>
    <div class="flex flex-col gap-6"> {{-- Gap qo'shildi --}}

        {{-- Qidiruv filtrlari paneli --}}
        <div class="bg-white rounded-lg shadow-sm p-4 dark:bg-gray-800">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700 mb-4">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Filtrlash</span>
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

            {{-- Filtrlar formasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Filial bo'yicha qidirish --}}
                <div>
                    <label for="branch" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filial</label>
                    <select wire:model.defer="branch_id" id="branch" name="branch" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Barcha filiallar</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Chiqim kategoriyasi bo'yicha qidirish --}}
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chiqim kategoriyasi</label>
                    <select wire:model.defer="category_id" id="category" name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Barcha kategoriyalar</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Sana dan bo'yicha qidirish --}}
                <div>
                    <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sana (dan)</label>
                    <input
                        wire:model.defer="startDate"
                        type="date"
                        id="startDate"
                        name="startDate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                </div>

                {{-- Sana gacha bo'yicha qidirish --}}
                <div>
                    <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sana (gacha)</label>
                    <input
                        wire:model.defer="endDate"
                        type="date"
                        id="endDate"
                        name="endDate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                </div>
            </div>
            {{-- Qidirish tugmasi (agar .live ishlatilmasa kerak bo'ladi) --}}
            
            <div class="flex justify-end mt-4">
                <button
                    wire:click="searchExpenses" {{-- Yoki bo'sh qoldirsa ham bo'ladi, chunki .live ishlatilyapti --}}
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto"
                >
                    <svg wire:loading wire:target="searchExpenses" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">...</svg>
                    <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Qidirish
                </button>
            </div>
           
        </div>

        {{-- Chiqimlar jadvali paneli --}}
        <div class="bg-white rounded-lg shadow-sm p-4 dark:bg-gray-800">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">Chiqim natijasi</h2>

            {{-- Jadval --}}
            <div class="overflow-x-auto">
                {{-- Jadval ichidagi qidiruv inputi --}}
                <div class="relative mb-3 float-left mr-4">
                    <label for="tableSearch" class="sr-only">Qidirish</label>
                    <input wire:model.live.debounce.300ms="searchQuery" id="tableSearch" type="search" placeholder="Jadvaldan qidirish..." class="block w-64 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2 pl-10 pr-4 text-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white" />
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
                            @php
                                $columns = [
                                    'Nomi' => 'expenseName',
                                    'Kategoriya' => 'category',
                                    'Filial' => 'branch',
                                    'Sana' => 'date',
                                    'To\'lov shakli' => 'paymentType',
                                    'Narxi(sum)' => 'price',
                                    'Tavsifi' => 'note',
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
                                                'expenseName' => 'name',
                                                'category' => 'category_id',
                                                'branch' => 'branch_id',
                                                'date' => 'expense_date',
                                                'paymentType' => 'payment_type',
                                                'note' => 'description',
                                                'price' => 'amount',
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
                        </tr>
                    </thead>
                    {{-- Jadval tanasi (Dinamik) --}}
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($expenses as $expense)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 dark:text-gray-200">{{ $expense->name }}</td>
                                <td class="px-6 py-4 dark:text-gray-300">{{ $expense->category?->name ?? '-' }}</td>
                                <td class="px-6 py-4 dark:text-gray-300">{{ $expense->branch?->name ?? '-' }}</td>
                                <td class="px-6 py-4 dark:text-gray-300">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d.m.Y') }}</td>
                                <td class="px-6 py-4 dark:text-gray-300">
                                    {{ match($expense->payment_type) { 'cash' => 'Naqd', 'card' => 'Karta', 'transfer' => 'O\'tkazma', default => $expense->payment_type } }}
                                </td>
                                <td class="px-6 py-4 dark:text-gray-200">{{ number_format($expense->amount, 0, ',', ' ') }} UZS</td>
                                <td class="px-6 py-4 dark:text-gray-300">{{ Str::limit($expense->description, 40) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 1 }}"> {{-- Ustunlar soniga moslashtirish --}}
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Ma'lumot topilmadi</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Tanlangan filterlar bo'yicha hech qanday chiqim topilmadi.
                                        </p>
                                        <div class="mt-6">
                                            <button
                                                wire:click="resetFilters"
                                                type="button"
                                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
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
                    {{ $expenses->links() }}
                </div>
            </div>
        </div>

    </div> {{-- Asosiy o'rab turuvchi div yopilishi --}}
</x-filament::page>
