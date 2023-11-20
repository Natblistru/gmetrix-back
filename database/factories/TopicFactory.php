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
        'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
        'Operaţii cu numere naturale',
        'Rapoarte. Proporţii. Proprietatea fundamentală a proporţiilor',
        'Mărimi direct proporţionale şi mărimi invers proporţionale',
        'Opțiunile politice în perioada neutralității',
    ];
    private $index = 0;

    public function definition(): array
    {
        $thems = [ 
        'Mulțimea numerelor naturale', 
        'Mulțimea numerelor întregi', 
        'Mulțimea numerelor raționale',
        'Mulțimea numerelor reale',
        'România în Primul Război Mondial',
        ];

        $subjects = [
            'Matematica', 
            'Matematica', 
            'Matematica', 
            'Matematica',
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

