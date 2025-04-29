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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade'); // Imtihon bilan bog'lash
            $table->foreignId('student_id')->constrained()->onDelete('cascade'); // O'quvchi bilan bog'lash
            $table->decimal('mark', 5, 2)->nullable(); // Baho (masalan, 100.00 gacha) yoki boshqa tur (integer, string)
            // $table->foreignId('marked_by')->nullable()->constrained('users')->onDelete('set null'); // Kim baholaganini saqlash
            $table->timestamps();

            // Bir o'quvchi bir imtihondan faqat bir marta baho olishi uchun unique constraint
            $table->unique(['exam_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
