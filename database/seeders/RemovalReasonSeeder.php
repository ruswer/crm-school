<?php

namespace Database\Seeders;

use App\Models\RemovalReason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // Str ni import qilish

class RemovalReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            'Qiyin',
            'Qimmat',
            'Joylashuv noqulay',
            'Darsdan qoniqmadi',
            'Sababi noma\'lum', // Apostrofni to'g'ri yozish
            'Bitirdi',
            // Kerak bo'lsa boshqa sabablarni qo'shing
        ];

        foreach ($reasons as $reasonName) {
            RemovalReason::firstOrCreate(
                ['slug' => Str::slug($reasonName)], // Noyobligini tekshirish uchun slug
                ['name' => $reasonName]
            );
        }
    }
}
