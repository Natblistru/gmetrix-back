<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeacherTopic;

class TeacherTopicSeeder extends Seeder
{
    public function run(): void
    {
        TeacherTopic::factory()->count(5)->create();
    }
}
