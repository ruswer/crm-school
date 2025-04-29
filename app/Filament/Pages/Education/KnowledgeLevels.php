<?php

namespace App\Filament\Pages\Education;

use App\Models\KnowledgeLevel; // Modelni import qilish
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle; // is_active uchun
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str; // Slug uchun

class KnowledgeLevels extends Page implements HasForms
{
    use WithPagination, InteractsWithForms; // Traitlarni qo'shish

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Ta\'lim';
    protected static ?string $title = 'Bilim darajasi';
    protected static ?string $slug = 'education/knowledge-levels';
    protected static ?int $navigationSort = 15;

    protected static string $view = 'filament.pages.education.knowledge-levels';

    // --- Form Data ---
    public array $createLevelData = [];

    // --- Table Data & Filters ---
    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    // --- Edit Modal Data ---
    public bool $showEditModal = false;
    public ?KnowledgeLevel $editingLevel = null;
    public array $editLevelData = [];

    // --- Delete Modal Data ---
    public bool $showDeleteModal = false;
    public ?int $deletingLevelId = null;
    public ?string $deletingLevelName = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount(): void
    {
        // Boshlang'ich qiymatlarni o'rnatish (masalan, is_active uchun)
        $this->createForm->fill([
            'is_active' => true,
        ]);
        // Edit form modal ochilganda to'ldiriladi
        // $this->editForm->fill();
    }

    // --- Form Schema (Yaratish va Tahrirlash uchun umumiy) ---
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Daraja nomi')
                ->required()
                ->maxLength(255),
            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255)
                ->unique(
                    table: KnowledgeLevel::class,
                    column: 'slug',
                    ignorable: fn () => $this->editingLevel
                ),
            Toggle::make('is_active')
                ->label('Faol')
                ->default(true)
                ->onColor('success')
                ->offColor('danger'),
        ];
    }

    // --- Form Registration ---
    protected function getForms(): array
    {
        return [
            'createForm' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('createLevelData'),
            'editForm' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('editLevelData'),
        ];
    }

    // --- Create Knowledge Level Method ---
    public function createLevel(): void
    {
        try {
            $validatedData = $this->createForm->getState();

            // Agar slug avtomatik yaratilmasa va bo'sh bo'lsa, nomdan yaratish
            if (empty($validatedData['slug'])) {
                $validatedData['slug'] = Str::slug($validatedData['name']);
                // Bu yerda unique slugni tekshirish logikasi qo'shilishi mumkin
            }

            KnowledgeLevel::create($validatedData);

            Notification::make()
                ->title('Bilim darajasi muvaffaqiyatli qo\'shildi')
                ->success()
                ->send();

            // Formani tozalash va boshlang'ich holatga qaytarish
            $this->createForm->fill([
                'is_active' => true,
                'name' => '',
                'slug' => '',
            ]);
            $this->resetPage(); // Jadvalni yangilash uchun

        } catch (ValidationException $e) {
            // Validatsiya xatosi Filament tomonidan avtomatik ko'rsatiladi
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body($e->getMessage()) // Batafsil xato xabari
                ->danger()
                ->send();
        }
    }

    // --- Fetch Knowledge Levels for Table ---
    public function getLevelsProperty(): Paginator
    {
        return KnowledgeLevel::query()
            ->when($this->search, function (Builder $query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10); // Har bir sahifada 10 ta yozuv
    }

    // --- Table Actions ---
    public function updatedSearch(): void
    {
        $this->resetPage(); // Qidiruv o'zgarganda birinchi sahifaga o'tish
    }

    public function sortBy(string $field): void
    {
        // Ruxsat etilgan saralash maydonlari
        $allowedFields = ['name', 'slug', 'is_active'];
        if (!in_array($field, $allowedFields)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage(); // Saralash o'zgarganda birinchi sahifaga o'tish
    }

    // --- Edit/Delete Methods ---
    public function editLevel(int $levelId): void
    {
        $this->editingLevel = KnowledgeLevel::find($levelId);

        if ($this->editingLevel) {
            // Formani model ma'lumotlari bilan to'ldirish
            $this->editForm->fill([
                'name' => $this->editingLevel->name,
                'slug' => $this->editingLevel->slug,
                'is_active' => $this->editingLevel->is_active,
            ]);
            $this->showEditModal = true;
        } else {
            Notification::make()
                ->title('Xatolik')
                ->body('Bilim darajasi topilmadi.')
                ->danger()
                ->send();
        }
    }

    public function updateLevel(): void
    {
        if (!$this->editingLevel) {
            Notification::make()
                ->title('Xatolik')
                ->body('Tahrirlanayotgan bilim darajasi topilmadi.')
                ->danger()
                ->send();
            return;
        }

        try {
            $validatedData = $this->editForm->getState();

            // Slugni generatsiya qilish
            $validatedData['slug'] = !empty($validatedData['slug']) ? $validatedData['slug'] : Str::slug($validatedData['name']);

            // Modelni yangilash
            $this->editingLevel->update([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'],
                'is_active' => $validatedData['is_active'],
            ]);

            Notification::make()
                ->title('Bilim darajasi muvaffaqiyatli yangilandi')
                ->success()
                ->send();

            $this->showEditModal = false;
            $this->resetPage();

        } catch (ValidationException $e) {
            Notification::make()
                ->title('Validatsiya xatosi')
                ->body(implode(', ', array_merge(...array_values($e->errors()))))
                ->danger()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body('Bilim darajasini yangilashda xatolik: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function openDeleteModal(int $levelId): void
    {
        $level = KnowledgeLevel::find($levelId);
        if ($level) {
            $this->deletingLevelId = $level->id;
            $this->deletingLevelName = $level->name; // Tasdiqlash uchun nomni saqlash
            $this->showDeleteModal = true; // O'chirish modalini ko'rsatish
        } else {
             Notification::make()->title('Xatolik')->body('Bilim darajasi topilmadi.')->danger()->send();
        }
    }

    public function confirmDeleteLevel(): void
    {
        if (!$this->deletingLevelId) return; // Agar ID mavjud bo'lmasa, chiqib ketish

        try {
            $level = KnowledgeLevel::findOrFail($this->deletingLevelId);

            // Muhim: O'chirishdan oldin bog'liq yozuvlarni tekshirish (masalan, studentlar yoki guruhlar)
            if ($level->students()->exists() || $level->groups()->exists()) {
                 Notification::make()
                    ->title('O\'chirish mumkin emas')
                    ->body('Bu bilim darajasiga bog\'liq o\'quvchilar yoki guruhlar mavjud.')
                    ->warning() // Yoki danger()
                    ->send();
                $this->showDeleteModal = false; // Modalni yopish
                return; // O'chirishni to'xtatish
            }

            $level->delete(); // Darajani o'chirish

            Notification::make()
                ->title('Bilim darajasi muvaffaqiyatli o\'chirildi')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body('Bilim darajasini o\'chirishda xatolik: ' . $e->getMessage())
                ->danger()
                ->send();
        } finally {
            // Modal va o'zgaruvchilarni tozalash
            $this->showDeleteModal = false;
            $this->deletingLevelId = null;
            $this->deletingLevelName = null;
            $this->resetPage(); // Jadvalni yangilash
        }
    }
}
