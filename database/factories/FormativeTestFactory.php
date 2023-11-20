<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FormativeTest;
use App\Models\TeacherTopic;
use App\Models\Teacher;
use App\Models\Topic;

class FormativeTestFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $types = ["quiz", 
                "dnd", 
                "check", 
                "snap", 
                "dnd_group", 
                "words", 
                "dnd_chrono_double", 
                "dnd_chrono"
            ];

        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $topicId = Topic::firstWhere('name', 'Opțiunile politice în perioada neutralității')->id;

        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                ->where('topic_id', $topicId)
                                ->first()->id;
        $type = $types[$this->index];

        $this->index++;

        return [
            'type' => $type,
            'teacher_topic_id' => $teacherTopictId,
        ];
    }
}
