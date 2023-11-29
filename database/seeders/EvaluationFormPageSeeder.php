<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EvaluationFormPage;

class EvaluationFormPageSeeder extends Seeder
{

    public function run(): void
    {
        EvaluationFormPage::factory()->count(7)->create();
    }
}
