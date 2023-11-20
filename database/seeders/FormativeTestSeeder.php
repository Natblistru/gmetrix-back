<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormativeTest;

class FormativeTestSeeder extends Seeder
{

    public function run(): void
    {
        FormativeTest::factory()->count(8)->create();
    }
}
