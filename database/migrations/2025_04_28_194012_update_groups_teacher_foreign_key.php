<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            // Eski foreign key'ni o'chiramiz
            $table->dropForeign(['teacher_id']);
            
            // Yangi foreign key qo'shamiz
            $table->foreign('teacher_id')
                ->references('id')
                ->on('staff')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            // Eski holatga qaytarish
            $table->dropForeign(['teacher_id']);
            
            $table->foreign('teacher_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }
};