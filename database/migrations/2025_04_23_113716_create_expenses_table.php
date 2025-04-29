<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('expense_categories')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name'); // Chiqim nomi
            $table->date('expense_date'); // Sana
            $table->decimal('amount', 15, 2); // Narxi
            $table->string('payment_type'); // To'lov shakli (naqd, karta, o'tkazma)
            $table->text('description')->nullable(); // Tavsifi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
