<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SubjectStudyLevel;
use App\Models\StudyLevel;
use App\Models\Subject;

class SubjectStudyLevelFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjects = ['Matematica', 'Istoria', 'Limba română'];
        $subjectId = Subject::firstWhere('name', $subjects[$this->index])->id;
        $paths = ['/matem', '/istoria', '/romana'];
        $images = ['/images/matematica.jpg', '/images/istoria.jpg', '/images/limbaRom.jpg'];

        $path = $paths[$this->index];
        $img = $images[$this->index];
        $name = $subjects[$this->index] . ', clasa 9';

        $this->index++;

        return [
            'study_level_id' => $studyLevelId,
            'subject_id' => $subjectId,
            'name' => $name,
            'path' => $path,
            'img' => $img,
        ];
    }
}
