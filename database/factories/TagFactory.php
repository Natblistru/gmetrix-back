<?php

namespace Database\Factories;

use App\Models\Tag;
// TagFactory.php

use App\Models\Theme;
use App\Models\Topic;
use App\Models\Subject;
use App\Models\StudyLevel;
use App\Models\SubjectStudyLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    private $index = 0;

    public function definition(): array
    {
        $tags = [
            'primul război mondial',
            'neutralitate',
            'consiliul de coroană',
            'pacea de la bucurești',
            'armistițiul de la focșani',
        ];

        $themes = [
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
        ];

        $topics = [
            'Opțiunile politice în perioada neutralității',
            'Opțiunile politice în perioada neutralității',
            'Opțiunile politice în perioada neutralității',
            'Opțiunile politice în perioada neutralității',
            'Opțiunile politice în perioada neutralității',
        ];

        $tag = $tags[$this->index];

        $itemId = ($this->index < 1) ?
            Theme::firstWhere('name', $themes[$this->index])->id :
            Topic::firstWhere('name', $topics[$this->index])->id;

        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjectIstoriaId = Subject::firstWhere('name', 'Istoria')->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId) 
                                                ->where('subject_id', $subjectIstoriaId) ->first()->id;
        $this->index++;

        return [
            'tag_name' => $tag,
            'taggable_id' => $itemId,
            'taggable_type' => $this->index < 2 ? Theme::class : Topic::class,
            'subject_study_level_id'=> $subjectStudyLevelId
        ];
    }
}
