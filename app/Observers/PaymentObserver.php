<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Tranzaksiya uchun

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function created(Payment $payment): void
    {
        // Agar to'lov summasi 0 dan katta bo'lsa, invoyslarga taqsimlash
        if ($payment->amount > 0) {
            $this->allocatePaymentToInvoices($payment);
        }
    }

    /**
     * Allocate the payment amount to the student's unpaid invoices.
     *
     * @param Payment $payment
     */
    protected function allocatePaymentToInvoices(Payment $payment): void
    {
        // To'lov summasini float sifatida olish
        $paymentAmountToAllocate = (float) $payment->amount;
        $studentId = $payment->student_id;

        Log::info("Allocating payment ID: {$payment->id} for student ID: {$studentId}. Amount: {$paymentAmountToAllocate}");

        // Studentning to'lanmagan yoki qisman to'langan invoyslarini olish (eng eskisi birinchi)
        $unpaidInvoices = Invoice::where('student_id', $studentId)
                                ->whereIn('status', [Invoice::STATUS_PENDING, Invoice::STATUS_PARTIAL, Invoice::STATUS_OVERDUE])
                                ->orderBy('due_date', 'asc') // Eng eski due_date birinchi
                                ->orderBy('created_at', 'asc') // Agar due_date bir xil bo'lsa
                                ->get();

        if ($unpaidInvoices->isEmpty()) {
            Log::info("No unpaid invoices found for student ID: {$studentId}. Payment allocation skipped.");
            // Bu yerda ortiqcha to'lovni (credit) saqlash logikasini qo'shish mumkin
            return;
        }

        DB::beginTransaction(); // Xavfsizlik uchun tranzaksiyani boshlash
        try {
            foreach ($unpaidInvoices as $invoice) {
                if ($paymentAmountToAllocate <= 0) {
                    break; // Agar taqsimlanadigan summa qolmasa, tsikldan chiqish
                }

                $invoiceRemaining = $invoice->remaining_amount; // Model accessoridan foydalanish

                if ($invoiceRemaining <= 0) {
                    continue; // Agar invoys allaqachon to'langan bo'lsa (ehtimoldan yiroq, lekin tekshirish)
                }

                // Bu invoysga qancha to'lash mumkinligini aniqlash
                $amountToApply = min($paymentAmountToAllocate, $invoiceRemaining);

                // Invoysni yangilash
                $invoice->amount_paid = (float) $invoice->amount_paid + $amountToApply;

                // Yangi statusni aniqlash
                if ($invoice->isPaid()) { // Accessordan foydalanish
                    $invoice->status = Invoice::STATUS_PAID;
                } else {
                    $invoice->status = Invoice::STATUS_PARTIAL;
                }

                $invoice->save(); // O'zgarishlarni saqlash

                // Taqsimlanadigan summani kamaytirish
                $paymentAmountToAllocate -= $amountToApply;

                Log::info("Applied {$amountToApply} to invoice ID: {$invoice->id}. Remaining payment amount: {$paymentAmountToAllocate}. Invoice status: {$invoice->status}");
            }

            DB::commit(); // Tranzaksiyani muvaffaqiyatli yakunlash

            if ($paymentAmountToAllocate > 0) {
                Log::warning("Payment ID: {$payment->id} has an overpayment of {$paymentAmountToAllocate} for student ID: {$studentId}.");
                // Bu yerda ortiqcha to'lovni (credit) saqlash logikasini qo'shish mumkin
                // Masalan, student modeliga 'credit_balance' ustuni qo'shib, uni yangilash
                // $student = $payment->student;
                // $student->credit_balance = (float)($student->credit_balance ?? 0) + $paymentAmountToAllocate;
                // $student->save();
            }

        } catch (\Exception $e) {
            DB::rollBack(); // Xatolik yuz bersa, tranzaksiyani bekor qilish
            Log::error("Error allocating payment ID: {$payment->id} to invoices for student ID: {$studentId}. Error: " . $e->getMessage());
            // Bu yerda adminni xabardor qilish uchun qo'shimcha logika qo'shish mumkin
        }
    }

    /**
     * Handle the Payment "updated" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function updated(Payment $payment): void
    {
        // Agar to'lov summasi o'zgartirilsa, qayta taqsimlash kerak bo'lishi mumkin
        // Bu logika murakkabroq, chunki avvalgi taqsimotni bekor qilish kerak
        // Hozircha bu qismni implementatsiya qilmaymiz
        Log::warning("Payment ID: {$payment->id} was updated. Invoice allocation might need manual review or recalculation.");
    }

    /**
     * Handle the Payment "deleted" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function deleted(Payment $payment): void
    {
        // Agar to'lov o'chirilsa, invoyslardagi summani qaytarish kerak
        // Bu logika ham murakkabroq
        // Hozircha bu qismni implementatsiya qilmaymiz
        Log::warning("Payment ID: {$payment->id} was deleted. Invoice amounts might need manual adjustment.");
    }
}
