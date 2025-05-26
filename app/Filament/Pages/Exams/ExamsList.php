<?php

namespace App\Filament\Pages\Exams;

use App\Models\Branch;
use App\Models\Group;
use App\Models\Exam;
use App\Models\Student;
use App\Models\ExamResult;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class ExamsList extends Page implements HasForms
{
    use WithPagination, InteractsWithForms;

    protected static ?string $navigationGroup = 'Imtihonlar';
    protected static ?string $navigationLabel = 'Imtihonlar ro\'yxati';
    protected static ?string $title = 'Imtihonlar ro\'yxati';
    protected static ?int $navigationSort = 11;
    protected static string $view = 'filament.pages.exams.exams-list';

    // Filters
    public ?string $selectedBranch = '';
    public ?string $selectedGroup = '';

    // Table properties
    public string $search = '';
    public string $sortField = 'exam_date';
    public string $sortDirection = 'desc';

    // Create Exam Modal
    public bool $showCreateExamModal = false;
    public array $createExamData = [];

    // Marking Modal
    public bool $showMarkingModal = false;
    public ?Exam $examToMark = null;
    public Collection $studentsToMark;
    public array $marks = [];

    // Students in selected group for Create Exam Modal
    public Collection $studentsInSelectedGroup;

    public function mount(): void
    {
        $this->studentsToMark = collect();
        $this->studentsInSelectedGroup = collect();
    }

    protected function getCreateExamFormSchema(): array
    {
        return [
            Section::make('Yangi Imtihon Qo\'shish')
                ->schema([
                    Select::make('branch_id')
                        ->label('Filial')
                        ->options($this->branches)
                        ->required()
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set) {
                             $set('group_id', null);
                             $this->studentsInSelectedGroup = collect(); // Clear student list when branch changes
                        }),
                    Select::make('group_id')
                        ->label('Guruh')
                        ->options($this->groups)
                        ->required()
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state) {
                            if ($state) {
                                $group = Group::with('students')->find($state);
                                $this->studentsInSelectedGroup = $group ? $group->students : collect();
                            } else {
                                $this->studentsInSelectedGroup = collect();
                            }
                        }),
                    TextInput::make('name')
                        ->label('Imtihon nomi / Fani')
                        ->maxLength(255),
                    TextInput::make('type')
                        ->label('Turi')
                        ->placeholder('Masalan: Kirish, Oraliq, Yakuniy')
                        ->maxLength(255),
                    DatePicker::make('exam_date')
                        ->label('Imtihon sanasi')
                        ->required()
                        ->native(false),
                     TimePicker::make('exam_time')
                        ->label('Imtihon vaqti')
                        ->placeholder('Masalan: 14:00')
                        ->seconds(false),
                    TextInput::make('duration')
                        ->label('Davomiyligi (daqiqa)')
                        ->numeric()
                        ->minValue(0),
                    TextInput::make('location')
                        ->label('O\'tkazilish joyi')
                        ->placeholder('Masalan: 3-xona yoki Online')
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label('Izoh')
                        ->rows(3)
                        ->columnSpanFull(),
                    Select::make('status')
                        ->label('Holati')
                        ->options([
                            'scheduled' => 'Rejalashtirilgan',
                            'ongoing' => 'O\'tkazilmoqda',
                            'completed' => 'Yakunlangan',
                            'cancelled' => 'Bekor qilingan',
                        ])
                        ->default('scheduled')
                        ->required(),
                    TextInput::make('max_score')
                        ->label('Maksimal ball')
                        ->numeric()
                        ->minValue(0),
                    TextInput::make('passing_score')
                        ->label('O\'tish bali')
                        ->numeric()
                        ->minValue(0),
                ])->columns(2),
        ];
    }

    protected function getForms(): array
    {
        return [
            'createExamForm' => $this->makeForm()
                ->schema($this->getCreateExamFormSchema())
                ->statePath('createExamData'),
        ];
    }

    // --- Create Exam Modal Methods ---
    public function openCreateModal(): void
    {
        $this->createExamForm->fill([]);
        $this->showCreateExamModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateExamModal = false;
        $this->studentsInSelectedGroup = collect(); // Clear student list on close
    }

    public function createExam(): void
    {
        try {
            $validatedData = $this->createExamForm->getState();
            Exam::create($validatedData);
            Notification::make()
                ->title('Imtihon muvaffaqiyatli qo\'shildi')
                ->success()
                ->send();
            $this->closeCreateModal();
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
    // --- /Create Exam Modal Methods ---


    // --- Marking Modal Methods ---
    public function openMarkingModal(int $examId): void
    {
        $this->examToMark = Exam::with('group.students')->find($examId);

        if (!$this->examToMark) {
            Notification::make()
                ->title('Xatolik')
                ->body('Imtihon topilmadi.')
                ->danger()
                ->send();
            return;
        }

        if (!$this->examToMark->group) {
             Notification::make()
                ->title('Xatolik')
                ->body('Imtihonga guruh biriktirilmagan.')
                ->danger()
                ->send();
             $this->closeMarkingModal();
             return;
        }

        $this->studentsToMark = $this->examToMark->group->students ?? collect();
        $this->marks = [];

        // Load existing marks from database
        $existingResults = ExamResult::where('exam_id', $this->examToMark->id)
                    ->whereIn('student_id', $this->studentsToMark->pluck('id'))
                    ->pluck('mark', 'student_id')
                    ->toArray();

        // Populate marks array with existing results
        foreach ($this->studentsToMark as $student) {
            $this->marks[$student->id] = $existingResults[$student->id] ?? null;
        }

        $this->showMarkingModal = true;
    }

    public function closeMarkingModal(): void
    {
        $this->showMarkingModal = false;
        $this->examToMark = null;
        $this->studentsToMark = collect();
        $this->marks = [];
    }

    public function saveMarks(): void
    {
        if (!$this->examToMark) {
            Notification::make()->title('Xatolik')->body('Baholanadigan imtihon tanlanmagan.')->danger()->send();
            return;
        }

        // Validate marks
        $validatedData = $this->validate([
            'marks.*' => [
                'nullable',
                'numeric',
                'min:0',
                'max:6',
            ],
        ], [
            'marks.*.numeric' => 'Har bir baho raqam bo\'lishi kerak.',
            'marks.*.min' => 'Baho manfiy bo\'lishi mumkin emas.',
            'marks.*.max' => 'Baho 6 balldan oshmasligi kerak.',
        ]);

        try {
            $studentsInGroup = $this->studentsToMark->pluck('id')->toArray();

            foreach ($this->marks as $studentId => $mark) {
                if (in_array($studentId, $studentsInGroup)) {
                    $markValue = ($mark === '' || is_null($mark)) ? null : $mark;

                    ExamResult::updateOrCreate(
                        [
                            'exam_id' => $this->examToMark->id,
                            'student_id' => $studentId,
                        ],
                        [
                            'mark' => $markValue,
                        ]
                    );
                }
            }

            Notification::make()
                ->title('Baholar muvaffaqiyatli saqlandi')
                ->success()
                ->send();
            $this->closeMarkingModal();

        } catch (\Illuminate\Validation\ValidationException $e) {
             // Optionally notify user on validation error, though Filament might handle it
             Notification::make()
                ->title('Validatsiya xatosi')
                ->body('Iltimos, kiritilgan baholarni tekshiring (0 dan 6 gacha).')
                ->danger()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body('Baholarni saqlashda xatolik: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
    // --- /Marking Modal Methods ---


    // --- Filtering and Sorting Methods ---
    public function applyFilters(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

     public function updatedSelectedBranch(): void
    {
        $this->reset('selectedGroup');
        $this->resetPage();
    }

    public function updatedSelectedGroup(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        $sortableFields = ['name', 'exam_date', 'exam_time', 'status', 'branch_name', 'group_name'];

        if (!in_array($field, $sortableFields)) {
             $this->sortField = 'exam_date';
             $this->sortDirection = 'desc';
             $this->resetPage();
             return;
        }

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
        $this->reset('selectedBranch', 'selectedGroup', 'search');
        $this->sortField = 'exam_date';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }
    // --- /Filtering and Sorting Methods ---


    // --- Computed Properties ---
    public function getExamsProperty(): Paginator
    {
        $query = Exam::query()
            ->with(['branch', 'group'])
            ->when($this->selectedBranch, fn(Builder $query, $branchId) => $query->where('branch_id', $branchId))
            ->when($this->selectedGroup, fn(Builder $query, $groupId) => $query->where('group_id', $groupId))
            ->when($this->search, function (Builder $query, $search) {
                 $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                             ->orWhere('exam_date', 'like', '%' . $search . '%')
                             ->orWhere('status', 'like', '%' . $search . '%')
                             ->orWhere('description', 'like', '%' . $search . '%')
                             ->orWhereHas('branch', fn(Builder $branchQuery) => $branchQuery->where('name', 'like', '%' . $search . '%'))
                             ->orWhereHas('group', fn(Builder $groupQuery) => $groupQuery->where('name', 'like', '%' . $search . '%'));
                });
            });

        // Sorting logic
        if ($this->sortField === 'branch_name') {
            $query->join('branches', 'exams.branch_id', '=', 'branches.id')
                  ->orderBy('branches.name', $this->sortDirection)
                  ->select('exams.*');
        } elseif ($this->sortField === 'group_name') {
            $query->join('groups', 'exams.group_id', '=', 'groups.id')
                  ->orderBy('groups.name', $this->sortDirection)
                  ->select('exams.*');
        } elseif (in_array($this->sortField, ['name', 'exam_date', 'exam_time', 'status'])) {
             $query->orderBy($this->sortField, $this->sortDirection);
        } else {
             $query->orderBy('exam_date', 'desc'); // Default sort
        }

        return $query->paginate(10);
    }

    public function getBranchesProperty(): Collection
    {
        return Branch::query()
            ->whereNotNull('name')
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    public function getGroupsProperty(): Collection
    {
        return Group::query()
            ->when($this->selectedBranch, fn(Builder $query, $branchId) => $query->where('branch_id', $branchId))
            ->whereNotNull('name')
            ->orderBy('name')
            ->pluck('name', 'id');
    }
    // --- /Computed Properties ---


    // --- Edit and Delete Methods (Placeholder) ---
    public function editExam(int $examId): void
    {
        Notification::make()->title('Funksiya ishlab chiqilmoqda')->body("Tahrirlash funksiyasi hali tayyor emas.")->warning()->send();
    }

    public function deleteExam(int $examId): void
    {
        try {
            $exam = Exam::findOrFail($examId);
            $exam->delete();
            Notification::make()
                ->title('Imtihon o\'chirildi')
                ->success()
                ->send();
            $this->resetPage();
        } catch (\Exception $e) {
             Notification::make()
                ->title('Xatolik yuz berdi')
                ->body('Imtihonni o\'chirishda xatolik: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
    // --- /Edit and Delete Methods ---
}
