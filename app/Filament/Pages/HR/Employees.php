<?php

namespace App\Filament\Pages\HR;

use App\Models\Staff;
use App\Models\Branch;
use App\Models\Role;
use App\Models\Department;
use App\Models\Position;
use Filament\Pages\Page;
// EmployeeProfilePage importi agar ishlatilsa kerak bo'ladi, hozircha kommentariyada
// use App\Filament\Pages\HR\EmployeeProfilePage;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator; // To'g'ri interfeysni import qilish
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\WithPagination; // Pagination uchun trait

class Employees extends Page
{
    use WithPagination; // Pagination trait'ini ishlatish

    public Staff $staff;

    //<editor-fold desc="Filament Page Configuration">
    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Xodimlar';
    protected static ?string $title = 'Xodimlar ro\'yxati';
    protected static ?string $slug = 'hr/employees';
    protected static ?int $navigationSort = 18;
    
    protected static string $view = 'filament.pages.h-r.employees';
    //</editor-fold>

    //<editor-fold desc="Component Properties">
    // Filterlash uchun xususiyatlar
    public string $search = '';
    public ?string $branchFilter = null;
    public ?string $roleFilter = null;
    public ?string $departmentFilter = null;
    public ?string $positionFilter = null;

    public string $sortField = 'first_name';
    public string $sortDirection = 'asc';

    public bool $showDeleteConfirmModal = false;
    public ?int $staffToDeleteId = null;
    public ?string $staffToDeleteName = null;

    public int $perPage = 10;
    public string $displayMode = 'grid';
    //</editor-fold>

    public function toggleView(string $mode): void
    {
        $this->displayMode = $mode;
    }

    //<editor-fold desc="Computed Properties for Filters">
    public function getBranchesProperty(): Collection
    {
        return Branch::query()
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    public function getRolesProperty(): Collection
    {
        return Role::query()
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    public function getDepartmentsProperty(): Collection
    {
        return Department::query()
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    public function getPositionsProperty(): Collection
    {
        if (!$this->branchFilter) {
            return collect();
        }

        $positionIdsInBranch = Staff::query()
            ->where('branch_id', $this->branchFilter)
            ->whereNotNull('position_id')
            ->distinct()
            ->pluck('position_id');

        if ($positionIdsInBranch->isEmpty()) {
            return collect();
        }

        return Position::query()
            ->whereIn('id', $positionIdsInBranch)
            ->orderBy('name')
            ->pluck('name', 'id');
    }
    //</editor-fold>

    public function getStaffListProperty(): LengthAwarePaginator
    {
        // Bu metod o'zgarishsiz qoladi, chunki u joriy filter qiymatlariga asoslanadi
        return Staff::query()
            ->with(['branch', 'role', 'department', 'position'])
            ->when($this->search, function (Builder $query, $search) {
                 $query->where(function (Builder $q) use ($search) {
                    $searchTerm = '%' . $search . '%';
                    $q->where('first_name', 'like', $searchTerm)
                      ->orWhere('last_name', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('phone', 'like', $searchTerm)
                      ->when(is_numeric($search), fn($numQ) => $numQ->orWhere('id', (int)$search))
                      ->orWhereHas('role', fn($roleQuery) => $roleQuery->where('name', 'like', $searchTerm))
                      ->orWhereHas('position', fn($posQuery) => $posQuery->where('name', 'like', $searchTerm))
                      ->orWhereHas('branch', fn($branchQuery) => $branchQuery->where('name', 'like', $searchTerm));
                });
            })
            ->when($this->branchFilter, fn (Builder $q) => $q->where('branch_id', $this->branchFilter))
            ->when($this->roleFilter, fn (Builder $q) => $q->where('role_id', $this->roleFilter))
            ->when($this->departmentFilter, fn (Builder $q) => $q->where('department_id', $this->departmentFilter))
            ->when($this->positionFilter, fn (Builder $q) => $q->where('position_id', $this->positionFilter)) // Bu shart endi search bosilganda ishlaydi
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage, ['*'], 'employeesPage');
    }

    //<editor-fold desc="Action Methods">

    /**
     * "Qidirish" tugmasi bosilganda filtrlar qo'llaniladi va sahifa reset qilinadi.
     */
    public function search(): void
    {
        // Filtrlar allaqachon $this xususiyatlarida saqlangan (wire:model.defer tufayli).
        // getStaffListProperty ularni avtomatik ravishda ishlatadi.
        // Faqat paginationni reset qilish kerak.
        $this->resetPage('employeesPage');
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage('employeesPage');
    }

    // ... (confirmDeleteStaff, cancelDeleteStaff, deleteStaff, toggleView metodlari o'zgarishsiz) ...
    public function confirmDeleteStaff(int $staffId, string $staffName): void
    {
        $this->staffToDeleteId = $staffId;
        $this->staffToDeleteName = $staffName;
        $this->showDeleteConfirmModal = true;
    }

    public function cancelDeleteStaff(): void
    {
        $this->reset(['showDeleteConfirmModal', 'staffToDeleteId', 'staffToDeleteName']);
    }

    public function deleteStaff(): void
    {
         if ($this->staffToDeleteId) {
            try {
                $staff = Staff::findOrFail($this->staffToDeleteId);
                $staffName = $this->staffToDeleteName;
                $staff->delete();

                Notification::make()
                    ->success()
                    ->title("'$staffName' muvaffaqiyatli o'chirildi")
                    ->send();

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                Notification::make()
                    ->warning()
                    ->title('Xodim topilmadi yoki allaqachon o\'chirilgan')
                    ->send();
            } catch (\Exception $e) {
                 Notification::make()
                    ->danger()
                    ->title('Xodimni o\'chirishda xatolik yuz berdi')
                    // ->body($e->getMessage()) // Development uchun
                    ->send();
            }
        }
        $this->cancelDeleteStaff();
    }

    public function showEmployeeProfile($staffId)
    {
        return redirect()->to(\App\Filament\Pages\HR\EmployeeProfilePage::getUrl(['staff' => $staffId]));
    }
    //</editor-fold>

    //<editor-fold desc="Livewire Lifecycle Hooks">
    // Sahifa soni o'zgarganda reset qilish qoladi
    public function updatedPerPage(): void { $this->resetPage('employeesPage'); }

    /**
     * Filial filtri o'zgarganda faqat lavozim filtrini reset qilish.
     * Sahifani reset QILMAYDI.
     */
    public function updatedBranchFilter(): void
    {
        $this->reset('positionFilter'); // Lavozim filtrini tozalash
        // $this->resetPage('employeesPage'); // BU QATOR OLIB TASHLANDI
    }

}
