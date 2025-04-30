<x-filament::page>
    {{-- Bu yerga xodimning to'liq ma'lumotlarini chiqarish uchun dizayn qo'shasiz --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <div class="flex items-center space-x-4 mb-6 pb-6 border-b dark:border-gray-700">
            {{-- Rasm uchun joy (agar bo'lsa) --}}
            <div class="flex-shrink-0">
                <span class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-500 dark:bg-gray-600">
                    <span class="text-xl font-medium leading-none text-white">
                        {{ strtoupper(substr($staff->first_name ?? 'X', 0, 1) . substr($staff->last_name ?? '', 0, 1)) }}
                    </span>
                </span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $staff->full_name }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $staff->role?->name ?? $staff->position ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $staff->department?->name ?? 'Bo\'lim belgilanmagan' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">Bog'lanish ma'lumotlari</h3>
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $staff->email ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefon:</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $staff->phone ?? '-' }}</dd>
                    </div>
                    {{-- Boshqa ma'lumotlar --}}
                </dl>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">Ish joyi ma'lumotlari</h3>
                 <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Filial:</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $staff->branch?->name ?? 'N/A' }}</dd>
                    </div>
                     <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status:</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $staff->status ?? 'N/A' }}</dd>
                    </div>
                    {{-- Boshqa ma'lumotlar --}}
                </dl>
            </div>
        </div>

        {{-- Qo'shimcha bo'limlar (masalan, davomat, ish haqi tarixi va hokazo) --}}

        <div class="mt-8 flex justify-end space-x-3">
             <x-filament::button
                tag="a"
                :href="\App\Filament\Pages\HR\Employees::getUrl()"
                color="gray"
                icon="heroicon-m-arrow-left">
                Orqaga
            </x-filament::button>
            <x-filament::button
                tag="a"
                :href="\App\Filament\Resources\StaffResource::getUrl('edit', ['record' => $staff])"
                color="info"
                icon="heroicon-m-pencil-square">
                Tahrirlash
            </x-filament::button>
        </div>
    </div>
</x-filament::page>
