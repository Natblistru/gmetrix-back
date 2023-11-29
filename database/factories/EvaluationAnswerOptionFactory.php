<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EvaluationAnswerOption;
use App\Models\Evaluation;
use App\Models\EvaluationSubject;
use App\Models\EvaluationItem;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationOption;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\SubjectStudyLevel;
use App\Models\Theme;

class EvaluationAnswerOptionFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $optionOrders = [
            ["ans_id" => 1, "option" => '0 p. - răspuns greșit/ lipsă'],
            ["ans_id" => 1, "option" => '1 p. - răspuns corect'],
            ["ans_id" => 2, "option" => '0 p. - răspuns greșit/ lipsă'],
            ["ans_id" => 2, "option" => '1 p. - argumentare parțială, fără invocarea unor exemple/dovezi'],
            ["ans_id" => 2, "option" => '2 p. - argumentare deplină, cu exemple invocate din sursă sau din cunoștințele obținute anterior'],
            ["ans_id" => 3, "option" => '0 p. - răspuns lipsă sau fără a face trimitere la surse'],
            ["ans_id" => 3, "option" => '1 p. - se fac unele încercări de valorificare a surselor; textul surselor este preluat fără a fi integrat în text'],
            ["ans_id" => 3, "option" => '2 p. - sursele sunt parte integră a textului, servesc ca suport al reflecției autorului'],
            ["ans_id" => 4, "option" => '0 p. - răspuns lipsă'],
            ["ans_id" => 4, "option" => '1 p. - întroducere corect formulată, clar organizată ca mesaj/ structură'],
            ["ans_id" => 5, "option" => '0 p. - răspuns lipsă'],
            ["ans_id" => 5, "option" => '1 p. - cuprins corect formulat, clar organizat ca mesaj/ structură'],
            ["ans_id" => 6, "option" => '0 p. - răspuns lipsă'],
            ["ans_id" => 6, "option" => '1 p. - concluzie corect formulată, clar organizată ca mesaj/ structură'],
            ["ans_id" => 7, "option" => '0 p. - răspuns lipsă sau sunt doar enumerate informații disparate din surse'],
            ["ans_id" => 7, "option" => '1 p. - argumentele care reflectă explicit afirmația propusă'],
            ["ans_id" => 8, "option" => '0 p. - răspuns lipsă'],
            ["ans_id" => 8, "option" => '1 p. - argument parțial/declarativ'],
            ["ans_id" => 8, "option" => '2 p. - argument deplin (raționament și exemplu)'],
            ["ans_id" => 9, "option" => '0 p. - răspuns lipsă'],
            ["ans_id" => 9, "option" => '1 p. - argument parțial/declarativ'],
            ["ans_id" => 9, "option" => '2 p. - argument deplin (raționament și exemplu)'],
            ["ans_id" => 10, "option" => '0 p. - răspuns lipsă'],
            ["ans_id" => 10, "option" => '1 p. - argument parțial/declarativ'],
            ["ans_id" => 10, "option" => '2 p. - argument deplin (raționament și exemplu)'],
            ["ans_id" => 11, "option" => '0 p. - răspuns lipsă'],
            ["ans_id" => 11, "option" => '1 p. - referințele sunt parțial relevante pentru tema propusă'],
            ["ans_id" => 11, "option" => '2 p. - refrințele sunt relevante pentru prezentarea temei'],
            ["ans_id" => 12, "option" => '0 p. - răspuns lipsă/ volum irelevant (2-3 enunțuri)'],
            ["ans_id" => 12, "option" => '1 p. - nu sunt comise greșeli științifice grave'],
        ];

        $optionOrder = $optionOrders[$this->index];


        $orderItem = $optionOrder['ans_id'];
        $option = $optionOrder['option'];

        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjectIstoriaId = Subject::firstWhere('name', 'Istoria')->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId) 
                                                ->where('subject_id', $subjectIstoriaId) ->first()->id;

        $themeId = Theme::where('name', 'România în Primul Război Mondial')->first()->id;

        $evaluationId = Evaluation::where('subject_study_level_id', $subjectStudyLevelId)
                                        ->where('year', 2022)
                                        ->first()->id;

        $evaluation_subjectId = EvaluationSubject::where('order_number', 1)
                                        ->where('evaluation_id', $evaluationId)
                                        ->first()->id;

        $evaluation_itemId = EvaluationItem::where('order_number', 1)
                                        ->where('evaluation_subject_id', $evaluation_subjectId)  
                                        ->first()->id;

        $evaluation_answerId = EvaluationAnswer::where('id', $orderItem)
                                        ->first()->id;

        $evaluation_optionId = EvaluationOption::where('label', $option)
                                        ->first()->id;

        $this->index++;

        return [
            'evaluation_answer_id' => $evaluation_answerId,
            'evaluation_option_id'=> $evaluation_optionId
        ];
    }
}
