<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Web Dasturlash - 1',
                'status' => 'active',
            ],
            [
                'name' => 'Web Dasturlash - 2',
                'status' => 'active',
            ],
            [
                'name' => 'SMM - 1',
                'status' => 'active',
            ],
            [
                'name' => 'Ingliz tili - 1',
                'status' => 'active',
            ],
            [
                'name' => 'Web Design - 1',
                'status' => 'waiting',
            ],
        ];

        foreach ($groups as $group) {
            Group::create($group);
        }
    }
}