<?php

namespace App\Filament\Pages\HR;

use App\Models\Department;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Exception;

class DepartmentPage extends Page
{
    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Bo\'lim';
    protected static ?string $title = 'Bo\'lim';
    protected static ?string $slug = 'hr/department';
    protected static ?int $navigationSort = 22;

    protected static string $view = 'filament.pages.h-r.department';

    use InteractsWithForms;
    use WithPagination;

    // Forma uchun
    public ?string $name = null;

    // Modal uchun
    public bool $showModal = false;
    public ?string $modalType = null; // 'edit' yoki 'delete'
    public ?Department $editingDepartment = null;
    public ?string $editName = null;
    public ?int $departmentToDeleteId = null;
    public ?string $departmentToDeleteName = null;

    // Qidiruv va Saralash
    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    // Pagination
    public int $perPage = 10;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Bo\'lim nomi')
                ->required()
                ->maxLength(255),
        ];
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    // Bo'limlar ro'yxatini olish
    public function getDepartmentsProperty(): LengthAwarePaginator
    {
        return Department::query()
            ->when($this->search, function (Builder $query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    // Yangi bo'lim yaratish
    public function createDepartment(): void
    {
        $validatedData = $this->form->getState();

        try {
            Department::create($validatedData);
            Notification::make()->success()->title('Bo\'lim muvaffaqiyatli qo\'shildi')->send();
            $this->form->fill(); // Formani tozalash
            $this->resetPage(); // Paginationni reset qilish
        } catch (Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('Bo\'limni qo\'shishda xatolik: ' . $e->getMessage())->send();
        }
    }

    // Modalni ochish
    public function openModal(string $type, int $departmentId): void
    {
        $department = Department::find($departmentId);
        if ($department) {
            $this->modalType = $type;
            if ($type === 'edit') {
                $this->editingDepartment = $department;
                $this->editName = $department->name;
            } else {
                $this->departmentToDeleteId = $departmentId;
                $this->departmentToDeleteName = $department->name;
            }
            $this->showModal = true;
        }
    }

    // Bo'limni yangilash
    public function updateDepartment(): void
    {
        $this->validate([
            'editName' => 'required|string|max:255|unique:departments,name,' . $this->editingDepartment->id,
        ], [
            'editName.required' => 'Bo\'lim nomi majburiy.',
            'editName.unique' => 'Bu nomdagi bo\'lim allaqachon mavjud.',
        ]);

        try {
            $this->editingDepartment->update(['name' => $this->editName]);
            Notification::make()->success()->title('Bo\'lim muvaffaqiyatli yangilandi')->send();
            $this->closeModal();
            $this->resetPage();
        } catch (Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('Bo\'limni yangilashda xatolik: ' . $e->getMessage())->send();
        }
    }

    // Bo'limni o'chirish
    public function deleteDepartment(): void
    {
        try {
            $department = Department::findOrFail($this->departmentToDeleteId);
            $department->delete();
            Notification::make()->success()->title('Bo\'lim muvaffaqiyatli o\'chirildi')->send();
        } catch (Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('Bo\'limni o\'chirishda xatolik: ' . $e->getMessage())->send();
        } finally {
            $this->closeModal();
            $this->resetPage();
        }
    }

    // Modalni yopish
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->modalType = null;
        $this->editingDepartment = null;
        $this->editName = null;
        $this->departmentToDeleteId = null;
        $this->departmentToDeleteName = null;
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

    // Live search va pagination uchun
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }
}