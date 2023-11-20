<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EvaluationOption;

class EvaluationOptionSeeder extends Seeder
{

    public function run(): void
    {
        EvaluationOption::factory()->count(6)->create();
    }
}
