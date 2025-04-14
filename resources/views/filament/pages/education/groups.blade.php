<x-filament::page>
    <div class="flex flex-col lg:flex-row gap-4">
        <!-- Chap panel (30%) -->
        <div class="w-full lg:w-[30%] bg-white rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-4 border-b">Guruh qo'shish</h2>
            <form class="space-y-4">
                <!-- Guruh nomi -->
                <div>
                    <label for="group_name" class="block text-sm font-medium text-gray-700">Guruh nomi</label>
                    <input type="text" id="group_name" name="group_name" 
                           placeholder="Guruh nomini kiriting"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Filial -->
                <div>
                    <label for="branch" class="block text-sm font-medium text-gray-700">Filial</label>
                    <select id="branch" name="branch" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Filialni tanlang</option>
                    </select>
                </div>

                <!-- Guruh turi -->
                <div>
                    <label for="groupType" class="block text-sm font-medium text-gray-700">Guruh turi</label>
                    <select id="brangroupTypech" name="groupType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="active">Faol</option>
                        <option value="waiting">Kutilmoqda</option>
                    </select>
                </div>

                <!-- Kurs -->
                <div>
                    <label for="course" class="block text-sm font-medium text-gray-700">Kurs</label>
                    <select id="course" name="course" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Tanlang</option>
                        <option value="">Ingliz tili</option>
                        <option value="">Web dasturlash</option>
                    </select>
                </div>

                <!-- Daraja -->
                <div>
                    <label for="degree" class="block text-sm font-medium text-gray-700">Daraja</label>
                    <select id="degree" name="degree" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Tanlang</option>
                        <option value="">Boshlang'ich</option>
                        <option value="">O'rta</option>
                    </select>
                </div>

                <!-- O'qituvchi -->
                <div>
                    <label for="teacher" class="block text-sm font-medium text-gray-700">O'qituvchi</label>
                    <select id="teacher" name="teacher" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">O'qituvchini tanlang</option>
                    </select>
                </div>

                <!-- O'qituvchi maosh turi -->
                <div>
                    <label for="teacherSalaryType" class="block text-sm font-medium text-gray-700">O'qituvchi maoshi turi</label>
                    <select id="teacherSalaryType" name="teacherSalaryType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">O'qituvchini tanlang</option>
                    </select>
                </div>

                <!-- Maosh miqdori -->
                <div class="relative">
                    <label for="salaryType" class="block text-sm font-medium text-gray-700">Maosh miqdori</label>
                    <div class="relative mt-1">
                        <input type="number" id="salaryType" name="salaryType" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-16">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">UZS</span>
                        </div>
                    </div>
                </div>

                <!-- Tarif davri -->
                <div>
                    <label for="tarifPeriod" class="block text-sm font-medium text-gray-700">Tarif davri</label>
                    <select id="tarifPeriod" name="teacherSatarifPeriodlaryType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">1oy</option>
                        <option value="">2oy</option>
                        <option value="">3oy</option>
                        <option value="">4oy</option>
                        <option value="">5oy</option>
                        <option value="">6oy</option>
                        <option value="">7oy</option>
                        <option value="">8oy</option>
                    </select>
                </div>

                <!-- Kunlar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kunlar</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="monday" name="days[]" value="monday"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="monday" class="ml-2 text-sm text-gray-700">Dushanba</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="tuesday" name="days[]" value="tuesday"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="tuesday" class="ml-2 text-sm text-gray-700">Seshanba</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="wednesday" name="days[]" value="wednesday"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="wednesday" class="ml-2 text-sm text-gray-700">Chorshanba</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="thursday" name="days[]" value="thursday"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="thursday" class="ml-2 text-sm text-gray-700">Payshanba</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="friday" name="days[]" value="friday"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="friday" class="ml-2 text-sm text-gray-700">Juma</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="saturday" name="days[]" value="saturday"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="saturday" class="ml-2 text-sm text-gray-700">Shanba</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="sunday" name="days[]" value="sunday"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="sunday" class="ml-2 text-sm text-gray-700">Yakshanba</label>
                        </div>
                    </div>
                </div>
                
                <!-- Kurs narxi -->
                <div class="relative">
                    <label for="totalPrice" class="block text-sm font-medium text-gray-700">Kurs narxi</label>
                    <div class="relative mt-1">
                        <input type="number" id="totalPrice" name="totalPrice" 
                            placeholder="Masalan, 1200000"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-16">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">UZS</span>
                        </div>
                    </div>
                </div>

                <!-- Darslar soni -->
                <div>
                    <label for="lessonsCount" class="block text-sm font-medium text-gray-700">Darslar soni</label>
                    <input type="number" id="lessonsCount" name="lessonsCount" 
                        placeholder="Masalan, 12"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Bitta dars narxi (readonly) -->
                <div class="relative">
                    <label for="coursePrice" class="block text-sm font-medium text-gray-700">Bitta dars narxi</label>
                    <div class="relative mt-1">
                        <input type="number" id="coursePrice" name="coursePrice" 
                            readonly
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-16">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">UZS</span>
                        </div>
                    </div>
                </div>


                <!-- Saqlash tugmasi -->
                <div class="flex justify-end pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Saqlash
                    </button>
                </div>
            </form>
        </div>

        <!-- O'ng panel (70%) -->
        <div class="w-full lg:w-[70%] bg-white rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-4 border-b">Guruhlar ro'yxati</h2>

            <!-- Status filterlari -->
            <div class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-1 mb-4" x-data="{ selected: 'active' }">
                <button type="button"
                        @click="selected = 'active'"
                        :class="{
                            'bg-green-600 shadow-sm text-white': selected === 'active',
                            'hover:bg-gray-100 text-gray-600': selected !== 'active'
                        }"
                        class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Faol (23)
                </button>
            
                <button type="button"
                        @click="selected = 'waiting'"
                        :class="{
                            'bg-gray-500 shadow-sm text-white': selected === 'waiting',
                            'hover:bg-gray-100 text-gray-600': selected !== 'waiting'
                        }"
                        class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Kutilmoqda (12)
                </button>
            
                <button type="button"
                        @click="selected = 'deleted'"
                        :class="{
                            'bg-red-600 shadow-sm text-white': selected === 'deleted',
                            'hover:bg-gray-100 text-gray-600': selected !== 'deleted'
                        }"
                        class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    O'chirilgan (8)
                </button>
            </div>
            <!-- Table wrapper -->
            <div x-data="sortableTable">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="uppercase tracking-wider border-b-2 dark:border-neutral-600 bg-gray-100 text-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-4">
                                <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('group')">
                                    <span>Guruh</span>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500" 
                                        :class="{ 'rotate-180': sortField === 'group' && sortDirection === 'desc' }"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4">
                                <div class="flex items-center gap-x-1 cursor-pointer group" @click="sort('course')">
                                    <span>Kurs</span>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-500"
                                        :class="{ 'rotate-180': sortField === 'course' && sortDirection === 'desc' }"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </th>
                            <!-- Boshqa ustunlar... -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table rows with data-field attributes -->
                        <tr class="border-b dark:border-neutral-600">
                            <td data-field="group" class="px-6 py-4">Guruh A</td>
                            <td data-field="course" class="px-6 py-4">Frontend</td>
                            <!-- Boshqa maydonlar... -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament::page>