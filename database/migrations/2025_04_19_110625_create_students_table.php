<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/xxxx_xx_xx_create_students_table.php
public function up()
{
    // database/migrations/xxxx_xx_xx_create_students_table.php
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->string('passport_number')->unique();
        $table->foreignId('branch_id')->constrained();
        $table->foreignId('group_id')->nullable()->constrained();
        $table->string('first_name');
        $table->string('last_name');
        $table->enum('gender', ['male', 'female']);
        $table->date('birth_date');
        $table->string('phone');
        $table->string('email')->nullable();
        $table->foreignId('status_id')->constrained('student_statuses');
        $table->foreignId('knowledge_level_id')->constrained('knowledge_levels');
        $table->text('notes')->nullable();
        $table->foreignId('trial_teacher_id')->nullable()->constrained('staff');
        $table->timestamp('trial_called_at')->nullable();
        $table->timestamp('trial_attended_at')->nullable();
        $table->foreignId('marketing_source_id')->constrained('marketing_sources');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
