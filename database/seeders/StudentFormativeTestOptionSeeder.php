<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudentFormativeTestOption;

class StudentFormativeTestOptionSeeder extends Seeder
{
    public function run(): void
    {
        StudentFormativeTestOption::factory()->count(3)->create();
    }
}
