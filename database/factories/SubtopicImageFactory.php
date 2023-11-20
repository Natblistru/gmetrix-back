<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SubtopicImage;
use App\Models\Subtopic;

class SubtopicImageFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $paths = ['calea-spre-image1', 'calea-spre-image2'
                 ];
        $subtopics = ['Definiția mulțimilor',
                      'Definiția mulțimilor'
                ];
        $subtopictId = Subtopic::firstWhere('name', $subtopics[$this->index])->id;

        $path = $paths[$this->index];

        $this->index++;

        return [
            'path' => $path,
            'subtopic_id' => $subtopictId,
        ];
    }
}
