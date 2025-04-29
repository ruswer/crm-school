<x-filament::page>

    {{-- Filtrlar paneli --}}
    <div class="mb-2 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tanlash</h3>
            <x-filament::button
                tag="a"
                :href="route('filament.admin.pages.education.schedule.create')"
                class="inline-flex items-center space-x-2"
            >
                <div class="flex items-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="ml-2">Dars Jadvali qo'shish/Yangilash</span>
                </div>
            </x-filament::button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Filial filtri --}}
            <div>
                <label for="branch_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filial</label>
                <select id="branch_filter" wire:model.defer="branch_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Barcha filiallar</option>
                    @foreach($this->branchOptions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Guruh filtri --}}
            <div>
                <label for="group_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guruh</label>
                <select id="group_filter" wire:model.defer="group_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        @disabled(!$this->branch_id)>
                    <option value="">Barcha guruhlar</option>
                     {{-- $this->groups o'rniga $this->groupOptions --}}
                    @foreach($this->groupOptions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- O'qituvchi filtri --}}
            <div>
                <label for="teacher_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">O'qituvchi</label>
                <select id="teacher_filter" wire:model.defer="teacher_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Barcha o'qituvchilar</option>
                    @foreach($this->teacherOptions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Agar "Qidirish" tugmasi kerak bo'lsa --}}
            <div class="md:col-span-3 flex justify-end">
                <button
                    type="button"
                    wire:click="searchSchedule"
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

    {{-- Umumiy konteyner --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        {{-- Sarlavha va kabinetlar qismi --}}
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                Kabinetlar bo'yicha dars jadvallari
            </h2>
            
            {{-- Kabinetlar ro'yxati --}}
            <div class="flex flex-wrap gap-2">
                @foreach($this->getCabinets() as $cabinet)
                    <button
                        wire:click="filterByRoom({{ $cabinet->id }})"
                        wire:loading.attr="disabled"
                        @class([
                            'px-4 py-4 text-sm font-medium rounded-md transition-colors',
                            'bg-primary-600 text-white hover:bg-primary-500' => $this->selectedCabinetId === $cabinet->id,
                            'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' => $this->selectedCabinetId !== $cabinet->id,
                        ])
                    >
                        {{ $cabinet->name }}
                        @if($this->getScheduleCountForCabinet($cabinet->id))
                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-white text-primary-600">
                                {{ $this->getScheduleCountForCabinet($cabinet->id) }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Dars jadvali --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                {{-- Jadval sarlavhasi --}}
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                            Vaqt
                        </th>
                        @foreach($this->getWeekDays() as $day)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ $day['short'] }} <br>
                                <span class="text-xs">{{ $day['date'] }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                {{-- Jadval tanasi --}}
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($this->getTimeSlots() as $time)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $time }}
                            </td>
                            @foreach($this->getWeekDays() as $day)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                    @if($schedule = $this->getScheduleForTimeAndDay($time, $day['dayNum']))
                                        <div class="p-2 bg-blue-50 dark:bg-blue-900/50 rounded">
                                            <div class="font-medium">{{ $schedule->group?->name }}</div>
                                            <div class="text-xs">{{ $schedule->teacher?->full_name }}</div>
                                            <div class="text-xs">{{ $schedule->cabinet?->name }}</div>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Keyinchalik Qo'shish/Tahrirlash/O'chirish modallari uchun joy --}}
    {{-- @if($showCreateModal) ... @endif --}}
    {{-- @if($showEditModal) ... @endif --}}
    {{-- @if($showDeleteModal) ... @endif --}}

</x-filament::page>
