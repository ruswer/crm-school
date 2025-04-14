   <html>
    <head>
          <title>Test Page</title>
          <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
          <script src="https://cdn.tailwindcss.com"></script>
     </head>
     <body class="bg-gray-100">
          <div class="container mx-auto mt-10">
                @yield('content')
          </div>
     </body>
   </html>
   <div class="flex flex-col gap-4">
        <!-- Qidirish bo'limi -->
        <div class="flex justify-between items-center p-4">
            <!-- Chap tomon: Filial va guruh bo'yicha qidirish -->
            <div class="flex items-center gap-4">
                <div>
                    <select id="branch" name="branch" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Barcha filiallar</option>
                        <option value="1">Filial 1</option>
                        <option value="2">Filial 2</option>
                    </select>
                </div>
                <div>
                    <select id="group" name="group" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Barcha guruhlar</option>
                        <option value="A">Guruh A</option>
                        <option value="B">Guruh B</option>
                    </select>
                </div>
                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Qidirish
                </button>
            </div>

            <!-- O'ng tomon: Student nomi bo'yicha qidirish -->
            <div class="flex items-center gap-2">
                <label for="student-name" class="sr-only">Student nomi</label>
                <input
                    type="text"
                    id="student-name"
                    name="student-name"
                    placeholder="Student nomi..."
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                />
                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Qidirish
                </button>
            </div>
        </div>

        <div class="p-4 bg-blue-500 text-white">
            Salom, Tailwind Filamentda ishlayapti!
        </div>
    </div>
    
