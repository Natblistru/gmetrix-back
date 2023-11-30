<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubtopicImage;

class SubtopicImageSeeder extends Seeder
{
    public function run(): void
    {
        SubtopicImage::factory()->count(13)->create();
    }
}
