<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Topic;
use App\Models\TeacherTopic;
use App\Models\Teacher;

class SubtopicFactory extends Factory
{
    private $values = [
        'Definiția mulțimilor',
        'Modurile de reprezentare a mulțimii',
        'Adunarea și scăderea numerelor naturale. Proprietăți',
        'Înmulțirea numerelor naturale. Proprietăți. Factorul comun'
    ];

    private $paths = [
        'calea-spre-audio1', 'calea-spre-audio2', 'calea-spre-audio3',
        'calea-spre-audio4'
    ];

    private $index = 0;

    public function definition(): array
    {
        $topics = ['Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R', 
                         'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R', 
                         'Operaţii cu numere naturale', 
                         'Operaţii cu numere naturale'];

        $topicId = Topic::firstWhere('name', $topics[$this->index])->id;
 
        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                ->where('topic_id', $topicId)
                                ->first()->id;

        $name = $this->values[$this->index % count($this->values)];

        $path = $this->paths[$this->index];

        $this->index++;

        return [
            'name' => $name,
            'teacher_topic_id' => $teacherTopictId,
            'audio_path' => $path
        ];
    }
}

