<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EvaluationSubject;

class EvaluationSubjectSeeder extends Seeder
{

    public function run(): void
    {
        EvaluationSubject::factory()->count(3)->create();
    }
}
