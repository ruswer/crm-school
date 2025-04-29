<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BillingSetting; // Modelni import qilish

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('billing_settings', function (Blueprint $table) {
            $table->id(); // Har ehtimolga qarshi ID
            // Asosiy sozlamalar uchun ustunlar
            $table->decimal('points_discount_rate', 15, 2)->default(1000.00); // Ball chegirma stavkasi
            // --- Kelajakda boshqa billing sozlamalari uchun ustunlar shu yerga qo'shiladi ---
            // Masalan: $table->boolean('enable_auto_billing')->default(false);
            $table->timestamps(); // created_at va updated_at
        });

        // Jadval yaratilgandan so'ng, boshlang'ich sozlamalar bilan bitta qator qo'shish
        BillingSetting::create([
            'points_discount_rate' => 1000.00, // Standart qiymat
            // Boshqa standart qiymatlar...
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_settings');
    }
};
