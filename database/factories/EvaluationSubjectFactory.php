<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EvaluationSubject;
use App\Models\Evaluation;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\SubjectStudyLevel;

class EvaluationSubjectFactory extends Factory
{
    private $years = [
        2022, 2022, 2022
      ];
    private $index = 0;
    public function definition(): array
    {
        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjectIstoriaId = Subject::firstWhere('name', 'Istoria')->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId)
                                                ->where('subject_id', $subjectIstoriaId) ->first()->id;

        $themeName = $this->years[$this->index];

        $evaluation = Evaluation::where('subject_study_level_id', $subjectStudyLevelId)
                                        ->where('year', $this->years[$this->index])
                                        ->first();

        $this->index++;
        return [
            'order_number' => $this->index,
            'evaluation_id'=> $evaluation->id,
        ];
    }
}

