<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TestItemColumn;

class TestItemColumnSeeder extends Seeder
{

    public function run(): void
    {
        TestItemColumn::factory()->count(12)->create();
    }
}
