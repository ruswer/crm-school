<?php

namespace App\Filament\Pages\Education;

use App\Models\Branch;
use App\Models\Course;
use App\Models\Group;
use App\Models\KnowledgeLevel;
use App\Models\Staff;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\WithPagination;

class Groups extends Page implements HasForms
{
    use WithPagination, InteractsWithForms;

    protected static ?string $navigationGroup = 'Ta\'lim';
    protected static ?string $title = 'Guruhlar';
    protected static ?string $slug = 'education/groups';
    protected static ?int $navigationSort = 12;
    protected static string $view = 'filament.pages.education.groups';

    // --- Form Data ---
    public array $createGroupData = [];

    // --- Table Data & Filters ---
    // public string $search = ''; // <-- Olib tashlandi
    public string $selectedStatus = 'active';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    // --- Edit Modal (Placeholder) ---
    public bool $showEditModal = false;
    public ?Group $editingGroup = null;
    public array $editGroupData = [];

    protected $queryString = [
        // 'search' => ['except' => ''], // <-- Olib tashlandi
        'selectedStatus' => ['except' => 'active'],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount(): void
    {
        $this->createGroupForm->fill();
        // $this->editGroupForm->fill();
    }

    // --- Computed Properties for Select Options ---

    protected function getBranchesProperty(): Collection
    {
        return Branch::pluck('name', 'id');
    }

    protected function getCoursesProperty(): Collection
    {
        return Course::pluck('name', 'id');
    }

    protected function getKnowledgeLevelsProperty(): Collection
    {
        return KnowledgeLevel::pluck('name', 'id');
    }

    protected function getTeachersProperty(): Collection
    {
        return Staff::get()->pluck('full_name', 'id');
    }

    protected function getSalaryTypesProperty(): array
    {
        return [
            'percentage' => 'Foiz (%)',
            'fixed' => 'Qat\'iy belgilangan',
            'per_student' => 'Har bir o\'quvchi uchun',
        ];
    }

    protected function getTariffPeriodsProperty(): array
    {
        $periods = [];
        for ($i = 1; $i <= 12; $i++) {
            $periods[$i] = $i . ' oy';
        }
        return $periods;
    }

    // --- Form Schema ---
    protected function getCreateGroupFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Guruh nomi')
                ->required()
                ->maxLength(255),
            Select::make('branch_id')
                ->label('Filial')
                ->options(fn () => $this->getBranchesProperty())
                ->required(),
            Select::make('status')
                ->label('Guruh holati')
                ->options([
                    'active' => 'Faol',
                    'waiting' => 'Kutilmoqda',
                ])
                ->default('waiting')
                ->required(),
            Select::make('course_id')
                ->label('Kurs')
                ->options(fn () => $this->getCoursesProperty())
                ->required(),
            Select::make('knowledge_level_id')
                ->label('Daraja')
                ->options(fn () => $this->getKnowledgeLevelsProperty())
                ->required(),
            Select::make('teacher_id')
                ->label('O\'qituvchi')
                ->options(fn () => $this->getTeachersProperty())
                ->required(),
            Select::make('teacher_salary_type')
                ->label('O\'qituvchi maoshi turi')
                ->options(fn () => $this->getSalaryTypesProperty())
                ->required(),
            TextInput::make('teacher_salary_amount')
                ->label('Maosh miqdori')
                ->numeric()
                ->required()
                ->minValue(0)
                ->suffix('UZS'),
            Select::make('price_period_months')
                ->label('Tarif davri')
                ->options(fn () => $this->getTariffPeriodsProperty())
                ->required(),
            CheckboxList::make('lesson_days')
                ->label('Kunlar')
                ->options([
                    'monday' => 'Dushanba',
                    'wednesday' => 'Chorshanba',
                    'friday' => 'Juma',
                    'tuesday' => 'Seshanba',
                    'thursday' => 'Payshanba',
                    'saturday' => 'Shanba',
                ])
                ->columns(2)
                ->required(),
            TextInput::make('total_price')
                ->label('Kurs narxi')
                ->numeric()
                ->required()
                ->minValue(0)
                ->suffix('UZS')
                ->reactive()
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    $lessonsCount = $get('lessons_count');
                    if ($state && $lessonsCount > 0) {
                        $set('price_per_lesson', round($state / $lessonsCount));
                    } else {
                        $set('price_per_lesson', 0);
                    }
                }),
            TextInput::make('lessons_count')
                ->label('Darslar soni')
                ->numeric()
                ->required()
                ->minValue(1)
                ->reactive()
                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                    $totalPrice = $get('total_price');
                    if ($totalPrice && $state > 0) {
                        $set('price_per_lesson', round($totalPrice / $state));
                    } else {
                        $set('price_per_lesson', 0);
                    }
                }),
            TextInput::make('price_per_lesson')
                ->label('Bitta dars narxi')
                ->numeric()
                ->suffix('UZS')
                ->disabled()
                ->dehydrated(false),
            TimePicker::make('lesson_start_time')
                ->label('Dars boshlanish vaqti')
                ->seconds(false),
            TimePicker::make('lesson_end_time')
                ->label('Dars tugash vaqti'),
            // Select::make('cabinet_id')
            //     ->label('Kabinet')
            //     ->options(fn() => \App\Models\Cabinet::pluck('name', 'id'))
            //     ->searchable(),
        ];
    }

    // --- Form Registration ---
    protected function getForms(): array
    {
        return [
            'createGroupForm' => $this->makeForm()
                ->schema($this->getCreateGroupFormSchema())
                ->statePath('createGroupData'),
            // 'editGroupForm' => $this->makeForm()
            //     ->schema($this->getEditGroupFormSchema())
            //     ->statePath('editGroupData'),
        ];
    }

    // --- Create Group Method ---
    public function createGroup(): void
    {
        try {
            $validatedData = $this->createGroupForm->getState();
            unset($validatedData['price_per_lesson']);
            Group::create($validatedData);
            Notification::make()
                ->title('Guruh muvaffaqiyatli qo\'shildi')
                ->success()
                ->send();
            $this->createGroupForm->fill();
            $this->resetPage();
        } catch (\Illuminate\Validation\ValidationException $e) {
            Notification::make()
                ->title('Validatsiya xatosi')
                ->body('Iltimos, barcha maydonlarni to\'g\'ri to\'ldiring.')
                ->danger()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    // --- Fetch Groups for Table ---
    public function getGroupsProperty(): Paginator
    {
        $query = Group::query()
            ->with(['branch', 'course', 'knowledgeLevel', 'teacher'])
            ->when($this->selectedStatus === 'deleted', function (Builder $query) {
                $query->onlyTrashed();
            })
            ->when($this->selectedStatus !== 'deleted', function (Builder $query) {
                $query->where('status', $this->selectedStatus);
            });

        // Sorting
        if (str_contains($this->sortField, '.')) {
            [$relation, $field] = explode('.', $this->sortField);
            // Join logic here if needed
            // $query->join(...)->orderBy($field, $this->sortDirection);
        } elseif (Schema::hasColumn('groups', $this->sortField)) {
             $query->orderBy($this->sortField, $this->sortDirection);
        } else {
             $query->orderBy('name', 'asc'); // Default sort if field is invalid
        }

        return $query->paginate(10);
    }

    // --- Table Actions ---
    public function filterByStatus(string $status): void
    {
        $this->selectedStatus = $status;
        $this->resetPage();
    }

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

    // public function updatedSearch(): void // <-- Olib tashlandi
    // {
    //     $this->resetPage();
    // }

    public function resetFilters(): void
    {
        $this->reset(/*'search',*/ 'selectedStatus', 'sortField', 'sortDirection'); // <-- 'search' olib tashlandi
        $this->resetPage();
    }

    // --- Edit/Delete Placeholders ---
    public function editGroup(int $groupId): void
    {
        Notification::make()->title('Tahrirlash')->body('Bu funksiya hali tayyor emas.')->warning()->send();
    }

    public function updateGroup(): void
    {
        // Update logic here
    }

    public function deleteGroup(int $groupId): void
    {
        try {
            $group = Group::withTrashed()->find($groupId);
            if ($group) {
                if ($this->selectedStatus === 'deleted') {
                    $group->forceDelete();
                    Notification::make()->title('Guruh butunlay o\'chirildi')->success()->send();
                } else {
                    $group->delete();
                    Notification::make()->title('Guruh o\'chirildi (arxivlandi)')->success()->send();
                }
            }
            $this->resetPage();
        } catch (\Exception $e) {
            Notification::make()->title('Xatolik')->body('Guruhni o\'chirishda xatolik: ' . $e->getMessage())->danger()->send();
        }
    }

     public function restoreGroup(int $groupId): void
    {
        try {
            $group = Group::onlyTrashed()->find($groupId);
            if ($group) {
                $group->restore();
                Notification::make()->title('Guruh tiklandi')->success()->send();
            }
            $this->resetPage();
        } catch (\Exception $e) {
            Notification::make()->title('Xatolik')->body('Guruhni tiklashda xatolik: ' . $e->getMessage())->danger()->send();
        }
    }
}
