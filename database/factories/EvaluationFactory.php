<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Evaluation;
use App\Models\Subject;
use App\Models\StudyLevel;
use App\Models\SubjectStudyLevel;


class EvaluationFactory extends Factory
{

    public function definition(): array
    {
        $subjectId = Subject::firstWhere('name', 'Istoria')->id;
        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId) ->where('subject_id', $subjectId) ->first()->id;
        return [
            'year' => 2022,
            'subject_study_level_id' => $subjectStudyLevelId,
        ];
    }
}
