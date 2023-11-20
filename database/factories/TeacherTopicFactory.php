<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TeacherTopic;
use App\Models\Teacher;
use App\Models\Topic;

class TeacherTopicFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $teachers = ['userT1 teacher',
                    'userT1 teacher', 
                    'userT2 teacher', 
                    'userT2 teacher',
                    'userT1 teacher'
                ];
        $topics = ['Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
                'Operaţii cu numere naturale', 
                'Rapoarte. Proporţii. Proprietatea fundamentală a proporţiilor', 
                'Mărimi direct proporţionale şi mărimi invers proporţionale',
                'Opțiunile politice în perioada neutralității'
            ];
        $teacherId = Teacher::firstWhere('name', $teachers[$this->index])->id;
        $topictId = Topic::firstWhere('name', $topics[$this->index])->id;

        $this->index++;
        return [
            'teacher_id' => $teacherId,
            'topic_id' => $topictId,
        ];
    }

}
