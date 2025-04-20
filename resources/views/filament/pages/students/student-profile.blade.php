<x-filament::page>
    <div class="flex flex-col lg:flex-row gap-4">
        <!-- Chap panel (30%) - O'quvchi profili -->
        <div class="w-full lg:w-[30%] bg-white rounded-lg shadow-sm p-4 h-fit">
            <div class="flex flex-col items-center space-y-4">
                <!-- Profile rasm -->
                <div class="relative">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200">
                        @if($record->avatar)
                            <img src="{{ $record->avatar }}" alt="{{ $record->first_name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Tahrirlash tugmasi -->
                    <button type="button" class="absolute bottom-0 right-0 bg-amber-600 hover:bg-amber-700 text-white rounded-full p-2 shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                </div>

                <!-- O'quvchi ma'lumotlari -->
                <div class="text-center">
                    <h3 class="text-xl font-medium text-gray-900">
                        {{ $record->first_name }} {{ $record->last_name }}
                    </h3>
                </div>

                <!-- Qo'shimcha ma'lumotlar -->
                <div class="w-full pt-4 border-t border-gray-200">
                    <dl class="space-y-4">
                        <!-- Metrika/Pasport -->
                        <div class="flex flex-col">
                            <dt class="text-gray-900 font-medium">Metrika/Pasport:</dt>
                            <dd class="text-gray-500 text-sm mt-1">{{ $record->passport_number ?? 'YY00000' }}</dd>
                        </div>
                    
                        <!-- Filial -->
                        <div class="flex flex-col">
                            <dt class="text-gray-900 font-medium">Filial:</dt>
                            <dd class="text-gray-500 text-sm mt-1">{{ $record->branch->name }}</dd>
                        </div>
                        <!-- Guruhlar -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Guruhlar</h4>
                            @foreach($record->groups as $group)
                                <div class="bg-gray-100 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <h5 class="font-medium text-gray-900">{{ $group->name }}</h5>
                                        <span class="text-sm text-gray-500">
                                            ({{ number_format($group->price ?? 0, 0, ',', ' ') }}/{{ $group->duration ?? 12 }})
                                        </span>
                                    </div>

                                    <!-- Guruh statusi -->
                                    <div class="mb-2">
                                        <span class="text-sm {{ $group->pivot->status === 'Bitirgan' ? 'text-green-600' : 'text-gray-600' }}">
                                            @if($group->pivot->start_date && $group->pivot->end_date)
                                                {{ \Carbon\Carbon::parse($group->pivot->start_date)->format('d.m.Y') }}-{{ \Carbon\Carbon::parse($group->pivot->end_date)->format('d.m.Y') }}
                                            @else
                                                Sana belgilanmagan
                                            @endif
                                        </span>
                                        @if($group->pivot->status === 'Bitirgan')
                                            <span class="ml-2 px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                Bu guruhni bitirgan
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Qarz va Imtiyoz -->
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500">Qarz:</span>
                                            <span class="font-medium {{ ($group->pivot->debt ?? 0) > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ number_format($group->pivot->debt ?? 0, 0, ',', ' ') }} so'm
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500">Imtiyoz:</span>
                                            <span class="font-medium text-gray-900">
                                                {{ number_format($group->pivot->discount ?? 0, 0, ',', ' ') }} so'm
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- O'ng panel (70%) -->
        <div class="w-full lg:w-[70%] space-y-4">
            <!-- Tab buttons -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="flex items-center space-x-1 px-1 py-2 bg-gray-50">
                    <!-- Profil -->
                    <button type="button" 
                        wire:click="setActiveTab('profile')"
                        class="flex items-center space-x-2 px-4 py-2 rounded-md text-base font-medium transition-all duration-200 {{ $activeTab === 'profile' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profil</span>
                    </button>
        
                    <!-- To'lovlar -->
                    <button type="button" 
                        wire:click="setActiveTab('payments')"
                        class="flex items-center space-x-2 px-4 py-2 rounded-md text-base font-medium transition-all duration-200 {{ $activeTab === 'payments' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>To'lovlar</span>
                    </button>
        
                    <!-- To'lov tarixi -->
                    <button type="button" 
                        wire:click="setActiveTab('payment-history')"
                        class="flex items-center space-x-2 px-4 py-2 rounded-md text-base font-medium transition-all duration-200 {{ $activeTab === 'payment-history' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>To'lov tarixi</span>
                    </button>
        
                    <!-- Davomat -->
                    <button type="button" 
                        wire:click="setActiveTab('attendance')"
                        class="flex items-center space-x-2 px-4 py-2 rounded-md text-base font-medium transition-all duration-200 {{ $activeTab === 'attendance' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span>Davomat</span>
                    </button>
        
                    <!-- Imtihon -->
                    <button type="button" 
                        wire:click="setActiveTab('exam')"
                        class="flex items-center space-x-2 px-4 py-2 rounded-md text-base font-medium transition-all duration-200 {{ $activeTab === 'exam' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Imtihon</span>
                    </button>
                </div>
            </div>
            
            <!-- Action buttons -->
            <div class="flex justify-end">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden w-fit">
                    <div class="flex items-center px-3 py-3 bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <!-- Tahrirlash -->
                            <button type="button" 
                                class="px-2 rounded-md text-gray-700 hover:text-primary-600 hover:bg-white transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            <!-- Saytga kirish -->
                            <a href="#" 
                                class="px-2 rounded-md text-gray-700 hover:text-primary-600 hover:bg-white transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </a>

                            <!-- Safdan chiqarish -->
                            <button type="button" 
                                class="px-2 rounded-md text-gray-700 hover:text-primary-600 hover:bg-white transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Container -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    @if($activeTab === 'profile')
                        <!-- Profile Content -->
                        @include('filament.pages.students.partials.profile-content')
                    @elseif($activeTab === 'payments')
                        @include('filament.pages.students.partials.payments-content')
                    @elseif($activeTab === 'payment-history')
                        @include('filament.pages.students.partials.payment-history-content')
                    @elseif($activeTab === 'attendance')
                        @include('filament.pages.students.partials.attendance-content')
                    @elseif($activeTab === 'exam')
                        @include('filament.pages.students.partials.exam-content')
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament::page>