<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentStatus;
use App\Models\StudyLanguage;
use App\Models\KnowledgeLevel;

class StudentSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // O'quvchi statuslari
        $statuses = [
            ['name' => 'Aloqa', 'slug' => 'contact'],
            ['name' => 'Sinov darsi', 'slug' => 'trial'],
            ['name' => 'Sinov darsidan o\'tgan', 'slug' => 'after_trial'],
            ['name' => 'Mijoz', 'slug' => 'client'],
            ['name' => 'Onlayn dars', 'slug' => 'online'],
        ];

        foreach ($statuses as $status) {
            StudentStatus::create($status);
        }

        // O'qish tillari
        $languages = [
            ['name' => 'O\'zbek', 'slug' => 'uzbek'],
            ['name' => 'Rus', 'slug' => 'russian'],
            ['name' => 'Ingliz', 'slug' => 'english'],
        ];

        foreach ($languages as $language) {
            StudyLanguage::create($language);
        }

        // Bilim darajalari
        $levels = [
            ['name' => 'Boshlang\'ich', 'slug' => 'beginner'],
            ['name' => 'O\'rta', 'slug' => 'intermediate'],
            ['name' => 'Yuqori', 'slug' => 'advanced'],
            ['name' => 'Boshqa', 'slug' => 'other'],
        ];

        foreach ($levels as $level) {
            KnowledgeLevel::create($level);
        }
    }
}