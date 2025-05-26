<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Models\Group;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentStatus;
use App\Models\User;
use App\Models\BillingSetting; // <-- Import BillingSetting model
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class PaymentPage extends Page
{
    use WithPagination;

    // Navigatsiya sozlamalari
    protected static ?string $navigationGroup = 'To\'lovlar';
    protected static ?string $navigationLabel = 'To\'lovlar Ro\'yxati';
    protected static ?string $title = 'To\'lovlar Ro\'yxati';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.payments.payment-page';

    // --- Livewire Xususiyatlari ---

    // Filtrlar uchun
    public $search = '';
    public $selectedBranch = '';
    public $selectedGroup = '';
    public $selectedMonth = '';
    public $selectedYear = '';

    // Saralash uchun
    public $sortField = 'payment_date';
    public $sortDirection = 'desc';

    // Filtr optionlari uchun
    public $branches = [];
    public $groups = [];
    public $years = [];
    public $months = [];

    // --- Yangi To'lov Modali uchun Xususiyatlar ---
    public bool $showCreatePaymentModal = false;
    // Modal form maydonlari
    public $modal_branch_id = null;
    public $modal_group_id = null;
    public $modal_student_id = null;
    public $amount = null; // Asosiy summa uchun
    public $payment_date = null;
    public $payment_method = null;
    public $reference = '';
    public $notes = '';

    // --- Discount Properties (YANGI) ---
    public $modal_discount_points = null; // Ballar uchun input
    public float $modal_calculated_discount = 0.00; // Hisoblangan chegirma
    public float $modal_payable_amount = 0.00; // Yakuniy to'lov summasi
    public float $pointsDiscountRate = 1000.00; // Ball stavkasi (standart)
    // --- End Discount Properties ---

    // Modal dropdownlari uchun optionlar
    public $modal_branches = [];
    public $modal_groups_options = []; // Guruhlar uchun
    public $modal_students_options = []; // O'quvchilar uchun

    // Query String
    protected $queryString = [
        'search' => ['except' => ''],
        'selectedBranch' => ['except' => ''],
        'selectedGroup' => ['except' => ''],
        'selectedMonth' => ['except' => ''],
        'selectedYear' => ['except' => ''],
        'sortField' => ['except' => 'payment_date'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    // --- Hayot Sikli Metodlari ---

    public function mount(): void
    {
        $this->loadFilterOptions();
        // $this->loadStudentsForSelect(); // No longer needed if loading per group
        $this->payment_date = now()->format('Y-m-d'); // Standart sanani o'rnatish
        $this->loadPointsDiscountRate(); // <-- Ball stavkasini yuklash
        $this->calculatePayableAmount(); // Initial calculation
    }

    protected function loadFilterOptions(): void
    {
        $this->branches = Branch::where('status', 'active')->get(['id', 'name']);
        $this->groups = Group::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        $currentYear = now()->year;
        $this->years = range($currentYear + 1, $currentYear - 4);
        $this->months = [
            1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
            5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
            9 => 'Sentyabr', 10 => 'Oktyabr', 11 => 'Noyabr', 12 => 'Dekabr',
        ];
        if (empty($this->selectedYear) && !request()->has('selectedYear')) {
            $this->selectedYear = $currentYear;
        }
    }

    // Modal select uchun filiallarni yuklash
    protected function loadBranchesForModal(): void
    {
        $this->modal_branches = Branch::where('status', 'active')->get(['id', 'name']);
    }

    // Ball stavkasini sozlamalardan yuklash
    protected function loadPointsDiscountRate(): void
    {
        $billingSetting = BillingSetting::first();
        if ($billingSetting && isset($billingSetting->points_discount_rate)) {
            $this->pointsDiscountRate = (float)$billingSetting->points_discount_rate;
        }
        // Fallback if setting not found
        // else { $this->pointsDiscountRate = 1000.00; }
    }

    // --- Amallar ---

    // --- Yangi To'lov Modali Uchun Metodlar ---
    public function openCreatePaymentModal(): void
    {
        $this->resetCreatePaymentForm(); // Formani tozalash
        $this->loadBranchesForModal(); // Modal ochilganda filiallarni yuklash
        $this->showCreatePaymentModal = true;
    }

    protected function resetCreatePaymentForm(): void
    {
        $this->modal_branch_id = null;
        $this->modal_group_id = null;
        $this->modal_student_id = null;
        $this->amount = null;
        $this->payment_date = now()->format('Y-m-d');
        $this->payment_method = null;
        $this->reference = '';
        $this->notes = '';
        // Reset discount fields (YANGI)
        $this->modal_discount_points = null;
        $this->modal_calculated_discount = 0.00;
        $this->modal_payable_amount = 0.00;
        // Modal optionlarini tozalash
        $this->modal_groups_options = [];
        $this->modal_students_options = [];
        $this->resetErrorBag();
    }

    /**
     * Modal ochilganda filialni tanlanganda guruhlarni yuklaydi (wire:model.live uchun).
     */
    public function updatedModalBranchId($branchId): void
    {
        $this->modal_group_id = null; // Guruh tanlovini tozalash
        $this->modal_student_id = null; // O'quvchi tanlovini tozalash
        $this->modal_groups_options = [];
        $this->modal_students_options = [];

        if ($branchId) {
            $this->modal_groups_options = Group::where('branch_id', $branchId)
                                            ->where('status', 'active')
                                            ->orderBy('name')
                                            ->pluck('name', 'id')
                                            ->toArray();
        }
    }

    /**
     * Modal ochilganda guruhni tanlanganda o'quvchilarni yuklaydi (wire:model.live uchun).
     */
    public function updatedModalGroupId($groupId): void
    {
        $this->modal_student_id = null; // O'quvchi tanlovini tozalash
        $this->modal_students_options = [];

        if ($groupId) {
            // Guruhga tegishli BARCHA o'quvchilarni olish (statusidan qat'iy nazar)
            $this->modal_students_options = Student::whereHas('groups', function ($query) use ($groupId) {
                                                    $query->where('groups.id', $groupId);
                                                })
                                                // Agar filial bo'yicha ham filtr kerak bo'lsa (ixtiyoriy):
                                                // ->where('branch_id', $this->modal_branch_id)
                                                ->orderBy('first_name')->orderBy('last_name')
                                                ->get(['id', 'first_name', 'last_name', 'phone']);
        }
    }

    // --- Dynamic Calculation Logic (YANGI) ---

    // Calculate discount and payable amount
    protected function calculatePayableAmount(): void
    {
        $amount = is_numeric($this->amount) ? (float)$this->amount : 0;
        $points = is_numeric($this->modal_discount_points) ? (float)$this->modal_discount_points : 0;

        // Validate points (max 6, non-negative)
        if ($points < 0) {
            $points = 0;
            // Optionally add immediate error feedback
            // $this->addError('modal_discount_points', 'Ball manfiy bo\'lishi mumkin emas.');
        } elseif ($points > 6) {
            $points = 6;
            // Optionally add immediate error feedback
            // $this->addError('modal_discount_points', 'Ball 6 dan oshmasligi kerak.');
        }

        // Calculate discount
        $this->modal_calculated_discount = $points * $this->pointsDiscountRate;

        // Ensure discount doesn't exceed amount
        if ($this->modal_calculated_discount > $amount) {
            $this->modal_calculated_discount = $amount;
            // Recalculate points used if discount capped by amount
            // $points = $this->pointsDiscountRate > 0 ? floor($this->modal_calculated_discount / $this->pointsDiscountRate) : 0;
            // $this->modal_discount_points = $points; // Update input if needed
        }

        // Calculate final payable amount
        $this->modal_payable_amount = max(0, $amount - $this->modal_calculated_discount); // Ensure non-negative
    }

    // Recalculate when amount changes
    public function updatedAmount($value): void
    {
        $this->calculatePayableAmount();
    }

    // Recalculate when discount points change
    public function updatedModalDiscountPoints($value): void
    {
        // Basic validation before calculation
        if (!is_numeric($value) && $value !== null && $value !== '') {
             $this->addError('modal_discount_points', 'Faqat son kiriting.');
             $this->modal_calculated_discount = 0;
             $this->modal_payable_amount = is_numeric($this->amount) ? (float)$this->amount : 0;
             return;
        }
         // Clear error if input becomes valid
        $this->resetErrorBag('modal_discount_points');
        $this->calculatePayableAmount();
    }

    // --- End Dynamic Calculation Logic ---

    /**
     * Yangi to'lovni validatsiya qiladi va saqlaydi.
     */
    public function savePayment(): void
    {
        // Recalculate just before validation to ensure consistency
        $this->calculatePayableAmount();

        // Add modal_discount_points to validation rules
        $rules = [
            'modal_student_id' => ['required', 'integer', 'exists:students,id'],
            'modal_group_id' => [
                'nullable', 'integer',
                Rule::exists('groups', 'id')->where(function ($query) {
                    if ($this->modal_student_id) {
                        $query->whereExists(function ($subQuery) {
                            $subQuery->select(DB::raw(1))
                                     ->from('student_groups')
                                     ->whereColumn('student_groups.group_id', 'groups.id')
                                     ->where('student_groups.student_id', $this->modal_student_id);
                        });
                    }
                })
            ],
            'amount' => ['required', 'numeric', 'min:0'],
            'modal_discount_points' => ['nullable', 'numeric', 'min:0', 'max:6'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:50'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];

        // Validate form data including discount points
        $validatedData = $this->validate($rules);

        try {
            $student = Student::find($validatedData['modal_student_id']);
            if (!$student) {
                throw new \Exception("O'quvchi topilmadi.");
            }

            // --- Prepare Payment Data with Discount ---
            $paymentData = [
                'user_id' => Auth::id(),
                'student_id' => $validatedData['modal_student_id'],
                'group_id' => $validatedData['modal_group_id'] ?? null,
                'branch_id' => $student->branch_id,
                'amount' => (float)$validatedData['amount'],
                'discount_amount' => $this->modal_calculated_discount,
                'payment_date' => $validatedData['payment_date'],
                'payment_method' => $validatedData['payment_method'],
                'reference' => $validatedData['reference'],
                'notes' => $validatedData['notes'],
            ];

            // Points used for the discount
            $pointsUsed = is_numeric($validatedData['modal_discount_points']) ? (float)$validatedData['modal_discount_points'] : 0;
            // If discount was capped by amount, recalculate points used based on actual discount
             if ($this->modal_calculated_discount < ($pointsUsed * $this->pointsDiscountRate) && $this->pointsDiscountRate > 0) {
                 // Use floor to avoid deducting partial points if rate isn't a perfect divisor
                 $pointsUsed = floor($this->modal_calculated_discount / $this->pointsDiscountRate);
             }


            // --- Transaction ---
            DB::transaction(function () use ($student, $paymentData, $pointsUsed) {
                // Create Payment
                $payment = Payment::create($paymentData);

                // Deduct points from student (YANGI) - Check if student has 'points' attribute
                if ($pointsUsed > 0 && property_exists($student, 'points')) {
                    $student->points = max(0, (float)$student->points - $pointsUsed);
                    $student->save();
                } elseif ($pointsUsed > 0) {
                     Log::warning("Student modelida 'points' atributi topilmadi (ID: {$student->id}). Ballar kamaytirilmaydi.");
                }


                // Update Debt (Use payable amount)
                if (isset($paymentData['group_id']) && $paymentData['group_id'] !== null) {
                    // Pass the actual paid amount ($modal_payable_amount) to update debt
                    $this->updateStudentDebt(
                        $paymentData['student_id'],
                        $paymentData['group_id'],
                        $this->modal_payable_amount // <-- Yakuniy summa bilan qarzni yangilash
                    );
                }
            });
            // --- End Transaction ---

            Notification::make()
                ->title('Muvaffaqiyatli saqlandi')
                ->success()
                ->send();

            $this->showCreatePaymentModal = false; // Close modal

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Errors are shown automatically by Livewire/Filament
            Log::warning('To\'lov validatsiyasida xatolik: ', $e->errors());
            // Notification::make()->title('Validatsiya xatosi')... // Optional explicit notification
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik')
                ->body('To\'lovni saqlashda xatolik yuz berdi: ' . $e->getMessage())
                ->danger()
                ->send();
            Log::error('To\'lov saqlashda xatolik: ' . $e->getMessage(), [
                'data' => $validatedData ?? 'Validatsiyadan o\'tmadi',
                'trace' => $e->getTraceAsString()
                ]);
        }
    }

    // --- End Yangi To'lov Modali Uchun Metodlar ---

    // --- Boshqa Metodlar ---
    public function filterPayments(): void
    {
        $this->resetPage();
    }

    public function sort($field): void
    {
        // Haqiqiy saralash maydonini aniqlash
        $sortableField = match ($field) {
            'student_name' => 'students.first_name', // JOIN uchun
            'group_name' => 'groups.name',         // JOIN uchun
            'user_name' => 'users.name',           // JOIN uchun (agar user bo'yicha saralash kerak bo'lsa)
            // Ensure 'discount' refers to the correct table column
            'discount' => 'payments.discount_amount', // Assuming discount is stored in payments table
            default => $field,
        };

        if ($this->sortField === $sortableField) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Agar yangi maydon tanlansa, haqiqiy ustun nomini saqlash
            $this->sortField = $sortableField;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'selectedBranch', 'selectedGroup', 'selectedMonth', 'selectedYear');
        // Standart yilni qayta o'rnatish
        $this->selectedYear = now()->year;
        $this->resetPage();
    }

    // --- Ma'lumotlarni Olish Uchun Computed Property ---
    public function getPaymentsProperty(): LengthAwarePaginator
    {
        try {
            $query = Payment::query()
                ->with([
                    'student:id,first_name,last_name,phone,branch_id',
                    'group:id,name',
                    'user:id,name', // Agar user bo'yicha saralash yoki ko'rsatish kerak bo'lsa
                ]);

            // Filial bo'yicha filtr
            $query->when($this->selectedBranch, function (Builder $query, $branchId) {
                 $query->whereHas('student', fn(Builder $q) => $q->where('branch_id', $branchId));
            });

            // Guruh bo'yicha filtr
            $query->when($this->selectedGroup, function (Builder $query, $groupId) {
                $query->where('group_id', $groupId);
            });

            // Oy bo'yicha filtr
            $query->when($this->selectedMonth, function (Builder $query, $month) {
                $query->whereMonth('payment_date', $month);
            });

            // Yil bo'yicha filtr
            $query->when($this->selectedYear, function (Builder $query, $year) {
                $query->whereYear('payment_date', $year);
            });

            // Qidiruv
            $query->when($this->search, function (Builder $query, $searchTerm) {
                $searchTerm = '%' . $searchTerm . '%';
                $query->where(function (Builder $subQuery) use ($searchTerm) {
                    // Search by payment ID
                    if (is_numeric(str_replace('%', '', $searchTerm))) {
                         $subQuery->orWhere('payments.id', (int)str_replace('%', '', $searchTerm));
                    }
                    // Search by student details
                    $subQuery->orWhereHas('student', function (Builder $q) use ($searchTerm) {
                        $q->where(function (Builder $subQ) use ($searchTerm) {
                            $subQ->where('first_name', 'like', $searchTerm)
                                 ->orWhere('last_name', 'like', $searchTerm)
                                 ->orWhere('phone', 'like', $searchTerm);
                        });
                    });
                    // Search by group name
                    $subQuery->orWhereHas('group', function (Builder $q) use ($searchTerm) {
                        $q->where('name', 'like', $searchTerm);
                    });
                    // Search by reference
                    $subQuery->orWhere('payments.reference', 'like', $searchTerm);
                });
            });


            // Saralash
            $sortField = $this->sortField;
            $sortDirection = $this->sortDirection;

            // Munosabatlar uchun JOIN
            $relationsToJoin = [
                'students.first_name' => ['students', 'payments.student_id', '=', 'students.id'],
                'groups.name' => ['groups', 'payments.group_id', '=', 'groups.id', 'leftJoin'], // leftJoin
                'users.name' => ['users', 'payments.user_id', '=', 'users.id', 'leftJoin'],     // leftJoin
            ];

            // Check if sorting by a relationship field
            if (array_key_exists($sortField, $relationsToJoin)) {
                [$relationTable, $localKey, $operator, $foreignKey, $joinType] = array_pad($relationsToJoin[$sortField], 5, null);
                $joinMethod = $joinType ?? 'join'; // Default to 'join'

                // Ensure JOIN is added only once
                if (!collect($query->getQuery()->joins)->pluck('table')->contains($relationTable)) {
                    $query->$joinMethod($relationTable, $localKey, $operator, $foreignKey);
                }
                // Explicitly select columns to avoid ambiguity, especially after joins
                $query->select('payments.*')->orderBy($sortField, $sortDirection);
                // Add secondary sort for name
                if ($sortField === 'students.first_name') {
                    $query->orderBy('students.last_name', $sortDirection);
                }
            }
            // Check if sorting by a column directly on the payments table
            elseif (Schema::hasColumn('payments', $sortField)) {
                 $query->orderBy($sortField, $sortDirection);
            }
            // Default sort if the field is not recognized or not sortable this way
            else {
                $query->orderBy('payments.payment_date', 'desc'); // Default sort on payments table
            }


            // Natijani olish
            $payments = $query->paginate(15);

            if ($payments === null) {
                 Log::error('getPaymentsProperty paginate() dan null qaytardi!');
                 return new LengthAwarePaginator([], 0, 15);
            }

            return $payments;

        } catch (\Exception $e) {
            Log::error('getPaymentsProperty ichida xatolik: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            Notification::make()
                ->title('Xatolik')
                ->body('Ma\'lumotlarni yuklashda xatolik yuz berdi.')
                ->danger()
                ->send();
            return new LengthAwarePaginator([], 0, 15);
        }
    }

    /**
     * Qarzdorlikni yangilash uchun metod (student_groups jadvali uchun).
     */
    protected function updateStudentDebt($studentId, $groupId, $paidAmount)
    {
        try {
            $studentGroup = DB::table('student_groups')
                              ->where('student_id', $studentId)
                              ->where('group_id', $groupId)
                              ->first();

            // Check if the record and 'debt' column exist
            if ($studentGroup && Schema::hasColumn('student_groups', 'debt')) {
                // Ensure paidAmount is float
                $paidAmountFloat = (float)$paidAmount;
                // Calculate new debt, ensuring it doesn't go below zero
                $newDebt = max(0, (float)$studentGroup->debt - $paidAmountFloat);
                DB::table('student_groups')
                    ->where('student_id', $studentId)
                    ->where('group_id', $groupId)
                    ->update(['debt' => $newDebt]);
            } else {
                 Log::warning("Qarzdorlikni yangilab bo'lmadi (yozuv yoki 'debt' ustuni topilmadi): Student ID: {$studentId}, Group ID: {$groupId}");
            }
        } catch (\Exception $e) {
             Log::error("Qarzdorlikni yangilashda xatolik: Student ID: {$studentId}, Group ID: {$groupId}, Xato: " . $e->getMessage());
        }
    }

    // --- Qo'shimcha Metodlar (Jadval Amallari Uchun) ---
    // Bu yerga kelajakda jadval amallari uchun metodlar qo'shilishi mumkin
}
