<x-filament::page>
    <div class="flex flex-col h-full">
        <!-- Tepa qism -->
        <div class="flex-1 bg-white p-4 rounded-md shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-4 bg-gray-100 py-4 px-4">O'quvchi Ma'lumotlari</h2>
            <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Qabul qilinganlik raqami -->
                <div>
                    <label for="admission-number" class="block text-sm font-medium text-gray-700">Metirka/Pasport raqami</label>
                    <input
                        type="text"
                        id="admission-number"
                        name="admission-number"
                        placeholder="YY00000"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                </div>
                <!-- Filial -->
                <div>
                    <label for="branch" class="block text-sm font-medium text-gray-700">Filial</label>
                    <select
                        id="branch"
                        name="branch"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                        <option value="">Filialni tanlang</option>
                        <option value="1">Filial 1</option>
                        <option value="2">Filial 2</option>
                    </select>
                </div>
                <!-- Guruhlarni tanlang -->
                <div>
                    <label for="group" class="block text-sm font-medium text-gray-700">Guruhlar</label>
                    <select
                        id="group"
                        name="group"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                        <option value="">Guruhni tanlang</option>
                        <option value="A">Guruh A</option>
                        <option value="B">Guruh B</option>
                    </select>
                </div>
                <!-- Ism -->
                <div>
                    <label for="first-name" class="block text-sm font-medium text-gray-700">Ism</label>
                    <input
                        type="text"
                        id="first-name"
                        name="first-name"
                        placeholder="Ism kiriting"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                </div>
                <!-- Familiya -->
                <div>
                    <label for="last-name" class="block text-sm font-medium text-gray-700">Familiya</label>
                    <input
                        type="text"
                        id="last-name"
                        name="last-name"
                        placeholder="Familiya kiriting"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                </div>
                <!-- Jinsi -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Jinsi</label>
                    <select
                        id="gender"
                        name="gender"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                        <option value="">Jinsni tanlang</option>
                        <option value="male">Erkak</option>
                        <option value="female">Ayol</option>
                    </select>
                </div>
                <!-- Tug'ilgan yili -->
                <div>
                    <label for="birth-year" class="block text-sm font-medium text-gray-700">Tug'ilgan yili</label>
                    <input
                        type="date"
                        id="birth-year"
                        name="birth-year"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                </div>
                <!-- Telefon raqami -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Telefon raqami</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        placeholder="Telefon kiriting"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                </div>
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Email kiriting"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                </div>
                <!-- Filial -->
                <div>
                    <label for="studentStatus" class="block text-sm font-medium text-gray-700">O'quvchi statusi</label>
                    <select
                        id="studentStatus"
                        name="studentStatus"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                        <option value="">Aloqa</option>
                        <option value="1">Sinov darsi</option>
                        <option value="2">Sinov darsidan o'tgan</option>
                        <option value="3">Mijoz</option>
                        <option value="4">Onlayn dars</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Pastki qism -->
        <div class="flex-1 mt-4 bg-white p-4 rounded-md shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-4 bg-gray-100 py-4 px-4">Qo'shimcha</h2>
            <div class="flex gap-4">
                <!-- 1-qism: Til tanlash -->
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-700 mb-2">O'qish tili</p>
                    <div class="flex flex-col space-y-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" class="form-checkbox text-blue-600" name="language[]" value="uzbek">
                            <span>O‘zbek</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" class="form-checkbox text-blue-600" name="language[]" value="russian">
                            <span>Rus</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" class="form-checkbox text-blue-600" name="language[]" value="english">
                            <span>Ingliz</span>
                        </label>
                    </div>
                </div>

                <!-- 2-qism: Kurslar va O'quvchining bilim darajasi -->
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-700 mb-2">Kurslar</p>
                    <select
                        name="course"
                        id="source"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mb-2"
                    >
                        <option value="">Kursni tanlang</option>
                        <option value="social-media">SMM</option>
                        <option value="webDesign">Web design</option>
                        <option value="webDevelop">Web dasturlash</option>
                        <option value="english">Ingliz tili</option>
                    </select>
                    <p class="text-sm font-medium text-gray-700 mb-2">O'quvchining bilim darajasi</p>
                    <select
                        name="knowledge-level"
                        id="knowledge-level"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        onchange="toggleCustomInput(this)"
                    >
                        <option value="">Darajani tanlang</option>
                        <option value="beginner">Boshlang'ich</option>
                        <option value="intermediate">O'rta</option>
                        <option value="advanced">Yuqori</option>
                        <option value="custom">Boshqa</option>
                    </select>
                    <!-- Qo'shimcha input -->
                    <div id="custom-knowledge-level" class="mt-2 hidden">
                        <label for="custom-level" class="block text-sm font-medium text-gray-700">O'z darajangizni kiriting</label>
                        <input
                            type="text"
                            id="custom-level"
                            name="custom-knowledge-level"
                            placeholder="Darajani kiriting"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                    </div>
                </div>

                <!-- 3-qism: Hafta kunlari -->
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-700 mb-2">Kunlar</p>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="days[]" value="monday" class="form-checkbox text-blue-600">
                            <span>Dushanba</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="days[]" value="tuesday" class="form-checkbox text-blue-600">
                            <span>Seshanba</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="days[]" value="wednesday" class="form-checkbox text-blue-600">
                            <span>Chorshanba</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="days[]" value="thursday" class="form-checkbox text-blue-600">
                            <span>Payshanba</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="days[]" value="friday" class="form-checkbox text-blue-600">
                            <span>Juma</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="days[]" value="saturday" class="form-checkbox text-blue-600">
                            <span>Shanba</span>
                        </label>
                    </div>
                </div>

                <!-- 4-qism: O'quvchi o'quv markaz haqida qayerdan bildi -->
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-700 mb-2">O'quvchi o'quv markaz haqida qayerdan bildi?</p>
                    <select
                        name="source"
                        id="source"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                        <option value="">Manbani tanlang</option>
                        <option value="social-media">Ijtimoiy tarmoqlar</option>
                        <option value="friends">Do'stlar</option>
                        <option value="ads">Reklama</option>
                        <option value="other">Boshqa</option>
                    </select>
                    <!-- Qo'shimcha ma'lumot uchun textarea -->
                    <div class="mt-2">
                        <label for="additional-details" class="block text-sm font-medium text-gray-700">Izoh</label>
                        <textarea
                            id="additional-details"
                            name="additional-details"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        ></textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pastki qism tugadi -->

        <!-- Sinov darsi buyicha ma'lumotlar -->

        <div class="flex-1 bg-white p-4 rounded-md shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-4 bg-gray-100 py-4 px-4">Sinov darsi buyicha ma'lumotlar</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>
                <label for="teacherName" class="block text-sm font-medium text-gray-700">O'qituvchini tanlang</label>
            <select
                        name="teacher"
                        id="teacherName"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                        <option value="">Alisher Kadirov</option>
                        <option value="1">Jasur Karimov</option>
                        <option value="2">Nozim Egamov</option>
                        <option value="3">Shahlo Zokirova</option>
                        <option value="4">Malika Nosirova</option>
                    </select>
                </div>

            <div>
                <label for="callTrial" class="block text-sm font-medium text-gray-700">Sinov darsiga chaqirildi</label>
                    <input
                        type="date"
                        id="callTrial"
                        name="callTrial"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
            </div>
            <div>
                <label for="cameTrial" class="block text-sm font-medium text-gray-700">Sinov darsiga keldi</label>
                    <input
                        type="date"
                        id="cameTrial"
                        name="cameTrial"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
            </div>
            </div>
        </div>

        <!-- Sinov darsi buyicha ma'lumotlar tugadi -->

        <!-- Ota onalar malumoti -->

        <div class="flex-1 bg-white p-4 rounded-md shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-4 bg-gray-100 py-4 px-4">Ota-onalar malumoti</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Ota yoki Onasini Ism -->
                <div>
                    <label for="parentsName" class="block text-sm font-medium text-gray-700">Ota yoki Onasini ismi</label>
                    <input
                        type="text"
                        id="parentsName"
                        name="parentsName"
                        placeholder="Ism kiriting"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                </div>
                
                <!-- Ota yoki Onasini Telefon raqami -->
                <div>
                    <label for="parentsPhone" class="block text-sm font-medium text-gray-700">Ota yoki Onasini telefoni</label>
                    <input
                        type="number"
                        id="parentsPhone"
                        name="parentsPhone"
                        placeholder="Telefon kiriting"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                </div>
                <!-- Ota yoki Onasini Email -->
                <div>
                    <label for="parentsEmail" class="block text-sm font-medium text-gray-700">Ota yoki Onasini Email</label>
                    <input
                        type="email"
                        id="parentsEmail"
                        name="parentsEmail"
                        placeholder="Email kiriting"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                </div>
            </div>
        </div>
        <!-- Ota-onalar malumoti tugadi -->

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
                class="inline-flex items-center px-4 py-2 border border-primary-600 text-sm font-medium rounded-md shadow-sm text-primary-600 bg-white hover:bg-primary-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
                Qo'shish & Yana Qo'shish
            </button>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function toggleCustomInput(selectElement) {
            const customInput = document.getElementById('custom-knowledge-level');
            if (selectElement.value === 'custom') {
                customInput.classList.remove('hidden');
            } else {
                customInput.classList.add('hidden');
            }
        }
    </script>
</x-filament::page>