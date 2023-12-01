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

    public function transformaText($text) {

        $text = trim($text);

        $text = strtolower($text);
    
        // Definim diacriticele și caracterele cu spațiu
        $diacritice = array('ă', 'â', 'ș', 'ț', 'î', ' ', 'ă', 'â', 'ș', 'ț', 'î');
        $replace = array('a', 'a', 's', 't', 'i', '-', 'a', 'a', 's', 't', 'i');
    
        // Înlocuim diacriticele și spațiile cu cratime
        $text = str_replace($diacritice, $replace, $text);
    
        // Înlăturăm orice alte caractere speciale
        $text = preg_replace('/[^a-z0-9-]/', '', $text);
    
        return '/' . $text;
    }

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
        $path = $this->transformaText($formativeTest['title']); 
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
            'path' => $path,
            'type' => $type,
            'teacher_topic_id' => $teacherTopictId,
            'test_complexity_id' => $complexityId,
        ];
    }
}
