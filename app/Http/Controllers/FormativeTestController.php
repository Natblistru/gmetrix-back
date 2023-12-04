<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FormativeTest;

class FormativeTestController extends Controller
{
    public static function index() {
        return FormativeTest::all();
    }

    public static function show($id) {
        return FormativeTest::find($id); 
    }

    public static function formativeTest(Request $request)  {

        $teacher_topic_id = $request->query('topic');

        $params = [$teacher_topic_id];

        DB::statement("
        CREATE TEMPORARY TABLE temp_test_item_columns AS
        SELECT 
            TI.id,
            GROUP_CONCAT(TIC.title ORDER BY TIC.order_number SEPARATOR ', ') AS column_titles
        FROM  
            formative_test_items AS FTI
        INNER JOIN
            formative_tests FT ON FTI.formative_test_id = FT.id AND FT.teacher_topic_id = ?
        INNER JOIN 
            test_items TI ON FTI.test_item_id = TI.id
        LEFT JOIN 
            test_item_columns TIC ON TIC.test_item_id = TI.id
        GROUP BY TI.id;
        ", $params);

        $result = DB::select("
        SELECT 
            FTI.formative_test_id,
            FT.title,
            FT.path,
            FT.type,
            FT.order_number order_test,
            FTI.order_number,
            TI.id AS test_item_id,
            TI.task,
            TI.type AS item_type,
            TI.test_complexity_id,
            COALESCE(TTIC.column_titles, '') AS column_title,
            TIO.id AS option_id,
            TIO.option,
            TIO.explanation,
            TIO.text_additional,
            TIO.correct
        FROM  
            formative_test_items AS FTI
        INNER JOIN
            formative_tests FT ON FTI.formative_test_id = FT.id AND FT.teacher_topic_id = ?
        INNER JOIN 
            test_items TI ON FTI.test_item_id = TI.id
        INNER JOIN
            test_item_options TIO ON TIO.test_item_id = TI.id
        LEFT JOIN 
        	temp_test_item_columns TTIC ON TTIC.id = TI.id 
        ", $params);

        $collection = collect($result);

        $finalResult = [];
        
        $groupedByFormativeTest = $collection->groupBy('formative_test_id');

        foreach ($groupedByFormativeTest as $formativeTestGroup) {
            $groupedByOrderNumber = $formativeTestGroup->groupBy('order_number');

            $orderNumberOptions = [];

            foreach ($groupedByOrderNumber as $orderNumberGroup) {
                $formativeTestDetails = $orderNumberGroup->first();

                $testItemOptions = [];

                foreach ($orderNumberGroup as $item) {
                    if ($item instanceof \stdClass) {
                        $testItemOptions[] = [
                            'option_id' => $item->option_id,
                            'option' => $item->option,
                            'explanation' => $item->explanation,
                            'text_additional' => $item->text_additional,
                            'correct' => $item->correct,
                        ];
                    }
                }

                $orderNumberOptions[] = [
                    'order_number' => $formativeTestDetails->order_number,
                    'test_item_id' => $formativeTestDetails->test_item_id,
                    'test_item_task' => $formativeTestDetails->task,
                    'test_item_complexity' => $formativeTestDetails->test_complexity_id,
                    'test_item_options' => $testItemOptions,
                ];
            }

            // Adăugăm array-ul cu opțiuni în array-ul final pentru fiecare formative_test_id
            $finalResult[] = [
                'formative_test_id' => $formativeTestGroup->first()->formative_test_id,
                'order_test' => $formativeTestGroup->first()->order_test,
                'type' => $formativeTestGroup->first()->type,
                'title' => $formativeTestGroup->first()->title,
                'column_title' => $formativeTestGroup->first()->column_title,
                'path' => $formativeTestGroup->first()->path,
                'order_number_options' => $orderNumberOptions,
            ];
        }

        return array_values($finalResult);
    }

}
