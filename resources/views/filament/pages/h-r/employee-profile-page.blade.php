<x-filament::page>
    <div class="flex flex-col lg:flex-row gap-4">
        <!-- Chap panel (30%) - Xodim profili -->
        <div @class([
            'w-full lg:w-[30%] rounded-lg shadow-sm p-4 h-fit',
            'bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-700' => $staff->deleted_at, // Agar o'chirilgan bo'lsa
            'bg-white dark:bg-gray-800' => !$staff->deleted_at, // Agar o'chirilmagan bo'lsa
        ])>
        <!-- Xodim ma'lumotlari -->
             <div class="flex flex-col items-center space-y-4">
                <!-- Profile rasm -->
                <div class="relative">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 dark:border-gray-700">
                        {{-- TODO: Agar xodim rasmi bo'lsa, uni ko'rsatish logikasini qo'shing --}}
                        {{-- @if($staff->photo_url)
                            <img src="{{ $staff->photo_url }}" alt="{{ $staff->full_name }}" class="w-full h-full object-cover">
                        @else --}}
                            <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                {{-- Placeholder Icon --}}
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                        {{-- @endif --}}
                    </div>
                </div>

                <!-- Xodim ma'lumotlari -->
                <div class="text-center">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                        {{ $staff->full_name }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $staff->position?->name ?? 'Lavozim belgilanmagan' }}</p>
                </div>

                <!-- Qo'shimcha ma'lumotlar -->
                <div class="w-full pt-4 border-t border-gray-200 dark:border-gray-700">
                    <dl class="space-y-4">
                        <!-- Filial -->
                        <div class="flex flex-col">
                            <dt class="text-gray-900 dark:text-gray-200 font-medium">Filial:</dt>
                            <dd class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $staff->branch?->name ?? 'N/A' }}</dd>
                        </div>
                        <!-- Bo'lim -->
                        <div class="flex flex-col">
                            <dt class="text-gray-900 dark:text-gray-200 font-medium">Bo'lim:</dt>
                            <dd class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $staff->department?->name ?? 'N/A' }}</dd>
                        </div>
                        <!-- Lavozim -->
                        <div class="flex flex-col">
                            <dt class="text-gray-900 dark:text-gray-200 font-medium">Lavozim:</dt>
                            <dd class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $staff->position?->name ?? 'N/A' }}</dd>
                        </div>
                        <!-- Rol -->
                        <div class="flex flex-col">
                            <dt class="text-gray-900 dark:text-gray-200 font-medium">Rol:</dt>
                            <dd class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $staff->role?->name ?? 'N/A' }}</dd>
                        </div>
                        <!-- Status -->
                        <div class="flex flex-col">
                            <dt class="text-gray-900 dark:text-gray-200 font-medium">Status:</dt>
                            <dd class="text-sm mt-1">
                                <span @class([
                                    'px-2 py-0.5 rounded-full text-xs font-medium',
                                    'bg-success-100 text-success-700 dark:bg-success-500/20 dark:text-success-400' => $staff->status === 'active',
                                    'bg-danger-100 text-danger-700 dark:bg-danger-500/20 dark:text-danger-400' => $staff->status !== 'active',
                                ])>
                                    {{ $staff->status === 'active' ? 'Faol' : 'Faol emas' }}
                                </span>
                            </dd>
                        </div>
                        <!-- Ishga qabul qilingan sana -->
                        <div class="flex flex-col">
                            <dt class="text-gray-900 dark:text-gray-200 font-medium">Ishga qabul qilingan sana:</dt>
                            <dd class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $staff->created_at ? \Carbon\Carbon::parse($staff->created_at)->format('d.m.Y') : '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- O'ng panel (70%) -->
        <div class="w-full lg:w-[70%] space-y-4">
            <!-- Tab buttons -->
            {{-- TODO: Livewire komponentida $activeTab propertysini va setActiveTab metodini qo'shing --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <div class="flex items-center space-x-1 px-1 py-2 bg-gray-50 dark:bg-gray-700/50">
                    {{-- Profil Tab --}}
                    <button type="button"
                        wire:click="setActiveTab('profile')"
                        class="flex items-center space-x-2 px-4 py-2 rounded-md text-base font-medium transition-all duration-200 {{ $activeTab === 'profile' ? 'bg-white dark:bg-gray-700 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-white dark:hover:bg-gray-700' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profil</span>
                    </button>
                    {{-- Davomat Tab --}}
                    <button type="button"
                        wire:click="setActiveTab('attendance')"
                        class="flex items-center space-x-2 px-4 py-2 rounded-md text-base font-medium transition-all duration-200 {{ $activeTab === 'attendance' ? 'bg-white dark:bg-gray-700 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-white dark:hover:bg-gray-700' }}">
                        {{-- Calendar Days Icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                        </svg>
                        <span>Davomat</span>
                    </button>
                    {{-- Ish haqi Tab --}}
                    <button type="button"
                        wire:click="setActiveTab('salary')"
                        class="flex items-center space-x-2 px-4 py-2 rounded-md text-base font-medium transition-all duration-200 {{ $activeTab === 'salary' ? 'bg-white dark:bg-gray-700 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-white dark:hover:bg-gray-700' }}">
                        {{-- Banknotes Icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.75A.75.75 0 013.75 4.5h.75m0 0v.75A.75.75 0 013.75 6h-.75m0 0h1.5m1.5 0h1.5m1.5 0h1.5m1.5 0h1.5m1.5 0h1.5m1.5 0h1.5m0 0h.75a.75.75 0 00.75-.75V5.25a.75.75 0 00-.75-.75h-.75m0 0H21M3 12h18M3 15h18M3 18h18M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                        <span>Ish haqi</span>
                    </button>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex justify-end">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden w-fit">
                    <div class="flex items-center px-3 py-3 bg-gray-50 dark:bg-gray-700/50">
                        <div class="flex items-center space-x-3">
                            {{-- Tahrirlash --}}
                            <a href="#"
                               title="Tahrirlash"
                               class="p-2 rounded-md text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- SMS Yuborish (hozircha o'chirilgan) --}}
                            <button type="button" title="SMS Yuborish" disabled
                                    class="p-2 rounded-md text-gray-400 dark:text-gray-500 cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-3.04 8.25-6.75 8.25a8.25 8.25 0 01-8.25-8.25S3 7.444 3 12c0-1.103.268-2.134.743-3.099.446-.91.99-1.748 1.6-2.487V5.25c0-.938.762-1.75 1.75-1.75h8.5c.988 0 1.75.813 1.75 1.75v1.164c.61.74 1.154 1.576 1.6 2.487.475.965.743 1.996.743 3.1z" />
                                </svg>
                            </button>

                            {{-- Safdan chiqarish (Modal ochish uchun) --}}
                            {{-- TODO: Livewire komponentida confirmDeleteStaff metodini va modal logikasini qo'shing --}}
                            <button type="button"
                                    {{-- wire:click="confirmDeleteStaff({{ $staff->id }}, '{{ addslashes($staff->full_name) }}')" --}}
                                    title="Safdan chiqarish"
                                    class="p-2 rounded-md text-gray-700 dark:text-gray-300 hover:text-danger-600 dark:hover:text-danger-400 hover:bg-danger-50 dark:hover:bg-danger-500/10 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Container -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="p-6">
                        @if($activeTab === 'profile')
                            @include('filament.pages.h-r.partials.profile-content')
                        @elseif($activeTab === 'attendance')
                            @include('filament.pages.h-r.partials.attendance-content')
                        @elseif($activeTab === 'salary')
                            @include('filament.pages.h-r.partials.salary-content')
                        @endif
                </div>
            </div>
        </div>

    {{-- TODO: Safdan chiqarish uchun modal oynani qo'shing (student-profile.blade.php dagi kabi) --}}
    {{-- @if($showDeleteConfirmModal) ... @endif --}}
 </x-filament::page>