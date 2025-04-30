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
            // Mavjud 'status' ustunidan keyin qo'shish
            $table->foreignId('branch_id')->nullable()->after('status')->constrained()->onDelete('set null');
            $table->foreignId('role_id')->nullable()->after('branch_id')->constrained()->onDelete('set null');
            $table->foreignId('department_id')->nullable()->after('role_id')->constrained()->onDelete('set null');
            $table->string('email')->nullable()->unique()->after('department_id');
            $table->string('phone')->nullable()->after('email');
            $table->softDeletes()->after('updated_at'); // SoftDeletes uchun deleted_at ustuni

            // Agar 'position' ustuni endi kerak bo'lmasa (role_id ishlatilsa)
            // $table->dropColumn('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            // Foreign keylarni o'chirish
            // Agar DB driver (masalan, SQLite ning eski versiyalari) dropForeign ni qo'llab-quvvatlamasa,
            // bu qatorlarni kommentga olish yoki shartli ravishda bajarish kerak bo'lishi mumkin.
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['role_id']);
            $table->dropForeign(['department_id']);

            // Ustunlarni o'chirish
            $table->dropColumn([
                'branch_id',
                'role_id',
                'department_id',
                'email',
                'phone',
                'deleted_at', // SoftDeletes ustunini o'chirish
            ]);

            // Agar 'position' ustuni 'up' metodida o'chirilgan bo'lsa, uni qayta qo'shish
            // $table->string('position')->after('last_name');
        });
    }
};
