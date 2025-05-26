<?php

namespace App\Filament\Pages\Education;

// Kerakli klasslarni import qilamiz
use App\Models\Branch;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\Staff;
use App\Models\Cabinet;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\WithPagination;

// HasForms va traitlarni qo'shamiz
class LessonSchedule extends Page implements HasForms
{
    use WithPagination, InteractsWithForms; // Traitlarni qo'shish

    protected static ?string $navigationGroup = 'Ta\'lim';
    protected static ?string $navigationLabel = 'Darslar jadvali';
    protected static ?string $title = 'Darslar jadvali';
    protected static ?string $slug = 'education/schedule';
    protected static ?int $navigationSort = 17;

    protected static string $view = 'filament.pages.education.schedule';

    // --- Filtrlar uchun xususiyatlar ---
    public ?int $branch_id = null;
    public ?int $group_id = null;
    public ?string $teacher_id = null;
    public ?int $selectedCabinetId = null;
    // public ?string $selected_week = null; // Keyinchalik hafta bo'yicha filtr uchun

    // --- Qidiruv natijalarini saqlash uchun ---
    // Bu yerda Paginator tipini ishlatamiz
    // public Paginator $scheduleEntries; // Buni computed property qilamiz

    protected $queryString = [
        'branch_id' => ['except' => null],
        'group_id' => ['except' => null],
        'teacher_id' => ['except' => null],
        // 'selected_week' => ['except' => null],
    ];

    public function mount(): void
    {
        // Agar kerak bo'lsa, boshlang'ich filtr qiymatlarini o'rnatish
        // Masalan, birinchi filialni tanlab qo'yish
        // $this->branch_id = Branch::first()?->id;
        // $this->loadSchedule(); // Boshlang'ich ma'lumotlarni yuklash
    }

    // --- Filtr Selectlari uchun ma'lumotlar ---

    public function getBranchOptionsProperty(): Collection
    {
        return Branch::pluck('name', 'id');
    }

    public function getGroupOptionsProperty(): Collection
    {
        return Group::query()
            ->when($this->branch_id, fn(Builder $query) => $query->where('branch_id', $this->branch_id))
            ->pluck('name', 'id');
    }

    public function getTeacherOptionsProperty(): Collection
    {
        return Staff::query()
            ->whereNotNull('first_name')
            ->whereNotNull('last_name')
            ->get()
            ->pluck('full_name', 'id');
    }

    public function getCabinets()
    {
        return Cabinet::query()
            ->whereHas('schedules')
            ->orderBy('name')
            ->get();
    }

    // --- Asosiy ma'lumotlarni olish (Computed Property) ---
    public function getScheduleListProperty(): Paginator
    {
        return Schedule::query()
            ->with(['branch', 'group', 'teacher', 'cabinet'])
            ->when($this->branch_id, fn (Builder $query, $branchId) => $query->where('branch_id', $branchId))
            ->when($this->group_id, fn (Builder $query, $groupId) => $query->where('group_id', $groupId))
            ->when($this->teacher_id, fn (Builder $query, $teacherId) => $query->where('teacher_id', $teacherId))
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->orderBy('id')
            ->paginate(15);
    }
 
    // --- Filtrlar o'zgarganda ---
   
    public function updatedBranchId(): void
    {
        $this->group_id = null;
        $this->resetPage();
    }

    public function updatedGroupId(): void
    {
        $this->resetPage();
    }

    public function updatedTeacherId($value): void
    {
        // Agar kelgan qiymat bo'sh qator bo'lsa, null ga o'zgartiramiz
        if ($value === '') {
            $this->teacher_id = null;
        }
        $this->resetPage();
    }

    public function searchSchedule(): void
    {
        // Filtrlar o'zgarganda paginatsiyani birinchi sahifaga qaytarish muhim
        $this->resetPage();

    }

    public function getWeekDays(): array
    {
        $days = [];
        $today = now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $date = $today->copy()->addDays($i);
            $days[] = [
                'dayNum' => $date->dayOfWeek,
                'short' => $date->format('D'),
                'date' => $date->format('n/j'),
            ];
        }

        return $days;
    }

    public function getTimeSlots(): array
    {
        $slots = [];
        $start = 8; // 08:00
        $end = 20;  // 20:00

        for ($hour = $start; $hour < $end; $hour++) {
            $slots[] = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
        }

        return $slots;
    }

    public function getScheduleForTimeAndDay($time, $dayNum)
    {
        return $this->scheduleList->first(function ($schedule) use ($time, $dayNum) {
            return $schedule->day_of_week == $dayNum && 
                   $schedule->start_time == $time;
        });
    }

    public function getScheduleCountForCabinet($cabinetId)
    {
        return Schedule::where('cabinet_id', $cabinetId)->count();
    }

    public function filterByRoom($cabinetId)
    {
        $this->selectedCabinetId = $cabinetId;
        $this->dispatch('refresh-calendar');
    }


    
}
