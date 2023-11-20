<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EvaluationOption;

class EvaluationOptionFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $labels = ['răspuns greșit/ lipsă',
                'răspuns corect',
                'argumentare parțială, fără invocarea unor exemple/ dovezi',
                'explicație parțială; doar selectează informații din surse parțială, fără invocarea unor exemple/ dovezi',
                'argumentare deplină, cu exemple invocate din sursă sau din cunoștințele obținute anterior',
                'explicație deplină',
            ];
        $points = [0,1,1,1,2,2];
        $label = $labels[$this->index];
        $point = $points[$this->index];

        $this->index++;

        return [
            'label' => $label,
            'points' => $point,

        ];
    }
}
