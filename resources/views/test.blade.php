
<html>
    <head>
          <title>Test Page</title>
          <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
          <script src="https://cdn.tailwindcss.com"></script>
     </head>
     <body class="bg-gray-100">
          <div class="container mx-auto mt-10">
                <!-- Admin panel link added -->
                <div class="mb-4 flex justify-center">
                    <a href="{{ route('filament.admin.auth.login') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg">
                        CRM Admin panelga o'tish
                    </a>
                </div>

                @yield('content')
          </div>
     </body>
   </html>