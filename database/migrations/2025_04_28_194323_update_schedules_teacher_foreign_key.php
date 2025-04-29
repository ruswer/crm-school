<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Eski foreign key'ni o'chirish
            $table->dropForeign(['teacher_id']);
            
            // Yangi foreign key qo'shish
            $table->foreign('teacher_id')
                ->references('id')
                ->on('staff')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Eski holatga qaytarish
            $table->dropForeign(['teacher_id']);
            
            $table->foreign('teacher_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};