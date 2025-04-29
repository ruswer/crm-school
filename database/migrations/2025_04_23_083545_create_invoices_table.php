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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete(); // O'quvchiga bog'lanish
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete(); // Guruhga bog'lanish (ixtiyoriy bo'lishi mumkin)
            $table->string('period_description'); // Davr tavsifi (masalan, "Noyabr 2024 to'lovi")
            $table->decimal('amount_due', 15, 2); // To'lanishi kerak bo'lgan summa (chegirmadan keyin)
            $table->decimal('amount_paid', 15, 2)->default(0); // Haqiqatda to'langan summa
            $table->date('due_date'); // To'lov muddati
            $table->string('status')->default('pending'); // Holati: pending, paid, partial, overdue
            $table->text('notes')->nullable(); // Qo'shimcha izohlar
            $table->timestamps(); // created_at va updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
