<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EvaluationAnswerOption;

class EvaluationAnswerOptionSeeder extends Seeder
{

    public function run(): void
    {
        EvaluationAnswerOption::factory()->count(5)->create();
    }
}
