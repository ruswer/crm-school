<?php

namespace App\Filament\Pages\HR;

use App\Models\AttendanceRecord;
use App\Models\Branch;
use App\Models\Staff;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection as EloquentCollection; // Alias for Eloquent Collection
use Illuminate\Support\Collection; // Alias for Support Collection
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination; // Pagination uchun

class Attendance extends Page
{
    use WithPagination; // Pagination trait'ini ishlatish

    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Xodimlar davomati';
    protected static ?string $title = 'Xodimlar davomati';
    protected static ?string $slug = 'hr/attendance';
    protected static ?int $navigationSort = 19;

    protected static string $view = 'filament.pages.h-r.attendance';

    // Filtrlar uchun xususiyatlar
    public ?int $selectedBranchId = null;
    public ?string $selectedDate = null;

    // Xodimlar ro'yxati va davomat ma'lumotlari
    public ?EloquentCollection $staffList = null; // Eloquent Collection uchun type hint
    public array $attendanceData = []; // Davomat ma'lumotlarini saqlash uchun massiv
    // Tanlangan sana uchun davomat mavjudligini bildiruvchi flag
    public bool $attendanceExistsForDate = false;
    // Saralash uchun xususiyatlar
    public string $sortField = 'id'; // Standart saralash maydoni
    public string $sortDirection = 'asc'; // Standart saralash yo'nalishi

    public function updated($property)
{
    // Debug uchun log yozing
    logger($property, [$this->attendanceData]);
}
    // Sahifa yuklanganda
    public function mount(): void
    {
        // Bugungi sanani standart qilib o'rnatish
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->staffList = new EloquentCollection(); // Bo'sh kolleksiya bilan boshlash
    }

    // Filiallar ro'yxatini olish uchun computed property
    public function getBranchesProperty(): EloquentCollection
    {
        return Branch::orderBy('name')->get();
    }

    // Qidirish tugmasi bosilganda
    public function searchStaff(): void
    {
        $this->validate([
            'selectedBranchId' => 'required|integer|exists:branches,id',
            'selectedDate' => 'required|date_format:Y-m-d',
        ]);

        // Qidiruv boshlanishida flagni reset qilish
        $this->attendanceExistsForDate = false;

        $date = Carbon::parse($this->selectedDate);

        // Faqat aktiv (soft delete bo'lmagan) xodimlarni olish
        $this->staffList = Staff::query()
            ->where('branch_id', $this->selectedBranchId)
            // ->where('status', 'active') // Agar status bo'yicha ham filtr kerak bo'lsa
            // Saralashni qo'llash
            ->orderBy($this->sortField, $this->sortDirection)
            // Agar ism bo'yicha saralansa, qo'shimcha familiya bo'yicha saralash
            ->when($this->sortField === 'first_name', fn ($query) => $query->orderBy('last_name', $this->sortDirection))
            ->get(); // Pagination kerak bo'lsa ->paginate() ishlatiladi

        // Mavjud davomat yozuvlarini olish
        $existingRecords = AttendanceRecord::where('date', $date)
            ->whereIn('staff_id', $this->staffList->pluck('id'))
            ->get()
            ->keyBy('staff_id');

        // Agar mavjud yozuvlar topilsa, flagni true qilish
        if ($existingRecords->isNotEmpty()) {
            $this->attendanceExistsForDate = true;
        }
        // attendanceData massivini tayyorlash
        $this->attendanceData = [];
        foreach ($this->staffList as $staff) {
            $record = $existingRecords->get($staff->id);
            $this->attendanceData[$staff->id] = [
                'status' => $record?->status ?? 'not_working', // Agar yozuv bo'lmasa 'not_working'
                'comment' => $record?->comment ?? '',
            ];
        }

        // Agar pagination ishlatilsa, sahifani reset qilish
        // $this->resetPage();
    }

    // Saralash metodi
    public function sortBy(string $field): void
    {
        // Agar bir xil maydon bosilsa, yo'nalishni o'zgartirish
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Aks holda, yangi maydon bo'yicha 'asc' yo'nalishida saralash
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        // Ma'lumotlarni qayta qidirish (saralashni qo'llash uchun)
        $this->searchStaff();
    }

    // Davomatni saqlash
    public function saveAttendance(): void
    {
        $this->validate([
            'selectedBranchId' => 'required|integer|exists:branches,id',
            'selectedDate' => 'required|date_format:Y-m-d',
            'attendanceData' => 'required|array',
            'attendanceData.*.status' => 'required|in:present,absent,not_working',
            'attendanceData.*.comment' => 'nullable|string|max:255',
        ]);

        $date = Carbon::parse($this->selectedDate);

        try {
            DB::beginTransaction(); // Xatolik bo'lsa, o'zgarishlarni qaytarish uchun

            foreach ($this->attendanceData as $staffId => $data) {
                AttendanceRecord::updateOrCreate(
                    [
                        'staff_id' => $staffId,
                        'date' => $date,
                    ],
                    [
                        'status' => $data['status'],
                        'comment' => $data['comment'] ?? null, // Agar comment kelmasa null
                    ]
                );
            }

            DB::commit(); // O'zgarishlarni saqlash

            Notification::make()->success()->title('Davomat muvaffaqiyatli saqlandi!')->send();
            // Saqlagandan keyin ro'yxatni yangilash uchun qayta qidirish
            $this->searchStaff();
        } catch (\Exception $e) {
            DB::rollBack(); // Xatolik yuz berganda o'zgarishlarni bekor qilish
            Notification::make()->danger()->title('Xatolik yuz berdi!')->body($e->getMessage())->send(); // Development uchun xato xabari
        }
    }
}