<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Course;
use App\Models\Staff;
use App\Models\MarketingSource;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Filiallar
        Branch::create([
            'name' => 'Asosiy Filial',
            'address' => 'Toshkent sh., Chilonzor t., 1-uy',
            'status' => 'active'
        ]);

        // Kurslar
        $courses = [
            ['name' => 'SMM', 'description' => 'SMM kursi', 'status' => 'active'],
            ['name' => 'Web design', 'description' => 'Web dizayn kursi', 'status' => 'active'],
            ['name' => 'Web dasturlash', 'description' => 'Web dasturlash kursi', 'status' => 'active'],
            ['name' => 'Ingliz tili', 'description' => 'Ingliz tili kursi', 'status' => 'active'],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }

        // O'qituvchilar
        $teachers = [
            ['first_name' => 'Alisher', 'last_name' => 'Kadirov', 'position' => 'Senior o\'qituvchi', 'status' => 'active'],
            ['first_name' => 'Jasur', 'last_name' => 'Karimov', 'position' => 'O\'qituvchi', 'status' => 'active'],
            ['first_name' => 'Nozim', 'last_name' => 'Egamov', 'position' => 'O\'qituvchi', 'status' => 'active'],
            ['first_name' => 'Shahlo', 'last_name' => 'Zokirova', 'position' => 'O\'qituvchi', 'status' => 'active'],
            ['first_name' => 'Malika', 'last_name' => 'Nosirova', 'position' => 'O\'qituvchi', 'status' => 'active'],
        ];

        foreach ($teachers as $teacher) {
            Staff::create($teacher);
        }

        // Marketing manbalari
        $sources = [
            ['name' => 'Ijtimoiy tarmoqlar', 'type' => 'social_media', 'status' => 'active'],
            ['name' => 'Do\'stlar', 'type' => 'friends', 'status' => 'active'],
            ['name' => 'Reklama', 'type' => 'advertising', 'status' => 'active'],
            ['name' => 'Boshqa', 'type' => 'other', 'status' => 'active'],
        ];

        foreach ($sources as $source) {
            MarketingSource::create($source);
        }
    }
}