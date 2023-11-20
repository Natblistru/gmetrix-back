<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudentSummativeTestResult;

class StudentSummativeTestResultSeeder extends Seeder
{
    public function run(): void
    {
        StudentSummativeTestResult::factory()->count(4)->create();
    }
}
