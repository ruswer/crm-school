<?php

namespace App\Filament\Pages\Education;

use App\Models\Branch;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Illuminate\Validation\ValidationException;

class Branches extends Page implements HasForms
{
    use WithPagination, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Ta\'lim';
    protected static ?string $title = 'Filiallar';
    protected static ?string $slug = 'education/branches';
    protected static ?int $navigationSort = 13;
    protected static string $view = 'filament.pages.education.branches';

    // --- Form Data ---
    public array $createBranchData = [];

    // --- Table Data & Filters ---
    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    // --- Edit Modal Data ---
    public bool $showEditModal = false;
    public ?Branch $editingBranch = null;
    public array $editBranchData = [];

    // --- Delete Modal Data ---
    public bool $showDeleteModal = false;
    public ?int $deletingBranchId = null;
    public ?string $deletingBranchName = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount(): void
    {
        $this->createForm->fill();
        $this->editForm->fill();
    }

    // --- Form Schema ---
    protected function getCreateFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Filial nomi')
                ->required()
                ->maxLength(255),
            Textarea::make('address')
                ->label('Manzil')
                ->rows(3)
                ->maxLength(65535),
            TextInput::make('phone')
                ->label('Telefon')
                ->tel()
                ->maxLength(20),
        ];
    }

    // --- Edit Form Schema ---
    protected function getEditFormSchema(): array
    {
        return $this->getCreateFormSchema();
    }

    // --- Form Registration ---
    protected function getForms(): array
    {
        return [
            'createForm' => $this->makeForm()
                ->schema($this->getCreateFormSchema())
                ->statePath('createBranchData'),
            'editForm' => $this->makeForm()
                ->schema($this->getEditFormSchema())
                ->statePath('editBranchData'),
        ];
    }

    // --- Create Branch Method ---
    public function createBranch(): void
    {
        try {
            $validatedData = $this->createForm->getState();
            Branch::create($validatedData);

            Notification::make()
                ->title('Filial muvaffaqiyatli qo\'shildi')
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

    // --- Fetch Branches for Table ---
    public function getBranchesProperty(): Paginator
    {
        return Branch::query()
            ->when($this->search, function (Builder $query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('address', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%');
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
        $allowedFields = ['name', 'address', 'phone'];
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
    public function editBranch(int $branchId): void
    {
        $this->editingBranch = Branch::find($branchId);

        if ($this->editingBranch) {
             $this->editForm->fill($this->editingBranch->toArray());
             $this->showEditModal = true;
        } else {
            Notification::make()->title('Xatolik')->body('Filial topilmadi.')->danger()->send();
        }
    }

    public function updateBranch(): void
    {
        if (!$this->editingBranch) {
            return;
        }

        try {
            $validatedData = $this->editForm->getState();
            $this->editingBranch->update($validatedData);

            Notification::make()
                ->title('Filial muvaffaqiyatli yangilandi')
                ->success()
                ->send();

            $this->showEditModal = false;
            // $this->resetPage(); // Usually not needed as Livewire updates the table

        } catch (ValidationException $e) {
             // Validation handled by Filament
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body('Filialni yangilashda xatolik: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function openDeleteModal(int $branchId): void
    {
        $branch = Branch::find($branchId);
        if ($branch) {
            $this->deletingBranchId = $branch->id;
            $this->deletingBranchName = $branch->name;
            $this->showDeleteModal = true;
        } else {
             Notification::make()->title('Xatolik')->body('Filial topilmadi.')->danger()->send();
        }
    }

    public function confirmDeleteBranch(): void
    {
        if (!$this->deletingBranchId) return;

        try {
            $branch = Branch::findOrFail($this->deletingBranchId);
            // Optional: Check for related records before deleting
            // if ($branch->groups()->exists() || $branch->students()->exists()) { ... }
            $branch->delete();
            Notification::make()
                ->title('Filial muvaffaqiyatli o\'chirildi')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body('Filialni o\'chirishda xatolik: ' . $e->getMessage())
                ->danger()
                ->send();
        } finally {
            $this->showDeleteModal = false;
            $this->deletingBranchId = null;
            $this->deletingBranchName = null;
            $this->resetPage();
        }
    }
}
