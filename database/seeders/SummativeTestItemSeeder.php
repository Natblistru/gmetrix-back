<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SummativeTestItem;

class SummativeTestItemSeeder extends Seeder
{
    public function run(): void
    {
        SummativeTestItem::factory()->count(8)->create();
    }
}
