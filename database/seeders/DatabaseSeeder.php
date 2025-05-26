<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Boshqa seederlarni chaqirish
        $this->call([
            StudentSettingsSeeder::class,
            InitialDataSeeder::class,
            GroupSeeder::class,
            RemovalReasonSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}