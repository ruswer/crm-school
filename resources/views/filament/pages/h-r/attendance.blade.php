<x-filament::page>
    <div class="flex flex-col h-full">
        <!-- Tepa qism: Filtrlar -->
        <div class="flex-1 mb-4 bg-white dark:bg-gray-800 rounded-md shadow-sm">
            <div class="flex items-center justify-between p-4 rounded-md shadow-sm bg-white dark:bg-gray-800">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">Tanlash</span>
                </div>
            </div>

            <!-- Filtrlar formasi -->
            <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm gap-4 flex-nowrap">
                <div class="flex flex-col gap-4 w-full">
                    <!-- Tepa qator: Filial va Sana -->
                    <div class="flex flex-wrap lg:flex-nowrap gap-4">
                        <!-- Filial bo'yicha qidirish -->
                        <div class="w-full lg:w-1/2">
                            <label for="branch" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filial</label>
                            {{-- Filiallar uchun Filament Forms komponentini ishlatish tavsiya etiladi --}}
                            <select wire:model.defer="selectedBranchId" id="branch" name="branch" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
                                <option value="">Filialni tanlang</option>
                                @foreach($this->branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedBranchId') <span class="text-danger-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <!-- Sana bo'yicha qidirish -->
                        <div class="w-full lg:w-1/2">
                            <label for="selected_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sana</label>
                            {{-- Sana uchun Filament Forms DatePicker komponentini ishlatish tavsiya etiladi --}}
                            <input wire:model.defer="selectedDate"
                                    type="date"
                                    id="selected_date"
                                    name="selected_date" 
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                                    onclick="this.showPicker();" 
                            />
                            @error('selectedDate') <span class="text-danger-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <!-- Pastki qator: Qidirish tugmasi o'ngda -->
                    <div class="flex justify-end">
                        {{-- Qidirish tugmasi uchun Filament Button komponentini ishlatish tavsiya etiladi --}}
                        <button
                            type="button"
                            wire:click="searchStaff"
                            wire:loading.attr="disabled"
                            wire:target="searchStaff"
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto disabled:opacity-50 dark:focus:ring-offset-gray-800"
                        >
                            <svg wire:loading.remove wire:target="searchStaff" class="w-5 h-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                            <svg wire:loading wire:target="searchStaff" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="searchStaff">Qidirish</span>
                            <span wire:loading wire:target="searchStaff">Qidirilmoqda...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pastki qism: Xodimlar ro'yxati va Davomat jadvali -->
        {{-- Jadval faqat qidiruv amalga oshirilgandan keyin ko'rsatiladi ($staffList null bo'lmaganda) --}}
        @if($staffList !== null)
            <x-filament::card class="mt-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 pb-4 border-b dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Xodimlar ro'yxati ({{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') : 'Sana tanlanmagan' }})
                    </h2>
                    {{-- Agar xodimlar ro'yxati bo'sh bo'lmasa, Saqlash tugmasini ko'rsatish --}}
                    @if($staffList->isNotEmpty())
                        {{-- Saqlash tugmasi uchun Filament Button komponentini ishlatish tavsiya etiladi --}}
                        <button
                            type="button"
                            wire:click="saveAttendance"
                            wire:loading.attr="disabled"
                            wire:target="saveAttendance"
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-success-600 hover:bg-success-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-500 disabled:opacity-50 dark:focus:ring-offset-gray-800 w-full sm:w-auto"
                        >
                            <svg wire:loading.remove wire:target="saveAttendance" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                             <svg wire:loading wire:target="saveAttendance" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveAttendance">Saqlash</span>
                            <span wire:loading wire:target="saveAttendance">Saqlanmoqda...</span>
                        </button>
                    @endif
                </div>

                {{-- Agar tanlangan sana uchun davomat allaqachon mavjud bo'lsa, xabar chiqarish --}}
                @if($attendanceExistsForDate)
                    {{-- <x-filament::alert> komponenti o'rniga stilizatsiya qilingan div --}}
                    <div class="flex items-center gap-4 rounded-lg border border-primary-300 bg-primary-50 p-4 text-primary-600 dark:border-primary-600 dark:bg-gray-800 dark:text-primary-400 mb-4" role="alert">
                        <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        <span class="font-medium">Bu kunga davomat allaqachon qilingan.</span> Uni quyidagi jadvalda o'zgartirishingiz mumkin.
                    </div>
                @endif


                <!-- Table responsive wrapper -->
                <div class="overflow-x-auto">
                    {{-- Filament Table komponentini ishlatish ancha qulayroq bo'lishi mumkin --}}
                    <table class="min-w-full text-left text-sm whitespace-nowrap dark:text-gray-300">
                        <thead class="border-b-2 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col" wire:click="sortBy('id')" class="px-6 py-3 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer">
                                    <div class="flex items-center gap-x-1">
                                        <span>#</span>
                                        @if ($sortField === 'id')
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" @if($sortDirection === 'desc') style="transform: rotate(180deg);" @endif>
                                                <path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .75.75v12.5a.75.75 0 0 1-1.5 0V3.75A.75.75 0 0 1 10 3zM5.707 6.707a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06L10.5 2.81V16.25a.75.75 0 0 1-1.5 0V2.81L5.707 6.707z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .75.75v12.5a.75.75 0 0 1-1.5 0V3.75A.75.75 0 0 1 10 3zM5.707 6.707a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06L10.5 2.81V16.25a.75.75 0 0 1-1.5 0V2.81L5.707 6.707z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </div>
                                </th>
                                <th scope="col" wire:click="sortBy('first_name')" class="px-6 py-3 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer">
                                    <div class="flex items-center gap-x-1">
                                        <span>Nomi</span>
                                        @if ($sortField === 'first_name')
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" @if($sortDirection === 'desc') style="transform: rotate(180deg);" @endif>
                                                <path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .75.75v12.5a.75.75 0 0 1-1.5 0V3.75A.75.75 0 0 1 10 3zM5.707 6.707a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06L10.5 2.81V16.25a.75.75 0 0 1-1.5 0V2.81L5.707 6.707z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .75.75v12.5a.75.75 0 0 1-1.5 0V3.75A.75.75 0 0 1 10 3zM5.707 6.707a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06L10.5 2.81V16.25a.75.75 0 0 1-1.5 0V2.81L5.707 6.707z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </div>
                                </th>
                                {{-- Davomat va Izoh ustunlari uchun saralash mantiqiy emas, shuning uchun ularga wire:click qo'shilmaydi --}}
                                <th scope="col" class="px-6 py-3 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Davomat</th>
                                <th scope="col" class="px-6 py-3 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Izoh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($staffList as $index => $staff)
                            <tr wire:key="staff-{{ $staff->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}</td>
                                <td class="px-6 py-4">
                                    {{-- Radio tugmalar uchun Filament Forms Radio komponentini ishlatish mumkin --}}
                                    <div class="flex flex-col space-y-2">
                                        @php
                                            $statuses = [
                                                'not_working' => ['label' => 'Ish kuni emas', 'color' => 'gray'],
                                                'present' => ['label' => 'Ishtirok etdi', 'color' => 'success'], // Rangni Filament ranglariga moslashtirish
                                                'absent' => ['label' => 'Qatnashmadi', 'color' => 'danger'], // Rangni Filament ranglariga moslashtirish
                                            ];
                                        @endphp
                                        @foreach($statuses as $value => $details)
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input wire:model.live="attendanceData.{{ $staff->id }}.status"
                                                    type="radio"
                                                    name="attendance_{{ $staff->id }}"
                                                    value="{{ $value }}"
                                                    class="form-radio h-4 w-4 text-{{ $details['color'] }}-600 border-gray-300 focus:ring-{{ $details['color'] }}-500 !rounded-full dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-offset-gray-800"
                                                >
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $details['label'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('attendanceData.'.$staff->id.'.status') <span class="text-danger-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Izoh uchun Filament Forms Textarea yoki TextInput komponentini ishlatish mumkin --}}
                                    <input wire:model.defer="attendanceData.{{ $staff->id }}.comment"
                                        type="text"
                                        name="note_{{ $staff->id }}"
                                        placeholder="Izoh kiriting..."
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white disabled:opacity-50 dark:disabled:bg-gray-800"
                                        {{-- Agar status 'not_working' bo'lsa, izohni o'chirish --}}
                                        @if(isset($attendanceData[$staff->id]['status']) && $attendanceData[$staff->id]['status'] === 'not_working') disabled @endif
                                    >
                                    @error('attendanceData.'.$staff->id.'.comment') <span class="text-danger-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 px-6 text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.166 1.318m0 0A1.5 1.5 0 0 1 5.536 17.818a1.5 1.5 0 0 1-1.8-1.8A1.5 1.5 0 0 1 5.536 14.22m6.48 2.098a1.5 1.5 0 0 1 1.8 1.8a1.5 1.5 0 0 1-1.8 1.8a1.5 1.5 0 0 1-1.8-1.8a1.5 1.5 0 0 1 1.8-1.8Zm4.49-2.097a1.5 1.5 0 0 1 1.8 1.8a1.5 1.5 0 0 1-1.8 1.8a1.5 1.5 0 0 1-1.8-1.8a1.5 1.5 0 0 1 1.8-1.8Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 4.875 4.875 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.092 1.21-.138 2.43-.138 3.662 0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 4.875 4.875 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.092-1.21.138-2.43.138-3.662Z" />
                                            </svg>
                                            <span class="font-medium">Xodimlar topilmadi</span>
                                            <p class="text-sm text-gray-400">Tanlangan filial va sana uchun ma'lumot mavjud emas.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::card>
        {{-- Agar qidiruv amalga oshirilgan, lekin natija bo'sh bo'lsa --}}
        @elseif($staffList !== null && $staffList->isEmpty())
            <div class="w-full bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mt-4 text-center text-gray-500 dark:text-gray-400">
                 <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.166 1.318m0 0A1.5 1.5 0 0 1 5.536 17.818a1.5 1.5 0 0 1-1.8-1.8A1.5 1.5 0 0 1 5.536 14.22m6.48 2.098a1.5 1.5 0 0 1 1.8 1.8a1.5 1.5 0 0 1-1.8 1.8a1.5 1.5 0 0 1-1.8-1.8a1.5 1.5 0 0 1 1.8-1.8Zm4.49-2.097a1.5 1.5 0 0 1 1.8 1.8a1.5 1.5 0 0 1-1.8 1.8a1.5 1.5 0 0 1-1.8-1.8a1.5 1.5 0 0 1 1.8-1.8Z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 4.875 4.875 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.092 1.21-.138 2.43-.138 3.662 0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 4.875 4.875 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.092-1.21.138-2.43.138-3.662Z" />
                    </svg>
                    <span class="font-medium">Xodimlar topilmadi</span>
                    <p class="text-sm text-gray-400">Tanlangan filial va sana uchun ma'lumot mavjud emas.</p>
                </div>
            </div>
        @endif
    </div>
</x-filament::page>
