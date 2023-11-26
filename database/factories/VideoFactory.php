<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Video;

class VideoFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $titles = [
            'România în Primul Război Mondial',
        ];
        $sources = [
            'https://www.youtube.com/embed/qV2PSgIK-c4',
        ];
        $title = $titles[$this->index];
        $source = $sources[$this->index];

        $this->index++;
        return [
            'title' => $title,
            'source' => $source,
        ];
    }
}
