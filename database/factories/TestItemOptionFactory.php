<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TestItemOption;
use App\Models\TeacherTopic;
use App\Models\Teacher;
use App\Models\Topic;
use App\Models\FormativeTest;
use App\Models\TestItem;


class TestItemOptionFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $testOptions = [
//quiz id=1:
            [
                "option" => "România a intrat în Primul Război Mondial în anul 1916",
                "explanation" => "România întradevăr a intrat în Primul Război Mondial în anul 1916",
                "texts_add" => [],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 1
            ],
            [
                "option" => "Regele României în timpul Primului Război Mondial a fost Carol I",
                "explanation" => "Regele României în timpul Primului Război Mondial nu a fost Carol I",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 1
            ],
            [
                "option" => "România a semnat Tratatul de la Trianon, care a pus capăt participării sale în Primul Război Mondial",
                "explanation" => "România a semnat Tratatul de la București, care a pus capăt participării sale în Primul Război Mondial",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 1
            ],
            [
                "option" => "Motivul principal pentru intrarea României în Primul Război Mondial a fost dorința de a-și extinde teritoriile",
                "explanation" => "Motivul principal pentru intrarea României în Primul Război Mondial a fost dorința de a susține Antanta",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 1
            ],
//quiz id=11:
            [
                "option" => "România a intrat în Primul Război Mondial de partea Puterilor Antantei",
                "explanation" => "România a intrat în Primul Război Mondial de partea Puterilor Antantei",
                "texts_add" => [],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 11
            ],
            [
                "option" => "România a intrat în Primul Război Mondial de partea Puterilor Centrale",
                "explanation" => "România nu a intrat în Primul Război Mondial de partea Puterilor Centrale",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 11
            ],
            [
                "option" => "Tratatul de la București, a dus la obținerea unor noi teritorii pentru România",
                "explanation" => "Tratatul de la București, nu a dus la obținerea unor noi teritorii pentru România",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 11
            ],
            [
                "option" => "Romania nu a participat la Primul Război Mondial",
                "explanation" => "Romania a participat la Primul Război Mondial",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 11
            ],
//quiz id=12:
            [
                "option" => "Principala bătălie în care România a suferit pierderi semnificative în timpul războiului a fost Bătălia de la Turtucaia",
                "explanation" => "Principala bătălie în care România a suferit pierderi semnificative în timpul războiului a fost Bătălia de la Turtucaia",
                "texts_add" => [],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 12
            ],
            [
                "option" => "România a intrat în Primul Război Mondial în urma unei invazii surpriză a Puterilor Centrale",
                "explanation" => "România a intrat în Primul Război Mondial nu în urma unei invazii surpriză a Puterilor Centrale",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 12
            ],
            [
                "option" => "România a intrat în Primul Război Mondial în anul 1914",
                "explanation" => "România a intrat în Primul Război Mondial în anul 1916",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 12
            ],
            [
                "option" => "Motivul principal pentru intrarea României în Primul Război Mondial a fost sprijinirea Puterilor Centrale",
                "explanation" => "Motivul principal pentru intrarea României în Primul Război Mondial a fost sprijinirea Antantei",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 12
            ],
//quiz id=13
            [
                "option" => "România a câștigat o victorie importantă în Bătălia de la Mărăști în 1917",
                "explanation" => "România a câștigat o victorie importantă în Bătălia de la Mărăști în 1917",
                "texts_add" => [],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 13
            ],
            [
                "option" => "România a intrat în Primul Război Mondial în anul 1918",
                "explanation" => "România a intrat în Primul Război Mondial în anul 1916",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 13
            ],
            [
                "option" => "România a semnat Tratatul de la Trianon, care a pus capăt participării sale în Primul Război Mondial",
                "explanation" => "România a semnat Tratatul de la București, care a pus capăt participării sale în Primul Război Mondial",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 13
            ],
            [
                "option" => "Motivul principal pentru intrarea României în Primul Război Mondial a fost sprijinirea Austro-Ungariei",
                "explanation" => "Motivul principal pentru intrarea României în Primul Război Mondial nu a fost sprijinirea Austro-Ungariei",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 13
            ],
//quiz id = 14
            [
                "option" => "România a obținut câștiguri teritoriale semnificative în urma Tratatului de la Trianon",
                "explanation" => "România a obținut câștiguri teritoriale semnificative în urma Tratatului de la Trianon",
                "texts_add" => [],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 14
            ],
            [
                "option" => "România a semnat Tratatul de la Versailles, care a pus capăt participării sale în Primul Război Mondial",
                "explanation" => "România a semnat Tratatul de la București, care a pus capăt participării sale în Primul Război Mondial",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 14
            ],
            [
                "option" => "România nu a participat la Primul Război Mondial",
                "explanation" => "România a participat la Primul Război Mondial",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 14
            ],
            [
                "option" => "România a semnat un armistițiu cu Puterile Centrale în 1917",
                "explanation" => "România nu a semnat un armistițiu cu Puterile Centrale în 1917",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "quiz",
                "task" => "Alege afirmația corectă",
                "test_item_id" => 14
            ],
//dnd - cauze
            [
                "option" => "Lupta pentru reîmpărțirea lumii",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește cauzele evenimentelor",
                "test_item_id" => 2
            ],
            [
                "option" => "Conflictele politice între marile puteri: Germania, Franța, Marea Britanie, Austro-Ungaria și Rusia",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește cauzele evenimentelor",
                "test_item_id" => 2
            ],
            [
                "option" => "Dezvoltarea economică inegală a marilor puteri de la sf. sec. XIX - începutul sec. XX",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește cauzele evenimentelor",
                "test_item_id" => 2
            ],
            [
                "option" => "Creșterea rasismului și naționalismului în formele sale extreme",
                "explanation" => "",
                "texts_add" => "null",
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește cauzele evenimentelor",
                "test_item_id" => 2
            ],
//15
            [
                "option" => "Dezvoltarea relativ slabă a României din punct de vedere economic și militar",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește cauzele evenimentelor",
                "test_item_id" => 15
            ],
            [
                "option" => "Politica de echilibru a României care se afla la granița dintre două imperii puternice, Rusia și Austro-Ungaria",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește cauzele evenimentelor",
                "test_item_id" => 15
            ],
            [
                "option" => "Dezvoltarea economică inegală a marilor puteri de la sf. sec. XIX - începutul sec. XX",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește cauzele evenimentelor",
                "test_item_id" => 15
            ],
            [
                "option" => "Absența unor alianțe clare care să-i ofere Romaniei protecție în război",
                "explanation" => "",
                "texts_add" => "null",
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește cauzele evenimentelor",
                "test_item_id" => 15
            ],
//dnd - consecințele
            [
                "option" => "Pierderi umane masive",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește consecințele evenimentelor",
                "test_item_id" => 3
            ],
            [
                "option" => "Schimbări teritoriale și politice",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește consecințele evenimentelor",
                "test_item_id" => 3
            ],
            [
                "option" => "Tratatul de la Versailles și instabilitatea postbelică",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește consecințele evenimentelor",
                "test_item_id" => 3
            ],
            [
                "option" => "Formarea Organizației Națiunilor Unite (ONU)",
                "explanation" => "",
                "texts_add" => "null",
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește consecințele evenimentelor",
                "test_item_id" => 3
            ],
//16
            [
                "option" => "Obținerea teritoriilor din Austro-Ungaria locuite de români",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește consecințele evenimentelor",
                "test_item_id" => 16
            ],
            [
                "option" => "Conflictele politice între marile puteri: Germania, Franța, Marea Britanie, Austro-Ungaria și Rusia",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește consecințele evenimentelor",
                "test_item_id" => 16
            ],
            [
                "option" => "Dezvoltarea economică inegală a marilor puteri de la sf. sec. XIX - începutul sec. XX",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește consecințele evenimentelor",
                "test_item_id" => 16
            ],
            [
                "option" => "Creșterea rasismului și naționalismului în formele sale extreme",
                "explanation" => "",
                "texts_add" => "null",
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Stabilește consecințele evenimentelor",
                "test_item_id" => 16
            ],
//check
            [
                "option" => "România a intrat în Primul Război Mondial în anul 1916",
                "explanation" => "România a intrat în Primul Război Mondial în anul 1916",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "check",
                "task" => "Verifică corectitudinea afirmațiilor",
                "test_item_id" => 4
            ],
            [
                "option" => "Regele României în timpul Primului Război Mondial a fost Carol I",
                "explanation" => "Regele României în timpul Primului Război Mondial nu a fost Carol I",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "check",
                "task" => "Verifică corectitudinea afirmațiilor",
                "test_item_id" => 4
            ],
            [
                "option" => "România a semnat Tratatul de la Trianon, care a pus capăt participării sale în Primul Război Mondial",
                "explanation" => "România a semnat Tratatul de la Trianon, care a pus capăt participării sale în Primul Război Mondial",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "check",
                "task" => "Verifică corectitudinea afirmațiilor",
                "test_item_id" => 4
            ],
            [
                "option" => "Motivul principal pentru intrarea României în Primul Război Mondial a fost dorința de a-și extinde teritoriile",
                "explanation" => "Motivul principal pentru intrarea României în Primul Război Mondial a fost dorința de a susține Antanta",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "check",
                "task" => "Verifică corectitudinea afirmațiilor",
                "test_item_id" => 4
            ],
//17
            [
                "option" => "România a intrat în Primul Război Mondial în anul 1916",
                "explanation" => "România a intrat în Primul Război Mondial în anul 1916",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "check",
                "task" => "Verifică corectitudinea afirmațiilor",
                "test_item_id" => 17
            ],
            [
                "option" => "Regele României în timpul Primului Război Mondial a fost Carol I",
                "explanation" => "Regele României în timpul Primului Război Mondial nu a fost Carol I",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "check",
                "task" => "Verifică corectitudinea afirmațiilor",
                "test_item_id" => 17
            ],
            [
                "option" => "România a semnat Tratatul de la Trianon, care a pus capăt participării sale în Primul Război Mondial",
                "explanation" => "România a semnat Tratatul de la Trianon, care a pus capăt participării sale în Primul Război Mondial",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "check",
                "task" => "Verifică corectitudinea afirmațiilor",
                "test_item_id" => 17
            ],
            [
                "option" => "Motivul principal pentru intrarea României în Primul Război Mondial a fost dorința de a-și extinde teritoriile",
                "explanation" => "Motivul principal pentru intrarea României în Primul Război Mondial a fost dorința de a susține Antanta",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "check",
                "task" => "Verifică corectitudinea afirmațiilor",
                "test_item_id" => 17
            ],
//snap
            [
                "option" => "1. Regele Mihai|B. Prim-ministru al României",
                "explanation" => "",
                "texts_add" => ["x1" => "285", "y1" => "17", "x2" => "342", "y2" => "109"],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "snap",
                "task" => "Formează perechi logice",
                "test_item_id" => 5
            ],
            [
                "option" => "4. Regele Ferdinand I|A. Instituirea regimului monarhiei autoritare",
                "explanation" => "",
                "texts_add" => ["x1" => "285", "y1" => "109", "x2" => "342", "y2" => "201"],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "snap",
                "task" => "Formează perechi logice",
                "test_item_id" => 5
            ],
            [
                "option" => "2. Generalul Alexandru Averescu|C.'Rege unificator",
                "explanation" => "",
                "texts_add" => ["x1" => "285", "y1" => "201", "x2" => "342", "y2" => "293"],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "snap",
                "task" => "Formează perechi logice",
                "test_item_id" => 5
            ],
            [
                "option" => "3. Regele Carol II|D. Greva regală",
                "explanation" => "",
                "texts_add" => ["x1" => "285", "y1" => "293", "x2" => "342", "y2" => "17"],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "snap",
                "task" => "Formează perechi logice",
                "test_item_id" => 5
            ],
//18
            [
                "option" => "1. Regele Mihai|B. Prim-ministru al României",
                "explanation" => "",
                "texts_add" => ["x1" => "285", "y1" => "17", "x2" => "342", "y2" => "109"],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "snap",
                "task" => "Formează perechi logice",
                "test_item_id" => 18
            ],
            [
                "option" => "4. Regele Ferdinand I|A. Instituirea regimului monarhiei autoritare",
                "explanation" => "",
                "texts_add" => ["x1" => "285", "y1" => "109", "x2" => "342", "y2" => "201"],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "snap",
                "task" => "Formează perechi logice",
                "test_item_id" => 18
            ],
            [
                "option" => "2. Generalul Alexandru Averescu|C.'Rege unificator",
                "explanation" => "",
                "texts_add" => ["x1" => "285", "y1" => "201", "x2" => "342", "y2" => "293"],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "snap",
                "task" => "Formează perechi logice",
                "test_item_id" => 18
            ],
            [
                "option" => "3. Regele Carol II|D. Greva regală",
                "explanation" => "",
                "texts_add" => ["x1" => "285", "y1" => "293", "x2" => "342", "y2" => "17"],
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "snap",
                "task" => "Formează perechi logice",
                "test_item_id" => 18
            ],

//dnd_group
            [
                "option" => "Germania",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_group",
                "task" => "Grupează elementele",
                "test_item_id" => 6
            ],
            [
                "option" => "Austro-Ungaria",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_group",
                "task" => "Grupează elementele",
                "test_item_id" => 6
            ],
            [
                "option" => "Franța",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 2,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_group",
                "task" => "Grupează elementele",
                "test_item_id" => 6
            ],
            [
                "option" => "Imperiul Otoman",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_group",
                "task" => "Grupează elementele",
                "test_item_id" => 6
            ],
            [
                "option" => "Bulgaria",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_group",
                "task" => "Grupează elementele",
                "test_item_id" => 6
            ],
//19
            [
                "option" => "Germania",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_group",
                "task" => "Grupează elementele",
                "test_item_id" => 19
            ],
            [
                "option" => "Austro-Ungaria",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_group",
                "task" => "Grupează elementele",
                "test_item_id" => 19
            ],
            [
                "option" => "Franța",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 2,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_group",
                "task" => "Grupează elementele",
                "test_item_id" => 19
            ],
            [
                "option" => "Imperiul Otoman",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_group",
                "task" => "Grupează elementele",
                "test_item_id" => 19
            ],
            [
                "option" => "Bulgaria",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_group",
                "task" => "Grupează elementele",
                "test_item_id" => 19
            ],
//dnd - caracteristica          
            [
                "option" => "Lupta pentru reîmpărțirea lumii",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Caracteristicile evenimentelor",
                "test_item_id" => 7
            ],
            [
                "option" => "Conflictele politice între marile puteri: Germania, Franța, Marea Britanie, Austro-Ungaria și Rusia",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Caracteristicile evenimentelor",
                "test_item_id" => 7
            ],
            [
                "option" => "Dezvoltarea economică inegală a marilor puteri de la sf. sec. XIX - începutul sec. XX",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Caracteristicile evenimentelor",
                "test_item_id" => 7
            ],
            [
                "option" => "Creșterea rasismului și naționalismului în formele sale extreme",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Caracteristicile evenimentelor",
                "test_item_id" => 7
            ],
//20
            [
                "option" => "Lupta pentru reîmpărțirea lumii",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Caracteristicile evenimentelor",
                "test_item_id" => 20
            ],
            [
                "option" => "Conflictele politice între marile puteri: Germania, Franța, Marea Britanie, Austro-Ungaria și Rusia",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Caracteristicile evenimentelor",
                "test_item_id" => 20
            ],
            [
                "option" => "Dezvoltarea economică inegală a marilor puteri de la sf. sec. XIX - începutul sec. XX",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Caracteristicile evenimentelor",
                "test_item_id" => 20
            ],
            [
                "option" => "Creșterea rasismului și naționalismului în formele sale extreme",
                "explanation" => "",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd",
                "task" => "Caracteristicile evenimentelor",
                "test_item_id" => 20
            ],

//words
            [
                "option" => "În perioada ;<1914>;-;<1916>; România a fost ;<neutră>;, deși avea un tratat de alianță cu ;<Tripla Alianță>;. Au existat dezbateri în țară privind participarea la război, iar în cele din urmă, pe ;<4 august 1916>;, România a semnat un tratat de alianță cu ;<Antanta>;, care prevedea eliberarea ;<Transilvaniei>; și realizarea unității naționale.",
                "explanation" => "În perioada 1914-1916, România a fost neutră, deși avea un tratat de alianță cu Tripla Alianță. Au existat dezbateri în țară privind participarea la război, iar în cele din urmă, pe 4 august 1916, România a semnat un tratat de alianță cu Antanta, care prevedea eliberarea Transilvaniei și realizarea unității naționale.",
                "texts_add" => ["1" => "1918", "2" => "1919 "],
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "words",
                "task" => "Completează propoziția",
                "test_item_id" => 8
            ],
//21
            [
                "option" => "În perioada ;<1914>;-;<1916>; România a fost ;<neutră>;, deși avea un tratat de alianță cu ;<Tripla Alianță>;. Au existat dezbateri în țară privind participarea la război, iar în cele din urmă, pe ;<4 august 1916>;, România a semnat un tratat de alianță cu ;<Antanta>;, care prevedea eliberarea ;<Transilvaniei>; și realizarea unității naționale.",
                "explanation" => "Europa de Vest era dominată de 2 state brown fox jumped over the dog",
                "texts_add" => ["1" => "1918", "2" => "1919 "],
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "words",
                "task" => "Completează propoziția",
                "test_item_id" => 21
            ],
//dnd_chrono_double
            [
                "option" => "România a intrat în Primul Război Mondial",
                "explanation" => "1",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono_double",
                "task" => "Elaborează un fragment de text",
                "test_item_id" => 9
            ],
            [
                "option" => "România a semnat Tratatul de la București",
                "explanation" => "4",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono_double",
                "task" => "Elaborează un fragment de text",
                "test_item_id" => 9
            ],
            [
                "option" => "România a câștigat o victorie importantă în Bătălia de la Mărăști",
                "explanation" => "3",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono_double",
                "task" => "Elaborează un fragment de text",
                "test_item_id" => 9
            ],
            [
                "option" => "Ocuparea Bucureștelui",
                "explanation" => "2",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono_double",
                "task" => "Elaborează un fragment de text",
                "test_item_id" => 9
            ],
            [
                "option" => "România a semnat Tratatul de la Versailles, care a pus capăt participării sale în Primul Război Mondial",
                "explanation" => "0",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono_double",
                "task" => "Elaborează un fragment de text",
                "test_item_id" => 9
            ],
//22
            [
                "option" => "România a intrat în Primul Război Mondial",
                "explanation" => "1",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono_double",
                "task" => "Elaborează un fragment de text",
                "test_item_id" => 22
            ],
            [
                "option" => "România a semnat Tratatul de la București",
                "explanation" => "4",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono_double",
                "task" => "Elaborează un fragment de text",
                "test_item_id" => 22
            ],
            [
                "option" => "România a câștigat o victorie importantă în Bătălia de la Mărăști",
                "explanation" => "3",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono_double",
                "task" => "Elaborează un fragment de text",
                "test_item_id" => 22
            ],
            [
                "option" => "Ocuparea Bucureștelui",
                "explanation" => "2",
                "texts_add" => null,
                "correctAnswer" => 1,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono_double",
                "task" => "Elaborează un fragment de text",
                "test_item_id" => 22
            ],
            [
                "option" => "România a semnat Tratatul de la Versailles, care a pus capăt participării sale în Primul Război Mondial",
                "explanation" => "0",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono_double",
                "task" => "Elaborează un fragment de text",
                "test_item_id" => 22
            ],
//dnd_chrono
            [
                "option" => "România a intrat în Primul Război Mondial",
                "explanation" => "1",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono",
                "task" => "Succesiunea cronologică a evenimentelor",
                "test_item_id" => 10
            ],
            [
                "option" => "România a semnat Tratatul de la București",
                "explanation" => "4",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono",
                "task" => "Succesiunea cronologică a evenimentelor",
                "test_item_id" => 10
            ],
            [
                "option" => "România a câștigat o victorie importantă în Bătălia de la Mărăști",
                "explanation" => "3",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono",
                "task" => "Succesiunea cronologică a evenimentelor",
                "test_item_id" => 10
            ],
            [
                "option" => "Ocuparea Bucureștelui",
                "explanation" => "2",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono",
                "task" => "Succesiunea cronologică a evenimentelor",
                "test_item_id" => 10
            ],
//23
            [
                "option" => "România a intrat în Primul Război Mondial",
                "explanation" => "1",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono",
                "task" => "Succesiunea cronologică a evenimentelor",
                "test_item_id" => 23
            ],
            [
                "option" => "România a semnat Tratatul de la București",
                "explanation" => "4",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono",
                "task" => "Succesiunea cronologică a evenimentelor",
                "test_item_id" => 23
            ],
            [
                "option" => "România a câștigat o victorie importantă în Bătălia de la Mărăști",
                "explanation" => "3",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono",
                "task" => "Succesiunea cronologică a evenimentelor",
                "test_item_id" => 23
            ],
            [
                "option" => "Ocuparea Bucureștelui",
                "explanation" => "2",
                "texts_add" => null,
                "correctAnswer" => 0,
                "topic" => "Opțiunile politice în perioada neutralității",
                "testType" => "dnd_chrono",
                "task" => "Succesiunea cronologică a evenimentelor",
                "test_item_id" => 23
            ],
        ];

        $testOption = $testOptions[$this->index];
        
        $option = $testOption['option'];
        $explanation = $testOption['explanation'];
        $correct = $testOption['correctAnswer'];
        $type = $testOption['testType'];
        $task = $testOption['task'];
        $text_add = json_encode($testOption['texts_add']);
        $topic = $testOption['topic'];   
        $testItemId = $testOption['test_item_id'];        
        
        // $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        // $topicId = Topic::firstWhere('name', $topic)->id;

        // $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
        //                         ->where('topic_id', $topicId)
        //                         ->first()->id;
        // $testId = FormativeTest::where('teacher_topic_id', $teacherTopictId)
        //                         ->where('type', $type)
        //                         ->first()->id;
        // $testItemId = TestItem::where('type', $type)
        //                         ->where('task', $task)
        //                         ->first()->id;
        $this->index++;

        $data = [
            'option' => $option,
            'explanation' => $explanation,
            'correct' => $correct,
            'test_item_id' => $testItemId,
        ];
        
        if ($type == "words" or $type == "snap") {
            $data['text_additional'] = $text_add;
        }
        
        return $data;
    }
}
