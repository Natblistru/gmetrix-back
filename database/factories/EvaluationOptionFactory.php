<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EvaluationOption;

class EvaluationOptionFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $labelsPoints = [

            ["points" => 0, "option" => '0 p. - răspuns greșit/ lipsă'],
            ["points" => 0, "option" => '0 p. - răspuns lipsă'],
            ["points" => 0, "option" => '0 p. - răspuns lipsă sau fără a face trimitere la surse'],
            ["points" => 0, "option" => '0 p. - răspuns lipsă sau sunt doar enumerate informații disparate din surse'],
            ["points" => 0, "option" => '0 p. - răspuns lipsă/ volum irelevant (2-3 enunțuri)'],

            ["points" => 1, "option" => '1 p. - răspuns corect'],
            ["points" => 1, "option" => '1 p. - argumentare parțială, fără invocarea unor exemple/dovezi'],
            ["points" => 1, "option" => '1 p. - argument parțial/declarativ'],
            ["points" => 1, "option" => '1 p. - explicație parțială; doar selectează informații din surse parțială, fără invocarea unor exemple/ dovezi'],
            ["points" => 1, "option" => '1 p. - se fac unele încercări de valorificare a surselor; textul surselor este preluat fără a fi integrat în text'],
            ["points" => 1, "option" => '1 p. - întroducere corect formulată, clar organizată ca mesaj/ structură'],
            ["points" => 1, "option" => '1 p. - cuprins corect formulat, clar organizat ca mesaj/ structură'],
            ["points" => 1, "option" => '1 p. - concluzie corect formulată, clar organizată ca mesaj/ structură'],
            ["points" => 1, "option" => '1 p. - argumentele care reflectă explicit afirmația propusă'],
            ["points" => 1, "option" => '1 p. - referințele sunt parțial relevante pentru tema propusă'],
            ["points" => 1, "option" => '1 p. - nu sunt comise greșeli științifice grave'],

            ["points" => 2, "option" => '2 p. - argumentare deplină, cu exemple invocate din sursă sau din cunoștințele obținute anterior'],
            ["points" => 2, "option" => '2 p. - explicație deplină'],
            ["points" => 2, "option" => '2 p. - sursele sunt parte integră a textului, servesc ca suport al reflecției autorului'],
            ["points" => 2, "option" => '2 p. - argument parțial/declarativ'],
            ["points" => 2, "option" => '2 p. - argument deplin (raționament și exemplu)'],
            ["points" => 2, "option" => '2 p. - refrințele sunt relevante pentru prezentarea temei'],
        ];
        
        $labelsPoint = $labelsPoints[$this->index];

      
        $label = $labelsPoint['option'];
        $point = $labelsPoint['points'];

        $this->index++;

        return [
            'label' => $label,
            'points' => $point,

        ];
    }
}
