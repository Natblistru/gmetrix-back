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
        
        $formativeTests = [
            [
                "order" => 1,
                "title" => "Alege afirmația corectă",
                "type" => "quiz",
                "complexity" => 1
            ],
            [
                "order" => 2,
                "title" => "Stabilește cauzele evenimentelor",
                "type" => "dnd",
                "complexity" => 2
            ],
            [
                "order" => 3,
                "title" => "Stabilește consecințele evenimentelor",
                "type" => "dnd",
                "complexity" => 2
            ],
            [
                "order" => 4,
                "title" => "Verifică corectitudinea afirmațiilor",
                "type" => "check",
                "complexity" => 1
            ],
            [
                "order" => 5,
                "title" => "Formează perechi logice",
                "type" => "snap",
                "complexity" => 1
            ],
            [
                "order" => 6,
                "title" => "Grupează elementele",
                "type" => "dnd_group",
                "complexity" => 1
            ],
            [
                "order" => 7,
                "title" => "Caracteristicile evenimentelor",
                "type" => "dnd",
                "complexity" => 2
            ],
            [
                "order" => 8,
                "title" => "Completează propoziția",
                "type" => "words",
                "complexity" => 1
            ],
            [
                "order" => 9,
                "title" => "Elaborează un fragment de text",
                "type" => "dnd_chrono_double",
                "complexity" => 3
            ],
            [
                "order" => 10,
                "title" => "Succesiunea cronologică a evenimentelor",
                "type" => "dnd_chrono",
                "complexity" => 2
            ]
        ];

        $formativeTest = $formativeTests[$this->index]; 

        $type = $formativeTest['type']; 
        $order = $formativeTest['order']; 
        $title = $formativeTest['title'];  
        $complexityId = $formativeTest['complexity'];       

        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $topicId = Topic::firstWhere('name', 'Opțiunile politice în perioada neutralității')->id;

        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                ->where('topic_id', $topicId)
                                ->first()->id;

        $this->index++;

        return [
            'order_number' => $order,
            'title' => $title,
            'type' => $type,
            'teacher_topic_id' => $teacherTopictId,
            'test_complexity_id' => $complexityId,
        ];
    }
}
