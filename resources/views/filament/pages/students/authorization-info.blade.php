<x-filament::page>
    <div class="flex flex-col h-full">
        <!-- Tepa qism (Filtrlar) -->
        <div class="mb-4 bg-white rounded-md shadow-sm"> {{-- mb-4 qo'shildi --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-200"> {{-- border qo'shildi --}}
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800">Tanlash</span>
                </div>
            </div>

            <div class="p-4"> {{-- Padding qo'shildi --}}
                <div class="flex flex-col gap-4 w-full">
                    <!-- Tepa qator: Filial va Guruh select -->
                    <div class="flex flex-wrap lg:flex-nowrap gap-4">
                        <!-- Filial bo'yicha qidirish -->
                        <div class="w-full lg:w-1/2">
                            <label for="branch" class="block text-sm font-medium text-gray-700">Filial</label>
                            <select wire:model="selectedBranch" id="branch" name="branch" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha filiallar</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Guruh bo'yicha qidirish -->
                        <div class="w-full lg:w-1/2">
                            <label for="group" class="block text-sm font-medium text-gray-700">Guruh</label>
                            <select wire:model="selectedGroup" id="group" name="group" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha guruhlar</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Pastki qator: Qidirish tugmasi -->
                    <div class="flex justify-end mt-4">
                        <button
                            wire:click="filter"
                            type="button"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto"
                        >
                            <svg wire:loading wire:target="filter" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg wire:loading.remove wire:target="filter" class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Qidirish
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pastki qism: Kontent (Jadval) -->
        <div class="flex-1 bg-white rounded-md shadow-sm p-4">
            <h2 class="text-lg font-bold text-gray-800 mb-4 py-4 border-b border-gray-200">Saytga kirish ma'lumotlari Hisobot</h2>
            <div class="overflow-x-auto bg-white dark:bg-neutral-700">
                <!-- Search input -->
                <div class="relative m-[2px] mb-3 mr-5 float-left">
                    <label for="inputSearch" class="sr-only">Qidirish</label>
                    {{-- Livewire bilan bog'lash --}}
                    <input wire:model.live.debounce.500ms="search" id="inputSearch" type="search" placeholder="Ism, login bo'yicha qidirish..." class="block w-64 rounded-lg border dark:border-none dark:bg-neutral-600 py-2 pl-10 pr-4 text-sm focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400" />
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-neutral-500 dark:text-neutral-200">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                     {{-- Loading indikatori --}}
                    <div wire:loading wire:target="search, selectedBranch, selectedGroup" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Table -->
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 text-gray-800">
                        <tr>
                            {{-- Saralash sarlavhalari --}}
                            @php
                                $columns = [
                                    'O\'quvchi Ismi' => 'name', // 'first_name' yoki 'last_name' ga moslanadi
                                    'Login' => 'login', // Saralash uchun qo'shimcha ish kerak
                                    'Parol' => null, // KO'RSATILMAYDI!
                                    'Ota-ona login' => 'parentsLogin', // Saralash uchun qo'shimcha ish kerak
                                    'Ota-ona parol' => null, // KO'RSATILMAYDI!
                                ];
                            @endphp
                            @foreach ($columns as $label => $column)
                                <th scope="col" class="px-6 py-4">
                                    @if($column) {{-- Agar saralash mumkin bo'lsa --}}
                                        <button type="button" wire:click="sort('{{ $column }}')" class="flex items-center gap-1 uppercase tracking-wider">
                                            {{ $label }}
                                            {{-- Saralash ikonkalari --}}
                                            @if($sortField === $column)
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($sortDirection === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    @endif
                                                </svg>
                                            @else
                                                 <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                 </svg>
                                            @endif
                                        </button>
                                    @else
                                        <span class="uppercase tracking-wider">{{ $label }}</span> {{-- Saralash mumkin bo'lmagan ustunlar --}}
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Ma'lumotlar Livewire komponentidan keladi ($this->students) --}}
                        @forelse($this->students as $student)
                            <tr class="border-b dark:border-neutral-600 hover:bg-gray-50">
                                <th scope="col" class="px-6 py-4">
                                    {{-- O'quvchi profiliga link (agar kerak bo'lsa) --}}
                                    <button type="button"
                                        wire:click="showStudentProfile({{ $student->id }})"
                                        class="text-primary-600 hover:text-primary-900 hover:underline disabled:text-gray-500 disabled:no-underline"
                                        {{-- Agar showStudentProfile funksiyasi ishlamasa, tugmani o'chirish --}}
                                        {{-- @if(!method_exists($this, 'showStudentProfile')) disabled @endif --}}
                                        >
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </button>
                                </td>
                                {{-- Munosabat orqali loginni olish (?-> null-safe operatori) --}}
                                <td class="px-6 py-4">{{ $student->authorization?->login ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-400 italic">Yashirilgan</td> {{-- Parolni KO'RSATMASLIK! --}}
                                {{-- Ota-ona logini (birinchi topilgan ota-ona uchun) --}}
                                <td class="px-6 py-4">{{ $student->parents->first()?->authorization?->login ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-400 italic">Yashirilgan</td> {{-- Parolni KO'RSATMASLIK! --}}
                            </tr>
                        @empty
                            {{-- Ma'lumot topilmaganda ko'rsatiladigan xabar --}}
                            <tr>
                                <td colspan="5">
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
                                                wire:click="resetFilter"
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

                {{-- Livewire Pagination --}}
                <div class="px-4 py-3 border-t">
                    {{ $this->students->links() }}
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
