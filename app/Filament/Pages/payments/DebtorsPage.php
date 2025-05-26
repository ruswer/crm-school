<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Students\StudentProfilePage;
use App\Models\Branch;
use App\Models\Group;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
// use Filament\Forms\Concerns\InteractsWithForms; // Olib tashlandi
// use Filament\Forms\Contracts\HasForms; // Olib tashlandi
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Carbon\Carbon; // Sana bilan ishlash uchun

class DebtorsPage extends Page // HasForms olib tashlandi
{
    // use InteractsWithForms; // Olib tashlandi
    use WithPagination;

    protected static ?string $navigationGroup = 'To\'lovlar';
    protected static ?string $navigationLabel = 'Qarzdorlar';
    protected static ?string $title = 'Qarzdorlar';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.payments.debtors-page';

    // --- Filtrlar uchun Public Xususiyatlar ---
    public ?string $selectedBranch = null; // String, chunki select option value '' bo'lishi mumkin
    public ?string $selectedGroup = null;  // String
    public ?string $minDebtAmount = null;
    public string $searchQuery = '';
    // HTML da borligi uchun qo'shildi, lekin getDebtors da ishlatilmayapti hozircha
    public ?string $selectedMonth = null;
    public ?string $selectedYear = null;

    // --- Saralash uchun ---
    public string $sortField = 'total_debt';
    public string $sortDirection = 'desc';

    // --- Dropdown Optionlari uchun ---
    public $branches = [];
    public $groups = [];
    public $months = [];
    public $years = [];

    // Query String (URL da filtrlarni saqlash uchun - ixtiyoriy)
    protected $queryString = [
        'selectedBranch' => ['except' => ''],
        'selectedGroup' => ['except' => ''],
        'minDebtAmount' => ['except' => ''],
        'searchQuery' => ['except' => ''],
        'selectedMonth' => ['except' => ''],
        'selectedYear' => ['except' => ''],
        'sortField' => ['except' => 'total_debt'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    // --- Hayot Sikli Metodlari ---
    public function mount(): void
    {
        $this->loadFilterOptions(); // Filtr optionlarini yuklash
    }

    // --- Filtr Optionlarini Yuklash ---
    protected function loadFilterOptions(): void
    {
        $this->branches = Branch::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        // Guruhlarni ham yuklash (filial tanlanganda dinamik o'zgartirish kerak bo'lishi mumkin)
        $this->groups = Group::where('status', 'active')
                            ->when($this->selectedBranch, fn($q) => $q->where('branch_id', $this->selectedBranch))
                            ->orderBy('name')
                            ->get(['id', 'name']);

        // Oylar ro'yxati (masalan)
        $this->months = collect(range(1, 12))->mapWithKeys(fn($month) =>
            [$month => Carbon::create()->month($month)->translatedFormat('F')] // Oy nomlarini olish (til sozlamalariga bog'liq)
        )->all();

        // Yillar ro'yxati (masalan, joriy yildan 3 yil oldin va 1 yil keyin)
        $currentYear = now()->year;
        $this->years = range($currentYear - 3, $currentYear + 1);
    }

    // Guruhlar ro'yxatini filialga qarab yangilash (agar kerak bo'lsa)
    public function updatedSelectedBranch($value): void
    {
        // Filial o'zgarganda guruhlar ro'yxatini qayta yuklash
        $this->selectedGroup = null; // Guruh tanlovini tozalash
        $this->groups = Group::where('status', 'active')
                            ->when($value, fn($q) => $q->where('branch_id', $value))
                            ->orderBy('name')
                            ->get(['id', 'name']);
        $this->resetPage(); // Paginationni ham reset qilish
    }

    // --- Filtr Tugmasi Uchun Metod ---
    public function filterDebtors(): void
    {
        // getDebtors metodi public xususiyatlarni ishlatgani uchun,
        // faqat paginationni reset qilish kifoya
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('searchQuery', 'selectedBranch', 'selectedGroup', 'selectedMonth', 'selectedYear');
        // Standart yilni qayta o'rnatish
        $this->selectedYear = now()->year;
        $this->resetPage();
    }

    // --- Qarzdorlar Ro'yxatini Olish Metodi (Avvalgi kabi) ---
    protected function getDebtors(): Paginator
    {
        // Bu metod avvalgi javobdagi kabi qoladi,
        // chunki u public xususiyatlar ($this->selectedBranch, $this->searchQuery, ...)
        // asosida ishlaydi. Faqat Month/Year filtrini qo'shish kerak bo'lsa,
        // shu metodga qo'shimcha ->when(...) shartlari qo'shiladi.

        // To'lanmagan invoyslar uchun qoldiq summani hisoblash subquerysi
        $debtSubquery = Invoice::selectRaw('COALESCE(SUM(amount_due - amount_paid), 0)') // <-- COALESCE qo'shildi
            ->whereColumn('invoices.student_id', 'students.id')
            ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_CANCELLED]); 

        // Oxirgi to'lov sanasini olish subquerysi
        $lastPaymentDateSubquery = Payment::select('payment_date')
            ->whereColumn('payments.student_id', 'students.id')
            ->latest('payment_date')
            ->limit(1);

        // Eng yaqin to'lov muddatini olish subquerysi
        $nextDueDateSubquery = Invoice::select('due_date')
            ->whereColumn('invoices.student_id', 'students.id')
            ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_CANCELLED])
            ->orderBy('due_date', 'asc')
            ->limit(1);

        return Student::query()
            ->select('students.*')
            ->selectSub($debtSubquery, 'total_debt')
            ->selectSub($lastPaymentDateSubquery, 'last_payment_date')
            ->selectSub($nextDueDateSubquery, 'next_due_date')
            ->with(['groups' => fn($q) => $q->select('groups.id', 'groups.name'), 'branch:id,name'])
            ->whereHas('invoices', function (Builder $query) {
                $query->select('student_id') // GROUP BY uchun
                      ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_CANCELLED])
                      ->groupBy('student_id')
                      ->havingRaw('SUM(amount_due - amount_paid) > 0.001');
            })
            // Filtrlar:
            ->when($this->selectedBranch, function (Builder $query, $branchId) {
                $query->where('branch_id', $branchId);
            })
            ->when($this->selectedGroup, function (Builder $query, $groupId) {
                $query->whereHas('groups', fn (Builder $q) => $q->where('groups.id', $groupId));
            })
            ->when($this->minDebtAmount, function (Builder $query, $minAmount) {
                $query->having('total_debt', '>=', (float)$minAmount);
            })
            ->when($this->searchQuery, function (Builder $query, $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
             // OY VA YIL BO'YICHA FILTR (Agar kerak bo'lsa, invoyslar bilan bog'lash kerak)
             // ->when($this->selectedYear, function (Builder $query, $year) {
             //     $query->whereHas('invoices', fn(Builder $q) => $q->whereYear('due_date', $year)); // Yoki 'created_at'
             // })
             // ->when($this->selectedMonth, function (Builder $query, $month) {
             //     $query->whereHas('invoices', fn(Builder $q) => $q->whereMonth('due_date', $month)); // Yoki 'created_at'
             // })
            // Saralash:
            ->orderBy(match ($this->sortField) {
                'student_name' => 'first_name',
                'total_debt' => 'total_debt',
                'last_payment_date' => 'last_payment_date',
                'next_due_date' => 'next_due_date',
                default => 'total_debt'
            }, $this->sortDirection)
            ->when($this->sortField === 'student_name', function ($query) {
                $query->orderBy('last_name', $this->sortDirection);
            })
            ->paginate(15);
    }

    // --- Saralash Metodi (Avvalgi kabi) ---
    public function sort(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    // --- Eslatma Yuborish Metodi (Avvalgi kabi) ---
    public function sendReminder(int $studentId): void
    {
        $student = Student::find($studentId);
        if (!$student) {
            Notification::make()->title('Xatolik')->body('O\'quvchi topilmadi.')->danger()->send();
            return;
        }
        Notification::make()->title('Eslatma Yuborildi (Simulyatsiya)')->body($student->first_name . ' ' . $student->last_name . ' ga qarz haqida eslatma yuborildi.')->success()->send();
    }

    public function showStudentProfile($studentId) // Qaytarish turi kerak emas, chunki redirect qiladi
    {
        // StudentProfilePage klassidan foydalanib URL generatsiya qilish
        return redirect()->to(StudentProfilePage::getUrl(['record' => $studentId]));
    }

    // --- View'ga Ma'lumotlarni Uzatish ---
    protected function getViewData(): array
    {
        return [
            'debtors' => $this->getDebtors(),
            // Filtr optionlarini view'ga uzatish
            'branches' => $this->branches,
            'groups' => $this->groups,
            'months' => $this->months,
            'years' => $this->years,
        ];
    }
}
