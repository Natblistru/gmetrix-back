<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EvaluationSource;

class EvaluationSourceSeeder extends Seeder
{

    public function run(): void
    {
        EvaluationSource::factory()->count(4)->create();
    }
}
