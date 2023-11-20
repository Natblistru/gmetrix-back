<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudentSubopicProgress;

class StudentSubopicProgressSeeder extends Seeder
{
    public function run(): void
    {
        StudentSubopicProgress::factory()->count(4)->create();
    }
}
