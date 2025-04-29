<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator; // Pagination uchun
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination; // Pagination trait

class AddExpensePage extends Page
{
    use WithPagination; // Pagination trait qo'shildi

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationGroup = 'Chiqimlar';
    protected static ?string $navigationLabel = 'Chiqim qo\'shish';
    protected static ?string $title = 'Chiqimlar';
    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.pages.expenses.add-expense-page';

    // --- Forma Maydonlari ---
    public ?string $category_id = null;
    public ?string $branch_id = null;
    public ?string $expense_name = null;
    public ?string $expense_date = null;
    public ?string $amount = null;
    public ?string $payment_type = null;
    public ?string $description = null;

    // --- Jadval Uchun ---
    public string $searchQuery = ''; // Qidiruv uchun
    public string $sortField = 'expense_date'; // Standart saralash (sana bo'yicha)
    public string $sortDirection = 'desc'; // Standart yo'nalish (eng yangisi birinchi)

    // Mavjud kategoriyalar va filiallar
    public $categories = [];
    public $branches = [];

    // Query String
    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'sortField' => ['except' => 'expense_date'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    public function mount(): void
    {
        $this->categories = ExpenseCategory::where('status', 'active')->orderBy('name')->get(); // Faqat aktiv kategoriyalar
        $this->branches = Branch::where('status', 'active')->orderBy('name')->get(); // Faqat aktiv filiallar
        $this->expense_date = now()->format('Y-m-d'); // Sanani bugungi kun bilan to'ldirish
    }

    // Formani tozalash
    protected function resetForm(): void
    {
        $this->category_id = null;
        $this->branch_id = null;
        $this->expense_name = null;
        $this->expense_date = now()->format('Y-m-d'); // Tozalaganda ham bugungi kun
        $this->amount = null;
        $this->payment_type = null;
        $this->description = null;
        $this->resetValidation();
    }

    // Chiqimni saqlash
    public function saveExpense(): void
    {
        $validatedData = Validator::make(
            // ... (validatsiya qoidalari o'zgarishsiz) ...
            [
                'category_id' => $this->category_id,
                'branch_id' => $this->branch_id,
                'expense_name' => $this->expense_name,
                'expense_date' => $this->expense_date,
                'amount' => $this->amount,
                'payment_type' => $this->payment_type,
                'description' => $this->description,
            ],
            [
                'category_id' => ['required', 'exists:expense_categories,id'],
                'branch_id' => ['nullable', 'exists:branches,id'],
                'expense_name' => ['required', 'string', 'max:255'],
                'expense_date' => ['required', 'date'],
                'amount' => ['required', 'numeric', 'min:0'],
                'payment_type' => ['required', 'in:cash,card,transfer'],
                'description' => ['nullable', 'string'],
            ]
        )->validate();

        try {
            Expense::create([
                'category_id' => $validatedData['category_id'],
                'branch_id' => $validatedData['branch_id'],
                'name' => $validatedData['expense_name'],
                'expense_date' => $validatedData['expense_date'],
                'amount' => $validatedData['amount'],
                'payment_type' => $validatedData['payment_type'],
                'description' => $validatedData['description'],
            ]);

            Notification::make()->title('Chiqim muvaffaqiyatli qo\'shildi!')->success()->send();
            $this->resetForm();
            $this->resetPage(); // Jadvalni yangilash uchun

        } catch (\Exception $e) {
            Notification::make()->title('Xatolik yuz berdi!')->body('Chiqimni saqlashda xatolik: ' . $e->getMessage())->danger()->send();
        }
    }

    // Saralash metodi
    public function sort(string $field): void
    {
        // Saralash kalitlarini moslashtirish (Blade'dagi qiymatlarga)
        $actualField = match ($field) {
            'name' => 'name',
            'date' => 'expense_date',
            'category' => 'category_id', // Yoki join bilan kategoriya nomi bo'yicha
            'branch' => 'branch_id',     // Yoki join bilan filial nomi bo'yicha
            'amount' => 'amount',
            'payment_type' => 'payment_type',
            default => 'expense_date', // Agar noma'lum bo'lsa
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
        $this->resetPage();
    }

    // Qidiruv o'zgarganda paginationni reset qilish
    public function updatedSearchQuery(): void
    {
        $this->resetPage();
    }

    // Chiqimlar ro'yxatini olish
    protected function getExpenses(): Paginator
    {
        return Expense::query()
            ->with(['category:id,name', 'branch:id,name']) // Kerakli relationship ustunlarini yuklash
            ->when($this->searchQuery, function (Builder $query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('category', fn(Builder $q) => $q->where('name', 'like', "%{$search}%")) // Kategoriya nomi bo'yicha
                      ->orWhereHas('branch', fn(Builder $q) => $q->where('name', 'like', "%{$search}%")); // Filial nomi bo'yicha
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10); // Har sahifada 10 ta
    }

    // View'ga ma'lumotlarni uzatish
    protected function getViewData(): array
    {
        return [
            'categories' => $this->categories,
            'branches' => $this->branches,
            'expenses' => $this->getExpenses(), // Chiqimlar ro'yxatini uzatish
        ];
    }
}
