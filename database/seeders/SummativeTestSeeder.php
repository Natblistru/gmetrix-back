<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SummativeTest;

class SummativeTestSeeder extends Seeder
{
    public function run(): void
    {
        SummativeTest::factory()->count(1)->create();
    }
}
