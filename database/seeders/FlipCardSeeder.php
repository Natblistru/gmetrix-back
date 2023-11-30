<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FlipCard;

class FlipCardSeeder extends Seeder
{

    public function run(): void
    {
        FlipCard::factory()->count(22)->create();
    }
}
