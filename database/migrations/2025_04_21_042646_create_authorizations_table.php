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
        Schema::create('authorizations', function (Blueprint $table) {
            $table->id();
            $table->string('login')->unique(); // Login noyob bo'lishi kerak
            $table->string('password');       // HASH qilingan parol uchun
            // Polimorfik munosabat uchun ustunlar:
            $table->unsignedBigInteger('authenticatable_id'); // Student yoki Parent ID si
            $table->string('authenticatable_type'); // Model nomi (masalan, App\Models\Student)
            $table->timestamps();

            // ID va Type bo'yicha indeks qo'shish qidiruvni tezlashtiradi
            $table->index(['authenticatable_id', 'authenticatable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authorizations');
    }
};
