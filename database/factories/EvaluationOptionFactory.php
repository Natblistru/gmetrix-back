<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EvaluationOption;

class EvaluationOptionFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $labels = ['0 p. - răspuns greșit/ lipsă',
                '1 p. - răspuns corect',
                '1 p. - argumentare parțială, fără invocarea unor exemple/dovezi',
                '1 p. - explicație parțială; doar selectează informații din surse parțială, fără invocarea unor exemple/ dovezi',
                '2 p. - argumentare deplină, cu exemple invocate din sursă sau din cunoștințele obținute anterior',
                '2 p. - explicație deplină',
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
