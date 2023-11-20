<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StudyLevel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudyLevel>
 */
class StudyLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = StudyLevel::class;
     private $values = ['Ciclu gimnazial', 'Ciclu liceal'];
     private $index = 0;
 
     public function definition(): array
     {
         return [
             'name' => function () {
                 $value = $this->values[$this->index % count($this->values)];
                 $this->index++;
                 return $value;
             },
         ];
     }
}
