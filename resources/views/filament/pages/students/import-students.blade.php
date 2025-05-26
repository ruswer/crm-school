<x-filament::page>
    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
        <div class="flex items-center justify-between pb-4 mb-4 border-b dark:border-gray-700">
            <div class="flex items-center justify-between p-2 rounded-md shadow-sm bg-white dark:bg-gray-700">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">Tanlash</span>
                </div>
            </div>
            <div>
                <x-filament::button
                    tag="a"
                    href="{{ asset('samples/student_import_sample.xlsx') }}"
                    color="success"
                    icon="heroicon-o-arrow-down-tray"
                    target="_blank"
                >
                    Namuna faylini yuklab olish
                </x-filament::button>
            </div>
        </div>

        {{-- Import form and instructions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="space-y-6">
                <div>
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Filialni tanlang
                    </label>
                    <select
                        wire:model="selectedBranch"
                        id="branch_id"
                        name="branch_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                    >
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedBranch') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="excel_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Import qilinadigan fayl (Excel)
                    </label>
                    <label for="file-upload" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md cursor-pointer hover:border-gray-400 dark:hover:border-gray-500">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 1 0 01-4-4v Forse4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <span class="relative bg-white dark:bg-gray-700 rounded-md font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 dark:focus-within:ring-offset-gray-800 focus-within:ring-primary-500">
                                    <span>Fayl yuklash</span>
                                </span>
                                <p class="pl-1">yoki bu yerga tortib olib keling</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                XLSX, XLS 10MB gacha
                            </p>
                        </div>
                        <input id="file-upload" name="file-upload" type="file" class="sr-only" wire:model="excelFile">
                    </label>
                    @error('excelFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <x-filament::button
                        wire:click="importStudents"
                        type="button"
                        icon="heroicon-o-check-circle"
                    >
                        Import qilish
                    </x-filament::button>
                </div>
            </div>

            {{-- Right side: Instructions --}}
            <div class="p-4 sm:p-6 border border-gray-200 dark:border-gray-700 rounded-md bg-success-100 dark:bg-gray-800/50">
                <h4 class="text-lg font-semibold">Import qilish bo'yicha qo'llanma:</h4>
                <p class="text-sm">Bu bo‘limda excel faylidagi o’quvchilar ro’yhatini CRM tizimiga import qilishingiz mumkin. Import qilish uchun quyidagi amallarni bajaring:</p>
                <ol class="list-decimal pl-5 space-y-1 text-sm">
                    <li><strong>"Namuna faylini yuklash"</strong> tugmachasini bosib, namuna faylni o’zingizga yuklab oling.</li>
                    <li>O’quvchilarni filial bo’yicha jadvalga joylashtiring. Birinchi qator ustunlarning nomlanishi, shuning uchun uni o’zgartirmang.</li>
                    <li>Tug’ilgan kunini <strong>DD-MM-YYYY</strong> formatda kiriting. Masalan: <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">2002-05-25</code>.</li>
                    <li>O’quvchi jinsini ko’rsatishda <strong>male</strong>, <strong>female</strong> so’zlaridan foydalaning. (male – erkak, female – ayol).</li>
                    <li>Excelda jadval tayyor bo’lgandan so’ng, Filialni tanlang, Faylni yuklang va “Import qilish” tugmachasini bosing.</li>
                </ol>
            </div>
        </div>
    </div>
</x-filament::page>