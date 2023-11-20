<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StudentEvaluationAnswer;
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
        $orderItems = [1,2];
        $points = [1,2];

        $orderItem = $orderItems[$this->index];
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

        $evaluation_answerId = EvaluationAnswer::where('order_number', $orderItem)
                                         ->first()->id;

        $studentId = Student::firstWhere('name', 'userS1 student')->id;

        $this->index++;

        return [
            'student_id' => $studentId,
            'evaluation_answer_id' => $evaluation_answerId,
            'points' => $point
        ];
    }
}
