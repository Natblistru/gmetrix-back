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
            'Mișcarea națională a românilor din Basarabia și teritoriile din stânga Nistrului',
            'Formarea Statului Național Unitar Român. Recunoașterea Marii Uniri de la 1918',
            'Conferinţa de Pace de la Paris. Sistemul de la Versailles'
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
