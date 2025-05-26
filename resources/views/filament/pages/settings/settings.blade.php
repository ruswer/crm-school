<x-filament::page>
    <div class="flex flex-col lg:flex-row gap-4">
        <!-- Chap panel (30%) -->
        <div class="w-full lg:w-[30%] bg-white rounded-lg shadow-sm p-4 h-fit">
            <!-- Yuqori qism (70%) -->
            <div>
                <!-- Logo banner -->
                <div class="space-y-4">
                    <!-- Logo container -->
                    <div class="w-full h-60 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden">
                        @if($settings->logo_path)
                            <!-- Mavjud logo -->
                            <img src="{{ Storage::url($settings->logo_path) }}" alt="Logotip" class="max-h-full max-w-full object-contain">
                        @elseif($logo && !is_array($logo))
                            <!-- Yangi yuklangan logo -->
                            <img src="{{ $logo->temporaryUrl() }}" alt="Logotip" class="max-h-full max-w-full object-contain">
                        @else
                            <!-- Logo yo'q holatda -->
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex flex-col items-center mt-4">
                                    <span class="block text-sm font-medium text-gray-900">
                                        Logotip
                                    </span>
                                    <span class="block text-xs text-gray-500 mt-1">
                                        PNG, JPG, SVG (max. 2MB)
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Tahrirlash tugmasi -->
                    <div class="flex justify-center w-full">
                        <button type="button"
                                wire:click="openLogoModal"
                                class="w-full inline-flex items-center justify-center px-4 py-2 mt-6 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Logoni tahrirlash
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- O'ng panel (70%) -->
        <div class="w-full lg:w-[70%] bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between mb-4 pb-4 border-b">
                <h2 class="text-lg font-medium text-gray-900">Umumiy sozlamalar</h2>
                <button wire:click="openSettingsModal" class="p-3 rounded-xl bg-primary-50 hover:bg-primary-100 transition-colors duration-200">
                    <svg class="w-7 h-7 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
            </div>
            <!-- Table container -->
            <div class="overflow-x-auto bg-white dark:bg-neutral-700">
                <table class="w-full">
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- O'quv markazi nomi -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">O'quv markazi nomi</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">{{ $settings->center_name ?? 'School Management' }}</span>
                            </td>
                        </tr>

                        <!-- Manzil -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Manzil</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">{{ $settings->address ?? 'Toshkent sh., Chilonzor t., 1-uy' }}</span>
                            </td>
                        </tr>

                        <!-- Telefon -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Telefon raqam</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">{{ $settings->phone ?? '+998 90 123 45 67' }}</span>
                            </td>
                        </tr>

                        <!-- Email -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Email</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">{{ $settings->email ?? 'example@info.com' }}</span>
                            </td>
                        </tr>

                        <!-- Sessiya -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Sessiya</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">{{ $settings->session ?? '2' }}</span>
                            </td>
                        </tr>

                        <!-- Til -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Til</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">{{ $settings->language ?? 'Uzbek' }}</span>
                            </td>
                        </tr>

                        <!-- Kunlar bo'yicha to'lov -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Kunlar bo'yicha to'lov</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">{{ $settings->daily_payment ?? '30' }}</span>
                            </td>
                        </tr>

                        <!-- Timezone -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Vaqt mintaqasi</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">{{ $settings->timezone ?? 'Asia/Tashkent (UTC+5)' }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Logotip yuklash uchun modal -->
    <div
        wire:ignore.self
        x-data="{ isOpen: @entangle('showLogoModal') }"
        x-show="isOpen"
        x-on:keydown.escape.window="isOpen = false"
        class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto px-4 py-4 sm:py-6"
        style="display: none;"
    >
        <!-- Overlay -->
        <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80"
            wire:click="closeLogoModal"
        ></div>

        <!-- Modal Content -->
        <div
            x-show="isOpen"
            x-trap.inert.noscroll="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-md sm:max-w-md mx-2 sm:mx-auto mt-4 sm:mt-0 max-h-[90vh] overflow-y-auto rounded-xl bg-white p-6 text-left shadow-xl transition-all dark:bg-gray-800"
        >
            <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Logotipni yuklash</h3>
                <button wire:click="closeLogoModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="saveLogo" class="mt-4 space-y-4">
                <div>
                    {{ $this->logoForm }}
                </div>
                <div class="mt-6 flex justify-end gap-x-3">
                    <button type="button" wire:click="closeLogoModal" class="inline-flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                        Bekor qilish
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="saveLogo" class="inline-flex items-center justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                        <svg wire:loading wire:target="saveLogo" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saqlash
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Umumiy sozlamalar uchun modal -->
    <div
        wire:ignore.self
        x-data="{ isOpen: @entangle('showSettingsModal') }"
        x-show="isOpen"
        x-on:keydown.escape.window="isOpen = false"
        class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto px-4 py-4 sm:py-6"
        style="display: none;"
    >
        <!-- Overlay -->
        <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80"
            wire:click="closeSettingsModal"
        ></div>

        <!-- Modal Content -->
        <div
            x-show="isOpen"
            x-trap.inert.noscroll="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-lg sm:max-w-lg mx-2 sm:mx-auto mt-4 sm:mt-0 max-h-[90vh] overflow-y-auto rounded-xl bg-white p-6 text-left shadow-xl transition-all dark:bg-gray-800"
        >
            <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Sozlamalarni tahrirlash</h3>
                <button wire:click="closeSettingsModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="saveSettings" class="mt-4 space-y-4">
                <div>
                    <label for="center_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">O'quv markazi nomi</label>
                    <input wire:model.defer="editSettings.center_name" type="text" id="center_name" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('editSettings.center_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Manzil</label>
                    <input wire:model.defer="editSettings.address" type="text" id="address" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('editSettings.address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefon raqam</label>
                    <input wire:model.defer="editSettings.phone" type="text" id="phone" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('editSettings.phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input wire:model.defer="editSettings.email" type="email" id="email" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('editSettings.email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="session" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sessiya</label>
                    <input wire:model.defer="editSettings.session" type="number" id="session" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('editSettings.session') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="language" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Til</label>
                    <input wire:model.defer="editSettings.language" type="text" id="language" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('editSettings.language') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="daily_payment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kunlar bo'yicha to'lov</label>
                    <input wire:model.defer="editSettings.daily_payment" type="number" id="daily_payment" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('editSettings.daily_payment') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vaqt mintaqasi</label>
                    <input wire:model.defer="editSettings.timezone" type="text" id="timezone" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('editSettings.timezone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="mt-6 flex justify-end gap-x-3">
                    <button type="button" wire:click="closeSettingsModal" class="inline-flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                        Bekor qilish
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="saveSettings" class="inline-flex items-center justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                        <svg wire:loading wire:target="saveSettings" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saqlash
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-filament::page>