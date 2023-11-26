<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeacherThemeVideo;

class TeacherThemeVideoSeeder extends Seeder
{

    public function run(): void
    {
        TeacherThemeVideo::factory()->count(1)->create();
    }
}
