<?php

namespace App\Filament\Pages\Education;

use App\Models\Course; // Course modelini import qilish
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle; // Status uchun Toggle
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Illuminate\Validation\ValidationException;

class Courses extends Page implements HasForms
{
    use WithPagination, InteractsWithForms; // Traitlarni qo'shish

    protected static ?string $navigationGroup = 'Ta\'lim';
    protected static ?string $title = 'Kurslar';
    protected static ?string $slug = 'education/courses';
    protected static ?int $navigationSort = 14;
    protected static string $view = 'filament.pages.education.courses';

    // --- Form Data ---
    public array $createCourseData = [];

    // --- Table Data & Filters ---
    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    // --- Edit Modal Data ---
    public bool $showEditModal = false;
    public ?Course $editingCourse = null;
    public array $editCourseData = [];

    // --- Delete Modal Data ---
    public bool $showDeleteModal = false;
    public ?int $deletingCourseId = null;
    public ?string $deletingCourseName = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount(): void
    {
        $this->createForm->fill();
        // $this->editForm->fill(); // Tahrirlash uchun modal ochilganda to'ldiriladi
    }

    // --- Form Schema ---
    protected function getFormSchema(): array // Yaratish va tahrirlash uchun umumiy sxema
    {
        return [
            TextInput::make('name')
                ->label('Kurs nomi')
                ->required()
                ->maxLength(255),
            Textarea::make('description')
                ->label('Tavsif')
                ->rows(3)
                ->maxLength(65535),
            Toggle::make('status') // Toggle yoki Select ishlatish mumkin
                ->label('Holati')
                ->default(true) // Boshlang'ich holati (faol)
                ->onColor('success')
                ->offColor('danger')
                ->helperText('Kurs faol yoki faol emasligini belgilang.'),
                // Yoki Select:
                // Select::make('status')
                // ->label('Holati')
                // ->options([
                //     'active' => 'Faol',
                //     'inactive' => 'Faol emas',
                // ])
                // ->default('active')
                // ->required(),
        ];
    }

    // --- Form Registration ---
    protected function getForms(): array
    {
        return [
            'createForm' => $this->makeForm()
                ->schema($this->getFormSchema()) // Umumiy sxemani ishlatamiz
                ->statePath('createCourseData'),
            'editForm' => $this->makeForm()
                ->schema($this->getFormSchema()) // Umumiy sxemani ishlatamiz
                ->statePath('editCourseData'),
        ];
    }

    // --- Create Course Method ---
    public function createCourse(): void
    {
        try {
            $validatedData = $this->createForm->getState();
            // Toggle qiymatini stringga o'girish
            $validatedData['status'] = $validatedData['status'] ? 'active' : 'inactive'; // <-- Mana shu qator
            Course::create($validatedData);

            Notification::make()
                ->title('Kurs muvaffaqiyatli qo\'shildi')
                ->success()
                ->send();

            $this->createForm->fill();
            $this->resetPage();

        } catch (ValidationException $e) {
            // Validation handled by Filament
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    // --- Fetch Courses for Table ---
    public function getCoursesProperty(): Paginator
    {
        return Course::query()
            ->when($this->search, function (Builder $query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    // --- Table Actions ---
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        $allowedFields = ['name', 'description', 'status']; // Status bo'yicha saralash ham mumkin
        if (!in_array($field, $allowedFields)) {
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

    // --- Edit/Delete Methods ---
    public function editCourse(int $courseId): void
    {
        $this->editingCourse = Course::find($courseId);

        if ($this->editingCourse) {
            // Ma'lumotlar bazasidan olingan stringni Toggle uchun booleanga o'girish
            $editData = $this->editingCourse->toArray();
            $editData['status'] = $this->editingCourse->status === 'active'; // <-- Mana shu qator
            $this->editForm->fill($editData);
            $this->showEditModal = true;
        } else {
            Notification::make()->title('Xatolik')->body('Kurs topilmadi.')->danger()->send();
        }
    }

    public function updateCourse(): void
    {
        if (!$this->editingCourse) return;

        try {
            $validatedData = $this->editForm->getState();
            // Toggle qiymatini stringga o'girish
            $validatedData['status'] = $validatedData['status'] ? 'active' : 'inactive'; // <-- Mana shu qator
            $this->editingCourse->update($validatedData);

            Notification::make()
                ->title('Kurs muvaffaqiyatli yangilandi')
                ->success()
                ->send();

            $this->showEditModal = false;

        } catch (ValidationException $e) {
             // Validation handled by Filament
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body('Kursni yangilashda xatolik: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function openDeleteModal(int $courseId): void
    {
        $course = Course::find($courseId);
        if ($course) {
            $this->deletingCourseId = $course->id;
            $this->deletingCourseName = $course->name;
            $this->showDeleteModal = true;
        } else {
             Notification::make()->title('Xatolik')->body('Kurs topilmadi.')->danger()->send();
        }
    }

    public function confirmDeleteCourse(): void
    {
        if (!$this->deletingCourseId) return;

        try {
            $course = Course::findOrFail($this->deletingCourseId);
            // Bog'liq yozuvlarni tekshirish (masalan, guruhlar)
            // if ($course->groups()->exists()) { ... }
            $course->delete();
            Notification::make()
                ->title('Kurs muvaffaqiyatli o\'chirildi')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body('Kursni o\'chirishda xatolik: ' . $e->getMessage())
                ->danger()
                ->send();
        } finally {
            $this->showDeleteModal = false;
            $this->deletingCourseId = null;
            $this->deletingCourseName = null;
            $this->resetPage();
        }
    }
}
