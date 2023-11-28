<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StudentEvaluationAnswer;
use App\Models\EvaluationAnswerOption;
use App\Models\EvaluationOption;
use App\Models\EvaluationAnswer;
use App\Models\Evaluation;
use App\Models\EvaluationSubject;
use App\Models\EvaluationItem;
use App\Models\Student;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\SubjectStudyLevel;
use App\Models\Theme;

class StudentEvaluationAnswerFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $points = [1,2];

        $answer_options = [ [ 'content' => 'Fapt istoric: semnarea Pactului Molotov-Ribentrop din 23 august 1939', 'option' => '0 p. - răspuns greșit/ lipsă' ],
        [ 'content' => 'Argument: pe coperta cărții se vede denumirea "Pactului Molotov-Ribentrop", iar pe fotografie se văd semnatarii acestui document - Molotov și Ribentrop', 'option' => '2 p. - argumentare deplină, cu exemple invocate din sursă sau din cunoștințele obținute anterior' ], ];

        $point = $points[$this->index];

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
        
        $answer_optionId = $answer_options[$this->index];

        $evaluation_answerId = EvaluationAnswer::where('content', $answer_optionId['content'])
                                         ->first()->id;
        $evaluation_optionId = EvaluationOption::where('label', $answer_optionId['option'])
                                         ->first()->id;

        $evaluation_answer_optionId = EvaluationAnswerOption::where('evaluation_answer_id', $evaluation_answerId)
                                                        ->where('evaluation_option_id', $evaluation_optionId)
                                         ->first()->id;

        $studentId = Student::firstWhere('name', 'userS1 student')->id;

        $this->index++;

        return [
            'student_id' => $studentId,
            'evaluation_answer_option_id' => $evaluation_answer_optionId,
            'points' => $point
        ];
    }
}
