<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination; // Pagination uchun trait

class SearchExpensesPage extends Page
{
    use WithPagination; // Pagination trait qo'shildi

    protected static ?string $navigationGroup = 'Chiqimlar';
    protected static ?string $navigationLabel = 'Chiqimlarni izlash';
    protected static ?string $title = 'Chiqimlarni izlash';
    protected static ?int $navigationSort = 7;

    protected static string $view = 'filament.pages.expenses.search-expenses-page';

    // --- Filtr Maydonlari ---
    public ?string $branch_id = null;
    public ?string $category_id = null;
    public ?string $startDate = null;
    public ?string $endDate = null;

    // --- Filtr Uchun Ma'lumotlar ---
    public $branches = [];
    public $categories = [];

    // --- Jadval Uchun ---
    public string $searchQuery = ''; // Jadval ichidagi qidiruv
    public string $sortField = 'expense_date'; // Standart saralash
    public string $sortDirection = 'desc'; // Standart yo'nalish

    // Query String (URL da holatni saqlash)
    protected $queryString = [
        'branch_id' => ['except' => ''],
        'category_id' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'searchQuery' => ['except' => ''],
        'sortField' => ['except' => 'expense_date'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    // Sahifa yuklanganda filial va kategoriyalarni olish
    public function mount(): void
    {
        $this->branches = Branch::where('status', 'active')->orderBy('name')->get();
        $this->categories = ExpenseCategory::where('status', 'active')->orderBy('name')->get();
    }

    // Filtrlar o'zgarganda paginationni reset qilish
    public function updated($propertyName): void
    {
        // Agar filtr maydonlaridan biri o'zgarsa, sahifani boshiga qaytarish
        if (in_array($propertyName, ['branch_id', 'category_id', 'startDate', 'endDate', 'searchQuery'])) {
            $this->resetPage();
        }
    }

    // Saralash metodi
    public function sort(string $field): void
    {
        // Blade'dagi kalitlarni DB ustunlariga moslashtirish
        $actualField = match ($field) {
            'expenseName' => 'name',
            'category' => 'category_id', // Yoki join bilan kategoriya nomi
            'date' => 'expense_date',
            'paymentType' => 'payment_type',
            'note' => 'description',
            'price' => 'amount',
            default => 'expense_date',
        };

        if ($this->sortField === $actualField) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $actualField;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function searchExpenses(): void
    {
        $this->resetPage();
    }

    // Barcha filtrlarni tozalash
    public function resetFilters(): void
    {
        $this->reset('branch_id', 'category_id', 'startDate', 'endDate', 'searchQuery', 'sortField', 'sortDirection');
        $this->resetPage();
    }

    // Chiqimlarni qidirish va olish
    protected function getExpenses(): Paginator
    {
        return Expense::query()
            ->with(['category:id,name', 'branch:id,name']) // N+1 muammosini oldini olish
            // Asosiy filtrlar
            ->when($this->branch_id, fn(Builder $q) => $q->where('branch_id', $this->branch_id))
            ->when($this->category_id, fn(Builder $q) => $q->where('category_id', $this->category_id))
            ->when($this->startDate, fn(Builder $q) => $q->whereDate('expense_date', '>=', $this->startDate))
            ->when($this->endDate, fn(Builder $q) => $q->whereDate('expense_date', '<=', $this->endDate))
            // Jadval ichidagi qidiruv
            ->when($this->searchQuery, function (Builder $query, $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('category', fn(Builder $catQ) => $catQ->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('branch', fn(Builder $brQ) => $brQ->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10); // Har sahifada 10 ta natija
    }

    // View'ga ma'lumotlarni uzatish
    protected function getViewData(): array
    {
        return [
            'branches' => $this->branches,
            'categories' => $this->categories,
            'expenses' => $this->getExpenses(), // Filtrlangan va saralangan chiqimlar
        ];
    }
}
