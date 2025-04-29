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
        Schema::table('groups', function (Blueprint $table) {
            // Mavjud ustunlardan keyin qo'shish (masalan, 'status' dan keyin)

            // Agar bu ustunlar avvaldan yo'q bo'lsa:
            // $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null'); // Yoki cascade
            // $table->string('status')->default('waiting'); // Agar status avvaldan yo'q bo'lsa

            // Yangi ustunlar
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('knowledge_level_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null'); // 'users' jadvaliga bog'lash
            $table->string('teacher_salary_type')->nullable(); // Masalan: 'percentage', 'fixed', 'per_student'
            $table->decimal('teacher_salary_amount', 10, 2)->nullable(); // Maosh miqdori
            $table->unsignedTinyInteger('price_period_months')->nullable()->default(1); // Tarif davri (oy)
            $table->json('lesson_days')->nullable(); // Dars kunlari (JSON formatida)
            $table->decimal('total_price', 10, 2)->nullable(); // Kursning umumiy narxi
            $table->unsignedSmallInteger('lessons_count')->nullable(); // Darslar soni
            $table->time('lesson_start_time')->nullable(); // Dars boshlanish vaqti
            $table->time('lesson_end_time')->nullable(); // Dars tugash vaqti
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            // Ustunlarni o'chirish (foreign keylarni ham olib tashlash kerak)
            $table->dropForeign(['course_id']);
            $table->dropForeign(['knowledge_level_id']);
            $table->dropForeign(['teacher_id']);
            // $table->dropForeign(['cabinet_id']); // Agar qo'shilgan bo'lsa

            $table->dropColumn([
                'course_id',
                'knowledge_level_id',
                'teacher_id',
                'teacher_salary_type',
                'teacher_salary_amount',
                'price_period_months',
                'lesson_days',
                'total_price',
                'lessons_count',
                'lesson_start_time',
                'lesson_end_time',
                'deleted_at', // SoftDeletes ustunini o'chirish
            ]);
        });
    }
};
