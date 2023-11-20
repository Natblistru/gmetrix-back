<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EvaluationItem;

class EvaluationItemSeeder extends Seeder
{
    public function run(): void
    {
        EvaluationItem::factory()->count(3)->create();
    }
}
