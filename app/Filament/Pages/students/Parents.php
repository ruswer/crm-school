<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Models\Group;
use App\Models\Student;
use Filament\Pages\Page;
use Livewire\WithPagination;

class Parents extends Page
{
    use WithPagination;

    protected static ?string $navigationGroup = 'O\'quvchilar';
    protected static ?string $navigationLabel = 'Ota-ona';
    protected static ?string $title = 'Ota-ona';
    protected static ?string $slug = 'parents';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.students.parents';

    // Qidiruv va filter o'zgaruvchilari
    public $selectedBranch = '';
    public $selectedGroup = '';
    public $search = '';
    public $isSearching = false;

    // Search o'zgargan vaqtda sahifani qayta yuklash
    public function updatedSearch()
    {
        $this->isSearching = true;
        $this->resetPage();
    }

    // Filial va guruhlar uchun ma'lumotlar
    public $branches;
    public $groups;

    protected $queryString = ['search', 'selectedBranch', 'selectedGroup'];
    protected $listeners = ['refresh' => '$refresh'];
    public function mount()
    {
        $this->loadFilterData();
    }

    private function loadFilterData()
    {
        $this->branches = Branch::where('status', 'active')->get();
        $this->groups = Group::where('status', 'active')->get();
    }

    // Filter qilish
    public function filter()
    {
        $this->resetPage();
    }
     // Filterni tozalash
    public function resetFilter()
    {
        $this->reset(['selectedBranch', 'selectedGroup', 'search']);
        $this->resetPage();
    }
    // Sort uchun o'zgaruvchilar
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Student profilini ko'rish uchun
    public function showStudentProfile($studentId)
    {
        return redirect()->route('filament.admin.pages.students.student-profile', ['record' => $studentId]);
    }

    // Studentni tahrirlash uchun
    public function editStudent($studentId)
    {
        return redirect()->route('filament.admin.pages.students.edit', ['record' => $studentId]);
    }

    // Saralash uchun
    public function sort($column)
    {
        if ($this->sortField === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $column;
            $this->sortDirection = 'asc';
        }
    }
    
    // Ma'lumotlarni olish
    public function getStudentsProperty()
    {
        return Student::query()
            ->with(['parents', 'branch', 'groups'])
            // Search filter
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhereHas('parents', function($q) {
                          $q->where('full_name', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%');
                      });
                });
            })
            // Filial bo'yicha filtrlash
            ->when($this->selectedBranch, function ($query) {
                $query->where('branch_id', $this->selectedBranch);
            })
            // Guruh bo'yicha filtrlash
            ->when($this->selectedGroup, function ($query) {
                $query->whereHas('groups', function ($q) {
                    $q->where('groups.id', $this->selectedGroup);
                });
            })
            // Saralash
            ->when($this->sortField === 'name', function ($query) {
                $query->orderBy('first_name', $this->sortDirection);
            })
            ->when($this->sortField === 'phone', function ($query) {
                $query->orderBy('phone', $this->sortDirection);
            })
            ->when($this->sortField === 'parentsName', function ($query) {
                $query->select('students.*')
                    ->leftJoin('parents', 'students.id', '=', 'parents.student_id')
                    ->groupBy('students.id')
                    ->orderByRaw("MIN(parents.full_name) {$this->sortDirection}");
            })
            ->when($this->sortField === 'parentsPhone', function ($query) {
                $query->select('students.*')
                    ->leftJoin('parents', 'students.id', '=', 'parents.student_id')
                    ->groupBy('students.id')
                    ->orderByRaw("MIN(parents.phone) {$this->sortDirection}");
            })
            ->paginate(10);
    }
}