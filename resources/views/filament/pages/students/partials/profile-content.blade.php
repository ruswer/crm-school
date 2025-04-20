<div class="space-y-6 bg-white dark:bg-neutral-700">
    <!-- Asosiy ma'lumotlar -->
    <div class="border rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b">
            <h3 class="text-lg font-medium text-gray-900">Asosiy ma'lumotlar</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            <!-- Status -->
            <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                <div class="font-medium text-gray-500">Status</div>
                <div class="col-span-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium 
                        {{ $record->status->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                        style="{{ $record->status->color ? "background-color: {$record->status->color}19; color: {$record->status->color}" : '' }}">
                        {{ $record->status->name }}
                    </span>
                </div>
            </div>

            <!-- Jinsi -->
            <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                <div class="font-medium text-gray-500">Jinsi</div>
                <div class="col-span-2 text-gray-900">
                    {{ $record->gender === 'male' ? 'Erkak' : ($record->gender === 'female' ? 'Ayol' : '-') }}
                </div>
            </div>

            <!-- Yoshi -->
            <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                <div class="font-medium text-gray-500">Yoshi</div>
                <div class="col-span-2 text-gray-900">
                    @if($record->birth_date)
                        {{ \Carbon\Carbon::parse($record->birth_date)->age }} yosh
                    @else
                        <span class="text-gray-500">-</span>
                    @endif
                </div>
            </div>

            <!-- Qabul qilingan sana -->
            <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                <div class="font-medium text-gray-500">Qabul qilingan sana</div>
                <div class="col-span-2 text-gray-900">
                    {{ $record->created_at ? $record->created_at->format('d-m-Y') : '-' }}
                </div>
            </div>

            <!-- Tug'ilgan kuni -->
            <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                <div class="font-medium text-gray-500">Tug'ilgan kuni</div>
                <div class="col-span-2 text-gray-900">
                    {{ $record->birth_date ? $record->birth_date->format('d-m-Y') : '-' }}
                </div>
            </div>

            <!-- Email -->
            <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                <div class="font-medium text-gray-500">Email</div>
                <div class="col-span-2 text-gray-900">{{ $record->email ?? '-' }}</div>
            </div>

            <!-- O'qish tili -->
            <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                <div class="font-medium text-gray-500">O'qish tili</div>
                <div class="col-span-2 text-gray-900">
                    @forelse($record->studyLanguagesStudents as $studyLanguage)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mr-2">
                            {{ $studyLanguage->language }}
                        </span>
                    @empty
                        <span class="text-gray-500">-</span>
                    @endforelse
                </div>
            </div>

            <!-- Daraja -->
            <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                <div class="font-medium text-gray-500">Daraja</div>
                <div class="col-span-2 text-gray-900">{{ $record->knowledgeLevel->name ?? '-' }}</div>
            </div>

            <!-- O'qish kunlari -->
            <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                <div class="font-medium text-gray-500">O'qish kunlari</div>
                <div class="col-span-2 text-gray-900">
                    @forelse($record->studyDayStudents as $studyDay)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800 mr-2">
                            {{ $studyDay->day }}
                        </span>
                    @empty
                        <span class="text-gray-500">-</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Sinov darsi ma'lumotlari -->
    <div class="border rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b">
            <h3 class="text-lg font-medium text-gray-900">Sinov darsi ma'lumotlari</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            <!-- Mas'ul o'qituvchi -->
            <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                <div class="font-medium text-gray-500">Mas'ul o'qituvchi</div>
                <div class="col-span-2">
                    @if($record->trialTeacher)
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-500">
                                    <span class="text-lg font-medium leading-none text-white">
                                        {{ substr($record->trialTeacher->first_name, 0, 1) }}
                                    </span>
                                </span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $record->trialTeacher->first_name }} {{ $record->trialTeacher->last_name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $record->trialTeacher->phone }}
                                </div>
                            </div>
                        </div>
                    @else
                        <span class="text-gray-500">Belgilanmagan</span>
                    @endif
                </div>
            </div>

            <!-- Sinov darsiga chaqirildi -->
            <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                <div class="font-medium text-gray-500">Sinov darsiga chaqirildi</div>
                <div class="col-span-2">
                    @if($record->trial_called_at)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            {{ $record->trial_called_at->format('d-m-Y H:i') }}
                        </span>
                    @else
                        <span class="text-gray-500">Belgilanmagan</span>
                    @endif
                </div>
            </div>


            <!-- Sinov darsiga keldi -->
            <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                <div class="font-medium text-gray-500">Sinov darsiga keldi</div>
                <div class="col-span-2">
                    @if($record->trial_attended_at)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            {{ $record->trial_attended_at->format('d-m-Y H:i') }}
                        </span>
                    @else
                        <span class="text-gray-500">Kelmagan</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ota-ona ma'lumotlari -->
    <div class="border rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b">
            <h3 class="text-lg font-medium text-gray-900">Ota-ona ma'lumotlari</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($record->parents as $parent)
                <!-- Har bir ota-ona uchun alohida bo'lim -->
                <div class="divide-y divide-gray-200">
                    <!-- Ota-ona ismi -->
                    <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                        <div class="font-medium text-gray-500">Ota-ona ismi</div>
                        <div class="col-span-2 flex items-center space-x-3">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500">
                                <span class="text-sm font-medium leading-none text-white">
                                    {{ substr($parent->full_name, 0, 1) }}
                                </span>
                            </span>
                            <span class="text-gray-900">{{ $parent->full_name }}</span>
                        </div>
                    </div>

                    <!-- Ota-ona emaili -->
                    <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                        <div class="font-medium text-gray-500">Ota-ona email</div>
                        <div class="col-span-2 text-gray-900">
                            {{ $parent->email ?? '-' }}
                        </div>
                    </div>

                    <!-- Ota-ona telefoni -->
                    <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50">
                        <div class="font-medium text-gray-500">Ota-ona telefoni</div>
                        <div class="col-span-2 flex items-center space-x-2">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="text-gray-900">{{ $parent->phone }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-3 text-sm text-gray-500">
                    Ota-ona ma'lumotlari kiritilmagan
                </div>
            @endforelse
        </div>
    </div>
</div>