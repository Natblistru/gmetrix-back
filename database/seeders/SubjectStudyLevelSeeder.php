<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubjectStudyLevel;

class SubjectStudyLevelSeeder extends Seeder
{

    public function run(): void
    {
        SubjectStudyLevel::factory()->count(3)->create();
    }
}
