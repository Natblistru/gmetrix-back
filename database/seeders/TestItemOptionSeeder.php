<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TestItemOption;

class TestItemOptionSeeder extends Seeder
{

    public function run(): void
    {
        TestItemOption::factory()->count(90)->create();
    }
}
