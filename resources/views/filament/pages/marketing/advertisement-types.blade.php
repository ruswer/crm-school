<x-filament::page>
    <div class="flex flex-col lg:flex-row lg:items-start gap-4">
        {{-- Chap panel (30%) - Kategoriya qo'shish/tahrirlash --}}
        <div class="w-full lg:w-[30%] bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b dark:border-gray-700">
                {{ $editingSource ? 'Reklama turini tahrirlash' : 'Yangi reklama turi' }}
            </h2>
            {{-- Livewire form --}}
            <form wire:submit.prevent="save" class="space-y-4">
                {{ $this->form }} {{-- Filament form schema'sini render qilish --}}

                {{-- Tugmalar --}}
                <div class="flex justify-end pt-4 gap-2">
                    @if ($editingSource)
                        <x-filament::button type="button" color="gray" wire:click="resetForm">
                            Bekor qilish
                        </x-filament::button>
                    @endif
                    <x-filament::button type="submit" wire:loading.attr="disabled">
                        <span wire:loading wire:target="save" class="mr-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        {{ $editingSource ? 'Yangilash' : 'Saqlash' }}
                    </x-filament::button>
                </div>
            </form>
        </div>

        {{-- O'ng panel (70%) --}}
        <div class="w-full lg:w-[70%] bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b dark:border-gray-700">Reklama turi ro'yhati</h2>
            {{-- Table responsive wrapper --}}
            <div class="overflow-x-auto">
                {{-- Search input --}}
                <div class="relative m-[2px] mb-3 mr-5 float-left">
                    <label for="inputSearch" class="sr-only">Search </label>
                    <input id="inputSearch" type="text" placeholder="Qidirish..."
                           wire:model.live.debounce.300ms="search" {{-- Livewire qidiruv --}}
                           class="block w-64 rounded-lg border dark:border-gray-600 dark:bg-gray-700 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400 dark:text-gray-200" />
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-neutral-500 dark:text-neutral-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                </div>

                {{-- Table --}}
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    {{-- Table head --}}
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                        <tr>
                            {{-- Saralash uchun ustun nomlari --}}
                            @php
                                $columns = [
                                    'Reklama turi' => 'name',
                                ];
                            @endphp

                            @foreach ($columns as $label => $columnKey)
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('{{ $columnKey }}')">
                                    <div class="flex items-center gap-1">
                                        <span>{{ $label }}</span>
                                        {{-- Saralash ikonkalari --}}
                                        @if ($sortField === $columnKey)
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if ($sortDirection === 'asc')
                                                    {{-- Yuqoriga strelka (Chevron Up) --}}
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                @else
                                                    {{-- Pastga strelka (Chevron Down) --}}
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                @endif
                                            </svg>
                                        @else
                                            {{-- Saralanmagan holat uchun ikonka (ixtiyoriy) --}}
                                            <svg class="w-3 h-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                            @endforeach

                            {{-- Amallar uchun ustun --}}
                            <th scope="col" class="px-6 py-3">Tahrirlash</th>
                        </tr>
                    </thead>
                    {{-- Table body --}}
                    <tbody class="dark:text-gray-200">
                        @forelse ($this->getSources() as $source) {{-- Ma'lumotlarni olish --}}
                            <tr class="border-b dark:border-neutral-600 hover:bg-gray-50 dark:hover:bg-gray-700" wire:key="{{ $source->id }}">
                                <td class="px-6 py-4">{{ $source->name }}</td>
                                {{-- Boshqa ustunlar --}}
                                {{-- <td class="px-6 py-4">{{ $source->type ?? '-' }}</td> --}}
                                <td class="px-6 py-4">
                                    <button type="button"
                                            wire:click="edit({{ $source->id }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L9.38 17.273a4.5 4.5 0 01-1.897 1.13l-2.745.823.823-2.745a4.5 4.5 0 011.13-1.897l10.171-10.17z" />
                                        </svg>
                                    </button>
                                    {{-- O'chirish tugmasi (agar kerak bo'lsa) --}}
                                    <button type="button"
                                            wire:click="delete({{ $source->id }})"
                                            wire:confirm="Haqiqatan ham o'chirmoqchimisiz?"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">
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
                <div class="mt-4">
                    {{ $this->getSources()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
