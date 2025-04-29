<x-filament::page>
    {{-- x-data olib tashlandi --}}
    <div class="flex flex-col h-full">
        <!-- Tanlash va Qidiruv Filtrlari -->
        <div class="bg-white rounded-md shadow-sm mb-4">
            <!-- Sarlavha -->
            <div class="flex items-center justify-between p-4 border-b">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-xl font-semibold text-gray-800">Tanlash</span>
                </div>
            </div>

            <!-- Qidiruv filtrlari -->
            <div class="p-4">
                <div class="flex flex-col gap-4">
                    <!-- Tepa qator: Sana filtrlari -->
                    <div class="flex flex-wrap lg:flex-nowrap gap-4">
                        <!-- Sana dan -->
                        <div class="w-full sm:w-1/2 lg:flex-1"> 
                            <label for="startDate" class="block text-sm font-medium text-gray-700">Sana dan</label>
                            <input
                                type="date"
                                id="startDate"
                                wire:model="startDate"
                                x-ref="startDateInput"
                                @click.prevent="$refs.startDateInput.showPicker()"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <!-- Sana gacha -->
                        <div class="w-full sm:w-1/2 lg:flex-1"> 
                            <label for="endDate" class="block text-sm font-medium text-gray-700">Sana gacha</label>
                            <input
                                type="date"
                                id="endDate"
                                wire:model="endDate"
                                x-ref="endDateInput"
                                @click.prevent="$refs.endDateInput.showPicker()"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                    </div>
                    <!-- Pastki qator: Qidirish tugmasi -->
                    <div class="flex justify-end">
                        <button
                            type="button"
                            {{-- @click olib tashlandi, wire:click qo'shildi --}}
                            wire:click="search"
                            wire:loading.attr="disabled" {{-- Qidiruv paytida tugmani bloklash --}}
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto"
                        >
                            {{-- Loading indicator (ixtiyoriy) --}}
                            <svg wire:loading wire:target="search" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg wire:loading.remove wire:target="search" class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Qidirish
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reklama bo'yicha hisobot -->
        <div class="w-full bg-white rounded-md shadow-sm p-4 mb-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-4 border-b">Reklama bo'yicha hisobot</h2>
            <div class="flex flex-col lg:flex-row w-full space-y-4 lg:space-y-0 lg:space-x-4">
                <!-- Chap ustun (Jadval) -->
                <div class="w-full lg:w-3/5"> {{-- Kenglikni moslashtiring --}}
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2 px-4 font-medium text-gray-700 bg-gray-100">Reklama turi</th>
                                    <th class="text-left py-2 px-4 font-medium text-gray-700 bg-gray-100">Soni</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse ($advertisementReportData as $item)
                                    <tr>
                                        <td class="py-2 px-4">{{ $item['name'] }}</td>
                                        <td class="py-2 px-4">{{ $item['count'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="py-4 px-4 text-center text-gray-500">Ma'lumot topilmadi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if (!empty($advertisementReportData))
                                <tfoot>
                                    <tr class="border-t">
                                        <td class="py-2 px-4 font-medium">Jami:</td>
                                        <td class="py-2 px-4 font-medium">{{ $totalAdvertisementCount }}</td> {{-- Jami sonni chiqaring --}}
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- O'ng ustun (Reklama Diagrammasi) -->
                <div class="w-full lg:w-2/5">
                    <div class="h-full border rounded-md"
                        x-data="advertisementChart(
                            {{ json_encode(array_column($advertisementReportData, 'name')) }},
                            {{ json_encode(array_column($advertisementReportData, 'count')) }}
                        )"
                        @advertisement-chart-update.window="updateChart($event.detail.labels, $event.detail.values)"
                    >
                        <div class="p-4 border-b bg-gray-50">
                            <h3 class="font-medium text-gray-700">Reklama bo'yicha taqsimot</h3>
                        </div>
                        {{-- Yangi flex container: Legend chapda, Diagramma o'ngda --}}
                        <div class="flex flex-col sm:flex-row items-start p-4 gap-4">
                            {{-- Chap qism: Legend (qiymatlar) --}}
                            <div class="w-full sm:w-1/3 space-y-2">
                                <h4 class="text-sm font-medium text-gray-600 mb-2">Manbalar:</h4>
                                {{-- Legendni bu yerga ko'chiramiz --}}
                                @forelse ($advertisementReportData as $item)
                                    <div class="flex items-center gap-2 text-sm">
                                        {{-- Rangni dinamik olish qiyinroq, shuning uchun oddiy nuqta qo'yamiz yoki ranglarni JS dan olish kerak bo'ladi --}}
                                        <span class="w-2 h-2 rounded-full bg-gray-400"></span> {{-- Oddiy belgi --}}
                                        <span>{{ $item['name'] }}: {{ $item['count'] }}</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Ma'lumot yo'q.</p>
                                @endforelse
                            </div>

                            {{-- O'ng qism: Diagramma --}}
                            <div class="w-full sm:w-2/3" style="height: 200px;"> {{-- Balandlikni moslashtiring --}}
                                <canvas id="advertisementPieChart" x-ref="advertisementCanvas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jins bo'yicha taqsimot -->
        <div class="w-full bg-white rounded-md shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-4 border-b">Jins bo'yicha taqsimot</h2>
             <div class="flex flex-col lg:flex-row w-full space-y-4 lg:space-y-0 lg:space-x-4">
                <!-- Chap ustun (Jadval) -->
                <div class="w-full lg:w-3/5"> {{-- Kenglikni moslashtiring --}}
                    <div class="rounded-md mb-4 lg:mb-0">
                        <h3 class="text-gray-800 mb-4 pb-2 font-medium">O'quvchilarning umumiy soni: {{ $totalStudents }}</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-2 px-4 font-medium text-gray-700 bg-gray-100">Jinsi</th>
                                        <th class="text-left py-2 px-4 font-medium text-gray-700 bg-gray-100">Soni</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse ($genderDistributionData as $gender => $count)
                                        <tr>
                                            <td class="py-2 px-4">{{ $gender }}</td>
                                            <td class="py-2 px-4">{{ $count }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="py-4 px-4 text-center text-gray-500">Ma'lumot topilmadi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- O'ng ustun (Jins Diagrammasi) -->
                <div class="w-full lg:w-2/5">
                    <div class="h-full border rounded-md"
                        x-data="genderChart(
                            {{ json_encode(array_keys($genderDistributionData)) }},
                            {{ json_encode(array_values($genderDistributionData)) }}
                        )"
                        @gender-chart-update.window="updateChart($event.detail.labels, $event.detail.values)"
                    >
                        <div class="p-4 border-b bg-gray-50">
                            <h3 class="font-medium text-gray-700">Jins bo'yicha taqsimot</h3>
                        </div>
                        {{-- Yangi flex container: Legend chapda, Diagramma o'ngda --}}
                        <div class="flex flex-col sm:flex-row items-start p-4 gap-4">
                            {{-- Chap qism: Legend (qiymatlar) --}}
                            <div class="w-full sm:w-1/3 space-y-2">
                                <h4 class="text-sm font-medium text-gray-600 mb-2">Jinslar:</h4>
                                {{-- Legendni bu yerga ko'chiramiz --}}
                                @forelse ($genderDistributionData as $gender => $count)
                                    <div class="flex items-center gap-2 text-sm">
                                        {{-- Ranglarni moslashtiring --}}
                                        <span @class([
                                            'w-2 h-2 rounded-full', // Hajmini kichiklashtirdim
                                            'bg-blue-500' => strtolower($gender) === 'erkak',
                                            'bg-pink-500' => strtolower($gender) === 'ayol',
                                            'bg-gray-400' => !in_array(strtolower($gender), ['erkak', 'ayol']),
                                        ])></span>
                                        <span>{{ $gender }}: {{ $count }}</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Ma'lumot yo'q.</p>
                                @endforelse
                            </div>

                            {{-- O'ng qism: Diagramma --}}
                            <div class="w-full sm:w-2/3" style="height: 200px;"> {{-- Balandlikni moslashtiring --}}
                                <canvas id="genderPieChart" x-ref="genderCanvas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js kutubxonasini qo'shish (agar layoutda bo'lmasa) --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Alpine.js uchun diagramma logikasi
            function genderChart(initialLabels, initialValues) {
                return {
                    chartInstance: null,
                    labels: initialLabels,
                    values: initialValues,
                    init() {
                        // Diagrammani yaratish
                        this.chartInstance = new Chart(this.$refs.genderCanvas, {
                            type: 'pie', // Diagramma turi
                            data: {
                                labels: this.labels,
                                datasets: [{
                                    label: 'Jinslar Soni',
                                    data: this.values,
                                    backgroundColor: [ // Ranglarni moslashtiring
                                        'rgba(59, 130, 246, 0.7)', // Erkak uchun (ko'k)
                                        'rgba(236, 72, 153, 0.7)', // Ayol uchun (pushti)
                                        'rgba(107, 114, 128, 0.7)'  // Boshqalar uchun (kulrang)
                                    ],
                                    borderColor: [
                                        'rgba(59, 130, 246, 1)',
                                        'rgba(236, 72, 153, 1)',
                                        'rgba(107, 114, 128, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false, // Avvalgi o'zgarish
                                plugins: {
                                    legend: {
                                        // position: 'top', // Bu qatorni o'chiring yoki kommentariyaga oling
                                        display: false // <-- Bu qatorni qo'shing
                                    },
                                    tooltip: { /* ... */ }
                                }
                            }
                        });

                        // Livewire komponenti yuklanganda diagrammani yangilash (agar kerak bo'lsa)
                        Livewire.hook('component.init', ({ component, cleanup }) => {
                           // Agar boshlang'ich ma'lumotlar bo'sh bo'lsa va keyin yuklansa
                           // this.updateChart(component.serverMemo.data.genderDistributionData);
                        });
                    },
                    // Diagrammani yangilash funksiyasi
                    updateChart(newLabels, newValues) {
                        if (this.chartInstance) {
                            this.chartInstance.data.labels = newLabels;
                            this.chartInstance.data.datasets[0].data = newValues;
                            this.chartInstance.update();
                        }
                    }
                }
            }

            // Reklama diagrammasi uchun Alpine.js funksiyasi
            function advertisementChart(initialLabels, initialValues) {
                return {
                    chartInstance: null,
                    labels: initialLabels,
                    values: initialValues,
                    init() {
                        this.createOrUpdateChart(); // Boshlang'ich diagrammani yaratish
                    },
                    // Diagrammani yaratish yoki yangilash funksiyasi
                    createOrUpdateChart() {
                        // Agar eski diagramma mavjud bo'lsa, uni yo'q qilamiz
                        if (this.chartInstance) {
                            this.chartInstance.destroy();
                        }
                        // Yangi diagrammani yaratamiz
                        this.chartInstance = new Chart(this.$refs.advertisementCanvas, {
                            type: 'pie', // Yoki 'bar' chart turi
                            data: {
                                labels: this.labels,
                                datasets: [{
                                    label: 'Reklama Manbalari Soni',
                                    data: this.values,
                                    backgroundColor: [ // Har bir manba uchun ranglar (ko'proq rang qo'shing)
                                        'rgba(255, 99, 132, 0.7)',
                                        'rgba(54, 162, 235, 0.7)',
                                        'rgba(255, 206, 86, 0.7)',
                                        'rgba(75, 192, 192, 0.7)',
                                        'rgba(153, 102, 255, 0.7)',
                                        'rgba(255, 159, 64, 0.7)',
                                        'rgba(199, 199, 199, 0.7)', // Kulrang
                                        // ... kerak bo'lsa ko'proq ranglar
                                    ],
                                    borderColor: [ // Chegara ranglari
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)',
                                        'rgba(199, 199, 199, 1)',
                                        // ...
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false, // Avvalgi o'zgarish
                                plugins: {
                                    legend: {
                                        // position: 'top', // Bu qatorni o'chiring yoki kommentariyaga oling
                                        display: false // <-- Bu qatorni qo'shing
                                    },
                                    tooltip: { /* ... */ }
                                }
                            }
                        });
                    },
                    // Livewire eventidan kelgan ma'lumotlar bilan yangilash
                    updateChart(newLabels, newValues) {
                        this.labels = newLabels;
                        this.values = newValues;
                        this.createOrUpdateChart(); // Diagrammani yangi ma'lumotlar bilan qayta yaratish
                    }
                }
            }
        </script>
    @endpush
</x-filament::page>
