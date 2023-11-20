<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudentSummativeTestOption;

class StudentSummativeTestOptionSeeder extends Seeder
{

    public function run(): void
    {
        StudentSummativeTestOption::factory()->count(4)->create();
    }
}
