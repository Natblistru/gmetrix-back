<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\LearningProgram;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\SubjectStudyLevel;
use App\Models\Theme;

class LearningProgramFactory extends Factory {
    private $index = 0;

    public function definition(): array  {
        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjects = ['Matematica','Istoria','Limba română', 'Matematica'];
        $titles = ['Matematica, clasa 9 (2022)',
                    'Istoria, clasa 9 (2022)',
                    'Limba română, clasa 9 (2022)', 
                    'Matematica, clasa 9 (2014)'];        
        $years = [2022, 2022, 2022, 2014];
        $year = $years[$this->index];
        $name = $titles[$this->index];
        $subjectId = Subject::firstWhere('name', $subjects[$this->index])->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId)
                                                ->where('subject_id', $subjectId) ->first()->id;

        $this->index++;
        return [
            'name' => $name,
            'year' => $year,
            'subject_study_level_id' => $subjectStudyLevelId,
        ];
    }
}
