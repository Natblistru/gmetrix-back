<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EvaluationFormPage;
use App\Models\Evaluation;
use App\Models\EvaluationSubject;
use App\Models\EvaluationItem;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\SubjectStudyLevel;
use App\Models\Theme;


class EvaluationFormPageFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $task = [
            'Numește un fapt istoric pe care autorul îl poate utiliza pentru a justifica titlul cărții.', 
            'Argumentează răspunsul cu referire la copertă.'];
        $hints = [ 
            ["1" => "nu uita să indici data..."],
            ["1" => "denumirea cărții face trimitere la...", "2" => "în imagine vedem..."],
        ];

        $taskContent = $task[$this->index];
        $hintContent = json_encode($hints[$this->index]);

        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjectIstoriaId = Subject::firstWhere('name', 'Istoria')->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId) 
                                                ->where('subject_id', $subjectIstoriaId) ->first()->id;

        $themeId = Theme::where('name', 'România în Primul Război Mondial')->first()->id;

        $evaluationId = Evaluation::where('subject_study_level_id', $subjectStudyLevelId)
                                         ->where('year', 2022)
                                         ->first()->id;

        $evaluation_subjectId = EvaluationSubject::where('evaluation_id', $evaluationId)
                                         ->where('order_number', 1)
                                         ->first()->id;

       $evaluation_itemId = EvaluationItem::where('evaluation_subject_id', $evaluation_subjectId)
                                         ->where('order_number', 1)
                                         ->first()->id;


        $this->index++;

        return [
            'order_number' => $this->index,
            'task' => $taskContent,
            'hint' => $hintContent,
            'evaluation_item_id'=> $evaluation_itemId
        ];
    }
}
