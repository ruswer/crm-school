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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete(); // Filialga bog'lash
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();   // Guruhga bog'lash
            $table->string('name')->nullable(); // Imtihon nomi yoki fani
            $table->string('type')->nullable(); // Imtihon turi (yoki foreignId)
            $table->date('exam_date');         // Imtihon sanasi
            $table->string('exam_time')->nullable(); // Imtihon vaqti (masalan, "10:00" yoki "14:00-16:00")
            $table->integer('duration')->nullable(); // Davomiyligi (daqiqalarda)
            $table->string('location')->nullable(); // O'tkazilish joyi
            $table->text('description')->nullable(); // Izoh
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled'); // Holati
            $table->decimal('max_score', 8, 2)->nullable(); // Maksimal ball
            $table->decimal('passing_score', 8, 2)->nullable(); // O'tish bali
            $table->timestamp('results_published_at')->nullable(); // Natijalar e'lon qilingan vaqt
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); // Kim yaratganligi
            $table->timestamps(); // created_at va updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
