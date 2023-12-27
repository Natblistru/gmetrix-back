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
                    'userT1 teacher', 
                    'userT1 teacher',
                    'userT1 teacher',
                    'userT1 teacher',
                    'userT1 teacher', 
                    'userT1 teacher',
                    'userT1 teacher',
                    'userT1 teacher',
                    'userT1 teacher', 
                    'userT1 teacher', 
                    'userT1 teacher',
                    'userT1 teacher',
                    'userT1 teacher',
                    'userT1 teacher',
                    'userT1 teacher',
                    'userT1 teacher',
                ];
        $topics = [ 
            //Thema Mulțimea numerelor naturale
            ["title" => 'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R', "order" => 1],
            ["title" => 'Operaţii cu numere naturale', "order" => 2],
            ["title" => 'Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10',"order" => 3],
            ["title" => 'Cel mai mare divizor comun al două numere naturale',"order" => 4],
            ["title" => 'Cel mai mic multiplu comun al două numere naturale',"order" => 5],

            //Thema Mulțimea numerelor întregi
            ["title" => 'Modulul numărului întreg', "order" => 1],
            ["title" => 'Operaţii cu numere întregi',"order" => 2],

            //Thema Mulțimea numerelor raționale
            ["title" => 'Scrierea numerelor raţionale în diverse forme', "order" => 1],
            ["title" => 'Operaţii cu numere raţionale',"order" => 2],
            
            //Thema Mulțimea numerelor reale
            ["title" => 'Rădăcina pătrată (radical), proprietăți',"order" => 1],
            ["title" => 'Introducerea factorilor sub radical, scoaterea factorilor de sub radical', "order" => 2],
            ["title" => 'Compararea unor numere ce conțin radicali. Modulul numărului real',"order" => 3],
            ["title" => 'Operații cu radicali. Raţionalizarea numitorului', "order" => 4],
            ["title" => 'Submulţimi ale mulţimii numerelor reale. Noţiune de număr iraţional',"order" => 5],

            ["title" => 'Opțiunile politice în perioada neutralității',"order" => 1],
            ["title" => 'Mișcarea națională a românilor din Basarabia până în octombrie 1917',"order" => 2],
            ["title" => 'Recunoașterea internațională a Marii Uniri',"order" => 3],
            ["title" => 'Conferința de Pace de la Paris (18 ianuarie 1919 - 21 ianuarie 1920)',"order" => 4],
            ];

            $titles = [ 
                //Thema Mulțimea numerelor naturale
                'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R (userT1 teacher)',
                'Operaţii cu numere naturale (userT1 teacher)',
                'Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10 (userT1 teacher)',
                'Cel mai mare divizor comun al două numere naturale (userT1 teacher)',
                'Cel mai mic multiplu comun al două numere naturale (userT1 teacher)',
    
                //Thema Mulțimea numerelor întregi
                'Modulul numărului întreg (userT1 teacher)', 
                'Operaţii cu numere întregi (userT1 teacher)',
    
                //Thema Mulțimea numerelor raționale
                'Scrierea numerelor raţionale în diverse forme (userT1 teacher)', 
                'Operaţii cu numere raţionale (userT1 teacher)',
                
                //Thema Mulțimea numerelor reale
                'Rădăcina pătrată (radical), proprietăți  (userT1 teacher)',
                'Introducerea factorilor sub radical, scoaterea factorilor de sub radical (userT1 teacher)', 
                'Compararea unor numere ce conțin radicali. Modulul numărului real (userT1 teacher)',
                'Operații cu radicali. Raţionalizarea numitorului (userT1 teacher)', 
                'Submulţimi ale mulţimii numerelor reale. Noţiune de număr iraţional (userT1 teacher)',
    
                'Opțiunile politice în perioada neutralității (userT1 teacher)',
                'Mișcarea națională a românilor din Basarabia până în octombrie 1917 (userT1 teacher)',
                'Recunoașterea internațională a Marii Uniri (userT1 teacher)',
                'Conferința de Pace de la Paris (18 ianuarie 1919 - 21 ianuarie 1920) (userT1 teacher)',
                ];    
        $teacherId = Teacher::firstWhere('name', $teachers[$this->index])->id;

        $topic = $topics[$this->index];
        $topictId = Topic::firstWhere('name', $topic['title'])->id;
        $name = $titles[$this->index];

        $this->index++;
        return [
            'order_number' => $topic['order'],
            'name' => $name,
            'teacher_id' => $teacherId,
            'topic_id' => $topictId,
        ];
    }

}
