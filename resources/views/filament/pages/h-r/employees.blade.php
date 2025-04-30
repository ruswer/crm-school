<x-filament::page>

    {{-- ========================================================== --}}
    {{-- BOSHQA Hamma Narsani O'rab Turuvchi YAGONA Asosiy DIV    --}}
    {{-- ========================================================== --}}
    <div class="flex flex-col h-full">

        <!-- ================== Tepa qism: Filtrlar ================== -->
        <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6"> {{-- mb-6 qo'shildi --}}
            {{-- Filtrlar paneli sarlavhasi va "Xodim qo'shish" tugmasi --}}
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700"> {{-- bg-white va shadow olib tashlandi, chunki tashqi divda bor --}}
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">Tanlash</span>
                </div>

                {{-- Qo'shish tugmasi (Filament button ishlatish tavsiya etiladi) --}}
                <x-filament::button
                    {{-- wire:click="openModal" --}} {{-- Bu metod Employees.php da yo'q --}}
                    tag="a"
                    href="" {{-- Resource'ga yo'naltirish --}}
                    icon="heroicon-m-plus"
                    color="warning">
                    Xodim qo'shish
                </x-filament::button>
            </div>

            {{-- Filtrlar inputlari --}}
            <div class="p-4"> {{-- flex, justify-between, bg-white, rounded-md, shadow-sm olib tashlandi --}}
                <div class="flex flex-col gap-4 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"> {{-- flex-wrap va lg:flex-nowrap o'rniga grid --}}
                        {{-- Filial --}}
                        <div>
                            <x-filament::input.wrapper>
                                <x-filament::input.select wire:model.defer="branchFilter" id="branch">
                                    <option value="">Barcha filiallar</option>
                                    @foreach($this->branches as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>
                        {{-- Lavozim --}}
                        <div>
                            <x-filament::input.wrapper>
                                <x-filament::input.select wire:model.defer="positionFilter" id="positionFilter">
                                    <option value="">Barcha lavozimlar</option>
                                    @foreach($this->positions as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>
                        {{-- Bo'lim --}}
                        <div>
                            <x-filament::input.wrapper>
                                <x-filament::input.select wire:model.defer="departmentFilter" id="departmentFilter">
                                    <option value="">Barcha bo'limlar</option>
                                    @foreach($this->departments as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>
                        {{-- Kalit so'z --}}
                        <div>
                            <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
                                <x-filament::input
                                    type="search"
                                    wire:model.defer="search"
                                    id="search-keyword"
                                    placeholder="ID, Ism, Lavozim bo'yicha..."
                                />
                            </x-filament::input.wrapper>
                        </div>
                    </div>
                    {{-- Qidirish tugmasi --}}
                    <div class="flex justify-end">
                        <x-filament::button
                            wire:click="search"
                            wire:loading.attr="disabled"
                            wire:target="search"
                            icon="heroicon-m-magnifying-glass">
                            <span wire:loading.remove wire:target="search">Qidirish</span>
                            <span wire:loading wire:target="search">Qidirilmoqda...</span>
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ================== End Tepa qism ================== -->


        <!-- ================== Pastki qism: Kontent (Grid/List) ================== -->
        {{-- x-data ni bu yerga ko'chirish --}}
        <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4" x-data="{ view: 'grid' }">

            <!-- View Toggle Buttons -->
            <div class="flex justify-between items-center mb-6 border-b dark:border-gray-700 pb-4">
                 <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">Xodimlar</h2>
                 {{-- Alpine.js `view` o'zgaruvchisini ishlatish --}}
                 <div class="flex space-x-1 border dark:border-gray-600 rounded-lg p-0.5">
                    <button
                        type="button"
                        @click="view = 'grid'"
                        title="Grid ko'rinishi"
                        :class="{ 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200': view === 'grid', 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50': view !== 'grid' }"
                        class="p-2 rounded-md transition-colors duration-200"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click="view = 'list'"
                        title="Ro'yxat ko'rinishi"
                        :class="{ 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200': view === 'list', 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50': view !== 'list' }"
                        class="p-2 rounded-md transition-colors duration-200"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div wire:loading.flex class="justify-center items-center w-full py-8">
                 <x-filament::loading-indicator class="h-8 w-8" />
            </div>

            <!-- Employees Content (Grid va Listni o'z ichiga oladi) -->
            <div wire:loading.remove> {{-- x-data bu yerdan olib tashlandi --}}

                <!-- Grid View -->
                <div x-show="view === 'grid'" x-transition.opacity>
                    @php
                        // Bu yerda $this->staffList ni ishlatish kerak
                        $staffMembers = $this->staffList ?? collect();
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($staffMembers as $staff)
                            <div class="bg-white dark:bg-gray-800/50 rounded-lg shadow-sm p-5 border dark:border-gray-700 flex flex-col justify-between">
                                <div>
                                    {{-- Profil sahifasiga link (route helper bilan) --}}
                                    <a href=""
                                       class="block mb-1 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $staff->full_name }}</h3>
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $staff->position?->name ?? 'Lavozim yo\'q' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $staff->phone ?? '-' }}</p>
                                </div>
                                <div class="mt-4 pt-4 border-t dark:border-gray-700 flex justify-end space-x-1">
                                    <x-filament::icon-button
                                        icon="heroicon-o-chat-bubble-left-right"
                                        label="SMS Yuborish" tooltip="SMS Yuborish" tag="button" color="gray" size="sm" disabled />
                                    <x-filament::icon-button
                                        icon="heroicon-m-pencil-square"
                                        label="Tahrirlash" tooltip="Profilni ko'rish/Tahrirlash" tag="button" wire:click="goToStaffProfile({{ $staff->id }})" color="info" size="sm" />
                                    <x-filament::icon-button
                                        icon="heroicon-m-trash"
                                        wire:click="confirmDeleteStaff({{ $staff->id }}, '{{ $staff->full_name }}')"
                                        label="Safdan chiqarish" tooltip="Safdan chiqarish" color="danger" size="sm" />
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <x-heroicon-o-users class="mx-auto h-12 w-12 text-gray-400" />
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">Xodimlar topilmadi</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Qidiruv yoki filtr parametrlarini o'zgartirib ko'ring.</p>
                            </div>
                        @endforelse
                    </div>
                    {{-- Grid uchun Pagination --}}
                    @if ($staffMembers->hasPages())
                        <div class="mt-6 px-2">
                            {{ $staffMembers->links() }}
                        </div>
                    @endif
                </div>

                <!-- List View -->
                <div x-show="view === 'list'" x-transition.opacity>
                    {{-- List View uchun jadval kodi (Filament Tables ishlatish tavsiya etiladi) --}}
                    <div class="overflow-x-auto rounded-lg border dark:border-gray-700">
                        <x-filament-tables::table>
                            <x-slot name="header">
                                {{-- Saralash uchun wire:click va boshqa atributlar qo'shilgan --}}
                                <x-filament-tables::header-cell wire:click="sortBy('id')" :active="$sortField === 'id'" :direction="$sortField === 'id' ? $sortDirection : null" class="cursor-pointer">ID</x-filament-tables::header-cell>
                                <x-filament-tables::header-cell wire:click="sortBy('first_name')" :active="$sortField === 'first_name'" :direction="$sortField === 'first_name' ? $sortDirection : null" class="cursor-pointer">Xodim Nomi</x-filament-tables::header-cell>
                                <x-filament-tables::header-cell wire:click="sortBy('position_id')" :active="$sortField === 'position_id'" :direction="$sortField === 'position_id' ? $sortDirection : null" class="cursor-pointer">Lavozim</x-filament-tables::header-cell>
                                <x-filament-tables::header-cell wire:click="sortBy('department_id')" :active="$sortField === 'department_id'" :direction="$sortField === 'department_id' ? $sortDirection : null" class="cursor-pointer">Bo'lim</x-filament-tables::header-cell>
                                <x-filament-tables::header-cell wire:click="sortBy('phone')" :active="$sortField === 'phone'" :direction="$sortField === 'phone' ? $sortDirection : null" class="cursor-pointer">Mobil raqam</x-filament-tables::header-cell>
                                <x-filament-tables::header-cell wire:click="sortBy('branch_id')" :active="$sortField === 'branch_id'" :direction="$sortField === 'branch_id' ? $sortDirection : null" class="cursor-pointer">Filial</x-filament-tables::header-cell>
                                <x-filament-tables::header-cell class="text-right">Amallar</x-filament-tables::header-cell>
                            </x-slot>

                            @php
                                // Bu yerda ham $this->staffList ni ishlatish kerak
                                $staffMembersList = $this->staffList ?? collect();
                            @endphp
                            @forelse($staffMembersList as $staff)
                                <x-filament-tables::row wire:loading.class="opacity-50">
                                    <x-filament-tables::cell>{{ $staff->id }}</x-filament-tables::cell>
                                    <x-filament-tables::cell>
                                        <a href="" class="hover:text-primary-600 dark:hover:text-primary-400 font-medium">
                                            {{ $staff->full_name }}
                                        </a>
                                    </x-filament-tables::cell>
                                    <x-filament-tables::cell>{{ $staff->position?->name ?? '-' }}</x-filament-tables::cell>
                                    <x-filament-tables::cell>{{ $staff->department?->name ?? '-' }}</x-filament-tables::cell>
                                    <x-filament-tables::cell>{{ $staff->phone ?? '-' }}</x-filament-tables::cell>
                                    <x-filament-tables::cell>{{ $staff->branch?->name ?? '-' }}</x-filament-tables::cell>
                                    <x-filament-tables::cell class="text-right">
                                        <div class="flex justify-end space-x-1">
                                            <x-filament::icon-button icon="heroicon-o-chat-bubble-left-right" label="SMS" tooltip="SMS Yuborish" tag="button" color="gray" size="sm" disabled />
                                            <x-filament::icon-button icon="heroicon-m-pencil-square" label="Tahrirlash" tooltip="Profilni ko'rish/Tahrirlash" tag="button" wire:click="goToStaffProfile({{ $staff->id }})" color="info" size="sm" />
                                            <x-filament::icon-button icon="heroicon-m-trash" wire:click="confirmDeleteStaff({{ $staff->id }}, '{{ $staff->full_name }}')" label="O'chirish" tooltip="Safdan chiqarish" color="danger" size="sm" />
                                        </div>
                                    </x-filament-tables::cell>
                                </x-filament-tables::row>
                            @empty
                                <x-filament-tables::row>
                                    <x-filament-tables::cell colspan="7" class="text-center py-12">
                                        <x-heroicon-o-users class="mx-auto h-12 w-12 text-gray-400" />
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">Xodimlar topilmadi</h3>
                                    </x-filament-tables::cell>
                                </x-filament-tables::row>
                            @endforelse
                        </x-filament-tables::table>
                    </div>
                    {{-- List uchun Pagination --}}
                    @if ($staffMembersList->hasPages())
                        <div class="mt-6 px-2">
                            {{ $staffMembersList->links() }}
                        </div>
                    @endif
                </div>

            </div>
            <!-- End Employees Content -->

        </div>
        <!-- ================== End Pastki qism ================== -->


        {{-- ================== O'chirishni Tasdiqlash Modali ================== --}}
        {{-- BU MODAL ASOSIY DIV ICHIDA, KONTENT BLOKIDAN KEYIN --}}
        <x-filament::modal
            id="delete-staff-confirmation"
            wire:model.live="showDeleteConfirmModal"
            icon="heroicon-o-trash"
            icon-color="danger"
            alignment="center"
            width="md"
        >
            <x-slot name="heading">
                Xodimni safdan chiqarishni tasdiqlang
            </x-slot>

            <x-slot name="description">
                @if($staffToDeleteName)
                    Haqiqatan ham **{{ $staffToDeleteName }}**ni safdan chiqarmoqchimisiz? Bu amalni orqaga qaytarib bo'lmaydi (lekin qayta tiklash mumkin).
                @else
                    Haqiqatan ham tanlangan xodimni safdan chiqarmoqchimisiz?
                @endif
            </x-slot>

            <x-slot name="footer">
                <x-filament::button
                    color="danger"
                    wire:click="deleteStaff"
                    wire:loading.attr="disabled"
                    wire:target="deleteStaff"
                >
                    Ha, safdan chiqarish
                </x-filament::button>

                <x-filament::button
                    color="gray"
                    x-on:click="$wire.set('showDeleteConfirmModal', false)"
                    wire:loading.attr="disabled"
                    wire:target="deleteStaff"
                >
                    Bekor qilish
                </x-filament::button>
            </x-slot>
        </x-filament::modal>
        {{-- ================== End O'chirishni Tasdiqlash Modali ================== --}}


    </div> {{-- <<< YAGONA ASOSIY DIVNING YOPILISHI --}}

</x-filament::page>
