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
        Schema::create('positions', function (Blueprint $table) {
            $table->id(); // Avtomatik o'suvchi ID
            $table->string('name')->unique(); // Lavozim nomi (noyob bo'lishi kerak)
            // Agar qo'shimcha ma'lumotlar kerak bo'lsa (masalan, tavsif)
            // $table->text('description')->nullable();
            $table->timestamps(); // created_at va updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
