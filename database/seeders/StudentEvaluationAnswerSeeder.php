<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudentEvaluationAnswer;

class StudentEvaluationAnswerSeeder extends Seeder
{

    public function run(): void
    {
        StudentEvaluationAnswer::factory()->count(2)->create();
    }
}
