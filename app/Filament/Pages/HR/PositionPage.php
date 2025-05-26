<?php

namespace App\Filament\Pages\HR;

use App\Models\Position;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Exception;

class PositionPage extends Page
{
    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Lavozim';
    protected static ?string $title = 'Lavozim';
    protected static ?string $slug = 'hr/position';
    protected static ?int $navigationSort = 23;

    protected static string $view = 'filament.pages.h-r.position';

    use InteractsWithForms;
    use WithPagination;

    // Forma uchun
    public ?string $name = null;

    // Modal uchun
    public bool $showModal = false;
    public ?string $modalType = null; // 'edit' yoki 'delete'
    public ?Position $editingPosition = null;
    public ?string $editName = null;
    public ?int $positionToDeleteId = null;
    public ?string $positionToDeleteName = null;

    // Qidiruv va Saralash
    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    // Pagination
    public int $perPage = 10;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Lavozim nomi')
                ->required()
                ->maxLength(255),
        ];
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    // Lavozimlar ro'yxatini olish
    public function getPositionsProperty(): LengthAwarePaginator
    {
        return Position::query()
            ->when($this->search, function (Builder $query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    // Yangi lavozim yaratish
    public function createPosition(): void
    {
        $validatedData = $this->form->getState();

        try {
            Position::create($validatedData);
            Notification::make()->success()->title('Lavozim muvaffaqiyatli qo\'shildi')->send();
            $this->form->fill(); // Formani tozalash
            $this->resetPage(); // Paginationni reset qilish
        } catch (Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('Lavozimni qo\'shishda xatolik: ' . $e->getMessage())->send();
        }
    }

    // Modalni ochish
    public function openModal(string $type, int $positionId): void
    {
        $position = Position::find($positionId);
        if ($position) {
            $this->modalType = $type;
            if ($type === 'edit') {
                $this->editingPosition = $position;
                $this->editName = $position->name;
            } else {
                $this->positionToDeleteId = $positionId;
                $this->positionToDeleteName = $position->name;
            }
            $this->showModal = true;
        }
    }

    // Lavozimni yangilash
    public function updatePosition(): void
    {
        $this->validate([
            'editName' => 'required|string|max:255|unique:positions,name,' . $this->editingPosition->id,
        ], [
            'editName.required' => 'Lavozim nomi majburiy.',
            'editName.unique' => 'Bu nomdagi lavozim allaqachon mavjud.',
        ]);

        try {
            $this->editingPosition->update(['name' => $this->editName]);
            Notification::make()->success()->title('Lavozim muvaffaqiyatli yangilandi')->send();
            $this->closeModal();
            $this->resetPage();
        } catch (Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('Lavozimni yangilashda xatolik: ' . $e->getMessage())->send();
        }
    }

    // Lavozimni o'chirish
    public function deletePosition(): void
    {
        try {
            $position = Position::findOrFail($this->positionToDeleteId);
            // Agar lavozimda xodimlar bo'lsa, o'chirishni cheklash mumkin
            if ($position->staff()->exists()) {
                throw new Exception('Bu lavozimda xodimlar mavjud. Avval xodimlarni boshqa lavozimga o\'tkazing.');
            }
            $position->delete();
            Notification::make()->success()->title('Lavozim muvaffaqiyatli o\'chirildi')->send();
        } catch (Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('Lavozimni o\'chirishda xatolik: ' . $e->getMessage())->send();
        } finally {
            $this->closeModal();
            $this->resetPage();
        }
    }

    // Modalni yopish
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->modalType = null;
        $this->editingPosition = null;
        $this->editName = null;
        $this->positionToDeleteId = null;
        $this->positionToDeleteName = null;
    }

    // Saralash
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

    // Live search va pagination uchun
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }
}