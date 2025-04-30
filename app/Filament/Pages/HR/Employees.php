<?php

namespace App\Filament\Pages\HR;

use App\Models\Staff;
use App\Models\Branch;
use App\Models\Role;
use App\Models\Department;
use App\Models\Position; // Position modelini import qilish
use Filament\Pages\Page;
use App\Filament\Pages\HR\EmployeeProfilePage; // Profil sahifasini import qilish
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View; // View import qilish
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination; // Pagination uchun

class Employees extends Page
{
    use WithPagination; // Pagination traitini qo'shish

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Xodimlar';
    protected static ?string $title = 'Xodimlar ro\'yxati';
    protected static ?string $slug = 'hr/employees';
    protected static ?int $navigationSort = 18;
    protected static string $view = 'filament.pages.h-r.employees';

    // Filter properties
    public $search = '';
    public $branchFilter = null;
    public $roleFilter = null; // Bu Role uchun qoladi
    public $departmentFilter = null;
    public $positionFilter = null; // <<< Position uchun filtr qo'shildi

    // Sort properties
    public $sortField = 'first_name';
    public $sortDirection = 'asc';

    // --- O'chirish uchun Modal Xususiyatlari ---
    public bool $showDeleteConfirmModal = false;
    public ?int $staffToDeleteId = null;
    public ?string $staffToDeleteName = null;

    // Pagination
    public $perPage = 10;

    // Livewire lifecycle hook for pagination theme
    public function boot(): void
    {
        LengthAwarePaginator::useBootstrapFive(); // Yoki useTailwind()
    }

    public function goToStaffProfile($staffId)
    {
        // EmployeeProfilePage uchun marshrut nomini ishlatish
        // Yoki EmployeeProfilePage::getUrl() ni ishlatish mumkin, lekin marshrut nomi yaxshiroq
        return redirect()->to(EmployeeProfilePage::getUrl(['staff' => $staffId]));
        // Yoki marshrut nomi bilan:
        // return redirect()->route('filament.admin.pages.hr.employee-profile-page', ['staff' => $staffId]); // Marshrut nomini to'g'ri yozing
    }

    // Filter options
    public function getBranchesProperty(): Collection
    {
        return Branch::query()
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    // getRolesProperty endi Role modelidan ma'lumot oladi
    public function getRolesProperty(): Collection
    {
        return Role::query() // <<< Role modeliga o'zgartirildi
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    public function getDepartmentsProperty(): Collection
    {
        return Department::query()
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    // Position uchun yangi computed property
    public function getPositionsProperty(): Collection // <<< YANGI METOD QO'SHILDI
    {
        return Position::query()
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    // Staff list with filters
    // Computed property sifatida qayta yozish yaxshiroq
    public function getStaffListProperty(): LengthAwarePaginator
    {
        return Staff::query()
            ->with(['branch', 'role', 'department', 'position']) // Aloqalarni yuklash
            ->when($this->search, function (Builder $query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      // ID, telefon, rol, lavozim bo'yicha qidirishni ham qo'shish mumkin
                      ->orWhere('id', $search)
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhereHas('role', fn($roleQuery) => $roleQuery->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('position', fn($posQuery) => $posQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($this->branchFilter, fn ($q) => $q->where('branch_id', $this->branchFilter))
            ->when($this->roleFilter, fn ($q) => $q->where('role_id', $this->roleFilter)) // Role bo'yicha filtr
            ->when($this->departmentFilter, fn ($q) => $q->where('department_id', $this->departmentFilter)) // Department bo'yicha filtr (agar kerak bo'lsa)
            ->when($this->positionFilter, fn ($q) => $q->where('position_id', $this->positionFilter)) // Position bo'yicha filtr
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage, ['*'], 'employeesPage'); // Pagination uchun pageName
    }

    // "Qidirish" tugmasi bosilganda ishlaydi (defer modellari uchun)
    public function search(): void
    {
        $this->resetPage('employeesPage'); // Paginationni reset qilish
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage('employeesPage'); // Saralash o'zgarganda reset qilish
    }

    /**
     * O'chirishni tasdiqlash modalini ochadi
     */
    public function confirmDeleteStaff(int $staffId, string $staffName): void // <<< YANGI METOD
    {
        $this->staffToDeleteId = $staffId;
        $this->staffToDeleteName = $staffName;
        $this->showDeleteConfirmModal = true;
    }

    /**
     * Xodimni safdan chiqarish (Soft Delete)
     */
    public function deleteStaff(): void // <<< Argument olib tashlandi
    {
        if (!$this->staffToDeleteId) {
             // Agar ID topilmasa (xatolikni oldini olish)
             $this->showDeleteConfirmModal = false; // Modalni yopish
             return;
        }

        $staff = Staff::find($this->staffToDeleteId);
        if ($staff) {
            $staff->delete(); // Soft delete
            Notification::make()
                ->title('Muvaffaqiyatli')
                ->body($this->staffToDeleteName . ' muvaffaqiyatli safdan chiqarildi.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Xatolik')
                ->body('Xodim topilmadi.')
                ->danger()
                ->send();
        }

        // Modalni yopish va ID ni tozalash
        $this->showDeleteConfirmModal = false;
        $this->staffToDeleteId = null;
        $this->staffToDeleteName = null;
    }

    // wire:model.live ishlatilsa, bu metodlar kerak emas, chunki Livewire avtomatik yangilaydi
    // Agar wire:model.defer ishlatilsa, bu metodlar ham kerak emas, faqat search() metodi ishlaydi

    // Agar wire:model.live ishlatilsa, bu metodlar kerak bo'ladi:
    public function updatedSearch(): void { $this->resetPage('employeesPage'); }
    public function updatedPerPage(): void { $this->resetPage('employeesPage'); }
    public function updatedBranchFilter(): void { $this->resetPage('employeesPage'); }
    public function updatedRoleFilter(): void { $this->resetPage('employeesPage'); }
    public function updatedDepartmentFilter(): void { $this->resetPage('employeesPage'); }
    public function updatedPositionFilter(): void { $this->resetPage('employeesPage'); } // <<< Position uchun qo'shildi

    // Render method (Filament Page uchun odatda kerak emas, lekin custom logikada kerak bo'lishi mumkin)
    // Agar render() metodini ishlatsangiz, layoutni ko'rsatishni unutmang
    // public function render(): View
    // {
    //     return view(static::$view, [
    //         'staffList' => $this->staffList, // Computed propertyni uzatish
    //     ])->layout('filament-panels::components.layout.index');
    // }
}
