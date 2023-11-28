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
        $task = ['Studiază coperta cărții. Numește un fapt istoric pe care autorul îl poate utiliza pentru a justifica titlul cărții. Argumentează răspunsul.', 
                'cerinta2', 
                'Utilizează sursele pentru a argumenta, într-un text coerent, afirmația:'];
        $statement = ['afirmația1', ' afirmația2', 'Reforma agrară din 1921 a contribuit la modernizarea societății românești.'];
        $pathImage = ['/images/carte_planul_marshall.jpg', '', ''];
        $note = ['', '', 'Notă: În elaborarea textului vei:\n- folosi sursele propuse;\n- respecta coerența textului cu structura: introducere, cuprins, concluzie;\n- formula cel puțin trei argumente;\n- utiliza în argumentare referințe cu privire la personalități sau repere cronologice;\n- formula un mesaj corect din punct de vedere științific.'];
        $pathEditImage = ['', 'calea-editable-img', ''];
        $procentPapers = ['70%', '100%', '100%'];

        $taskContent = $task[$this->index];
        $nota = $note[$this->index];
        $procentPaper = $procentPapers[$this->index];
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
            'nota' => $nota,
            'image_path' => $pathImageContent,
            'editable_image_path' => $pathEditImageContent,
            'procent_paper' => $procentPaper,
            'theme_id' => $themeId,
            'evaluation_subject_id'=> $evaluation_subjectId,
        ];
    }
}
