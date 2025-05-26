<?php

namespace App\Filament\Pages; // Namespace to'g'rilandi

use App\Models\ExpenseCategory;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator; // Pagination uchun
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination; // Pagination trait

class ExpenseCategoriesPage extends Page
{
    use WithPagination; // Pagination trait qo'shildi

    protected static ?string $navigationGroup = 'Chiqimlar';
    protected static ?string $navigationLabel = 'Kategoriyalar';
    protected static ?string $title = 'Chiqimlar Kategoriyalari';
    protected static ?int $navigationSort = 8;

    protected static string $view = 'filament.pages.expenses.expense-categories-page';

    // --- Forma Maydonlari ---
    public ?string $name = null;
    public ?string $description = null;
    public string $status = 'active'; // Standart qiymat
    public ?int $editingCategoryId = null;

    // --- Jadval Uchun ---
    public string $searchQuery = '';
    public string $sortField = 'created_at'; // Standart saralash
    public string $sortDirection = 'desc'; // Standart yo'nalish

    // Query String (URL da holatni saqlash - ixtiyoriy)
    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    public function mount(): void
    {
        if (!$this->editingCategoryId) {
            $this->resetForm();
        }
    }

    // Formani tozalash
    protected function resetForm(): void
    {
        $this->name = null;
        $this->description = null;
        $this->status = 'active';
        $this->editingCategoryId = null;
        $this->resetValidation();
    }

    // Kategoriya saqlash/yangilash
    public function saveCategory(): void
    {
        $validatedData = Validator::make(
            [
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status,
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'status' => ['required', 'in:active,inactive'],
            ]
        )->validate();

        try {
            if ($this->editingCategoryId) {
                $category = ExpenseCategory::findOrFail($this->editingCategoryId);
                $category->update($validatedData);
                Notification::make()->title('Muvaffaqiyatli yangilandi')->success()->send();
            } else {
                ExpenseCategory::create($validatedData);
                Notification::make()->title('Muvaffaqiyatli yaratildi')->success()->send();
            }
            $this->resetForm();
            // Sahifani yangilash uchun (jadvalni qayta yuklash)
            $this->resetPage(); // Paginationni reset qilish ham jadvalni yangilaydi

        } catch (\Exception $e) {
            Notification::make()->title('Xatolik yuz berdi')->body($e->getMessage())->danger()->send();
        }
    }

    // Tahrirlash uchun formani to'ldirish
    public function editCategory(int $categoryId): void
    {
        try {
            $category = ExpenseCategory::findOrFail($categoryId);
            $this->editingCategoryId = $category->id;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->status = $category->status;
            $this->resetValidation(); // Oldingi xatolarni tozalash
        } catch (\Exception $e) {
            Notification::make()->title('Xatolik')->body('Kategoriya topilmadi.')->danger()->send();
            $this->resetForm();
        }
    }

    // Saralash metodi
    public function sort(string $field): void
    {
        // Saralash kalitlarini moslashtirish (Blade'dagi qiymatlarga)
        $actualField = match ($field) {
            'category' => 'name',
            'note' => 'description', // Tavsif bo'yicha saralash kam ishlatiladi
            'status' => 'status',
            default => 'created_at', // Agar noma'lum bo'lsa
        };

        if ($this->sortField === $actualField) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $actualField;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('searchQuery', 'sortField', 'sortDirection');
        // Standart yilni qayta o'rnatish
        $this->resetPage();
    }

    // Qidiruv o'zgarganda paginationni reset qilish
    public function updatedSearchQuery(): void
    {
        $this->resetPage();
    }

    // Kategoriyalar ro'yxatini olish
    protected function getCategories(): Paginator
    {
        return ExpenseCategory::query()
            ->when($this->searchQuery, function (Builder $query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10); // Har sahifada 10 ta
    }

    // View'ga ma'lumotlarni uzatish
    protected function getViewData(): array
    {
        return [
            'categories' => $this->getCategories(),
        ];
    }
}
