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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete(); // O'quvchiga bog'lash
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete(); // Guruhga bog'lash (ixtiyoriy)
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete(); // Filialga bog'lash (ixtiyoriy, studentdan olsa ham bo'ladi)
            $table->decimal('amount', 15, 2); // To'lov summasi (kattaroq qiymatlar uchun)
            $table->date('payment_date'); // To'lov sanasi
            $table->string('payment_method')->nullable(); // To'lov usuli (naqd, karta, click, payme, bank)
            $table->string('reference')->nullable(); // O'tkazma/chek raqami yoki izoh
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Qabul qilgan xodim (admin/kassir)
            $table->text('notes')->nullable(); // Qo'shimcha izohlar
            $table->timestamps(); // created_at va updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
