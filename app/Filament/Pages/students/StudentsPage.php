<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Students\StudentProfilePage;
use App\Models\Branch;
use App\Models\Group;
use App\Models\Student;
use Filament\Pages\Page;
use Livewire\WithPagination;

class StudentsPage extends Page
{
    use WithPagination;

    // Navigation sozlamalari
    protected static ?string $navigationGroup = 'O\'quvchilar';
    protected static ?string $navigationLabel = 'O\'quvchilar';
    protected static ?string $title = 'O\'quvchilar';
    protected static ?string $slug = 'students';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.students.students-page';

    // Qidiruv va filtrlash uchun o'zgaruvchilar
    public $search = '';
    public $selectedBranch = '';
    public $selectedGroup = '';
    public $isFilterActive = false;
    public $isSearchActive = false;

    // Select uchun ma'lumotlar
    public $branches;
    public $groups;

    public function mount()
    {
        $this->loadSelectData();
    }
    private function loadSelectData()
    {
        $this->branches = Branch::where('status', 'active')->get();
        $this->groups = Group::where('status', 'active')->get();
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
    // Tartiblash uchun o'zgaruvchilar
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public function sort($column)
    {
        if ($this->sortField === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $column;
            $this->sortDirection = 'asc';
        }
    }
    public function getStudentsProperty()
    {
        return $this->getFilteredStudentsQuery()->paginate(10);
    }
    private function getFilteredStudentsQuery()
    {
        $query = Student::query()->with([
            'branch',
            'groups',
            'status',
            'courses',
            'studyLanguages',
            'knowledgeLevel',
            'studyDays'
        ]);

        $this->applyFilters($query);
        $this->applySearch($query);
        $this->applySort($query); // Tartiblash qo'shildi

        return $query;
    }
    private function applySort($query)
    {
        switch ($this->sortField) {
            case 'name':
                $query->orderBy('first_name', $this->sortDirection)
                    ->orderBy('last_name', $this->sortDirection);
                break;
            case 'branch':
                $query->join('branches', 'students.branch_id', '=', 'branches.id')
                    ->orderBy('branches.name', $this->sortDirection)
                    ->select('students.*');
                break;
            case 'status':
                $query->join('student_statuses', 'students.status_id', '=', 'student_statuses.id')
                    ->orderBy('student_statuses.name', $this->sortDirection)
                    ->select('students.*');
                break;
            case 'course':
                // Kurs bo'yicha tartiblash
                $query->leftJoin('student_courses', 'students.id', '=', 'student_courses.student_id')
                    ->leftJoin('courses', 'student_courses.course_id', '=', 'courses.id')
                    ->select('students.*')
                    ->groupBy('students.id', 'courses.name')
                    ->orderBy(
                        \DB::raw('MIN(courses.name)'),
                        $this->sortDirection
                    );
                break;
            case 'group':
            $query->leftJoin('student_groups', 'students.id', '=', 'student_groups.student_id')
                ->leftJoin('groups', 'student_groups.group_id', '=', 'groups.id')
                ->select('students.*')
                ->groupBy('students.id')
                ->orderBy(
                    \DB::raw('MIN(groups.name)'),
                    $this->sortDirection
                );
            break;
            case 'phone':
                $query->orderBy('phone', $this->sortDirection);
                break;
            case 'language':
                // Til bo'yicha tartiblash
                $query->leftJoin('study_language_students', 'students.id', '=', 'study_language_students.student_id')
                    ->select('students.*')
                    ->groupBy('students.id')
                    ->orderBy(
                        \DB::raw('MIN(study_language_students.language)'),
                        $this->sortDirection
                    );
                break;
            case 'level':
                $query->leftJoin('knowledge_levels', 'students.knowledge_level_id', '=', 'knowledge_levels.id')
                    ->orderBy('knowledge_levels.name', $this->sortDirection)
                    ->select('students.*');
                break;
            case 'study_days':
                // Dars kunlari bo'yicha tartiblash
                $query->leftJoin('study_day_students', 'students.id', '=', 'study_day_students.student_id')
                    ->select('students.*')
                    ->groupBy('students.id')
                    ->orderBy(
                        \DB::raw('MIN(study_day_students.day)'),
                        $this->sortDirection
                    );
                break;
            default:
                $query->orderBy($this->sortField, $this->sortDirection);
                break;
        }
    }

    private function applyFilters($query)
    {
        if ($this->isFilterActive) {
            if ($this->selectedBranch) {
                $query->where('branch_id', $this->selectedBranch);
            }
            if ($this->selectedGroup) {
                $query->whereHas('groups', function ($groupQuery) {
                    $groupQuery->where('groups.id', $this->selectedGroup);
                });
            }
        }
    }
    private function applySearch($query)
    {
        if ($this->isSearchActive && $this->search) {
            $query->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('passport_number', 'like', '%' . $this->search . '%');
            });
        }
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'selectedBranch',
            'selectedGroup',
            'isFilterActive',
            'isSearchActive'
        ]);
        $this->resetPage();
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedBranch()
    {
        $this->resetPage();
    }

    public function updatedSelectedGroup()
    {
        $this->resetPage();
    }
    public function showStudentProfile($studentId): void
    {
        $student = Student::findOrFail($studentId);
        $this->redirect(StudentProfilePage::getUrl(['record' => $student]));
    }
}