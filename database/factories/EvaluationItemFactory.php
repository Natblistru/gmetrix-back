<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EvaluationItem;
use App\Models\EvaluationSubject;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\SubjectStudyLevel;
use App\Models\Evaluation;
use App\Models\Theme;

class EvaluationItemFactory extends Factory
{
    private $years = [
        2022, 2022, 2022
      ];
    private $index = 0;

    public function definition(): array
    {
        $task = ['cerinta1', 'cerinta2', 'cerinta3'];
        $statement = ['afirmația1', ' afirmația2', ' afirmația3'];
        $pathImage = ['calea-img1', '', ''];
        $pathEditImage = ['', 'calea-editable-img', ''];

        $taskContent = $task[$this->index];
        $statementContent = $statement[$this->index];
        $pathImageContent = $pathImage[$this->index];
        $pathEditImageContent = $pathEditImage[$this->index];

        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjectIstoriaId = Subject::firstWhere('name', 'Istoria')->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId)
                                                ->where('subject_id', $subjectIstoriaId) ->first()->id;

        $themeId = Theme::firstWhere('name','România în Primul Război Mondial')->id;

        $evaluationId = Evaluation::where('subject_study_level_id', $subjectStudyLevelId)
                                         ->where('year', $this-> years[$this->index])
                                         ->first()->id;

        $evaluation_subjectId = EvaluationSubject::where('evaluation_id', $evaluationId)
                                         ->where('order_number', $this->index+1)
                                         ->first()->id;

        $this->index++;

        return [
            'task' => $taskContent,
            'statement' => $statementContent,
            'image_path' => $pathImageContent,
            'editable_image_path' => $pathEditImageContent,
            'theme_id' => $themeId,
            'evaluation_subject_id'=> $evaluation_subjectId,
        ];
    }
}
