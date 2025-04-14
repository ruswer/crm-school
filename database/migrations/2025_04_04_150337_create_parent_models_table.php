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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ota-ona ismi
            $table->string('phone')->nullable(); // Telefon raqami
            $table->string('email')->nullable(); // Email
            $table->foreignId('student_id')->constrained()->cascadeOnDelete(); // Talaba bilan bog'lanish
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
