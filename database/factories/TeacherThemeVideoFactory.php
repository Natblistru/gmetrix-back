<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\LearningProgram;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\SubjectStudyLevel;
use App\Models\ThemeLearningProgram;
use App\Models\Theme;
use App\Models\Teacher;
use App\Models\Video;

class TeacherThemeVideoFactory extends Factory
{
    private $index = 0;
    public function definition(): array  {
        $titles = [
            'România în Primul Război Mondial',
        ];
        $sources = [
            'https://www.youtube.com/embed/qV2PSgIK-c4',
        ];
        $videoId = Video::where('title', $titles[$this->index])
                                         ->where('source', $sources[$this->index])
                                         ->first()->id;
        $teachers = ['userT1 teacher'];
        $teacherId = Teacher::firstWhere('name', $teachers[$this->index])->id;
        $teacherName = $teachers[$this->index];

        $subjectIstoriaId = Subject::firstWhere('name', 'Istoria')->id;
        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;

        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId)
                                                 ->where('subject_id', $subjectIstoriaId) ->first()->id;                                         

        $themeId = Theme::where('name', 'România în Primul Război Mondial')->first()->id;

        $learningProgramIstoria = LearningProgram::where('subject_study_level_id', $subjectStudyLevelId)
                                         ->where('year', 2022)
                                         ->first()->id;                                        

        $themeLearningProgramIstoria = ThemeLearningProgram::where('learning_program_id', $learningProgramIstoria)
        ->where('theme_id', $themeId)
        ->first();       

        // $themeLearningProgramIstoria = ThemeLearningProgram::where('learning_program_id', 2)
        //                                  ->where('theme_id', $themeId)
        //                                  ->first();
        $name = $titles[$this->index] . ' ( clasa 9, 2022, ' . $teacherName . ')';

        $this->index++;
        return [
            'name' =>$name,
            'video_id' =>$videoId,
            'teacher_id' => $teacherId,
            'theme_learning_program_id' => $themeLearningProgramIstoria,
        ];
    }
}
