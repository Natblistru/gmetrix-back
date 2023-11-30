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
        $topicFlipCards = [
            [
                "topic" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R",
                "task" => "Definiția mulțimilor",
                "answer" => "<p style='padding:15px;text-indent:20px;'><b>Mulțimea</b> este o totalitate de obiecte bine determinate și distincte, numite elementele mulțimii.<p>"
            ],
            [
                "topic" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R",
                "task" => "Modurile de reprezentare a mulțimii",
                "answer" => "<p style='padding:15px;'>1) prin <b>enumerarea</b> elementelor mulțimii (între acolade): A = {1,4, 9, 16, 25, 36, 49}<br>2) prin <b>descriere verbală</b>: 'B este mulțimea fetelor din clasa V-a'<br>3) prin <b>diagrama Venn-Euler</b><br>4) prin <b>definiția mulțimii</b>: C = {x | x∈N,x<5}"
            ],
            [
                "topic" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R",
                "task" => "Relația de apartenență la mulțime",
                "answer" => "<p style='padding:15px;text-indent:20px;'>Un element aparţine unei mulţimi dacă acel element este conţinut în mulţimea respectivă, se notează '∈'<br>Ex: pentru A = {2,3,5} 2 ∈ A; 4 ∉ A</p>"
            ],
            [
                "topic" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R",
                "task" => "Mulțimi egale",
                "answer" => "<p style='padding:15px;text-indent:20px;'><b>Mulțimi egale</b> sunt mulţimile care au aceleaşi elemente, indiferent de ordinea în care apar aceste elemente<br>Ex: {2,3,5} = {3,5,2}</p>"
            ],
            [
                "topic" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R",
                "task" => "Submulțimi",
                "answer" => "<p style='padding:15px;text-indent:20px;'><b>Submulțime</b> este o mulţime inclusă în mulţimea iniţială<br>Ex: Dacă A = {2,3,5} ,atunci submulțimile lui A sunt: {2,3}, {2,5}, {3,5}, {2}, {3}, {5}, ∅<br> Includerea (incluziunea) unei submulțimi în mulțimea inițială, se notează '⊂'<br>Ex: {2,3} ⊂  {2,3,5}; <br>{4} ⊄  {2,3,5};<br> {2,3,5} ⊄  {2,3};<br><b>Obs1</b>: Orice mulţime este inclusă în ea însăşi: A ⊂ A, pentru ∀ mulțime A.<br><b>Obs2</b>: ∅ este submulţime a oricărei mulţimi: ∅ ⊂ A, pentru  ∀ mulțime A.</p>"
            ],
            [
                "topic" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R",
                "task" => "Cardinalul mulțimii finite",
                "answer" => "<p style='padding:15px;text-indent:20px;'><b>Cardinalul mulțimii finite</b> este numărul de elemente din mulțimea respectivă, se notează 'card': <br>Ex: card {2,3,5} = 3;<br>card N = ∞;\ncard ∅ = 0</p>"
            ],
            [
                "topic" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R",
                "task" => "Operații cu mulțimi",
                "answer" => "<p style='padding:15px 15px 5px;text-indent:20px;'>1) <b>Reuniunea</b> mulțimilor A și B = toate elementele comune și necomune ale acestor mulțimi, fiecare element apărând o singură dată. <br>Ex: A = {2,3,5}, B = {3,4,5}, A ∪ B = {2,3,4,5}; </p><p style='padding:0 15px 5px;text-indent:20px;'>2) <b>Intersecția</b> mulțimilor A și B = toate elementele comune ambelor mulțimi, fiecare element apărând o singură dată.<br>Ex: A = {2,3,5}, B = {3,4,5}, A ∩ B = {3,5};<br><b><em>Notă</em></b>: <u>Mulțimi disjuncte</u> = mulțimile ce nu au nici un element comun: A ∩ B = ∅;</p><p style='padding:0 15px 5px;text-indent:20px;'>3) <b>Diferența</b> a 2 mulțimi A și B = toate elementele ce aparțin primei mulțimi (A) și nu aparțin celei de-a doua (B). <br>Ex: A = {2,3,5}, B = {3,4,5}, A \\ B = {2}; B \\ A = {4};</p><p style='padding:0 15px 15px;text-indent:20px;'>4) <b>Produs cartezian</b> a 2 mulțimi = mulțimea formată din perechi de forma (x,y), unde x ∈ A, y ∈ B; Ex: A = {1,2}, B = {a, b, c}; <br>A × B = {(1,a), (1,b), (1,c), (2,a), (2,b), (2,c)}<br>B × A = {(a,1), (b,1), (c,1), (a,2), (b,2), (c,2)} </p>"
            ],
            [
                "topic" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R",
                "task" => "Mulțimi de numere",
                "answer" => "<p style='padding:15px 15px 5px;text-indent:20px;'>1) <b>Numere naturale</b>: N = {0,1,2,3, √16…}</p><p style='padding:0 15px 5px;text-indent:20px;'>2) <b>Numere întregi</b>: Z = {-3,-2,-1,0,1,2,3,…}, Z \\ N = {-3,-2,-1}</p><p style='padding:0 15px 5px;text-indent:20px;'>3) <b>Numere raționale</b> Q = {fracții zecimale cu număr finit de zecimale} ∪ {fracții zecimale periodice} ∪ {fracții ordinare} = mulțimea numerelor care pot fi scrise sub formă de fracții între numerele întregi; <br>Q = { -0.7, 0.(3), 0.2(67), 3/4, 0, -3, 5, 8/1..}<br>Q \\ Z = { -0.7, 0.(3), 0.2(67), 3/4, }</p><p style='padding:0 15px 5px;text-indent:20px;'>4) <b>Numere reale</b> R = toate numerele</p><p style='padding:0 15px 15px;text-indent:20px;'>5) <b>Numere iraționale</b> R \\ Q = {fracţiile zecimale cu un număr infinit de zecimale neperiodice} => nu pot fi exprimate ca fracții între numerele întregi R \\ Q = {√2, √10, π, 2.3456…}</p>"
            ],
            [
                "topic" => "Mulţimi.Operaţii cu mulţimi. Mulţimile: N,Z,Q,R",
                "task" => "Incluziunile  N ⊂ Z ⊂ Q ⊂ R",
                "answer" => "<p style='padding:15px;'>Sunt adevărate incluziunile:  N ⊂ Z ⊂ Q ⊂ R</p>"
            ],
            [
                "topic" => "Operaţii cu numere naturale",
                "task" => "Proprietățile adunării",
                "answer" => "<p style='padding:15px;text-indent:20px;'>Adunarea este:<br>• <b>Comutativa</b> - la schimbarea locului termenilor suma nu se schimbă;<br>• <b>Asociativa</b> - oricum am grupa termenii, suma nu se schimbă;<br>• <b>Neutră la 0</b> - adunând un număr cu zero, obținem același număr.</p>"
            ],
            [
                "topic" => "Operaţii cu numere naturale",
                "task" => "Proprietățile înmulțirii",
                "answer" => "<p style='padding:15px;text-indent:20px;'>Înmulțirea este:<br>• <b>Comutativa</b> - La schimbarea locului factorilor produsul nu se schimbă;<br>• <b>Asociativa</b> - Oricum am grupa factorii, produsul nu se schimbă;<br>• <b>Neutră la 1</b> - Înmulțind un număr cu 1, obținem același număr;<br>• <b>Distribuitivă față de adunare</b> - Pentru a înmulți un număr cu o sumă, putem înmulți numărul cu fiecare termen al sumei, apoi să adunăm produsele obținute;<br>• <b>Distribuitivă față de scădere</b> - Pentru a înmulți un număr cu o diferență, putem înmulți numărul cu descăzutul și cu scăzătorul, apoi să scădem produsele obținute.</p>"
            ],
            [
                "topic" => "Operaţii cu numere naturale",
                "task" => "Proprietățile puterii cu exponent natural",
                "answer" => "<p style='padding:15px;'>• <b>Înmulțirea puterilor cu aceeași bază</b> - scriem baza și adunăm exponenții;<br>• <b>Împărțirea puterilor cu aceeași bază</b> - scriem baza și scădem exponenții;<br>• <b>Puterea unei puteri</b> - scriem baza și înmulțim exponenții;<br>• <b>Ridicare la putere a unui produs</b> - ridicăm fiecare factor la puterea respectivă.</p>"
            ],
            [
                "topic" => "Operaţii cu numere naturale",
                "task" => "Teorema împărțirii cu rest",
                "answer" => "<p style='padding:15px;'>Oricare ar fi numerele naturale <b>a</b> și <b>b</b>, există 2 numere naturale <b>c</b> și <b>r</b>, numite respective cât și rest, care satisfac condițiile:<br><b>a = c × b + r, unde  r > b</b></p>"
            ],
            [
                "topic" => "Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10",
                "task" => "Definiția divizorului",
                "answer" => "<p style='padding:15px;'>Numărul A este <b>divizor</b> al numărului natural C, dacă există numărul natural B astfel, încât A×B = C<br>Numărul natural nenul A este <b>divizor</b> al numărului C, dacă C se împarte exact la A.</p>"
            ],
            [
                "topic" => "Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10",
                "task" => "Definiția numărului multiplu",
                "answer" => "<p style='padding:15px;'>Numărul natural C este <b>multiplu</b> al numărului natural A, dacă C se împarte exact la A</p>"
            ],
            [
                "topic" => "Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10",
                "task" => "Definește numerele prime, numerele compuse",
                "answer" => "<p style='padding:15px 15px 5px;text-indent:20px;'><b>Numere prime</b> sunt numerele naturale > 1 care au doar 2 divizori pozitivi: 1 și ele însuși.<br>Exemplu: 2, 3, 5, 7, 11, 13, 17, etc</p><p style='padding:5px 15px 15px;text-indent:20px;'><b>Numere compuse</b> sunt numerele naturale > 1 care au mai mulți divizori pozitivi în afară de 1 și el însuși.<br>Exemplu: 4, 6, 8, 9, etc</p>"
            ],
            [
                "topic" => "Divizibilitate în N. Criteriile de divizibilitate cu 2, 3, 5, 9, 10",
                "task" => "Criteriile de divizibilitate cu 2, 3, 5, 9, 10",
                "answer" => "<p style='padding:15px 15px 5px;text-indent:20px;'>Un număr este divizibil cu:<br><b>2</b> - dacă ultima cifră este 0,2,4,6,8;<br><b>5</b> - dacă ultima cifră este 0,5;<br><b>10</b> - dacă ultima cifră este 0;<br><b>3</b> - dacă suma cifrelor sale este divizibilă cu 3;<br><b>9</b> - dacă suma cifrelor sale este divizibilă cu 9;</p>"
            ],
            [
                "topic" => "Cel mai mare divizor comun al două numere naturale",
                "task" => "Etapele descompunerii numărului în produs de factori primi",
                "answer" => "<p style='padding:15px;'>1) Identificăm cel mai mic divizor prim al numărului;<br>2) Împărțim numărul la acest divizor;<br>3) Identificăm cel mai mic divizor prim al câtului obținut;<br>4) Împărțim câtul la acest divizor;<br>5) Repetăm acești pași până obținem câtul 1;<br>6) Descompunerea numărului în factori primi este egală cu produsul divizorilor identificați.</p>"
            ],
            [
                "topic" => "Cel mai mare divizor comun al două numere naturale",
                "task" => "Cel mai mare divizor comun al numerelor naturale",
                "answer" => "<p style='padding:15px;'><b>Cel mai mare divizor comun</b> al numerelor naturale a și b este cel mai mare număr natural care se împarte exact la fiecare dintre numerele a și b.<br> Se calculează ca un produs de  factorilor comuni la puterea cea mai mică<br>Se notează: (a,b)</p>"
            ],
            [
                "topic" => "Cel mai mare divizor comun al două numere naturale",
                "task" => "Numere prime între ele",
                "answer" => "<p style='padding:15px;'><b>Numere prime</b> - numerele care au cel mai mare divizor comun pe 1.<br>Se notează: (a,b) = 1</p>"
            ],
            [
                "topic" => "Cel mai mic multiplu comun al două numere naturale",
                "task" => "Multipli comuni ai două numere naturale",
                "answer" => "<p style='padding:15px;'><b>Multipli comuni ai două numere naturale</b> - mulțimea numerelor care aparțin intersecției multimii multiplilor fiecărui număr respectiv.</p>"
            ],
            [
                "topic" => "Cel mai mic multiplu comun al două numere naturale",
                "task" => "Cel mai mic multiplu comun al numerelor naturale",
                "answer" => "<p style='padding:15px;'><b>Cel mai mic multiplu comun</b> al numerelor naturale a și b - este cel mai mic număr natural nenul la care se împarte exact fiecare dintre numerele a și b.<br>Se notează: [a,b]</p>"
            ]
        ];

        $topicFlipCard = $topicFlipCards[$this->index];

        $topic = $topicFlipCard['topic'];
        $task = $topicFlipCard['task'];
        $answer = $topicFlipCard['answer'];


        $topicId = Topic::firstWhere('name', $topic)->id;

        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                ->where('topic_id', $topicId)
                                ->first()->id;

        $this->index++;

        return [
            'task' => $task,
            'answer' => $answer,
            'teacher_topic_id' => $teacherTopictId,
        ];
    }
}
