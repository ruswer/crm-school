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
        Schema::table('student_groups', function (Blueprint $table) {
            // Mavjud ustunlardan keyin qo'shish (masalan, debt dan keyin)
            $table->date('join_date')->nullable()->after('debt');
            $table->date('leave_date')->nullable()->after('join_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_groups', function (Blueprint $table) {
            $table->dropColumn(['join_date', 'leave_date']);
        });
    }
};
