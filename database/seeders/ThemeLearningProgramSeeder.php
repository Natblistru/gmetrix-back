<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ThemeLearningProgram;

class ThemeLearningProgramSeeder extends Seeder
{
    public function run(): void
    {
        ThemeLearningProgram::factory()->count(86)->create();
    }
}
