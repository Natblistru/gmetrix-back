<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
// TagFactory.php

use App\Models\Tag;
use App\Models\Theme;
use App\Models\Topic;

class TagFactory extends Factory
{
    private $index = 0;

    public function definition(): array
    {
        $tags = [
            'primul război mondial',
            'neutralitate',
            'consiliul de coroană',
            'pacea de la bucurești',
            'armistițiul de la focșani',
        ];

        $themes = [
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
        ];

        $topics = [
            'Opțiunile politice în perioada neutralității',
            'Opțiunile politice în perioada neutralității',
            'Opțiunile politice în perioada neutralității',
            'Opțiunile politice în perioada neutralității',
            'Opțiunile politice în perioada neutralității',
        ];

        $tag = $tags[$this->index];

        $itemId = ($this->index < 1) ?
            Theme::firstWhere('name', $themes[$this->index])->id :
            Topic::firstWhere('name', $topics[$this->index])->id;

        $this->index++;

        return [
            'tag_name' => $tag,
            'taggable_id' => $itemId,
            'taggable_type' => $this->index < 2 ? Theme::class : Topic::class,
        ];
    }
}
