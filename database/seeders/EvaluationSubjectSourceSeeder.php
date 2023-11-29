<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EvaluationSubjectSource;

class EvaluationSubjectSourceSeeder extends Seeder
{

    public function run(): void
    {
        EvaluationSubjectSource::factory()->count(7)->create();
    }
}
