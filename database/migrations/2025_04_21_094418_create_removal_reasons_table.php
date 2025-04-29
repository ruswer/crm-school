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
        Schema::create('removal_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sabab nomi (masalan, "Qimmat")
            $table->string('slug')->unique()->nullable(); // Programmatik murojaat uchun (ixtiyoriy)
            $table->text('description')->nullable(); // Qo'shimcha tavsif (ixtiyoriy)
            $table->boolean('is_active')->default(true); // Aktiv/noaktiv qilish uchun
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('removal_reasons');
    }
};
