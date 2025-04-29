<?php

namespace App\Filament\Pages\Education;

use App\Models\Cabinet; // Modelni import qilish
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Illuminate\Validation\ValidationException;

class Cabinets extends Page implements HasForms
{
    use WithPagination, InteractsWithForms; // Traitlarni qo'shish

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Ta\'lim';
    protected static ?string $title = 'Kabinetlar';
    protected static ?string $slug = 'education/cabinets';
    protected static ?int $navigationSort = 16;

    protected static string $view = 'filament.pages.education.cabinets';

    // --- Form Data ---
    public array $createCabinetData = [];

    // --- Table Data & Filters ---
    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    // --- Edit Modal Data ---
    public bool $showEditModal = false;
    public ?Cabinet $editingCabinet = null;
    public array $editCabinetData = [];

    // --- Delete Modal Data ---
    public bool $showDeleteModal = false;
    public ?int $deletingCabinetId = null;
    public ?string $deletingCabinetName = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount(): void
    {
        $this->createForm->fill();
        // $this->editForm->fill(); // Modal ochilganda to'ldiriladi
    }

    // --- Form Schema (Yaratish va Tahrirlash uchun umumiy) ---
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Kabinet raqami yoki nomi') // Labelni o'zgartirdik
                ->required()
                ->maxLength(255)
                ->unique(
                    table: Cabinet::class,
                    column: 'name',
                    ignorable: fn () => $this->editingCabinet // Tahrirlashda joriy yozuvni ignor qilish
                ),
            // Kerak bo'lsa boshqa maydonlar
            // TextInput::make('capacity')->label('Sig\'imi')->numeric()->minValue(0),
            // Select::make('branch_id')->label('Filial')->options(Branch::pluck('name', 'id')),
        ];
    }

    // --- Form Registration ---
    protected function getForms(): array
    {
        return [
            'createForm' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('createCabinetData'),
            'editForm' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('editCabinetData'),
        ];
    }

    // --- Create Cabinet Method ---
    public function createCabinet(): void
    {
        try {
            $validatedData = $this->createForm->getState();
            Cabinet::create($validatedData);

            Notification::make()
                ->title('Kabinet muvaffaqiyatli qo\'shildi')
                ->success()
                ->send();

            $this->createForm->fill(); // Formani tozalash
            $this->resetPage(); // Jadvalni yangilash

        } catch (ValidationException $e) {
            // Validatsiya xatosi Filament tomonidan avtomatik ko'rsatiladi
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    // --- Fetch Cabinets for Table ---
    public function getCabinetsProperty(): Paginator
    {
        return Cabinet::query()
            ->when($this->search, function (Builder $query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
                // Agar boshqa maydonlar bo'yicha ham qidirish kerak bo'lsa:
                // ->orWhere('capacity', 'like', '%' . $search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10); // Har sahifada 10 ta
    }

    // --- Table Actions ---
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        // Ruxsat etilgan saralash maydonlari
        $allowedFields = ['name'/*, 'capacity', 'branch_id'*/]; // Kerakli maydonlarni qo'shing
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
    public function editCabinet(int $cabinetId): void
    {
        $this->editingCabinet = Cabinet::find($cabinetId);

        if ($this->editingCabinet) {
             // Edit formani topilgan kabinet ma'lumotlari bilan to'ldirish
             $this->editForm->fill($this->editingCabinet->toArray());
             $this->showEditModal = true; // Modalni ko'rsatish
        } else {
            Notification::make()->title('Xatolik')->body('Kabinet topilmadi.')->danger()->send();
        }
    }

    public function updateCabinet(): void
    {
        if (!$this->editingCabinet) {
            return;
        }

        try {
            $validatedData = $this->editForm->getState();
            $this->editingCabinet->update($validatedData);

            Notification::make()
                ->title('Kabinet muvaffaqiyatli yangilandi')
                ->success()
                ->send();

            $this->showEditModal = false; // Modalni yopish
            // $this->resetPage(); // Livewire avtomatik yangilaydi

        } catch (ValidationException $e) {
             // Validatsiya xatosi Filament tomonidan avtomatik ko'rsatiladi
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body('Kabinetni yangilashda xatolik: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function openDeleteModal(int $cabinetId): void
    {
        $cabinet = Cabinet::find($cabinetId);
        if ($cabinet) {
            $this->deletingCabinetId = $cabinet->id;
            $this->deletingCabinetName = $cabinet->name; // Tasdiqlash uchun nomni saqlash
            $this->showDeleteModal = true; // O'chirish modalini ko'rsatish
        } else {
             Notification::make()->title('Xatolik')->body('Kabinet topilmadi.')->danger()->send();
        }
    }

    public function confirmDeleteCabinet(): void
    {
        if (!$this->deletingCabinetId) return;

        try {
            $cabinet = Cabinet::findOrFail($this->deletingCabinetId);

            // Muhim: O'chirishdan oldin bog'liq yozuvlarni tekshirish (masalan, guruhlar)
            // if ($cabinet->groups()->exists()) {
            //      Notification::make()
            //         ->title('O\'chirish mumkin emas')
            //         ->body('Bu kabinetga bog\'liq guruhlar mavjud.')
            //         ->warning()
            //         ->send();
            //     $this->showDeleteModal = false;
            //     return;
            // }

            $cabinet->delete(); // Kabinetni o'chirish

            Notification::make()
                ->title('Kabinet muvaffaqiyatli o\'chirildi')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body('Kabinetni o\'chirishda xatolik: ' . $e->getMessage())
                ->danger()
                ->send();
        } finally {
            // Modal va o'zgaruvchilarni tozalash
            $this->showDeleteModal = false;
            $this->deletingCabinetId = null;
            $this->deletingCabinetName = null;
            $this->resetPage(); // Jadvalni yangilash
        }
    }
}
