<x-filament::page>
    <div class="flex flex-col h-full">
        <!-- Filtrlar qismi -->
        <div class="mb-4 bg-white rounded-md shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800">Filtrlar</span> {{-- Sarlavha o'zgartirildi --}}
                </div>
                {{-- Yangi To'lov Qo'shish Tugmasi (Filtrdan keyin) --}}
                <div class="flex justify-end">
                    <button
                        type="button"
                        wire:click="openCreatePaymentModal"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                        Yangi To'lov Qo'shish
                    </button>
                </div>
            </div>

            <div class="bg-white p-4 rounded-b-md">
                <div class="flex flex-col gap-4 w-full">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <div>
                            <label for="branch" class="block text-sm font-medium text-gray-700">Filial</label>
                            <select wire:model.defer="selectedBranch" id="branch" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha filiallar</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="group" class="block text-sm font-medium text-gray-700">Guruh</label>
                            <select wire:model.defer="selectedGroup" id="group" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha guruhlar</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="month" class="block text-sm font-medium text-gray-700">Oy</label>
                            <select wire:model.defer="selectedMonth" id="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha Oylar</option>
                                @foreach($months as $monthNumber => $monthName)
                                    <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700">Yil</label>
                            <select wire:model.defer="selectedYear" id="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Barcha Yillar</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between items-end pt-4 gap-4"> {{-- Qidiruv va tugma bir qatorda --}}
                         <div class="flex-1"> {{-- Qidiruv maydoni --}}
                            <label for="search" class="block text-sm font-medium text-gray-700">O'quvchi (Ism, Tel)</label>
                            <input wire:model.defer="search" id="search" type="search" placeholder="Qidirish..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                        </div>
                        <button
                            wire:click="filterPayments"
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

        <!-- To'lovlar jadvali -->
        <div class="flex-1 bg-white rounded-md shadow-sm p-4">
            <h2 class="text-lg font-bold text-gray-800 mb-4 py-4 border-b border-gray-200">To'lovlar Ro'yxati</h2>
            <div class="overflow-x-auto bg-white dark:bg-neutral-700">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 text-gray-800">
                        <tr>
                            @php
                                // Adjust column keys for sorting if needed, ensure they match component logic
                                $columns = [
                                    'O\'quvchi' => 'student_name',
                                    'Guruh' => 'group_name',
                                    'Yakuniy Summa' => 'amount', // Label changed, sorting might still use original amount or need adjustment
                                    'Qo\'llanilgan Imtiyoz' => 'discount_amount', // Label changed, sorting key updated
                                    'To\'lov Sanasi' => 'payment_date',
                                    'To\'lov Turi' => 'payment_method',
                                ];
                            @endphp
                            @foreach ($columns as $label => $column)
                                <th scope="col" class="px-6 py-4">
                                    <button type="button" wire:click="sort('{{ $column }}')" class="flex items-center gap-1 uppercase tracking-wider">
                                        {{ $label }}
                                        @if($sortField === $column || ($column === 'student_name' && $sortField === 'students.first_name') || ($column === 'group_name' && $sortField === 'groups.name') || ($column === 'discount_amount' && $sortField === 'payments.discount_amount')) {{-- Adjust sort field check --}}
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($sortDirection === 'asc') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /> @else <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /> @endif
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /> </svg>
                                        @endif
                                    </button>
                                </th>
                            @endforeach
                            {{-- Padding set to px-4 py-2 --}}
                            <th scope="col" class="px-6 py-4 uppercase tracking-wider">Amallar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->payments as $payment)
                            <tr class="border-b dark:border-neutral-600 hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    @if($payment->student)
                                        <a href="{{ \App\Filament\Pages\Students\StudentProfilePage::getUrl(['record' => $payment->student_id]) }}" target="_blank" class="text-primary-600 hover:text-primary-900 hover:underline">
                                            {{ $payment->student->first_name }} {{ $payment->student->last_name }}
                                        </a>
                                    @else O'quvchi topilmadi @endif
                                </td>
                                <td class="px-6 py-4">{{ $payment->group?->name ?? '-' }}</td>
                                {{-- Yakuniy Summa (Amount - Discount) --}}
                                <td class="px-6 py-4 font-semibold text-green-700">
                                    {{ number_format($payment->amount - ($payment->discount_amount ?? 0), 0, ',', ' ') }} UZS
                                </td>
                                {{-- Qo'llanilgan Imtiyoz (Ball va Summa) --}}
                                <td class="px-6 py-4">
                                    @if(($payment->discount_amount ?? 0) > 0)
                                        @php
                                            // Calculate points used (handle potential division by zero)
                                            $pointsUsed = ($this->pointsDiscountRate > 0)
                                                        ? floor($payment->discount_amount / $this->pointsDiscountRate)
                                                        : 0;
                                        @endphp
                                        <span class="text-red-600">
                                            {{ $pointsUsed }} ball ({{ number_format($payment->discount_amount, 0, ',', ' ') }} UZS)
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span> {{-- Agar imtiyoz bo'lmasa --}}
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $payment->payment_date ? $payment->payment_date->format('d.m.Y') : '-' }}</td>
                                <td class="px-6 py-4">{{ $payment->payment_method }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        {{-- SVG ikonkalarni to'liq qo'shishni unutmang --}}
                                        <button type="button" wire:click="viewPaymentDetails({{ $payment->id }})" class="text-blue-600 hover:text-blue-800" title="Tafsilotlar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /> </svg>
                                        </button>
                                        <button type="button" wire:click="printReceipt({{ $payment->id }})" class="text-gray-600 hover:text-gray-800" title="Chekni chop etish">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v3a2 2 0 002 2h6a2 2 0 002-2v-3h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v3h6v-3z" clip-rule="evenodd" /> </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- Ma'lumot topilmaganda ko'rsatiladigan xabar --}}
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
            </div> {{-- overflow-x-auto yopilishi --}}
            <div class="px-4 py-3 border-t">
                {{ $this->payments->links() }}
            </div>
        </div>

        {{-- Yangi To'lov Modali --}}
        @if($showCreatePaymentModal)
            <div class="fixed inset-0 z-[1999] overflow-y-auto" aria-labelledby="create-payment-modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    {{-- Background --}}
                    <div x-data x-show="$wire.showCreatePaymentModal" x-transition.opacity.duration.300ms class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" @click="$wire.set('showCreatePaymentModal', false)"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    {{-- Modal Panel --}}
                    <div x-data x-show="$wire.showCreatePaymentModal"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="inline-block w-full max-w-xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl">

                        {{-- Header --}}
                        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="create-payment-modal-title"> Yangi To'lov Qo'shish </h3>
                            <button type="button" @click="$wire.set('showCreatePaymentModal', false)" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        {{-- Form --}}
                        <form wire:submit.prevent="savePayment">
                            <div class="mt-6 space-y-4">
                                {{-- 1. Filial Tanlash --}}
                                <div>
                                    <label for="modal_branch_id" class="block text-sm font-medium text-gray-700">Filial</label>
                                    <select wire:model.live="modal_branch_id" id="modal_branch_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('modal_branch_id') border-red-500 @enderror">
                                        <option value="">Filialni tanlang...</option>
                                        @foreach($modal_branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    {{-- @error('modal_branch_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror --}}
                                </div>

                                {{-- 2. Guruh Tanlash --}}
                                <div>
                                    <label for="modal_group_id" class="block text-sm font-medium text-gray-700">Guruh (O'quvchini topish uchun)</label>
                                    <select wire:model.live="modal_group_id" id="modal_group_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('modal_group_id') border-red-500 @enderror" @if(empty($modal_groups_options)) disabled @endif>
                                        <option value="">Guruhni tanlang...</option>
                                        @foreach($modal_groups_options as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    {{-- @error('modal_group_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror --}}
                                </div>

                                {{-- 3. O'quvchi Tanlash --}}
                                <div>
                                    <label for="modal_student_id" class="block text-sm font-medium text-gray-700">O'quvchi</label>
                                    <select wire:model.live="modal_student_id" id="modal_student_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('modal_student_id') border-red-500 @enderror" @if(empty($modal_students_options)) disabled @endif>
                                        <option value="">O'quvchini tanlang...</option>
                                        @foreach($modal_students_options as $student)
                                            <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }} ({{ $student->phone }})</option>
                                        @endforeach
                                    </select>
                                    @error('modal_student_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>

                                {{-- 4. To'lov summasi --}}
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">To'lov summasi</label>
                                    <div class="relative mt-1 rounded-md shadow-sm">
                                        {{-- amount uchun wire:model.live qo'shildi --}}
                                        <input wire:model.live="amount" type="number" step="0.01" id="amount" class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('amount') border-red-500 @enderror" placeholder="0.00">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"> <span class="text-gray-500 sm:text-sm">UZS</span> </div>
                                    </div>
                                    @error('amount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>

                                {{-- 5. Imtiyoz Ballari (YANGI) --}}
                                <div>
                                    <label for="modal_discount_points" class="block text-sm font-medium text-gray-700">Imtiyoz Ballari (Maks: 6)</label>
                                    <input wire:model.live="modal_discount_points" type="number" step="0.1" min="0" max="6" id="modal_discount_points" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('modal_discount_points') border-red-500 @enderror" placeholder="0.0">
                                    @error('modal_discount_points') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>

                                {{-- 6. Hisoblangan Imtiyoz va Yakuniy Summa (YANGI) --}}
                                <div class="p-3 bg-gray-50 rounded-md space-y-1">
                                    <div class="flex justify-between text-sm">
                                        <span class="font-medium text-gray-700">Hisoblangan Imtiyoz:</span>
                                        <span class="font-semibold text-red-600">
                                            - {{ number_format($modal_calculated_discount, 0, ',', ' ') }} UZS
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm border-t pt-1 mt-1">
                                        <span class="font-medium text-gray-900">Yakuniy To'lov Summasi:</span>
                                        <span class="font-bold text-green-600">
                                            {{ number_format($modal_payable_amount, 0, ',', ' ') }} UZS
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-500 block text-right"> (1 ball = {{ number_format($pointsDiscountRate, 0, ',', ' ') }} so'm)</span>
                                </div>

                                {{-- 7. To'lov sanasi --}}
                                <div>
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700">To'lov sanasi</label>
                                    <input wire:model.defer="payment_date" type="date" id="payment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('payment_date') border-red-500 @enderror">
                                    @error('payment_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>

                                {{-- 8. To'lov turi --}}
                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">To'lov turi</label>
                                    <select wire:model.defer="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('payment_method') border-red-500 @enderror">
                                        <option value="">Tanlang...</option>
                                        <option value="naqd">Naqd</option>
                                        <option value="karta">Karta</option>
                                        <option value="otkazma">O'tkazma</option>
                                        <option value="click">Click</option>
                                        <option value="payme">Payme</option>
                                        <option value="boshqa">Boshqa</option>
                                    </select>
                                    @error('payment_method') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>

                                {{-- 9. Izoh / Referens raqami --}}
                                <div>
                                    <label for="reference" class="block text-sm font-medium text-gray-700">Izoh / Referens raqami</label>
                                    <input wire:model.defer="reference" type="text" id="reference" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('reference') border-red-500 @enderror">
                                    @error('reference') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>

                                {{-- 10. Qo'shimcha izohlar --}}
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Qo'shimcha izohlar</label>
                                    <textarea wire:model.defer="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('notes') border-red-500 @enderror"></textarea>
                                    @error('notes') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Footer / Buttons --}}
                            <div class="pt-5 mt-6 border-t border-gray-200 sm:flex sm:flex-row-reverse">
                                <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-wait" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-primary-600 border border-transparent rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                                    <span wire:loading wire:target="savePayment" class="mr-2">
                                        <svg class="w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                    <span wire:loading.remove wire:target="savePayment"> Saqlash </span>
                                    <span wire:loading wire:target="savePayment"> Saqlanmoqda... </span>
                                </button>
                                <button type="button" @click="$wire.set('showCreatePaymentModal', false)" wire:loading.attr="disabled" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm"> Bekor qilish </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament::page>
