<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TestItem;

class TestItemSeeder extends Seeder
{

    public function run(): void
    {
        TestItem::factory()->count(23)->create();
    }
}
