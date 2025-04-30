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
        Schema::table('staff', function (Blueprint $table) {
            // Yangi position_id ustunini qo'shish (masalan, last_name dan keyin)
            // Agar boshqa ustunlardan keyin qo'shmoqchi bo'lsangiz 'after' ni o'zgartiring
            $table->foreignId('position_id')
                  ->nullable() // Agar lavozim bo'sh bo'lishi mumkin bo'lsa
                  ->after('last_name') // Joylashuvini belgilash
                  ->constrained('positions') // 'positions' jadvaliga bog'lash
                  ->onDelete('set null'); // Agar lavozim o'chirilsa, staff.position_id ni NULL qilish

            // Eski 'position' (varchar) ustunini o'chirish
            $table->dropColumn('position');
        });
    }

    /**
     * Reverse the migrations.
     * (Migratsiyani orqaga qaytarganda eski holatni tiklash)
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            // Eski 'position' ustunini qayta qo'shish
            $table->string('position')->after('last_name'); // Eski joyiga qaytarish

            // Foreign key cheklovini o'chirish
            // Agar DB driver (masalan, SQLite ning eski versiyalari) dropForeign ni qo'llab-quvvatlamasa,
            // bu qatorni kommentga olish yoki shartli ravishda bajarish kerak bo'lishi mumkin.
            $table->dropForeign(['position_id']);

            // Yangi 'position_id' ustunini o'chirish
            $table->dropColumn('position_id');
        });
    }
};
