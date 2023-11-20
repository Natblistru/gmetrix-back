<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subject;
use App\Models\StudyLevel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $counter = 0;

    public function definition(): array
    {
        $uniqueNames = ['Limba română', 'Matematica', 'Istoria'];

        return [
            'name' => $uniqueNames[$this->counter++ % count($uniqueNames)]
        ];
    }
}
