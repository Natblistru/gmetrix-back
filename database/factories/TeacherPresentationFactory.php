<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TeacherPresentation;
use App\Models\Teacher;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\Theme;
use App\Models\LearningProgram;
use App\Models\SubjectStudyLevel;
use App\Models\ThemeLearningProgram;

class TeacherPresentationFactory extends Factory
{
    private $themes = [
        'România în Primul Război Mondial',
        'Mișcarea națională a românilor din Basarabia și teritoriile din stânga Nistrului',
        'Formarea Statului Național Unitar Român. Recunoașterea Marii Uniri de la 1918',
        'Conferinţa de Pace de la Paris. Sistemul de la Versailles',
      ];
    private $paths = [
        'calea-spre-presentare1', 'calea-spre-presentare2',
        'calea-spre-presentare3', 'calea-spre-presentare4'
    ];
    private $index = 0;

    public function definition(): array
    {
        $teachers = ['userT1 teacher', 'userT1 teacher', 'userT2 teacher', 'userT2 teacher'];
        $teacherId = Teacher::firstWhere('name', $teachers[$this->index])->id;
        $teacherName = $teachers[$this->index];

        $subjectIstoriaId = Subject::firstWhere('name', 'Istoria')->id;
        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;

        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId) ->where('subject_id', $subjectIstoriaId) ->first()->id;
        $themeId = Theme::firstWhere('name', $this->themes[$this->index])->id;
        $themeName = $this->themes[$this->index];

        $learningProgramIstoriaId = LearningProgram::where('subject_study_level_id', $subjectStudyLevelId)
                                         ->where('year', 2022)
                                         ->first()->id;

        $themeLearningProgramIstoriaId = ThemeLearningProgram::where('learning_program_id', $learningProgramIstoriaId)
                                         ->where('theme_id', $themeId)
                                         ->first()->id;


        $path = $this->paths[$this->index];

        $name = $themeName . ' (' . $teacherName . ', 2022)';

        $this->index++;


        return [
            'name' => $name,
            'path' => $path,
            'teacher_id' => $teacherId,
            'theme_learning_program_id'=> $themeLearningProgramIstoriaId,   
        ];
    }
}
