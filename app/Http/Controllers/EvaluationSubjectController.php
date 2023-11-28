<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EvaluationSubject;

class EvaluationSubjectController extends Controller
{
    public static function index() {
        return EvaluationSubject::all();
    }

    public static function show($id) {
        return EvaluationSubject::find($id); 
    }

    public static function themeEvaluation1(Request $request)  {

        $level = $request->query('level');
        $subjectId = $request->query('disciplina');
        $theme = $request->query('theme');

        $params = [$theme, $subjectId, $level];

        DB::statement("
        CREATE TEMPORARY TABLE temp_student_answers AS
        SELECT
            EA.id answer_id,
            ST.id student_id,
            EA.evaluation_item_id,
            EA.max_points,
            EO.id option_id,
            EO.points student_points
        FROM
            student_evaluation_answers SEA 
        INNER JOIN
            students ST ON ST.id = SEA.student_id
        INNER JOIN
            evaluation_answer_options EAO ON SEA.evaluation_answer_option_id = EAO.id
        INNER JOIN
            evaluation_answers EA ON EAO.evaluation_answer_id = EA.id
        INNER JOIN
            evaluation_items EI ON EA.evaluation_item_id = EI.id
        INNER JOIN
            evaluation_options EO ON EAO.evaluation_option_id = EO.id
        INNER JOIN
            evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
            AND ES.order_number = 1 
            AND EI.theme_id = ?
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?;
        ", $params);

        DB::statement("
        CREATE TEMPORARY TABLE temp_student_points AS
        SELECT 
            EI.theme_id,
            EI.evaluation_subject_id subject_id,
            EI.id AS item_id,
            EA.id answer_id,
            EA.max_points,
            EA.order_number answer_order,
            COALESCE(ST.student_points, 0) student_points
        FROM
            evaluation_items EI
        INNER JOIN
            evaluation_answers EA ON EA.evaluation_item_id = EI.id
        INNER JOIN
            evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
            AND ES.order_number = 1 
            AND EI.theme_id = ?
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?
        LEFT JOIN 
            temp_student_answers ST ON ST.answer_id = EA.id; 
        ", $params);

        DB::statement("
        CREATE TEMPORARY TABLE temp_student_procent AS
        SELECT 
            theme_id,
            subject_id,
            item_id,
            sum(student_points) *100/ sum(max_points) AS student_procent
        FROM temp_student_points 
        GROUP BY 
            theme_id,
            subject_id,
            item_id;        
        ");

        $result = DB::select("
        SELECT 
            EI.theme_id,
            EI.evaluation_subject_id,
            EI.id AS item_id,
            EA.id answer_id,
            EA.order_number answer_order,
            EI.task,
            EI.statement,
            EI.image_path,
            EA.content answer_text,
            EA.max_points,
            EO.id option_id,
            EO.label,
            EO.points,
            COALESCE(ST.student_procent, 0) AS student_procent 
        FROM
            evaluation_items EI
        INNER JOIN
            temp_student_procent ST ON ST.item_id = EI.id
        INNER JOIN
            evaluation_answers EA ON EA.evaluation_item_id = EI.id
        INNER JOIN
            evaluation_answer_options EAO ON EAO.evaluation_answer_id = EA.id
        INNER JOIN
            evaluation_options EO ON EAO.evaluation_option_id = EO.id
        INNER JOIN
            evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
            AND ES.order_number = 1 
            AND EI.theme_id = ?
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?;
        ", $params);

        // Transformăm rezultatul în colecție Laravel pentru a folosi metoda groupBy
        $collection = collect($result);

        // Inițializăm un array pentru rezultatul final
        $finalResult = [];

        // Grupăm inițial pe item_id
        $groupedByItem = $collection->groupBy('item_id');

        // Iterăm prin fiecare grup de item_id
        foreach ($groupedByItem as $itemGroup) {
            // Inițializăm array-ul pentru răspunsurile din acest item_id
            $answers = [];

            // Grupăm acum pe answer_id în cadrul fiecărui item_id
            $groupedByAnswer = $itemGroup->groupBy('answer_id');

            // Iterăm prin fiecare grup de answer_id
            foreach ($groupedByAnswer as $answerGroup) {
                // Inițializăm array-ul pentru opțiunile din acest answer_id
                $options = [];

                // Grupăm acum pe option_id în cadrul fiecărui answer_id
                $groupedByOption = $answerGroup->groupBy('option_id');

                // Iterăm prin fiecare grup de option_id
                foreach ($groupedByOption as $optionGroup) {
                    // Extragem prima intrare pentru a obține date comune
                    $firstOption = $optionGroup->first();

                    // Adăugăm array-ul pentru opțiune în array-ul final
                    $options[] = [
                        'option_id' => $firstOption->option_id,
                        'label' => $firstOption->label,
                        'points' => $firstOption->points,
                    ];
                }

                // Extragem prima intrare pentru a obține date comune
                $firstAnswer = $answerGroup->first();

                // Adăugăm array-ul pentru answer în array-ul final
                $answers[] = [
                    'answer_id' => $firstAnswer->answer_id,
                    'theme_id' => $firstAnswer->theme_id,
                    'subject_id' => $firstAnswer->evaluation_subject_id,
                    'answer_order' => $firstAnswer->answer_order,
                    'task' => $firstAnswer->task,
                    'statement' => $firstAnswer->statement,
                    'image_path' => $firstAnswer->image_path,
                    'answer_text' => $firstAnswer->answer_text,
                    'max_points' => $firstAnswer->max_points,
                    'options' => $options,
                ];
            }

            // Extragem prima intrare pentru a obține date comune
            $firstItem = $itemGroup->first();

            // Adăugăm array-ul pentru item în array-ul final
            $finalResult[] = [
                'item_id' => $firstItem->item_id,
                'theme_id' => $firstItem->theme_id,
                'subject_id' => $firstItem->evaluation_subject_id,
                'student_procent' => $firstItem->student_procent,
                'answers' => $answers,
            ];
        }


        return array_values($finalResult);

    }


}