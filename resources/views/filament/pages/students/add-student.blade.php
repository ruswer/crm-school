<x-filament::page>
    <div class="flex flex-col h-full">
        <form wire:submit.prevent="create">
            <!-- Tepa qism -->
            <div class="flex-1 bg-white p-4 rounded-md shadow-sm">
                <h2 class="text-lg font-bold text-gray-800 mb-4 bg-gray-100 py-4 px-4">O'quvchi Ma'lumotlari</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Qabul qilinganlik raqami -->
                    <div>
                        <label for="passport_number" class="block text-sm font-medium text-gray-700">Metirka/Pasport raqami</label>
                        <input
                            wire:model.defer="form.passport_number"                            
                            type="text"
                            id="passport_number"
                            name="passport_number"
                            placeholder="YY00000"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                        @error('form.passport_number')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Filial -->
                    <div>
                        <label for="branch_id" class="block text-sm font-medium text-gray-700">Filial</label>
                        <select
                            wire:model="form.branch_id"
                            id="branch"
                            name="branch"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        >
                            <option value="">Filialni tanlang</option>
                            @foreach($options['branches'] as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('form.branch_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- Guruhlarni tanlang -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Guruhlar</label>
                        <div class="relative" x-data="{ open: false }">
                            <button 
                                @click="open = !open"
                                type="button"
                                class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            >
                                <span class="block truncate" wire:key="selected-groups">
                                    {{ $this->selectedGroupNames }}
                                </span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>
                            <div 
                                x-show="open" 
                                @click.away="open = false"
                                class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                            >
                                <div class="px-2 py-2">
                                    @foreach($options['groups'] as $group)
                                        <div 
                                            class="flex items-center px-2 py-2 rounded-md cursor-pointer transition-colors duration-150 ease-in-out {{ in_array($group->id, $form['group_ids'] ?? []) ? 'bg-blue-50' : 'hover:bg-gray-100' }}"
                                        >
                                            <input
                                                type="checkbox"
                                                id="group_{{ $group->id }}"
                                                wire:model.debounce.500ms="form.group_ids"
                                                value="{{ $group->id }}"
                                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            >
                                            <label 
                                                for="group_{{ $group->id }}" 
                                                class="ml-3 block text-sm {{ in_array($group->id, $form['group_ids'] ?? []) ? 'text-blue-600 font-medium' : 'text-gray-700' }} w-full cursor-pointer"
                                            >
                                                {{ $group->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('form.group_ids')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Ism -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Ism</label>
                        <input
                            wire:model.defer="form.first_name"                            
                            type="text"
                            id="first_name"
                            name="first_name"
                            placeholder="Ism kiriting"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                        @error('form.first_name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Familiya -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Familiya</label>
                        <input
                            wire:model.defer="form.last_name"                            
                            type="text"
                            id="last_name"
                            name="last_name"
                            placeholder="Familiya kiriting"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                        @error('form.last_name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Jinsi -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Jinsi</label>
                        <select
                            wire:model="form.gender"
                            id="gender"
                            name="gender"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        >
                            <option value="">Jinsni tanlang</option>
                            <option value="male">Erkak</option>
                            <option value="female">Ayol</option>
                        </select>
                        @error('form.gender')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Tug'ilgan yili -->
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700">Tug'ilgan yili</label>
                        <input
                            wire:model.defer="form.birth_date"                            
                            type="date"
                            id="birth_date"
                            name="birth_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                        @error('form.birth_date')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Telefon raqami -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Telefon raqami</label>
                        <input
                            wire:model.defer="form.phone"                            
                            type="number"
                            id="phone"
                            name="phone"
                            placeholder="Telefon kiriting"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                        @error('form.phone')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input
                            wire:model.defer="form.email"                            
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Email kiriting"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                        @error('form.email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- O'quvchi statusi -->
                    <div>
                        <label for="studentStatus" class="block text-sm font-medium text-gray-700">O'quvchi statusi</label>
                        <select
                            wire:model="form.status_id"
                            id="studentStatus"
                            name="studentStatus"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        >
                            <option value="">Statusni tanlang</option>
                            @foreach($options['studentStatuses'] as $studentStatus)
                                <option value="{{ $studentStatus->id }}">{{ $studentStatus->name }}</option>
                            @endforeach
                        </select>
                        @error('form.status_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pastki qism -->
            <div class="mt-1 bg-white p-4 rounded-md shadow-sm">
                <h2 class="text-lg font-bold text-gray-800 mb-6 bg-gray-100 py-4 px-4">Qo'shimcha</h2>
                
                <!-- Grid Container -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- 1-qism: Til tanlash -->
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">O'qish tili</p>
                        <div class="flex flex-col">
                            <label class="inline-flex items-center hover:bg-gray-100 p-2 rounded-md transition-colors">
                                <input
                                    wire:model.defer="study.language"
                                    type="checkbox" 
                                    class="form-checkbox text-blue-600 rounded" 
                                    name="language[]" 
                                    value="uzbek"
                                >
                                <span class="ml-1 text-gray-700">O'zbek</span>
                            </label>
                            <label class="inline-flex items-center hover:bg-gray-100 p-2 rounded-md transition-colors">
                                <input
                                    wire:model.defer="study.language"
                                    type="checkbox" 
                                    class="form-checkbox text-blue-600 rounded" 
                                    name="language[]" 
                                    value="russian"
                                >
                                <span class="ml-2 text-gray-700">Rus</span>
                            </label>
                            <label class="inline-flex items-center hover:bg-gray-100 p-2 rounded-md transition-colors">
                                <input
                                    wire:model.defer="study.language"
                                    type="checkbox" 
                                    class="form-checkbox text-blue-600 rounded" 
                                    name="language[]" 
                                    value="english"
                                >
                                <span class="ml-2 text-gray-700">Ingliz</span>
                            </label>
                        </div>
                        @error('study.language')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <!-- 2-qism: Kurslar va Bilim darajasi -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Kurslar</p>
                            <select
                                wire:model="study.course_id"
                                name="course"
                                id="course"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="">Kursni tanlang</option>
                                @foreach($options['courses'] as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                            @error('study.course_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
            
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">O'quvchining bilim darajasi</p>
                            <select
                                wire:model="study.knowledge_level_id"
                                name="knowledge_level_id"
                                id="knowledge_level_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="">Darajani tanlang</option>
                                @foreach($options['knowledgeLevels'] as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                            @error('study.knowledge_level_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
            
                    <!-- 3-qism: Hafta kunlari -->
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">Kunlar</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2">
                            @foreach(['monday' => 'Dushanba', 'tuesday' => 'Seshanba', 'wednesday' => 'Chorshanba', 
                                     'thursday' => 'Payshanba', 'friday' => 'Juma', 'saturday' => 'Shanba'] as $value => $label)
                                <label class="inline-flex items-center hover:bg-gray-100 p-2 rounded-md transition-colors">
                                    <input
                                        wire:model.defer="study.days"
                                        type="checkbox" 
                                        name="days[]" 
                                        value="{{ $value }}" 
                                        class="form-checkbox text-blue-600 rounded"
                                    >
                                    <span class="ml-1 text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('study.days')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <!-- 4-qism: Marketing va Izoh -->
                    <div class="rounded-lg space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">O'quvchi o'quv markaz haqida qayerdan bildi?</p>
                            <select
                                wire:model="form.marketing_source_id"
                                name="marketing_source_id"
                                id="marketing_source_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="">Manbani tanlang</option>
                                @foreach($options['marketingSources'] as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('marketing_source_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
            
                        <div>
                            <label for="additional_details" class="block text-sm font-medium text-gray-700">Izoh</label>
                            <textarea
                                wire:model.defer="form.notes"
                                id="additional_details"
                                name="additional_details"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="Qo'shimcha ma'lumotlar..."
                            ></textarea>
                            @error('form.notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sinov darsi buyicha ma'lumotlar -->
            <div class="flex-1 bg-white p-4 rounded-md shadow-sm mt-1">
                <h2 class="text-lg font-bold text-gray-800 mb-4 bg-gray-100 py-4 px-4">Sinov darsi buyicha ma'lumotlar</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="teacherName" class="block text-sm font-medium text-gray-700">O'qituvchini tanlang</label>
                        <select
                            wire:model="trial.teacher_id"
                            id="teacherName"
                            name="teacher"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        >
                            <option value="">O'qituvchini tanlang</option>
                            @foreach($options['teachers'] as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                            @endforeach
                        </select>
                        @error('trial.teacher_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="called_at" class="block text-sm font-medium text-gray-700">Sinov darsiga chaqirildi</label>
                        <input
                            wire:model.defer="trial.called_at"                                
                            type="date"
                            id="called_at"
                            name="called_at"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                        @error('trial.called_at')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="attended_at" class="block text-sm font-medium text-gray-700">Sinov darsiga keldi</label>
                        <input
                            wire:model.defer="trial.attended_at"                                
                            type="date"
                            id="attended_at"
                            name="attended_at"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                        @error('trial.attended_at')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Ota onalar malumoti -->
            <div class="flex-1 bg-white p-4 rounded-md shadow-sm mt-1">
                <h2 class="text-lg font-bold text-gray-800 mb-4 bg-gray-100 py-4 px-4">Ota-onalar malumoti</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Ota yoki Onasini Ism -->
                    <div>
                        <label for="parentsName" class="block text-sm font-medium text-gray-700">Ota yoki Onasini ismi</label>
                        <input
                            wire:model.defer="parent.parentsName"                            
                            type="text"
                            id="parentsName"
                            name="parentsName"
                            placeholder="Ism kiriting"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                        @error('parent.parentsName')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- Ota yoki Onasini Telefon raqami -->
                    <div>
                        <label for="parentsPhone" class="block text-sm font-medium text-gray-700">Ota yoki Onasini telefoni</label>
                        <input
                            wire:model.defer="parent.parentsPhone"                            
                            type="number"
                            id="parentsPhone"
                            name="parentsPhone"
                            placeholder="Telefon kiriting"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                        @error('parent.parentsPhone')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Ota yoki Onasini Email -->
                    <div>
                        <label for="parentsEmail" class="block text-sm font-medium text-gray-700">Ota yoki Onasini Email</label>
                        <input
                            wire:model.defer="parent.parentsEmail"                            
                            type="email"
                            id="parentsEmail"
                            name="parentsEmail"
                            placeholder="Email kiriting"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                        @error('parent.parentsEmail')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tugmalar -->
            <div class="flex justify-end items-center gap-4 mt-6">
                <!-- Qo'shish tugmasi -->
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                >
                    Qo'shish
                </button>
                <!-- Qo'shish & Yana Qo'shish tugmasi -->
                <button
                    type="button"
                    wire:click="createAnother"
                    class="inline-flex items-center px-4 py-2 border border-primary-600 text-sm font-medium rounded-md shadow-sm text-primary-600 bg-white hover:bg-primary-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                >
                    Qo'shish & Yana Qo'shish
                </button>
            </div>
        </form>
    </div>
</x-filament::page>