<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Topic;
use App\Models\TeacherTopic;
use App\Models\Teacher;

class SubtopicFactory extends Factory
{
    private $values = [
        //TOPIC Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R
        "Definiția mulțimilor",
        "Modurile de reprezentare a mulțimii",
        "Relația de apartenență la mulțime",
        "Mulțimi egale",
        "Submulțimi",
        "Cardinalul mulțimii finite",
        "Operații cu mulțimi",
        "Mulțimi de numere",
        "Incluziunile N ⊂ Z ⊂ Q ⊂ R",

        //TOPIC Operaţii cu numere naturale
        "Adunarea și scăderea numerelor naturale. Proprietăți",
        "Înmulțirea numerelor naturale. Proprietăți. Factorul comun",
        "Puterea cu exponent număr natural. Proprietăți",
        "Împărțirea numerelor naturale. Împărțirea cu rest",

        //TOPIC Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10
        "Divizor. Mulțimea divizorilor unui număr natural",
        "Multiplu. Mulțimea multiplilor unui număr natural",
        "Numere prime, numere compuse",
        "Criteriile de divizibilitate cu 2, 3, 5, 9, 10. Numere pare, impare",

        //TOPIC Cel mai mare divizor comun al două numere naturale
        "Descompunerea numerelor naturale în produs de puteri de numere prime",
        "Divizor comun al două numere naturale. C.m.m.d.c. al două numere naturale",
        "Numere prime între ele",

        //TOPIC Cel mai mic multiplu comun al două numere naturale
        "Multipli comuni ai două numere naturale",
        "C.m.m.m.c. al două numere naturale",

        //TOPIC Modulul numărului întreg
        "Definiție. Propietăți",
        "Prezentarea pe axa numerică",

        //TOPIC Operaţii cu numere întregi
        "Operații de adunare, scădere, împărțire, înmulțire",
        "Puterea unui număr întreg cu exponent număr întreg",

        //TOPIC Scrierea numerelor raţionale în diverse forme
        "Fracții ordinare",
        "Fracții zecimale",

        //TOPIC Operaţii cu numere raţionale
        "Operații de adunare și scădere",
        "Operații de înmulțire",
        "Operații de împărţire",
        "Fracții etajate",
        "Ridicare la putere cu exponent număr întreg a unui număr rațional",

        //TOPIC Rădăcina pătrată (radical), proprietăți
        "Definiția rădăcinii pătrate,proprietăți",
        
        //TOPIC Introducerea factorilor sub radical, scoaterea factorilor de sub radical
        "Introducerea factorilor sub radical",
        "Scoaterea factorilor de sub radical",

        //TOPIC Compararea unor numere ce conțin radicali. Modulul numărului real
        "Compararea unor numere ce conțin radicali",
        "Modulul numărului real. Proprietăți",

        //TOPIC Operații cu radicali. Raţionalizarea numitorului
        "Adunarea și scăderea radicalilor",
        "Înmulțirea și Împărțirea Radicalilor",
        "Raţionalizarea numitorului",

        //TOPIC Submulţimi ale mulţimii numerelor reale. Noţiune de număr iraţional
        "Submulţimi ale mulţimii numerelor reale",
        "Noţiune de număr iraţional",
    ];

    private $paths = [
        'calea-spre-audio1', 'calea-spre-audio2', 'calea-spre-audio3','calea-spre-audio4','calea-spre-audio5',
        'calea-spre-audio6', 'calea-spre-audio7', 'calea-spre-audio8','calea-spre-audio9','calea-spre-audio10',
        'calea-spre-audio11', 'calea-spre-audio12', 'calea-spre-audio13','calea-spre-audio14','calea-spre-audio15',
        'calea-spre-audio16', 'calea-spre-audio17', 'calea-spre-audio18','calea-spre-audio19','calea-spre-audio20',
        'calea-spre-audio21', 'calea-spre-audio22', 'calea-spre-audio23','calea-spre-audio24','calea-spre-audio25',
        'calea-spre-audio26', 'calea-spre-audio27', 'calea-spre-audio28','calea-spre-audio29','calea-spre-audio30',
        'calea-spre-audio31', 'calea-spre-audio32', 'calea-spre-audio33','calea-spre-audio34','calea-spre-audio35',
        'calea-spre-audio36', 'calea-spre-audio37', 'calea-spre-audio38','calea-spre-audio39','calea-spre-audio40',
        'calea-spre-audio41', 'calea-spre-audio42', 'calea-spre-audio43'

    ];

    private $index = 0;

    public function definition(): array
    {
        $topics = [
            //Thema Mulțimea numerelor naturale
            'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
            'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
            'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
            'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
            'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
            'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
            'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
            'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
            'Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R',
            'Operaţii cu numere naturale',
            'Operaţii cu numere naturale',
            'Operaţii cu numere naturale',
            'Operaţii cu numere naturale',
            'Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10',
            'Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10',
            'Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10',
            'Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10',
            'Cel mai mare divizor comun al două numere naturale',
            'Cel mai mare divizor comun al două numere naturale',
            'Cel mai mare divizor comun al două numere naturale',
            'Cel mai mic multiplu comun al două numere naturale',
            'Cel mai mic multiplu comun al două numere naturale',

            //Thema Mulțimea numerelor întregi
            'Modulul numărului întreg', 
            'Modulul numărului întreg', 
            'Operaţii cu numere întregi',
            'Operaţii cu numere întregi',

            //Thema Mulțimea numerelor raționale
            'Scrierea numerelor raţionale în diverse forme', 
            'Scrierea numerelor raţionale în diverse forme',
            'Operaţii cu numere raţionale',
            'Operaţii cu numere raţionale',
            'Operaţii cu numere raţionale',
            'Operaţii cu numere raţionale',
            'Operaţii cu numere raţionale',
            
            //Thema Mulțimea numerelor reale
            'Rădăcina pătrată (radical), proprietăți',
            'Introducerea factorilor sub radical, scoaterea factorilor de sub radical', 
            'Introducerea factorilor sub radical, scoaterea factorilor de sub radical',
            'Compararea unor numere ce conțin radicali. Modulul numărului real',
            'Compararea unor numere ce conțin radicali. Modulul numărului real',
            'Operații cu radicali. Raţionalizarea numitorului', 
            'Operații cu radicali. Raţionalizarea numitorului', 
            'Operații cu radicali. Raţionalizarea numitorului', 
            'Submulţimi ale mulţimii numerelor reale. Noţiune de număr iraţional',
            'Submulţimi ale mulţimii numerelor reale. Noţiune de număr iraţional',
                ];

        $topicId = Topic::firstWhere('name', $topics[$this->index])->id;
 

        $name = $this->values[$this->index];

        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                ->where('topic_id', $topicId)
                                ->first()->id;


        $path = $this->paths[$this->index];

        $this->index++;

        return [
            'name' => $name,
            'teacher_topic_id' => $teacherTopictId,
            'audio_path' => $path
        ];
    }
}

