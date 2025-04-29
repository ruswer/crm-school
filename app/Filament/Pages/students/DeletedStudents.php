<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Models\Group;
use App\Models\Student;
use App\Models\RemovalReason;
use App\Filament\Pages\Students\StudentProfilePage;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class DeletedStudents extends Page
{
    use WithPagination;

    // Navigatsiya sozlamalari
    protected static ?string $navigationGroup = 'O\'quvchilar';
    protected static ?string $navigationLabel = 'Safdan Chiqarilganlar';
    protected static ?string $title = 'Safdan Chiqarilgan O\'quvchilar';
    protected static ?int $navigationSort = 5;
    protected static ?string $slug = 'deleted-students';

    // Blade fayli
    protected static string $view = 'filament.pages.students.deleted-students';

    // --- Livewire Xususiyatlari ---
    public $search = '';
    public $selectedBranch = '';
    public $selectedGroup = '';

    public $sortField = 'deleted_at';
    public $sortDirection = 'desc';

    public $branches = [];
    public $groups = [];

    // --- Query String ---
    protected $queryString = [
        'search' => ['except' => ''],
        'selectedBranch' => ['except' => ''],
        'selectedGroup' => ['except' => ''],
        'sortField' => ['except' => 'deleted_at'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    // --- Qayta tiklash uchun propertylar ---
    public bool $showRestoreConfirmModal = false;
    public ?int $studentToRestoreId = null;
    public ?string $studentToRestoreName = null;
    // --- End Qayta tiklash uchun propertylar ---

    // --- Butunlay o'chirish uchun propertylar ---
    public bool $showForceDeleteConfirmModal = false;
    public ?int $studentToForceDeleteId = null;
    public ?string $studentToForceDeleteName = null;
    // --- End Butunlay o'chirish uchun propertylar ---

    // --- Hayot Sikli Metodlari ---
    public function mount(): void
    {
        $this->branches = Branch::where('status', 'active')->get(['id', 'name']);
        $this->groups = Group::where('status', 'active')->get(['id', 'name']);
    }

    // Filtr/Qidiruv o'zgarganda paginationni reset qilish
    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedSelectedBranch(): void { $this->resetPage(); }
    public function updatedSelectedGroup(): void { $this->resetPage(); }

    // --- Amallar ---

    public function sort($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'selectedBranch', 'selectedGroup');
        $this->resetPage();
    }

    /**
     * Filial va guruh bo'yicha qidiruv tugmasi bosilganda chaqiriladi.
     * wire:model.defer ishlatilgani uchun asosan paginationni reset qiladi.
     */
    public function searchByFilters()
    {
        $this->resetPage();
    }

    /**
     * Kalit so'z bo'yicha qidiruv tugmasi bosilganda chaqiriladi.
     * wire:model.defer ishlatilgani uchun asosan paginationni reset qiladi.
     */
    public function searchByKeyword()
    {
        $this->resetPage();
    }

    /**
     * O'quvchi profil sahifasiga yo'naltirish (agar kerak bo'lsa).
     * O'chirilgan student uchun bu funksiya kerak bo'lmasligi mumkin.
     * Qaytarish turi : void dan mixed ga o'zgartirildi.
     */
    public function showStudentProfile($studentId): mixed // <-- Qaytarish turi o'zgartirildi
    {
        // Agar o'chirilgan student profilini ko'rsatish kerak bo'lmasa:
        // return null;

        // Agar ko'rsatish kerak bo'lsa:
        return $this->redirect(StudentProfilePage::getUrl(['record' => $studentId]));
    }


    // --- Qayta Tiklash Logikasi ---
    public function confirmRestoreStudent(int $studentId, string $studentName): void
    {
        $this->studentToRestoreId = $studentId;
        $this->studentToRestoreName = $studentName;
        $this->showRestoreConfirmModal = true;
    }

    public function restoreStudent(): void
    {
        if (!$this->studentToRestoreId) {
            return;
        }

        try {
            $student = Student::onlyTrashed()->find($this->studentToRestoreId);

            if ($student) {
                $student->restore();
                $student->removal_reason_id = null;
                $student->save();

                Notification::make()
                    ->title('Muvaffaqiyatli')
                    ->body($this->studentToRestoreName . ' muvaffaqiyatli qayta tiklandi.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Xatolik')
                    ->body('Qayta tiklanadigan student topilmadi.')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik')
                ->body('Studentni qayta tiklashda xatolik yuz berdi: ' . $e->getMessage())
                ->danger()
                ->send();
        } finally {
            $this->showRestoreConfirmModal = false;
            $this->studentToRestoreId = null;
            $this->studentToRestoreName = null;
        }
    }
    // --- End Qayta Tiklash Logikasi ---


    // --- Butunlay O'chirish Logikasi ---
    public function confirmForceDelete(int $studentId, string $studentName): void
    {
        $this->studentToForceDeleteId = $studentId;
        $this->studentToForceDeleteName = $studentName;
        $this->showForceDeleteConfirmModal = true;
    }

    public function forceDeleteStudent(): void
    {
        if (!$this->studentToForceDeleteId) {
            return;
        }

        try {
            $student = Student::onlyTrashed()->find($this->studentToForceDeleteId);

            if ($student) {
                // Ehtiyot bo'ling: Bog'liq ma'lumotlarni o'chirish kerak bo'lishi mumkin
                // $student->groups()->detach();
                // $student->courses()->detach();
                // $student->parents()->delete(); // Agar cascade o'rnatilmagan bo'lsa
                // $student->authorization()->delete(); // Agar cascade o'rnatilmagan bo'lsa

                $student->forceDelete();

                Notification::make()
                    ->title('Muvaffaqiyatli')
                    ->body($this->studentToForceDeleteName . ' bazadan butunlay o\'chirildi.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Xatolik')
                    ->body('O\'chiriladigan student topilmadi.')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik')
                ->body('Studentni butunlay o\'chirishda xatolik yuz berdi: ' . $e->getMessage())
                ->danger()
                ->send();
        } finally {
            $this->showForceDeleteConfirmModal = false;
            $this->studentToForceDeleteId = null;
            $this->studentToForceDeleteName = null;
        }
    }
    // --- End Butunlay O'chirish Logikasi ---

    // --- Ma'lumotlarni Olish Uchun Computed Property ---
    public function getDeletedStudentsProperty()
    {
        $query = Student::query()
            ->onlyTrashed()
            ->with([
                'branch:id,name',
                'removalReason:id,name'
                ]);

        // Filial Filtrini Qo'llash
        $query->when($this->selectedBranch, function ($query, $branchId) {
            $query->where('branch_id', $branchId);
        });

        // Guruh Filtrini Qo'llash
        $query->when($this->selectedGroup, function ($query, $groupId) {
            $query->whereHas('groups', function ($groupQuery) use ($groupId) {
                $groupQuery->where('groups.id', $groupId);
            });
        });

        // Qidiruv Filtrini Qo'llash
        $query->when($this->search, function ($query, $searchTerm) {
            $searchTerm = '%' . $searchTerm . '%';
            $query->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('first_name', 'like', $searchTerm)
                         ->orWhere('last_name', 'like', $searchTerm)
                         ->orWhere('phone', 'like', $searchTerm);
            });
        });

        // Saralashni Qo'llash
        // Eslatma: Munosabatlar bo'yicha saralash (branch, reason) subquery ishlatadi.
        // Katta hajmdagi ma'lumotlarda JOIN samaraliroq bo'lishi mumkin.
        switch ($this->sortField) {
            case 'name':
                $query->orderBy('first_name', $this->sortDirection)
                      ->orderBy('last_name', $this->sortDirection);
                break;
            case 'branch':
                 $query->orderBy(Branch::select('name')->whereColumn('branches.id', 'students.branch_id'), $this->sortDirection);
                break;
            case 'reason':
                 $query->orderBy(RemovalReason::select('name')->whereColumn('removal_reasons.id', 'students.removal_reason_id'), $this->sortDirection);
                break;
            case 'birth_date':
            case 'gender':
            case 'phone':
            case 'deleted_at':
                $query->orderBy($this->sortField, $this->sortDirection);
                break;
            default:
                $query->orderBy('deleted_at', 'desc');
                break;
        }

        return $query->paginate(15);
    }
}
