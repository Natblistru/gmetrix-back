<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EvaluationAnswer;
use App\Models\Evaluation;
use App\Models\EvaluationSubject;
use App\Models\EvaluationItem;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\SubjectStudyLevel;
use App\Models\Theme;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EvaluationAnswer>
 */
class EvaluationAnswerFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {

        $answers = [
            ["answers" => 'Fapt istoric: semnarea Pactului Molotov-Ribentrop din 23 august 1939', 
                "task" => 'Numește...', 
                "orderItem" => 1,
                "order_number" => 1,
                "orderSubject" => 1,
                "maxPoint" => 1],
            ["answers" => 'Argument: pe coperta cărții se vede denumirea "Pactului Molotov-Ribentrop", iar pe fotografie se văd semnatarii acestui document - Molotov și Ribentrop', 
                "task" => 'Argumentează...', 
                "orderItem" => 1,
                "order_number" => 2,
                "orderSubject" => 1,
                "maxPoint" => 2],
            ["answers" => '', 
                "task" => 'Utilizează sursele..', 
                "orderItem" => 1,
                "order_number" => 1,
                "orderSubject" => 3,
                "maxPoint" => 2],
            ["answers" => 'Reforma agrară din 1921 a reprezentat un moment crucial în istoria României, având consecințe semnificative asupra structurii sociale și economice. În acest context, vom explora trei surse istorice care aduc argumente relevante privind contribuția acestei reforme la modernizarea societății românești.\n', 
                "task" => 'Întroducere..', 
                "orderItem" => 1,
                "order_number" => 2,
                "orderSubject" => 3,
                "maxPoint" => 1],
            ["answers" => '1. Exproprierile și transferul de proprietăți:\n
                    Conform Legii de reformă agrară pentru Basarabia din 1920, proprietățile expuse exproprierii au fost transferate asupra statului, eliberându-le de obligațiuni sau sarcini. Aceasta a dus la o redistribuire a terenurilor, incluzând exproprieri de la entități precum haznale, udeluri, bănci țărănești și mănăstiri străine. Astfel, reforma agrară a reconfigurat peisajul proprietăților și a pus bazele unei noi ordini sociale.\n\n
                    2. Dificultăți în implementare și schimbări reale:\n
                    Potrivit analizei lui Svetlana Suveică, specificul reformei agrare basarabene nu a constat doar în prevederile legislative, ci și în aplicarea acestora. Dificultățile legate de deficitul de pământ și de numărul mare de țărani îndreptățiți să primească teren au generat o aplicare greoaie a legii. Cu toate acestea, exproprierile au vizat terenurile ocupate cu forța de țărani în urma mișcărilor sociale din 1917-1918, contribuind la transformarea acestora în proprietate legală. Chiar dacă loturile de împroprietărire au fost mai mici decât prevedea legea, aceasta a marcat un pas semnificativ către o mai echitabilă distribuție a resurselor.\n\n
                    3. Schimbări sociale și educație:\n
                    O altă perspectivă, exprimată de Alexandra Georgescu, subliniază schimbările pozitive în viața țăranilor ca urmare a reformei agrare. Despreșiindu-se de condițiile precare, țăranii au început să-și îmbunătățească nivelul de trai. Desigur, cu toate că rentabilitatea loturilor era redusă, reforma a avut un impact social semnificativ. Mulți țărani și-au putut trimite copiii la școli, contribuind astfel la formarea unei noi generații de intelectuali în mediul rural. Aceste schimbări sociale pozitive subliniază faptul că reforma agrară nu a avut doar un impact economic, ci și unul social și educațional.\n', 
                "task" => 'Cuprins..', 
                "orderItem" => 1,
                "order_number" => 3,
                "orderSubject" => 3,
                "maxPoint" => 1],
            ["answers" => 'În lumina argumentelor prezentate de sursele istorice, putem concluziona că reforma agrară din 1921 a avut un rol esențial în modernizarea societății românești. Prin redistribuirea terenurilor, depășirea dificultăților de implementare și generarea unor schimbări sociale semnificative, această reformă a contribuit la transformarea profundă a structurii sociale și economice a țării în perioada interbelică.', 
                "task" => 'Concluzie..', 
                "orderItem" => 1,
                "order_number" => 4,
                "orderSubject" => 3,
                "maxPoint" => 1],
            ["answers" => '', 
                "task" => 'Relevanța argumentelor..', 
                "orderItem" => 1,
                "order_number" => 5,
                "orderSubject" => 3,
                "maxPoint" => 1],
            ["answers" => '', 
                "task" => 'Argumenteaza - I argument..', 
                "orderItem" => 1,
                "order_number" => 6,
                "orderSubject" => 3,
                "maxPoint" => 2],
            ["answers" => '', 
                "task" => 'Argumenteaza - II argument..', 
                "orderItem" => 1,
                "order_number" => 7,
                "orderSubject" => 3,
                "maxPoint" => 2],
            ["answers" => '', 
                "task" => 'Argumenteaza - III argument..', 
                "orderItem" => 1,
                "order_number" => 8,
                "orderSubject" => 3,
                "maxPoint" => 2],   
            ["answers" => '', 
                "task" => 'Referințe date/personalități..', 
                "orderItem" => 1,
                "order_number" => 9,
                "orderSubject" => 3,
                "maxPoint" => 2],  
            ["answers" => '', 
                "task" => 'Corect științific..', 
                "orderItem" => 1,
                "order_number" => 10,
                "orderSubject" => 3,
                "maxPoint" => 1],  
                
                
            ["answers" => '1922 - Marșul fasciștilor italieni spre Roma:\n\n
                            Evenimentul marcant în ascensiunea fascismului italian a fost Marșul asupra Romei din 1922, condus de Benito Mussolini și trupele sale fasciste. Acest marș a dus la preluarea puterii de către Mussolini și la formarea unui guvern fascist în Italia. Consolidarea puterii fasciste în Italia a reprezentat un exemplu pentru alte mișcări totalitare din Europa și a contribuit la legitimitatea și popularitatea ideilor totalitare în perioada interbelică.\n', 
                "task" => 'Identifică I eveniment..', 
                "orderItem" => 1,
                "order_number" => 1,
                "orderSubject" => 2,
                "maxPoint" => 1], 
            ["answers" => '1933 - Numirea lui Adolf Hitler în postul de cancelar al Germaniei:\n\n
            Evenimentul-cheie în ascensiunea nazismului german a fost numirea lui Adolf Hitler în funcția de cancelar al Germaniei în 1933. Acest moment a marcat începutul oficial al guvernării naziste în Germania. Prin preluarea puterii, Hitler a instituit o serie de politici totalitare, reprimând opoziția politică și consolidând controlul asupra statului și societății. Acest eveniment a avut consecințe semnificative asupra Europei și a contribuit la intensificarea regimurilor totalitare înainte de izbucnirea celui de-al Doilea Război Mondial în 1939.', 
                "task" => 'Identifică II eveniment..', 
                "orderItem" => 1,
                "order_number" => 2,
                "orderSubject" => 2,
                "maxPoint" => 1],                 
        ];

        $answer = $answers[$this->index];


        $answerContent = $answer['answers'];
        $task = $answer['task'];        
        $maxPoint = $answer['maxPoint'];
        $orderNumber = $answer['order_number'];
        $orderItem = $answer['orderItem'];
        $orderSubject = $answer['orderSubject'];

        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjectIstoriaId = Subject::firstWhere('name', 'Istoria')->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId) 
                                                ->where('subject_id', $subjectIstoriaId) ->first()->id;

        $themeId = Theme::where('name', 'România în Primul Război Mondial')->first()->id;

        $evaluationId = Evaluation::where('subject_study_level_id', $subjectStudyLevelId)
                                        ->where('year', 2022)
                                        ->first()->id;

        $evaluation_subjectId = EvaluationSubject::where('order_number', $orderSubject)
                                        ->where('evaluation_id', $evaluationId)
                                        ->first()->id;

        $evaluation_itemId = EvaluationItem::where('order_number', $orderItem)
                                        ->where('evaluation_subject_id', $evaluation_subjectId)        
                                        ->first()->id;

        $this->index++;

        return [
            'order_number' => $orderNumber,
            'content' => $answerContent,
            'task' => $task,
            'max_points' => $maxPoint,
            'evaluation_item_id'=> $evaluation_itemId
        ];
    }
}
