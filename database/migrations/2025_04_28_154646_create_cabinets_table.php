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
        Schema::create('cabinets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Kabinet nomi yoki raqami (unikal)
            // Kerak bo'lsa boshqa ustunlar qo'shilishi mumkin (masalan, sig'imi, filial IDsi)
            // $table->integer('capacity')->nullable();
            // $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            // $table->softDeletes(); // Agar arxivlash kerak bo'lsa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabinets');
    }
};
