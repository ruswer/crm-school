<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\Invoice;
use App\Models\Group; // Guruh modelini import qilish
use Carbon\Carbon;
use Filament\Forms\Components\Builder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log; // Log yozish uchun

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // Buyruqni chaqirish nomi: php artisan invoices:generate
    protected $signature = 'invoices:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates monthly invoices for active students in active groups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting monthly invoice generation...');
        Log::info('Starting monthly invoice generation...');

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $billingPeriod = Carbon::now()->format('F Y'); // Masalan, "May 2024"
        // To'lov muddati (masalan, keyingi oyning 5-sanasi)
        $dueDate = Carbon::now()->addMonth()->day(5)->format('Y-m-d');

        /// Aktiv statusdagi studentlarni olish ('client' slug'i bo'yicha)
        $activeStudents = Student::whereHas('status', function (\Illuminate\Database\Eloquent\Builder $query) { // <-- Eloquent Builder to'liq yozildi
                                $query->where('slug', 'client'); // 'client' statusidagilarni aktiv deb hisoblash
                                })
                                ->whereHas('groups', function (\Illuminate\Database\Eloquent\Builder $query) { // Guruh statusini tekshirish
                                    $query->where('status', 'active'); // Yoki Group uchun mos status sharti
                                })
                                ->with(['groups' => function ($query) {
                                    $query->where('status', 'active') // Faqat aktiv guruhlarni yuklash
                                        ->withPivot('discount');
                                }])
                                ->get();

        if ($activeStudents->isEmpty()) {
            $this->info('No active students found in active groups. No invoices generated.');
            Log::info('No active students found in active groups. No invoices generated.');
            return 0; // Buyruq muvaffaqiyatli tugadi
        }

        $generatedCount = 0;
        $skippedCount = 0;

        foreach ($activeStudents as $student) {
            foreach ($student->groups as $group) {
                // Guruh narxini olish (bu yerda group modelida 'price' degan ustun bor deb faraz qilamiz)
                // Agar narx boshqa joyda saqlansa, o'sha yerdan olish kerak
                $groupPrice = $group->price ?? 0; // Agar narx bo'lmasa 0

                // Agar narx 0 bo'lsa, bu guruh uchun invoys yaratmaymiz
                if ($groupPrice <= 0) {
                    Log::warning("Skipping invoice for student {$student->id} in group {$group->id}: Group price is zero or not set.");
                    continue;
                }

                // O'quvchining shu guruhdagi chegirmasini olish (student_groups pivot jadvalidan)
                $discount = $student->groups->find($group->id)->pivot->discount ?? 0;

                // Yakuniy to'lanishi kerak bo'lgan summa
                $amountDue = max(0, $groupPrice - $discount); // Manfiy bo'lmasligi uchun

                // Joriy oy uchun bu student/guruh juftligiga invoys mavjudligini tekshirish
                $existingInvoice = Invoice::where('student_id', $student->id)
                                          ->where('group_id', $group->id)
                                          ->whereYear('created_at', $currentYear) // Yoki davrga qarab boshqa ustun
                                          ->whereMonth('created_at', $currentMonth) // Yoki davrga qarab boshqa ustun
                                          // Yoki period_description bo'yicha tekshirish:
                                          // ->where('period_description', $billingPeriod . " - Group: " . $group->name)
                                          ->exists();

                if ($existingInvoice) {
                    $skippedCount++;
                    Log::info("Invoice already exists for student {$student->id} in group {$group->id} for period {$billingPeriod}. Skipping.");
                    continue; // Agar mavjud bo'lsa, keyingisiga o'tish
                }

                // Yangi invoys yaratish
                try {
                    Invoice::create([
                        'student_id' => $student->id,
                        'group_id' => $group->id,
                        'period_description' => $billingPeriod . " - Group: " . $group->name,
                        'amount_due' => $amountDue,
                        'amount_paid' => 0, // Boshida 0
                        'due_date' => $dueDate,
                        'status' => Invoice::STATUS_PENDING, // Boshlang'ich status
                    ]);
                    $generatedCount++;
                    Log::info("Generated invoice for student {$student->id} in group {$group->id} for period {$billingPeriod}. Amount: {$amountDue}");
                } catch (\Exception $e) {
                     Log::error("Error generating invoice for student {$student->id} in group {$group->id}: " . $e->getMessage());
                     $this->error("Error generating invoice for student {$student->id} in group {$group->id}. Check logs.");
                }
            }
        }

        $this->info("Invoice generation finished. Generated: {$generatedCount}, Skipped (already exist): {$skippedCount}.");
        Log::info("Invoice generation finished. Generated: {$generatedCount}, Skipped (already exist): {$skippedCount}.");
        return 0; // Buyruq muvaffaqiyatli tugadi
    }
}
