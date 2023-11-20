<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EvaluationSubjectSource;
use App\Models\Evaluation;
use App\Models\EvaluationSubject;
use App\Models\EvaluationSource;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\SubjectStudyLevel;
use App\Models\Theme;

class EvaluationSubjectSourceFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $titles = ['SURSA A. REPERE CRONOLOGICE',
                'SURSA B.',
                'SURSA C.',
                'SURSA D.'
            ];
        $authors = ['',
                    'Pierre Milza, Serge Bernstein',
                    'Ewan Murray',
                    'Dominique Vallaud'
                    ];

        $text_sourses = ['',
                    'Istoria secolului XX ,Bucureşti, 1998, vol.I',
                    'Shut Up: Tale of Totalitarianism, 2005',
                    'Dicționar istoric, București, 2008'
                    ];


        $title = $titles[$this->index];
        $author = $authors[$this->index];
        $text_sourse = $text_sourses[$this->index];

        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjectIstoriaId = Subject::firstWhere('name', 'Istoria')->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId)
                                                ->where('subject_id', $subjectIstoriaId) ->first()->id;

        $themeId = Theme::where('name', 'România în Primul Război Mondial')->first()->id;

        $evaluationId = Evaluation::where('subject_study_level_id', $subjectStudyLevelId)
                                    ->where('year', 2022)
                                    ->first()->id;

        $evaluation_subjectId = EvaluationSubject::where('order_number', 2)
                                    ->where('evaluation_id', $evaluationId)
                                    ->first()->id;

        $sourceId = EvaluationSource::firstWhere('title', $title)
                                    ->where('author', $author)
                                    ->where('text_sourse', $text_sourse)
                                    ->first()->id;

        // $sourceId = $source ? $source->id : null;
        $this->index++;

        return [
            'evaluation_source_id' => $sourceId,
            'evaluation_subject_id' => $evaluation_subjectId,
        ];
    }
}
