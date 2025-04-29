<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Students\StudentProfilePage;
use App\Models\Branch;
use App\Models\Group;
use App\Models\Student;
use App\Models\RemovalReason; // Added
use App\Models\StudentStatus;
use Filament\Pages\Page;
use Filament\Notifications\Notification; // Added
use Illuminate\Validation\ValidationException; // Added
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder; // Added
use Illuminate\Support\Facades\DB;

class StudentsPage extends Page
{
    use WithPagination;

    protected static ?string $navigationGroup = 'O\'quvchilar';
    protected static ?string $navigationLabel = 'O\'quvchilar';
    protected static ?string $title = 'O\'quvchilar';
    protected static ?string $slug = 'students';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.students.students-page';

    // Search and filter properties
    public $search = '';
    public $selectedBranch = '';
    public $selectedGroup = '';
    public $isFilterActive = false;
    public $isSearchActive = false;

    // Data for selects
    public $branches;
    public $groups;

    // Sorting properties
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // --- Student Removal Properties ---
    public bool $showRemoveStudentModal = false;
    public ?int $studentToRemoveId = null;
    public ?string $studentToRemoveName = null;
    public ?int $removalReasonId = null;
    public array $removalReasonsOptions = [];
    // --- End Student Removal Properties ---

    public function mount()
    {
        $this->loadSelectData();
        $this->loadRemovalReasonsOptions(); // Load removal reasons
    }

    private function loadSelectData()
    {
        $this->branches = Branch::where('status', 'active')->get();
        $this->groups = Group::where('status', 'active')->get();
    }

    // Load removal reason options
    protected function loadRemovalReasonsOptions(): void
    {
        $this->removalReasonsOptions = RemovalReason::where('is_active', true)
                                        ->orderBy('name')
                                        ->pluck('name', 'id')
                                        ->all();
    }

    public function searchByFilters()
    {
        $this->isFilterActive = true;
        $this->isSearchActive = false;
        $this->search = '';
        $this->resetPage();
    }

    public function searchByKeyword()
    {
        $this->isSearchActive = true;
        $this->isFilterActive = false;
        $this->selectedBranch = '';
        $this->selectedGroup = '';
        $this->resetPage();
    }

    public function sort($column)
    {
        if ($this->sortField === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage(); // Reset page when sorting changes
    }

    public function getStudentsProperty()
    {
        return $this->getFilteredStudentsQuery()->paginate(10);
    }

    private function getFilteredStudentsQuery(): Builder
    {
        $query = Student::query()->with([
            'branch:id,name', // Optimize eager loading
            'groups:id,name',
            'status:id,name',
            'courses:id,name',
            'studyLanguagesStudents', // Load related data if needed in blade
            'knowledgeLevel:id,name',
            'studyDayStudents' // Load related data if needed in blade
        ]);

        $this->applyFilters($query);
        $this->applySearch($query);
        $this->applySort($query);

        return $query;
    }

    private function applySort(Builder $query)
    {
        // Simplified sorting logic, add JOINs only when necessary
        switch ($this->sortField) {
            case 'name':
                $query->orderBy('first_name', $this->sortDirection)
                      ->orderBy('last_name', $this->sortDirection);
                break;
            case 'branch':
                $query->orderBy(Branch::select('name')->whereColumn('branches.id', 'students.branch_id'), $this->sortDirection);
                break;
            case 'status':
                 // Assuming StudentStatus model exists
                 $query->orderBy(StudentStatus::select('name')->whereColumn('student_statuses.id', 'students.status_id'), $this->sortDirection);
                break;
            case 'group':
                 $query->orderBy(
                     Group::select('name')
                         ->join('student_groups', 'groups.id', '=', 'student_groups.group_id')
                         ->whereColumn('student_groups.student_id', 'students.id')
                         ->orderBy('name', 'asc') // Order within subquery if multiple groups
                         ->limit(1),
                     $this->sortDirection
                 );
                 break;
            case 'phone':
            case 'created_at': // Add other direct columns here
                $query->orderBy($this->sortField, $this->sortDirection);
                break;
            default:
                $query->orderBy('created_at', 'desc'); // Default sort
                break;
        }
    }


    private function applyFilters(Builder $query)
    {
        if ($this->selectedBranch) { // No need for isFilterActive check if using wire:model
            $query->where('branch_id', $this->selectedBranch);
        }
        if ($this->selectedGroup) {
            $query->whereHas('groups', function ($groupQuery) {
                $groupQuery->where('groups.id', $this->selectedGroup);
            });
        }
    }

    private function applySearch(Builder $query)
    {
        if ($this->search) { // No need for isSearchActive check
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('first_name', 'like', $searchTerm)
                    ->orWhere('last_name', 'like', $searchTerm)
                    ->orWhere('phone', 'like', $searchTerm)
                    ->orWhere('passport_number', 'like', $searchTerm)
                    ->orWhere('id', $this->search); // Allow searching by ID
            });
        }
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'selectedBranch',
            'selectedGroup',
            // 'isFilterActive', // Not needed with wire:model approach
            // 'isSearchActive' // Not needed with wire:model approach
        ]);
        $this->resetPage();
    }

    // These updated methods are automatically called by Livewire with wire:model.live or .debounce
    public function updatedSearch() { $this->resetPage(); }
    public function updatedSelectedBranch() { $this->resetPage(); }
    public function updatedSelectedGroup() { $this->resetPage(); }

    public function showStudentProfile($studentId): void
    {
        // No need to findOrFail here, route model binding handles it
        $this->redirect(StudentProfilePage::getUrl(['record' => $studentId]));
    }

    // --- Student Removal Logic ---
    public function openRemoveStudentModal(int $studentId, string $studentName): void
    {
        $this->studentToRemoveId = $studentId;
        $this->studentToRemoveName = $studentName;
        $this->removalReasonId = null;
        $this->resetErrorBag();
        $this->showRemoveStudentModal = true;
    }

    public function removeStudent() // Return type removed for flexibility
    {
        if (!$this->studentToRemoveId) {
             Notification::make()
                ->title('Xatolik')
                ->body('Safdan chiqariladigan student aniqlanmadi.')
                ->danger()
                ->send();
             return null;
        }

        try {
            $validatedData = $this->validateOnly('removalReasonId', [
                'removalReasonId' => ['required', 'integer', 'exists:removal_reasons,id'],
            ], [
                'removalReasonId.required' => 'Iltimos, safdan chiqarish sababini tanlang.',
                'removalReasonId.exists' => 'Tanlangan sabab mavjud emas.',
            ]);
        } catch (ValidationException $e) {
            return null; // Keep modal open
        }

        try {
            $student = Student::find($this->studentToRemoveId);
            if (!$student) {
                 Notification::make()->title('Xatolik')->body('Student topilmadi.')->danger()->send();
                 $this->showRemoveStudentModal = false; // Close modal if student not found
                 return null;
            }

            $student->removal_reason_id = $validatedData['removalReasonId'];
            $student->save();
            $student->delete(); // Soft delete

            $removedName = $this->studentToRemoveName; // Store name before resetting
            $this->showRemoveStudentModal = false;
            $this->studentToRemoveId = null;
            $this->studentToRemoveName = null;


            Notification::make()
                ->title('Muvaffaqiyatli')
                ->body($removedName . ' muvaffaqiyatli safdan chiqarildi.')
                ->success()
                ->send();

            // No redirect needed, Livewire will refresh the list

        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik')
                ->body('Studentni safdan chiqarishda xatolik yuz berdi: ' . $e->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }
    // --- End Student Removal Logic ---
}
