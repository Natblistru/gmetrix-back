<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SummativeTest;

class SummativeTestController extends Controller
{
    public static function index() {
        return SummativeTest::all();
    }

    public static function show($id) {
        return SummativeTest::find($id); 
    }

    public static function summativeTest(Request $request)  {

        $teacher_topic_id = $request->query('topic');

        $params = [$teacher_topic_id];

        $result = DB::select("
        SELECT
            STI.summative_test_id,
            ST.title,
            ST.path,
            ST.time,
            STI.order_number,
            TI.id AS test_item_id,
            TI.task,
            TI.type AS item_type,
            TI.test_complexity_id,
            TIO.id AS option_id,
            TIO.option,
            TIO.explanation,
            TIO.text_additional,
            TIO.correct
        FROM 
        summative_test_items STI
        INNER JOIN
        summative_tests ST ON STI.summative_test_id = ST.id AND ST.teacher_topic_id = ?
        INNER JOIN
        test_items TI ON STI.test_item_id = TI.id
        INNER JOIN
        test_item_options TIO ON TIO.test_item_id = TI.id
        ", $params);

        $collection = collect($result);

        $finalResult = [];
        
        $groupedByFormativeTest = $collection->groupBy('summative_test_id');

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
                    'summative_test_id' => $formativeTestDetails->summative_test_id,
                    'item_type' => $formativeTestDetails->item_type,
                    'test_item_complexity' => $formativeTestDetails->test_complexity_id,
                    'test_item_options' => $testItemOptions,
                ];
            }

            // Adăugăm array-ul cu opțiuni în array-ul final pentru fiecare formative_test_id
            $finalResult[] = [
                'summative_test_id' => $formativeTestGroup->first()->summative_test_id,
                'title' => $formativeTestGroup->first()->title,
                'path' => $formativeTestGroup->first()->path,
                'time' => $formativeTestGroup->first()->time,
                'order_number_options' => $orderNumberOptions,
            ];
        }

        return array_values($finalResult);
    }

}
