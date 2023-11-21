<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Theme;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\LearningProgram;
use App\Models\SubjectStudyLevel;
use App\Models\ThemeLearningProgram;

class TopicFactory extends Factory
{
    private $values = [
        //Thema Mulțimea numerelor naturale
        'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
        'Operaţii cu numere naturale',
        'Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10',
        'Cel mai mare divizor comun al două numere naturale',
        'Cel mai mic multiplu comun al două numere naturale',

        //Thema Mulțimea numerelor întregi
        'Modulul numărului întreg', 
        'Operaţii cu numere întregi',

         //Thema Mulțimea numerelor raționale
        'Scrierea numerelor raţionale în diverse forme', 
        'Operaţii cu numere raţionale',
         
         //Thema Mulțimea numerelor reale
         'Rădăcina pătrată (radical), proprietăți',
         'Introducerea factorilor sub radical, scoaterea factorilor de sub radical', 
         'Compararea unor numere ce conțin radicali. Modulul numărului real',
         'Operații cu radicali. Raţionalizarea numitorului', 
         'Submulţimi ale mulţimii numerelor reale. Noţiune de număr iraţional',


        // Thema România în Primul Război Mondial
        'Opțiunile politice în perioada neutralității',
        'Mișcarea națională a românilor din Basarabia și teritoriile din stânga Nistrului',
        'Formarea Statului Național Unitar Român. Recunoașterea Marii Uniri de la 1918',
        'Conferinţa de Pace de la Paris. Sistemul de la Versailles'
    ];
    private $index = 0;

    public function definition(): array
    {
        $thems = [ 
        'Mulțimea numerelor naturale', 
        'Mulțimea numerelor naturale', 
        'Mulțimea numerelor naturale', 
        'Mulțimea numerelor naturale', 
        'Mulțimea numerelor naturale', 

        'Mulțimea numerelor întregi', 
        'Mulțimea numerelor întregi',         

        'Mulțimea numerelor raționale',
        'Mulțimea numerelor raționale',

        'Mulțimea numerelor reale',
        'Mulțimea numerelor reale',
        'Mulțimea numerelor reale',
        'Mulțimea numerelor reale',
        'Mulțimea numerelor reale',

        'România în Primul Război Mondial',
        'România în Primul Război Mondial',
        'România în Primul Război Mondial',
        'România în Primul Război Mondial'
        ];

        $subjects = [
            'Matematica', 
            'Matematica', 
            'Matematica', 
            'Matematica',
            'Matematica', 
            'Matematica', 
            'Matematica', 
            'Matematica',
            'Matematica', 
            'Matematica', 
            'Matematica',
            'Matematica', 
            'Matematica', 
            'Matematica',            
            'Istoria',
            'Istoria',
            'Istoria',
            'Istoria'
        ];


        $themeId = Theme::firstWhere('name', $thems[$this->index])->id;

        $name = $this->values[$this->index];

        $subjectId = Subject::firstWhere('name', $subjects[$this->index])->id;
        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;

        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId) ->where('subject_id', $subjectId) ->first()->id;
        $learningProgramIstoriaId = LearningProgram::where('subject_study_level_id', $subjectStudyLevelId)
                                         ->where('year', 2022)
                                         ->first()->id;

        $themeLearningProgramIstoriaId = ThemeLearningProgram::where('learning_program_id', $learningProgramIstoriaId)
                                         ->where('theme_id', $themeId)
                                         ->first()->id;

        $this->index++;

        return [
            'name' => $name,
            'theme_learning_program_id'=> $themeLearningProgramIstoriaId,   
        ];
    }
}

