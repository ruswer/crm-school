<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('payroll_id')->nullable()->constrained('payrolls')->nullOnDelete(); // Qaysi hisob-kitobga tegishli
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('month', 2)->comment('To\'lov qaysi oy uchun');
            $table->string('year', 4)->comment('To\'lov qaysi yil uchun');
            $table->string('payment_method')->nullable()->comment('To\'lov usuli');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index(['staff_id', 'month', 'year']); // Tez qidirish uchun indeks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};