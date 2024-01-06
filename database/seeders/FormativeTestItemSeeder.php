<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormativeTestItem;

class FormativeTestItemSeeder extends Seeder
{
    public function run(): void
    {
        FormativeTestItem::factory()->count(24)->create();
    }
}
