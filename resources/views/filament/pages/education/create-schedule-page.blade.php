<x-filament-panels::page>

    {{-- Tepa qism: Filtrlar --}}
    <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Guruhni tanlang</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> {{-- 2 ustun qilindi --}}
            {{-- Filial filtri --}}
            <div>
                <label for="branch_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filial</label>
                <select id="branch_filter" wire:model.live="branch_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">-- Filialni tanlang --</option>
                    @foreach($this->branchOptions() as $id => $name) {{-- Metodni chaqirish --}}
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Guruh filtri --}}
            <div>
                <label for="group_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guruh</label>
                <select id="group_filter" wire:model.live="group_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        @disabled(!$this->branch_id)>
                    <option value="">-- Guruhni tanlang --</option>
                    @foreach($this->groupOptions() as $id => $name) {{-- Metodni chaqirish --}}
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Past qism: Darslar jadvali (tahrirlanadigan) --}}
    @if($group_id) {{-- Faqat guruh tanlanganda ko'rsatish --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b dark:border-gray-700">
                {{-- Guruh va O'qituvchi ma'lumotlari --}}
                @php
                    $group = \App\Models\Group::with('teacher')->find($group_id);
                @endphp
                
                <div class="space-y-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $group?->name }} - Dars Jadvali
                    </h3>
                    
                    {{-- O'qituvchi ma'lumotlari --}}
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        @if($group?->teacher)
                            <span>O'qituvchi: {{ $group->teacher->first_name }} {{ $group->teacher->last_name }}</span>
                        @else
                            <span class="text-yellow-600 dark:text-yellow-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                O'qituvchi biriktirilmagan
                            </span>
                        @endif
                    </div>

                    {{-- Agar o'qituvchi biriktirilmagan bo'lsa, ogohlantirish --}}
                    @if(!$group?->teacher)
                        <div class="mt-2 p-4 bg-yellow-50 dark:bg-yellow-900/50 border border-yellow-200 dark:border-yellow-700 rounded-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Diqqat!</h3>
                                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                        <p>Guruhga o'qituvchi biriktirilmagan.</p>
                                    </div>
                                    <div class="mt-3">
                                        <div class="flex items-center gap-x-3">
                                            <select 
                                                wire:model="selectedTeacherId"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                <option value="">-- O'qituvchini tanlang --</option>
                                                @foreach($this->teachersOptions() as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            <x-filament::button 
                                                wire:click="assignTeacher"
                                                wire:loading.attr="disabled"
                                                color="warning"
                                                size="sm">
                                                <span wire:loading.remove wire:target="assignTeacher">Biriktirish</span>
                                                <span wire:loading wire:target="assignTeacher">Biriktirilmoqda...</span>
                                            </x-filament::button>
                                        </div>
                                        @error('selectedTeacherId') 
                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <form wire:submit.prevent="saveSchedule">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">Kun</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">Boshlanish</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">Tugash</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">Kabinet</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($daysOfWeek as $dayNum => $dayName)
                                <tr wire:key="schedule-day-{{ $dayNum }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $dayName }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- Boshlanish vaqti inputi --}}
                                        <input type="time" 
                                               wire:model.defer="scheduleData.{{ $dayNum }}.start_time"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                               step="900"
                                               pattern="[0-2][0-9]:[0-5][0-9]">
                                        @error('scheduleData.'.$dayNum.'.start_time') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- Tugash vaqti inputi --}}
                                        <input type="time" 
                                               wire:model.defer="scheduleData.{{ $dayNum }}.end_time"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                               step="900"
                                               pattern="[0-2][0-9]:[0-5][0-9]">
                                        @error('scheduleData.'.$dayNum.'.end_time') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select wire:model.defer="scheduleData.{{ $dayNum }}.cabinet_id"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            <option value="">-- Tanlang --</option>
                                            @foreach($this->cabinetOptions() as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('scheduleData.'.$dayNum.'.cabinet_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t dark:border-gray-700 flex justify-end">
                    <x-filament::button type="submit">
                        Jadvalni Saqlash
                    </x-filament::button>
                </div>
            </form>
        </div>
    @else
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow text-center text-gray-500">
            Iltimos, jadvalni ko'rish uchun filial va guruhni tanlang.
        </div>
    @endif

</x-filament-panels::page>
