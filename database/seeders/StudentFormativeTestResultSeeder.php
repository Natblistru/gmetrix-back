<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudentFormativeTestResult;

class StudentFormativeTestResultSeeder extends Seeder
{
    public function run(): void
    {
        StudentFormativeTestResult::factory()->count(4)->create();
    }
}
