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
        $options = [//quiz
                'România a intrat în Primul Război Mondial în anul 1916',
                'Regele României în timpul Primului Război Mondial a fost Carol I',
                'România a semnat Tratatul de la Trianon, care a pus capăt participării sale în Primul Război Mondial',
                'Motivul principal pentru intrarea României în Primul Război Mondial a fost dorința de a-și extinde teritoriile',
                //cauze
                'Lupta pentru reîmpărțirea lumii',
                'Conflictele politice între marile puteri: Germania, Franța, Marea Britanie, Austro-Ungaria și Rusia',
                'Dezvoltarea economică inegală a marilor puteri de la sf. sec. XIX - începutul sec. XX',
                'Creșterea rasismului și naționalismului în formele sale extreme',
                //consecinte
                'Pierderi umane masive',
                'Schimbări teritoriale și politice',
                'Tratatul de la Versailles și instabilitatea postbelică',
                'Formarea Organizației Națiunilor Unite (ONU)',
                //check
                'România a intrat în Primul Război Mondial în anul 1916',
                'Regele României în timpul Primului Război Mondial a fost Carol I',
                'România a semnat Tratatul de la Trianon, care a pus capăt participării sale în Primul Război Mondial',
                'Motivul principal pentru intrarea României în Primul Război Mondial a fost dorința de a-și extinde teritoriile',
                //snap
                "1. Regele Mihai|B. Prim-ministru al României", 
                "4. Regele Ferdinand I|A. Instituirea regimului monarhiei autoritare",
                "2. Generalul Alexandru Averescu|C.'Rege unificator'",
                "3. Regele Carol II|D. Greva regală",
                //group
                'Germania',
                'Austro-Ungaria',
                'Franța',
                'Imperiul Otoman',     
                'Bulgaria',   
                //caracteristica 
                'Lupta pentru reîmpărțirea lumii',
                'Conflictele politice între marile puteri: Germania, Franța, Marea Britanie, Austro-Ungaria și Rusia',
                'Dezvoltarea economică inegală a marilor puteri de la sf. sec. XIX - începutul sec. XX',
                'Creșterea rasismului și naționalismului în formele sale extreme', 
                //words
                'În perioada ;<1914>;-;<1916>; România a fost ;<neutră>;, deși avea un tratat de alianță cu ;<Tripla Alianță>;. Au existat dezbateri în țară privind participarea la război, iar în cele din urmă, pe ;<4 august 1916>;, România a semnat un tratat de alianță cu ;<Antanta>;, care prevedea eliberarea ;<Transilvaniei>; și realizarea unității naționale.', 
                //chronoDouble
                'România a intrat în Primul Război Mondial',
                'România a semnat Tratatul de la București',
                'România a câștigat o victorie importantă în Bătălia de la Mărăști',
                'Ocuparea Bucureștelui',     
                'România a semnat Tratatul de la Versailles, care a pus capăt participării sale în Primul Război Mondial',                   
                //chrono
                'România a intrat în Primul Război Mondial',
                'România a semnat Tratatul de la București',
                'România a câștigat o victorie importantă în Bătălia de la Mărăști',
                'Ocuparea Bucureștelui',             
            ];
        $explanations = [
                'România întradevăr a intrat în Primul Război Mondial în anul 1916',
                'Regele României în timpul Primului Război Mondial nu a fost Carol I',
                'România a semnat Tratatul de la București, care a pus capăt participării sale în Primul Război Mondial',
                'Motivul principal pentru intrarea României în Primul Război Mondial a fost dorința de a susține Antanta',
                //cauze
                '',
                '',
                '',
                '',    
                //consecinte
                '',
                '',
                '',
                '',   
                //check
                'România a intrat în Primul Război Mondial în anul 1916',
                'Regele României în timpul Primului Război Mondial nu a fost Carol I',
                'România a semnat Tratatul de la Trianon, care a pus capăt participării sale în Primul Război Mondial',
                'Motivul principal pentru intrarea României în Primul Război Mondial a fost dorința de a susține Antanta',              
                //snap
                '',
                '',
                '',
                '', 
                //group
                '',
                '',
                '',
                '',  
                '',
                //caracteristica
                '',
                '',
                '',
                '',  
                //words
                'În perioada 1914-1916, România a fost neutră, deși avea un tratat de alianță cu Tripla Alianță. Au existat dezbateri în țară privind participarea la război, iar în cele din urmă, pe 4 august 1916, România a semnat un tratat de alianță cu Antanta, care prevedea eliberarea Transilvaniei și realizarea unității naționale.',  
                //chronoDouble
                '1',
                '4',
                '3',
                '2',     
                '0',                   
                //chrono
                '1',
                '4',
                '3',
                '2',              
            ];
        $texts_add = [ //quiz
            [],null,null,null,
            //cauze
            null,null,null,null,
            //consecinte
            null,null,null,null,  
            //check
            null,null,null,null, 
            //snap
            ["x1" => "285", "y1" => "17", "x2" => "342", "y2" => "109"],
            ["x1" => "285", "y1" => "109", "x2" => "342", "y2" => "201"],
            ["x1" => "285", "y1" => "201", "x2" => "342", "y2" => "293"],
            ["x1" => "285", "y1" => "293", "x2" => "342", "y2" => "17"],
            //group
            null,null,null,null,null,   
            //caracteristica
            null,null,null,null,                                 
            ["1" => "1918",
                "2" => "1919 ",
            ],
            //chronoDouble
            null,null,null,null,null,  
            //chrono
            null,null,null,null,
        ];
        $correctAnswers = [1,0,0,0, 1,1,1,0, 1,1,1,0, 1,0,1,0, 1,1,1,1, 1,1,2,1,1, 1,1,1,0, 0, 1,1,1,1,0, 0, 0, 0, 0];
        $testTypes =     ['quiz','quiz','quiz','quiz', 
                            'dnd','dnd','dnd','dnd', 
                            'dnd','dnd','dnd','dnd',
                            'check','check','check','check',
                            'snap','snap','snap','snap',
                            'dnd_group','dnd_group','dnd_group','dnd_group','dnd_group',
                            'dnd','dnd','dnd','dnd',
                            'words',
                            'dnd_chrono_double','dnd_chrono_double','dnd_chrono_double','dnd_chrono_double','dnd_chrono_double',
                            'dnd_chrono','dnd_chrono','dnd_chrono','dnd_chrono'];
        $tasks = ["Alege afirmația corectă",
            "Alege afirmația corectă",
            "Alege afirmația corectă",
            "Alege afirmația corectă",
            "Stabilește cauzele evenimentelor", 
            "Stabilește cauzele evenimentelor", 
            "Stabilește cauzele evenimentelor", 
            "Stabilește cauzele evenimentelor", 
            "Stabilește consecințele evenimentelor", 
            "Stabilește consecințele evenimentelor",
            "Stabilește consecințele evenimentelor",
            "Stabilește consecințele evenimentelor",
            "Verifică corectitudinea afirmațiilor", 
            "Verifică corectitudinea afirmațiilor",
            "Verifică corectitudinea afirmațiilor",
            "Verifică corectitudinea afirmațiilor",
            "Formează perechi logice",             
            "Formează perechi logice", 
            "Formează perechi logice", 
            "Formează perechi logice", 
            "Grupează elementele", 
            "Grupează elementele",
            "Grupează elementele",
            "Grupează elementele",
            "Grupează elementele",
            "Caracteristicile evenimentelor", 
            "Caracteristicile evenimentelor",
            "Caracteristicile evenimentelor",
            "Caracteristicile evenimentelor",
            "Completează propoziția", 
            "Elaborează un fragment de text", 
            "Elaborează un fragment de text", 
            "Elaborează un fragment de text", 
            "Elaborează un fragment de text", 
            "Elaborează un fragment de text", 
            "Succesiunea cronologică a evenimentelor",
            "Succesiunea cronologică a evenimentelor",
            "Succesiunea cronologică a evenimentelor",
            "Succesiunea cronologică a evenimentelor"
        ];
        
        $option = $options[$this->index];
        $explanation = $explanations[$this->index];
        $correct = $correctAnswers[$this->index];
        $type = $testTypes[$this->index];
        $task = $tasks[$this->index];
        $text_add = json_encode($texts_add[$this->index]);
        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $topicId = Topic::firstWhere('name', 'Opțiunile politice în perioada neutralității')->id;

        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                ->where('topic_id', $topicId)
                                ->first()->id;
        $testId = FormativeTest::where('teacher_topic_id', $teacherTopictId)
                                ->where('type', $type)
                                ->first()->id;
        $testItemId = TestItem::where('type', $type)
                                ->where('task', $task)
                                ->first()->id;
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
