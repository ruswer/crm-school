<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete(); // staff jadvaliga bog'lanish
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'not_working'])->default('not_working'); // Davomat holati
            $table->text('comment')->nullable(); // Izoh
            $table->timestamps();

            // Bir xodim uchun bir kunda faqat bitta yozuv bo'lishini ta'minlash
            $table->unique(['staff_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
