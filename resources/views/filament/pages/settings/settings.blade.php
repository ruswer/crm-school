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
                        {{-- @if($logo) --}}
                            <!-- Mavjud logo -->
                        {{-- @else --}}
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
                        {{-- @endif --}}
                    </div>

                    <!-- Tahrirlash tugmasi -->
                    <div class="flex justify-center w-full">
                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 mt-6 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
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
                <a href="#" class="p-3 rounded-xl bg-primary-50 hover:bg-primary-100 transition-colors duration-200">
                    <svg class="w-7 h-7 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
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
                                <span class="text-gray-700 dark:text-gray-300">School Management</span>
                            </td>
                        </tr>

                        <!-- Manzil -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Manzil</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">Toshkent sh., Chilonzor t., 1-uy</span>
                            </td>
                        </tr>

                        <!-- Telefon-->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Telfon raqam</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">+998 90 123 45 67</span>
                            </td>
                        </tr>

                        <!-- Email -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Email</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">example@info.com</span>
                            </td>
                        </tr>

                        <!-- Sessiya -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Sessiya</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">2</span>
                            </td>
                        </tr>

                        <!-- Til -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Til</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">Uzbek</span>
                            </td>
                        </tr>

                        <!-- Kunlar bo'yicha tulov -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Kunlar bo'yicha tulov</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">30</span>
                            </td>
                        </tr>

                        <!-- Timezone -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 w-64">
                                <div class="font-medium text-gray-900 dark:text-white">Vaqt mintaqasi</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-700 dark:text-gray-300">Asia/Tashkent (UTC+5)</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament::page>