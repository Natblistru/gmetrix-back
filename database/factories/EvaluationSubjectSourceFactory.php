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
        $sources = [
            ["title" => 'SURSA A. REPERE CRONOLOGICE',
                "author" => '',
                "text_sourse" => '',
                "subject" => 2,
                "order_number" => 1,

            ],
            ["title" => 'SURSA B.',
                "author" => 'Pierre Milza, Serge Bernstein',
                "text_sourse" => 'Istoria secolului XX ,Bucureşti, 1998, vol.I',
                "subject" => 2,
                "order_number" => 2,
            ],            
            ["title" => 'SURSA C.',
                "author" => 'Ewan Murray',
                "text_sourse" => 'Shut Up: Tale of Totalitarianism, 2005',
                "subject" => 2,
                "order_number" => 3,
            ], 
            ["title" => 'SURSA D.',
                "author" => 'Dominique Vallaud',
                "text_sourse" => 'Dicționar istoric, București, 2008',
                "subject" => 2,
                "order_number" => 4,
            ],  
            ["title" => 'SURSA A.',
                "author" => '',
                "text_sourse" => '(Din Legea de reformă agrară pentru Basarabia, votată de Parlamentul României la 11 martie 1920)',
                "subject" => 3,
                "order_number" => 1,
            ],
            ["title" => 'SURSA B.',
                "author" => 'Svetlana Suveică',
                "text_sourse" => 'Basarabia în primul deceniu interbelic (1918-1929). Modernizare prin reforme.',
                "subject" => 3,
                "order_number" => 2,
            ], 
            ["title" => 'SURSA C.',
                "author" => 'Alexandra Georgescu',
                "text_sourse" => 'Cum s-a aplicat reforma agrară din 1921// Adevărul.ro',
                "subject" => 3,
                "order_number" => 3,
            ],             
        ];

        $source = $sources[$this->index];

        $title = $source['title'];
        $author = $source['author'];
        $order_number = $source['subject'];
        $order = $source['order_number'];
        $text_sourse = $source['text_sourse'];

        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjectIstoriaId = Subject::firstWhere('name', 'Istoria')->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId)
                                                ->where('subject_id', $subjectIstoriaId) ->first()->id;

        $themeId = Theme::where('name', 'România în Primul Război Mondial')->first()->id;

        $evaluationId = Evaluation::where('subject_study_level_id', $subjectStudyLevelId)
                                    ->where('year', 2022)
                                    ->first()->id;

        $evaluation_subjectId = EvaluationSubject::where('order_number', $order_number)
                                    ->where('evaluation_id', $evaluationId)
                                    ->first()->id;

        $sourceId = EvaluationSource::firstWhere('title', $title)
                                    ->where('author', $author)
                                    ->where('text_sourse', $text_sourse)
                                    ->first()->id;

        // $sourceId = $source ? $source->id : null;
        $this->index++;

        return [
            'order_number' => $order,
            'evaluation_source_id' => $sourceId,
            'evaluation_subject_id' => $evaluation_subjectId,
        ];
    }
}
