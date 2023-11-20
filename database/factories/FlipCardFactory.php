<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FlipCard;
use App\Models\TeacherTopic;
use App\Models\Teacher;
use App\Models\Topic;

class FlipCardFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $tasks = ['Operații cu mulțimi',
                'Mulțimi de numere'
            ];
        $answers = ["<p style='padding:15px 15px 5px;text-indent:20px;'>1) <b>Reuniunea</b> mulțimilor A și B = toate elementele comune și necomune ale acestor mulțimi, fiecare element apărând o singură dată. <br>Ex: A = {2,3,5}, B = {3,4,5}, A ∪ B = {2,3,4,5}; </p><p style='padding:0 15px 5px;text-indent:20px;'>2) <b>Intersecția</b> mulțimilor A și B = toate elementele comune ambelor mulțimi, fiecare element apărând o singură dată.<br>Ex: A = {2,3,5}, B = {3,4,5}, A ∩ B = {3,5};<br><b><em>Notă</em></b>: <u>Mulțimi disjuncte</u> = mulțimile ce nu au nici un element comun: A ∩ B = ∅;</p><p style='padding:0 15px 5px;text-indent:20px;'>3) <b>Diferența</b> a 2 mulțimi A și B = toate elementele ce aparțin primei mulțimi (A) și nu aparțin celei de-a doua (B). <br>Ex: A = {2,3,5}, B = {3,4,5}, A \\ B = {2}; B \\ A = {4};</p><p style='padding:0 15px 15px;text-indent:20px;'>4) <b>Produs cartezian</b> a 2 mulțimi = mulțimea formată din perechi de forma (x,y), unde x ∈ A, y ∈ B; Ex: A = {1,2}, B = {a, b, c}; <br>A × B = {(1,a), (1,b), (1,c), (2,a), (2,b), (2,c)}<br>B × A = {(a,1), (b,1), (c,1), (a,2), (b,2), (c,2)} </p>",
        
        "<p style='padding:15px 15px 5px;text-indent:20px;'>1) <b>Numere naturale</b>: N = {0,1,2,3, √16…}</p><p style='padding:0 15px 5px;text-indent:20px;'>2) <b>Numere întregi</b>: Z = {-3,-2,-1,0,1,2,3,…}, Z \\ N = {-3,-2,-1}</p><p style='padding:0 15px 5px;text-indent:20px;'>3) <b>Numere raționale</b> Q = {fracții zecimale cu număr finit de zecimale} ∪ {fracții zecimale periodice} ∪ {fracții ordinare} = mulțimea numerelor care pot fi scrise sub formă de fracții între numerele întregi; <br>Q = { -0.7, 0.(3), 0.2(67), 3/4, 0, -3, 5, 8/1..}<br>Q \\ Z = { -0.7, 0.(3), 0.2(67), 3/4, }</p><p style='padding:0 15px 5px;text-indent:20px;'>4) <b>Numere reale</b> R = toate numerele</p><p style='padding:0 15px 15px;text-indent:20px;'>5) <b>Numere iraționale</b> R \\ Q = {fracţiile zecimale cu un număr infinit de zecimale neperiodice} => nu pot fi exprimate ca fracții între numerele întregi R \\ Q = {√2, √10, π, 2.3456…}</p>"
            ];
        $topics = ['Operaţii cu numere naturale',
                'Operaţii cu numere naturale'
            ];
        $topicId = Topic::firstWhere('name', $topics[$this->index])->id;

        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                ->where('topic_id', $topicId)
                                ->first()->id;

        $task = $tasks[$this->index];
        $answer = $answers[$this->index];

        $this->index++;

        return [
            'task' => $task,
            'answer' => $answer,
            'teacher_topic_id' => $teacherTopictId,
        ];
    }
}
