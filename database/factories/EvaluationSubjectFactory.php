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
      private $paths = [
        "/examen-subiect1", "/examen-subiect2", "/examen-subiect3"
      ];
      private $names = [
        "Subiectul I", "Subiectul II", "Subiectul III"
      ];
    private $index = 0;
    public function definition(): array
    {
        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjectIstoriaId = Subject::firstWhere('name', 'Istoria')->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId)
                                                ->where('subject_id', $subjectIstoriaId) ->first()->id;

        $themeName = $this->years[$this->index];
        $path = $this->paths[$this->index];
        $name = $this->names[$this->index];

        $evaluation = Evaluation::where('subject_study_level_id', $subjectStudyLevelId)
                                        ->where('year', $this->years[$this->index])
                                        ->first();

        $this->index++;
        return [
            'name' => $name,
            'order_number' => $this->index,
            'path' => $path,
            'evaluation_id'=> $evaluation->id,
        ];
    }
}

