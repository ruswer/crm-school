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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('month', 2); // '01', '02', ..., '12'
            $table->string('year', 4); // '2024', '2025', ...
            $table->decimal('calculated_salary', 15, 2)->nullable()->comment('Hisoblangan maosh');
            $table->decimal('bonuses', 15, 2)->default(0)->comment('Bonuslar');
            $table->decimal('deductions', 15, 2)->default(0)->comment('Ushlanmalar');
            $table->decimal('final_salary', 15, 2)->default(0)->comment('Yakuniy maosh');
            $table->timestamps();

            // Bir xodim uchun bir oyda faqat bitta yozuv bo'lishini ta'minlash
            $table->unique(['staff_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};