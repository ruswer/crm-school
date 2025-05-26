<?php

namespace App\Filament\Pages\HR;

use App\Models\Branch;
use App\Models\Payroll;
use App\Models\SalaryPayment;
use App\Models\Staff;
use App\Models\Group;      // Qo'shildi
use App\Models\Student;    // Qo'shildi
use App\Models\Invoice;    // Qo'shildi
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\WithPagination;


class Salary extends Page
{
    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Ish haqi';
    protected static ?string $title = 'Ish haqi';
    protected static ?string $slug = 'hr/salary';
    protected static ?int $navigationSort = 21;

    protected static string $view = 'filament.pages.h-r.salary';

    use WithPagination;

    // Filtrlar, Qidiruv, Saralash
    public ?int $selectedBranchId = null;
    public ?string $selectedMonth = null;
    public ?string $selectedYear = null;
    public string $search = '';
    public string $sortField = 'staff.first_name'; // Default staff name bo'yicha
    public string $sortDirection = 'asc';
    public int $perPage = 15;

    // Ma'lumotlar va Saqlash uchun
    public array $payrollDataInput = []; // "Jami" inputlari uchun

    // To'lov Modali uchun
    public bool $showPaymentModal = false;
    public ?Staff $staffForPayment = null;
    public ?float $paymentAmount = null;
    public ?string $paymentDate = null;
    public ?string $paymentComment = null;
    public ?int $selectedPayrollIdForPayment = null; // Qaysi payrollga to'lov qilinayotganini bilish uchun

    public function mount(): void
    {
        // Joriy oy va yilni standart qilib o'rnatish (agar URLda bo'lmasa)
        $this->selectedMonth = $this->selectedMonth ?? Carbon::now()->format('m');
        $this->selectedYear = $this->selectedYear ?? Carbon::now()->format('Y');
        $this->paymentDate = Carbon::today()->format('Y-m-d'); // To'lov sanasini standart qilish
    }

    // Filiallar ro'yxati
    public function getBranchesProperty(): Collection
    {
        return Branch::orderBy('name')->pluck('name', 'id');
    }

    // Yillar ro'yxati
    public function getYearsProperty(): array
    {
        $currentYear = Carbon::now()->year;
        return array_combine(range($currentYear, $currentYear - 4), range($currentYear, $currentYear - 4));
    }

    // Oylar ro'yxati
    public function getMonthsProperty(): array
    {
        return [
            '01' => 'Yanvar', '02' => 'Fevral', '03' => 'Mart', '04' => 'Aprel',
            '05' => 'May', '06' => 'Iyun', '07' => 'Iyul', '08' => 'Avgust',
            '09' => 'Sentyabr', '10' => 'Oktyabr', '11' => 'Noyabr', '12' => 'Dekabr',
        ];
    }

    // Ish haqi ma'lumotlarini olish
    public function getPayrollDataProperty(): LengthAwarePaginator
    {
        if (!$this->selectedMonth || !$this->selectedYear) {
            return Staff::whereRaw('1 = 0')->paginate($this->perPage);
        }

        $startDate = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $query = Staff::query()
            ->select('staff.*') // Staff jadvalidan barcha ustunlarni olish
            ->with(['branch', 'position']) // Kerakli aloqadorliklar (position qo'shildi)
            ->leftJoin('payrolls', function ($join) {
                $join->on('staff.id', '=', 'payrolls.staff_id')
                    ->where('payrolls.month', '=', $this->selectedMonth)
                    ->where('payrolls.year', '=', $this->selectedYear);
            })
            ->addSelect(DB::raw('payrolls.id as payroll_id, COALESCE(payrolls.final_salary, 0) as final_salary')) // Payroll ID va Yakuniy maoshni olish
            ->withSum(['salaryPayments as total_paid' => function ($query) {
                $query->where('month', $this->selectedMonth)->where('year', $this->selectedYear);
            }], 'amount') // Shu oy/yil uchun to'langan jami summani hisoblash
            // Qarzni hisoblash (final_salary o'zgarishi mumkinligini hisobga oladi)
            ->addSelect(DB::raw('COALESCE(payrolls.final_salary, 0) - COALESCE((SELECT SUM(amount) FROM salary_payments WHERE salary_payments.staff_id = staff.id AND salary_payments.month = ? AND salary_payments.year = ?), 0) as debt'))
            ->addBinding($this->selectedMonth, 'select') // Binding qo'shish
            ->addBinding($this->selectedYear, 'select')  // Binding qo'shish
            ->when($this->selectedBranchId, fn ($q) => $q->where('staff.branch_id', $this->selectedBranchId))
            ->when($this->search, function (Builder $query, $search) {
                $searchTerm = '%' . $search . '%';
                $query->where(function (Builder $q) use ($searchTerm) {
                    $q->where('staff.first_name', 'like', $searchTerm)
                      ->orWhere('staff.last_name', 'like', $searchTerm)
                      ->orWhereRaw("CONCAT(staff.first_name, ' ', staff.last_name) LIKE ?", [$searchTerm]);
                });
            });
            // ->orderBy($this->sortField, $this->sortDirection); // Saralashni pastroqqa ko'chiramiz

        // Saralashni hisoblangan ustunlar uchun to'g'rilash
        if ($this->sortField === 'final_salary') {
            $query->orderBy('final_salary', $this->sortDirection);
        } elseif ($this->sortField === 'total_paid') {
            $query->orderBy('total_paid', $this->sortDirection);
        } elseif ($this->sortField === 'debt') {
            $query->orderBy('debt', $this->sortDirection); // Hisoblangan 'debt' bo'yicha saralash
        } else {
             $query->orderBy($this->sortField, $this->sortDirection); // Boshqa maydonlar bo'yicha saralash
        }

        $payrolls = $query->paginate($this->perPage);

        // Inputlar uchun ma'lumotlarni tayyorlash va o'qituvchi oyligini hisoblash
        $this->payrollDataInput = []; // Har сафар тозалаш

        $payrolls->getCollection()->transform(function ($staff) use ($startDate, $endDate) {

            // Xodim o'qituvchi ekanligini tekshirish (Lavozim nomini moslang)
            if ($staff->position?->name === 'O\'qituvchi') { // 'O\'qituvchi' nomini o'zingiznikiga moslang
                $totalMonthlyFees = 0;

                // O'qituvchiga biriktirilgan faol guruhlarni olish
                $groups = Group::where('teacher_id', $staff->id)
                    // ->where(function($q) use ($startDate, $endDate) { // Guruh tanlangan oyda faol bo'lganligini tekshirish
                    //     // BU YERDA start_date VA end_date USTUNLARI JADVALDA MAVJUD BO'LISHI KERAK
                    //     $q->whereNull('start_date')->orWhere('start_date', '<=', $endDate);
                    // })
                    // ->where(function($q) use ($startDate) {
                    //     $q->whereNull('end_date')->orWhere('end_date', '>=', $startDate);
                    // })
                    ->with(['students' => function($query) use ($startDate, $endDate) {
                        // Guruhdagi tanlangan oyda faol bo'lgan talabalarni olish
                        $query->wherePivot('join_date', '<=', $endDate) // Endi bu ustun mavjud
                              ->wherePivotNull('leave_date') // To'g'ri sintaksis
                              ->orWherePivot('leave_date', '>=', $startDate);// To'g'ri sintaksis

                        }])

                    ->get();

                foreach ($groups as $group) {
                    foreach ($group->students as $student) {
                        // Har bir talabaning shu oy uchun kutilayotgan to'lovini Invoice'dan olish
                        $invoice = Invoice::where('student_id', $student->id)
                            // ->where('group_id', $group->id) // Agar invoys guruhga bog'liq bo'lsa
                            ->whereYear('due_date', $this->selectedYear) // Invoysning to'lov sanasi bo'yicha
                            ->whereMonth('due_date', $this->selectedMonth)
                            ->first();

                        $fee = $invoice?->amount_due ?? 0; // amount_due ni olish (agar invoys bo'lmasa 0)
                        $totalMonthlyFees += $fee;
                    }
                }

                // Oylikni hisoblash (50%)
                $calculatedSalary = $totalMonthlyFees * 0.50;

                // Payroll yozuvidagi 'final_salary' ni yangilash (faqat shu obyekt uchun)
                $staff->final_salary = $calculatedSalary;

                // Qarzni yangilangan maosh asosida qayta hisoblash
                $staff->debt = $calculatedSalary - ($staff->total_paid ?? 0);
            }

            // Inputlar massivini to'ldirish (o'qituvchi yoki boshqa xodim uchun)
            // Formatlashni olib tashladim, chunki input type="number"
            $this->payrollDataInput[$staff->id] = $staff->final_salary ?? 0;

            return $staff; // O'zgartirilgan staff obyektini qaytarish
        });

        return $payrolls;
    }

    // Qidirish tugmasi
    public function searchPayrolls(): void
    {
        $this->resetPage();
    }

    // Live search
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

     // Saralash
    public function sortBy(string $field): void
    {
        // Blade faylidagi ustun nomlariga moslashtirish
        $sortableFields = [
            'employeeName' => 'staff.first_name', // Xodim nomi
            'salaryAll' => 'final_salary',        // Jami (hisoblangan)
            'paidSalary' => 'total_paid',         // To'langan (hisoblangan)
            'notPaidSalary' => 'debt',            // Qarz (hisoblangan)
        ];

        // Agar $field 'staff.first_name' kabi kelsa, uni to'g'ridan-to'g'ri ishlatish
        $actualField = $sortableFields[$field] ?? $field;

        // Agar maydon haqiqiy ustun nomlaridan biri bo'lmasa, chiqib ketish
        // Bu yerda tekshirishni kuchaytirish mumkin (masalan, faqat ruxsat etilganlar ro'yxati)
        // if (!in_array($actualField, ['staff.first_name', 'final_salary', 'total_paid', 'debt'])) return;

        if ($this->sortField === $actualField) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $actualField;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }


    // "Jami" ish haqini saqlash
    public function saveSalary(int $staffId): void
    {
        if (!isset($this->payrollDataInput[$staffId])) {
            Notification::make()->warning()->title('Xatolik')->body('Saqlash uchun ma\'lumot topilmadi.')->send();
            return;
        }

        // Inputdan kelgan qiymatni olish (string bo'lishi mumkin)
        $inputSalary = $this->payrollDataInput[$staffId];

        // Qiymatni float ga o'girish va validatsiya
        $finalSalary = filter_var($inputSalary, FILTER_VALIDATE_FLOAT);

        if ($finalSalary === false || $finalSalary < 0) {
             Notification::make()->danger()->title('Xatolik')->body('Jami summa noto\'g\'ri kiritildi. Raqam kiriting.')->send();
             // Inputni eski qiymatiga qaytarish (agar kerak bo'lsa)
             // $payroll = Payroll::where('staff_id', $staffId)->where('month', $this->selectedMonth)->where('year', $this->selectedYear)->first();
             // $this->payrollDataInput[$staffId] = $payroll?->final_salary ?? 0;
             return;
        }

        // O'qituvchi uchun qo'lda o'zgartirishni cheklash (ixtiyoriy)
        // $staff = Staff::with('position')->find($staffId);
        // if ($staff && $staff->position?->name === 'O\'qituvchi') {
        //     Notification::make()->warning()->title('Ogohlantirish')->body('O\'qituvchi oyligi avtomatik hisoblanadi va qo\'lda saqlanmaydi.')->send();
        //     // Inputni hisoblangan qiymatga qaytarish
        //     // Bu yerda qayta hisoblash yoki transformdagi qiymatni olish kerak
        //     // $this->payrollDataInput[$staffId] = $this->calculateTeacherSalary($staff); // Masalan
        //     return;
        // }


        try {
            Payroll::updateOrCreate(
                [
                    'staff_id' => $staffId,
                    'month' => $this->selectedMonth,
                    'year' => $this->selectedYear,
                ],
                [
                    'final_salary' => $finalSalary, // Float qiymatni saqlash
                    'branch_id' => Staff::find($staffId)?->branch_id, // Filialni olish
                ]
            );
            Notification::make()->success()->title('Muvaffaqiyatli saqlandi!')->send();
            // Ma'lumotlarni yangilash uchun (qarz va to'langan summani qayta olish uchun)
            $this->resetPage(); // Paginationni reset qilish jadvalni yangilaydi
        } catch (\Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('Saqlashda xatolik yuz berdi: ' . $e->getMessage())->send();
        }
    }

    // To'lov modalini ochish
    public function openPaymentModal(int $staffId, ?int $payrollId): void
    {
        $this->staffForPayment = Staff::find($staffId);
        $this->selectedPayrollIdForPayment = $payrollId; // Payroll ID ni saqlash
        $this->reset(['paymentAmount', 'paymentComment']); // Oldingi qiymatlarni tozalash
        $this->paymentDate = Carbon::today()->format('Y-m-d'); // Sanani standart qilish
        $this->showPaymentModal = true;
    }

    // To'lov modalini yopish
    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->reset(['staffForPayment', 'paymentAmount', 'paymentDate', 'paymentComment', 'selectedPayrollIdForPayment']);
    }

    // To'lovni saqlash
    public function savePayment(): void
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:0.01', // Minimal to'lov 0 dan katta bo'lishi kerak
            'paymentDate' => 'required|date_format:Y-m-d',
            'paymentComment' => 'nullable|string|max:255',
        ]);

        if (!$this->staffForPayment) {
            Notification::make()->danger()->title('Xatolik')->body('To\'lov uchun xodim topilmadi.')->send();
            return;
        }

        // To'lov sanasidan oy va yilni olish
        $paymentMonth = Carbon::parse($this->paymentDate)->format('m');
        $paymentYear = Carbon::parse($this->paymentDate)->format('Y');

        try {
            SalaryPayment::create([
                'staff_id' => $this->staffForPayment->id,
                'payroll_id' => $this->selectedPayrollIdForPayment, // Payroll ID ni bog'lash
                'amount' => $this->paymentAmount,
                'payment_date' => $this->paymentDate,
                'comment' => $this->paymentComment,
                'month' => $paymentMonth, // To'lov qilingan oy
                'year' => $paymentYear,   // To'lov qilingan yil
                // Agar kerak bo'lsa, boshqa maydonlar (masalan, to'lov usuli)
            ]);

            Notification::make()->success()->title('To\'lov muvaffaqiyatli qo\'shildi!')->send();
            $this->closePaymentModal();
            // Jadvalni yangilash uchun
            $this->resetPage();

        } catch (\Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('To\'lovni saqlashda xatolik: ' . $e->getMessage())->send();
        }
    }
}
