<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TestComlexity;

class TestComlexityFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {

        $names = ['simplu',
            'moderat',
            'dificil',
            ];
        $levels = [1,2,3];

        $name = $names[$this->index];
        $level = $levels[$this->index];

        $this->index++;
        return [
            'name' => $name,
            'level' => $level
        ];
    }
}
