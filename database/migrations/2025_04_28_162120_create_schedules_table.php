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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            // Filial (agar kerak bo'lsa va guruh/o'qituvchi/kabinetdan aniqlanmasa)
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
            // Guruh
            $table->foreignId('group_id')->constrained()->cascadeOnDelete(); // Guruh o'chirilganda jadval ham o'chiriladi
            // O'qituvchi (odatda users jadvaliga bog'lanadi)
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete(); // O'qituvchi (user) o'chirilganda jadval ham o'chiriladi
            // Kabinet
            $table->foreignId('cabinet_id')->constrained()->cascadeOnDelete(); // Kabinet o'chirilganda jadval ham o'chiriladi
            // Hafta kuni (Masalan: 1 - Dushanba, 2 - Seshanba, ..., 7 - Yakshanba)
            $table->unsignedTinyInteger('day_of_week');
            // Dars boshlanish vaqti
            $table->time('start_time');
            // Dars tugash vaqti
            $table->time('end_time');
            // Dars haqida qo'shimcha izoh (ixtiyoriy)
            $table->text('notes')->nullable();
            $table->timestamps();

            // Bir vaqtda bitta xonada faqat bitta dars bo'lishini ta'minlash (ixtiyoriy, lekin tavsiya etiladi)
            // $table->unique(['cabinet_id', 'day_of_week', 'start_time']);
            // Bir vaqtda bitta o'qituvchi faqat bitta dars o'tishini ta'minlash (ixtiyoriy, lekin tavsiya etiladi)
            // $table->unique(['teacher_id', 'day_of_week', 'start_time']);
            // Bir vaqtda bitta guruh faqat bitta darsda bo'lishini ta'minlash (ixtiyoriy, lekin tavsiya etiladi)
            // $table->unique(['group_id', 'day_of_week', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
