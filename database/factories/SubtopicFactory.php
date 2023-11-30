<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Topic;
use App\Models\TeacherTopic;
use App\Models\Teacher;

class SubtopicFactory extends Factory
{
    private $index = 0;

    public function definition(): array
    {
        $subtopicPaths = [
            ["name" => "Definiția mulțimilor", "theme" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R", "path" => "/sound/audio-joiner1_31.mp3"],
            ["name" => "Modurile de reprezentare a mulțimii", "theme" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R", "path" => "/sound/soundsample.mp3"],
            ["name" => "Relația de apartenență la mulțime", "theme" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R", "path" => "/sound/soundsample.mp3"],
            ["name" => "Mulțimi egale", "theme" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R", "path" => "/sound/soundsample.mp3"],
            ["name" => "Submulțimi", "theme" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R", "path" => "/sound/soundsample.mp3"],
            ["name" => "Cardinalul mulțimii finite", "theme" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R", "path" => "/sound/soundsample.mp3"],
            ["name" => "Operații cu mulțimi", "theme" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R", "path" => "/sound/soundsample.mp3"],
            ["name" => "Mulțimi de numere", "theme" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R", "path" => "/sound/soundsample.mp3"],
            ["name" => "Incluziunile N ⊂ Z ⊂ Q ⊂ R", "theme" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R", "path" => "/sound/soundsample.mp3"],
            ["name" => "Adunarea și scăderea numerelor naturale. Proprietăți", "theme" => "Operaţii cu numere naturale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Înmulțirea numerelor naturale. Proprietăți. Factorul comun", "theme" => "Operaţii cu numere naturale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Puterea cu exponent număr natural. Proprietăți", "theme" => "Operaţii cu numere naturale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Împărțirea numerelor naturale. Împărțirea cu rest", "theme" => "Operaţii cu numere naturale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Divizor. Mulțimea divizorilor unui număr natural", "theme" => "Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10", "path" => "/sound/soundsample.mp3"],
            ["name" => "Multiplu. Mulțimea multiplilor unui număr natural", "theme" => "Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10", "path" => "/sound/soundsample.mp3"],
            ["name" => "Numere prime, numere compuse", "theme" => "Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10", "path" => "/sound/soundsample.mp3"],
            ["name" => "Criteriile de divizibilitate cu 2, 3, 5, 9, 10. Numere pare, impare", "theme" => "Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10", "path" => "/sound/soundsample.mp3"],
            ["name" => "Descompunerea numerelor naturale în produs de puteri de numere prime", "theme" => "Cel mai mare divizor comun al două numere naturale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Divizor comun al două numere naturale. C.m.m.d.c. al două numere naturale", "theme" => "Cel mai mare divizor comun al două numere naturale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Numere prime între ele", "theme" => "Cel mai mare divizor comun al două numere naturale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Multipli comuni ai două numere naturale", "theme" => "Cel mai mic multiplu comun al două numere naturale", "path" => "/sound/soundsample.mp3"],
            ["name" => "C.m.m.m.c. al două numere naturale", "theme" => "Cel mai mic multiplu comun al două numere naturale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Definiție. Propietăți", "theme" => "Modulul numărului întreg", "path" => "/sound/soundsample.mp3"],
            ["name" => "Prezentarea pe axa numerică", "theme" => "Modulul numărului întreg", "path" => "/sound/soundsample.mp3"],
            ["name" => "Operații de adunare, scădere, împărțire, înmulțire", "theme" => "Operaţii cu numere întregi", "path" => "/sound/soundsample.mp3"],
            ["name" => "Puterea unui număr întreg cu exponent număr întreg", "theme" => "Operaţii cu numere întregi", "path" => "/sound/soundsample.mp3"],
            ["name" => "Fracții ordinare", "theme" => "Scrierea numerelor raţionale în diverse forme", "path" => "/sound/soundsample.mp3"],
            ["name" => "Fracții zecimale", "theme" => "Scrierea numerelor raţionale în diverse forme", "path" => "/sound/soundsample.mp3"],
            ["name" => "Operații de adunare și scădere", "theme" => "Operaţii cu numere raţionale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Operații de înmulțire", "theme" => "Operaţii cu numere raţionale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Operații de împărţire", "theme" => "Operaţii cu numere raţionale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Fracții etajate", "theme" => "Operaţii cu numere raţionale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Ridicare la putere cu exponent număr întreg a unui număr rațional", "theme" => "Operaţii cu numere raţionale", "path" => "/sound/soundsample.mp3"],
            ["name" => "Definiția rădăcinii pătrate,proprietăți", "theme" => "Rădăcina pătrată (radical), proprietăți", "path" => "/sound/soundsample.mp3"],
            ["name" => "Introducerea factorilor sub radical", "theme" => "Introducerea factorilor sub radical, scoaterea factorilor de sub radical", "path" => "/sound/soundsample.mp3"],
            ["name" => "Scoaterea factorilor de sub radical", "theme" => "Introducerea factorilor sub radical, scoaterea factorilor de sub radical", "path" => "/sound/soundsample.mp3"],
            ["name" => "Compararea unor numere ce conțin radicali", "theme" => "Compararea unor numere ce conțin radicali. Modulul numărului real", "path" => "/sound/soundsample.mp3"],
            ["name" => "Modulul numărului real. Proprietăți", "theme" => "Compararea unor numere ce conțin radicali. Modulul numărului real", "path" => "/sound/soundsample.mp3"],
            ["name" => "Adunarea și scăderea radicalilor", "theme" => "Operații cu radicali. Raţionalizarea numitorului", "path" => "/sound/soundsample.mp3"],
            ["name" => "Înmulțirea și Împărțirea Radicalilor", "theme" => "Operații cu radicali. Raţionalizarea numitorului", "path" => "/sound/soundsample.mp3"],
            ["name" => "Raţionalizarea numitorului", "theme" => "Operații cu radicali. Raţionalizarea numitorului", "path" => "/sound/soundsample.mp3"],
            ["name" => "Submulţimi ale mulţimii numerelor reale", "theme" => "Submulţimi ale mulţimii numerelor reale. Noţiune de număr iraţional", "path" => "/sound/soundsample.mp3"],
            ["name" => "Noţiune de număr iraţional", "theme" => "Submulţimi ale mulţimii numerelor reale. Noţiune de număr iraţional", "path" => "/sound/soundsample.mp3"],
        ];

        $subtopicPath = $subtopicPaths[$this->index];        
        
        $name = $subtopicPath['name'];
        $path = $subtopicPath['path'];
        $topic = $subtopicPath['theme'];

        $topicId = Topic::firstWhere('name', $topic)->id;
 
        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                ->where('topic_id', $topicId)
                                ->first()->id;
        $this->index++;

        return [
            'name' => $name,
            'teacher_topic_id' => $teacherTopictId,
            'audio_path' => $path
        ];
    }
}

