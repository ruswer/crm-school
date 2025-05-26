<?php

namespace App\Filament\Pages\HR;

use App\Models\AttendanceRecord;
use App\Models\Branch;
use App\Models\Staff;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\WithPagination;

class AttendanceReport extends Page implements HasForms
{
    use InteractsWithForms;
    use WithPagination;

    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Xodimlar davomat hisoboti';
    protected static ?string $title = 'Xodimlar davomat hisoboti';
    protected static ?string $slug = 'hr/attendance-report';
    protected static ?int $navigationSort = 20;

    protected static string $view = 'filament.pages.h-r.attendance-report';

    // Filtrlar va Qidiruv
    public ?int $selectedBranchId = null;
    public ?string $selectedMonth = null;
    public ?string $selectedYear = null;
    public string $search = '';

    // Saralash
    public string $sortField = 'staff.first_name'; // Default staff name bo'yicha
    public string $sortDirection = 'asc';

    // Modal
    public bool $showAbsentDatesModal = false;
    public ?Staff $staffForModal = null;
    public Collection $absentDatesForModal;

    // Pagination
    public int $perPage = 15;

    public function mount(): void
    {
        // Joriy oy va yilni standart qilib o'rnatish
        $this->selectedMonth = Carbon::now()->format('m');
        $this->selectedYear = Carbon::now()->format('Y');
        $this->absentDatesForModal = collect(); // Modal uchun bo'sh kolleksiya
    }

    // Filiallar ro'yxati
    public function getBranchesProperty(): Collection
    {
        return Branch::orderBy('name')->pluck('name', 'id');
    }

    // Yillar ro'yxati (masalan, oxirgi 5 yil)
    public function getYearsProperty(): array
    {
        $currentYear = Carbon::now()->year;
        return array_combine(range($currentYear, $currentYear - 4), range($currentYear, $currentYear - 4));
    }

    // Oylar ro'yxati
    public function getMonthsProperty(): array
    {
        return [
            '01' => 'Yanvar', '02' => 'Fevral', '03' => 'Mart', '04' => 'Aprel',
            '05' => 'May', '06' => 'Iyun', '07' => 'Iyul', '08' => 'Avgust',
            '09' => 'Sentyabr', '10' => 'Oktyabr', '11' => 'Noyabr', '12' => 'Dekabr',
        ];
    }

    // Hisobot ma'lumotlarini olish
    public function getReportDataProperty(): LengthAwarePaginator
    {
        // Agar oy yoki yil tanlanmagan bo'lsa, bo'sh paginator qaytarish
        if (!$this->selectedMonth || !$this->selectedYear) {
            return Staff::whereRaw('1 = 0')->paginate($this->perPage); // Hech narsa topmaslik uchun
        }

        $startDate = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Bu yerga Staff modelidan ma'lumotlarni olish, filtrlash, qidirish, saralash va hisoblash logikasi qo'shiladi
        // Hozircha placeholder
        return Staff::query()
            ->where('branch_id', $this->selectedBranchId) // Misol uchun filial bo'yicha filtr
            ->when($this->search, function (Builder $query, $search) {
                $searchTerm = '%' . $search . '%';
                $query->where(function (Builder $q) use ($searchTerm) {
                    $q->where('first_name', 'like', $searchTerm)
                      ->orWhere('last_name', 'like', $searchTerm)
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm]);
                });
            })
            ->withCount([
                'attendanceRecords as present_count' => fn($q) => $q->whereBetween('date', [$startDate, $endDate])->where('status', 'present'),
                'attendanceRecords as absent_count' => fn($q) => $q->whereBetween('date', [$startDate, $endDate])->where('status', 'absent'),
                // Boshqa statuslar uchun ham qo'shish mumkin
            ])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    // Hisobotni generatsiya qilish (filtrlar qo'llanilganda)
    public function generateReport(): void
    {
        // Filtrlar `wire:model` orqali bog'langan, shuning uchun bu yerda faqat paginationni reset qilish kifoya
        $this->resetPage();
    }

    // Qidiruv maydoni yangilanganda
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // Sahifa soni o'zgarganda
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    // Saralash
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    // Qatnashmagan kunlarni ko'rish uchun modalni ochish
    public function viewAbsentDates(int $staffId): void
    {
        $this->staffForModal = Staff::find($staffId);
        if (!$this->staffForModal || !$this->selectedMonth || !$this->selectedYear) {
            // Xatolik yoki xabar berish mumkin
            return;
        }

        $startDate = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $this->absentDatesForModal = AttendanceRecord::where('staff_id', $staffId)
            ->where('status', 'absent') // Faqat 'absent' statusdagilarni olish
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->select('date', 'comment')->get(); // Sana va izohni olish

        $this->showAbsentDatesModal = true;
    }

    // Modalni yopish
    public function closeAbsentDatesModal(): void
    {
        $this->showAbsentDatesModal = false;
        $this->staffForModal = null;
        $this->absentDatesForModal = collect();
    }
}