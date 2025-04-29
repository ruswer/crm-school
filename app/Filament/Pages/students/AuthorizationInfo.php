<?php

namespace App\Filament\Pages;

use App\Models\Authorization;
use App\Models\Branch;
use App\Models\Group;
use App\Models\Student;
use App\Models\Parents;
use App\Filament\Pages\Students\StudentProfilePage;
// use App\Filament\Resources\StudentResource;
use Filament\Pages\Page;
use Livewire\WithPagination;

class AuthorizationInfo extends Page
{
    use WithPagination;

    // Navigatsiya sozlamalari
    protected static ?string $navigationGroup = 'O\'quvchilar';
    protected static ?string $navigationLabel = 'Avtorizatsiya ma\'lumotlari';
    protected static ?string $title = 'Avtorizatsiya ma\'lumotlari';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'authorization-info';

    // Blade fayli
    protected static string $view = 'filament.pages.students.authorization-info';

    // Livewire Xususiyatlari
    public $search = '';
    public $selectedBranch = '';
    public $selectedGroup = '';

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $branches = [];
    public $groups = [];

    // Query String (URL da holatni saqlash)
    protected $queryString = [
        'search' => ['except' => ''],
        'selectedBranch' => ['except' => ''],
        'selectedGroup' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    // Hayot Sikli Metodlari
    public function mount(): void
    {
        $this->branches = Branch::where('status', 'active')->get(['id', 'name']);
        $this->groups = Group::where('status', 'active')->get(['id', 'name']);
    }

    // Filtr/Qidiruv o'zgarganda paginationni reset qilish
    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingSelectedBranch(): void
    {
        $this->resetPage();
    }
    public function updatingSelectedGroup(): void
    {
        $this->resetPage();
    }
    public function filter(): void
    {
        $this->resetPage();
    }
    public function resetFilter()
    {
        $this->reset(['selectedBranch', 'selectedGroup', 'search']);
        $this->resetPage();
    }

    // Amallar

    /**
     * Jadvalni berilgan ustun bo'yicha saralash.
     */
    public function sort($field): void
    {
        // Eslatma: Munosabatlar (login, parentsLogin) bo'yicha saralash JOIN talab qiladi.
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Barcha filtrlar va qidiruvni tozalash.
     */
    public function resetFilters(): void
    {
        $this->reset('search', 'selectedBranch', 'selectedGroup');
        $this->resetPage();
    }

    /**
     * O'quvchi profil sahifasiga yo'naltirish.
     */
    public function showStudentProfile($studentId)
    {
         // Loyiha strukturasiga qarab to'g'ri klassni ishlating
         return redirect(StudentProfilePage::getUrl(['record' => $studentId]));
    }


    // Ma'lumotlarni Olish Uchun Computed Property

    /**
     * Filtrlar va qidiruv asosida avtorizatsiya ma'lumotlari bilan o'quvchilarni olish.
     * Bu xususiyat Blade faylida $this->students sifatida avtomatik mavjud bo'ladi.
     */
    public function getStudentsProperty()
    {
        $query = Student::query()
            ->with([
                'authorization:id,login,authenticatable_id,authenticatable_type',
                'branch:id,name',
                // 'groups:id,name', // Agar kerak bo'lsa
                'parents' => function ($query) {
                    $query->with('authorization:id,login,authenticatable_id,authenticatable_type');
                }
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
                         ->orWhereHas('authorization', function ($authQuery) use ($searchTerm) {
                             $authQuery->where('login', 'like', $searchTerm);
                         })
                         ->orWhereHas('parents.authorization', function ($parentAuthQuery) use ($searchTerm) {
                             $parentAuthQuery->where('login', 'like', $searchTerm);
                         });
                         // ->orWhere('phone', 'like', $searchTerm);
            });
        });

        // Saralashni Qo'llash
        if (in_array($this->sortField, ['first_name', 'last_name', 'created_at', 'phone'])) {
             $sortColumn = $this->sortField === 'name' ? 'first_name' : $this->sortField;
             $query->orderBy($sortColumn, $this->sortDirection);
             if ($sortColumn === 'first_name') {
                 $query->orderBy('last_name', $this->sortDirection);
             }
        } else {
             $query->orderBy('created_at', 'desc');
        }

        return $query->paginate(15);
    }
}
