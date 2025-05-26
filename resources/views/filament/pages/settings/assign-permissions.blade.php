<x-filament::page>
    <div class="w-full mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-4 border-b dark:border-gray-700">
            "{{ $roleName }}" uchun ruxsatlarni belgilash
        </h2>

        <form wire:submit.prevent="savePermissions" class="space-y-6">
            <div class="space-y-4">
                @foreach ($modules as $module => $features)
                    <div class="border-b dark:border-gray-700 pb-4">
                        <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $module }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-gray-700 dark:text-gray-200">Xususiyat</th>
                                        <th class="px-4 py-2 text-gray-700 dark:text-gray-200 text-center">Ko‘rish</th>
                                        <th class="px-4 py-2 text-gray-700 dark:text-gray-200 text-center">Qo‘shish</th>
                                        <th class="px-4 py-2 text-gray-700 dark:text-gray-200 text-center">Tahrirlash</th>
                                        <th class="px-4 py-2 text-gray-700 dark:text-gray-200 text-center">O‘chirish</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                    @foreach ($features as $feature)
                                        <tr>
                                            <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $feature['name'] }}</td>
                                            <td class="px-4 py-2 text-center">
                                                <input wire:model="selectedPermissions.{{ $feature['permissions']['view'] }}"
                                                       type="checkbox"
                                                       value="1"
                                                       id="permission-{{ $feature['permissions']['view'] }}"
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <input wire:model="selectedPermissions.{{ $feature['permissions']['create'] }}"
                                                       type="checkbox"
                                                       value="1"
                                                       id="permission-{{ $feature['permissions']['create'] }}"
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <input wire:model.defer="selectedPermissions.{{ $feature['permissions']['edit'] }}"
                                                       type="checkbox"
                                                       id="permission-{{ $feature['permissions']['edit'] }}"
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <input wire:model.defer="selectedPermissions.{{ $feature['permissions']['delete'] }}"
                                                       type="checkbox"
                                                       id="permission-{{ $feature['permissions']['delete'] }}"
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end gap-x-3">
                <a href="{{ route('filament.admin.pages.roles-permissions') }}"
                   class="inline-flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                    Bekor qilish
                </a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="savePermissions"
                        class="inline-flex items-center justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                    <svg wire:loading wire:target="savePermissions" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saqlash
                </button>
            </div>
        </form>
    </div>
</x-filament::page>