<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VideoBreakpoint;

class VideoBreakpointSeeder extends Seeder
{
    public function run(): void
    {
        VideoBreakpoint::factory()->count(11)->create();
    }
}
