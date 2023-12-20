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
            'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
            'Operaţii cu numere naturale',
            'Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10',
            'Cel mai mare divizor comun al două numere naturale',
            'Cel mai mic multiplu comun al două numere naturale',

            //Thema Mulțimea numerelor întregi
            'Modulul numărului întreg', 
            'Operaţii cu numere întregi',

            //Thema Mulțimea numerelor raționale
            'Scrierea numerelor raţionale în diverse forme', 
            'Operaţii cu numere raţionale',
            
            //Thema Mulțimea numerelor reale
            'Rădăcina pătrată (radical), proprietăți',
            'Introducerea factorilor sub radical, scoaterea factorilor de sub radical', 
            'Compararea unor numere ce conțin radicali. Modulul numărului real',
            'Operații cu radicali. Raţionalizarea numitorului', 
            'Submulţimi ale mulţimii numerelor reale. Noţiune de număr iraţional',

            'Opțiunile politice în perioada neutralității',
            'Mișcarea națională a românilor din Basarabia până în octombrie 1917',
            'Recunoașterea internațională a Marii Uniri',
            'Conferința de Pace de la Paris (18 ianuarie 1919 - 21 ianuarie 1920)',
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
        $topictId = Topic::firstWhere('name', $topics[$this->index])->id;
        $name = $titles[$this->index];

        $this->index++;
        return [
            'name' => $name,
            'teacher_id' => $teacherId,
            'topic_id' => $topictId,
        ];
    }

}
