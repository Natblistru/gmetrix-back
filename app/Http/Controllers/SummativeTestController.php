<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummativeTest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SummativeTestController extends Controller
{
    public static function index() {
        $summativeTest =  SummativeTest::all();
        return response()->json([
            'status' => 200,
            'summativeTest' => $summativeTest,
        ]);
    }

    public static function show($id) {
        return SummativeTest::find($id); 
    }

    public static function allSummativeTests() {
        $summativeTests =  SummativeTest::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'summativeTests' => $summativeTests,
        ]);
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'path' => 'required|string|max:500',
            'time' => 'required|integer|min:60',
            'test_complexity_id' => 'required|exists:test_comlexities,id',
            'teacher_topic_id' => 'required|exists:teacher_topics,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'title' => $request->input('title'),
            'path' => $request->input('path'),
            'time' => $request->input('time'),
            'test_complexity_id' => $request->input('test_complexity_id'),
            'teacher_topic_id' => $request->input('teacher_topic_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'title' => $data['title'],
            'teacher_topic_id' => $data['teacher_topic_id'],         
        ];
    
        $existingRecord = SummativeTest::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            SummativeTest::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            SummativeTest::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Summative Test Added successfully',
        ]);
    }

    


    public static function edit($id) {
        $summativeTest = SummativeTest::with('teacher_topic')->find($id);
       
        if ($summativeTest) {
            return response()->json([
                'status' => 200,
                'summativeTest' => $summativeTest,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Summative Test Item Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'path' => 'required|string|max:500',
            'time' => 'required|integer|min:60',
            'test_complexity_id' => 'required|exists:test_comlexities,id',
            'teacher_topic_id' => 'required|exists:teacher_topics,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $summativeTest = SummativeTest::find($id);
        if($summativeTest) {
            $summativeTest->title = $request->input('title');
            $summativeTest->time = $request->input('time');
            $summativeTest->path = $request->input('path');
            $summativeTest->test_complexity_id = $request->input('test_complexity_id');
            $summativeTest->teacher_topic_id = $request->input('teacher_topic_id');                     
            $summativeTest->status = $request->input('status'); 
            $summativeTest->updated_at = now();             
            $summativeTest->update();
            return response()->json([
                'status'=>200,
                'message'=>'Summative Test Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Formative Test Id Found',
            ]); 
        }
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

    public static function summativeTest_exam(Request $request)  {

        DB::statement("
        CREATE TEMPORARY TABLE temp_test_item_columns AS
        SELECT 
            TI.id,
            GROUP_CONCAT(TIC.title ORDER BY TIC.order_number SEPARATOR ', ') AS column_titles
        FROM  
            summative_test_items AS FTI
        INNER JOIN
            summative_tests FT ON FTI.summative_test_id = FT.id
        INNER JOIN 
            test_items TI ON FTI.test_item_id = TI.id
        LEFT JOIN 
            test_item_columns TIC ON TIC.test_item_id = TI.id
        GROUP BY TI.id;
        ");

        $result = DB::select("
        SELECT
            STI.summative_test_id,
            ST.title,
            ST.path,
            ST.time,
            STI.order_number,
            1 AS order_test,
            TI.id AS test_item_id,
            TI.task,
            TI.content,
            TI.image_path,
            TI.type AS type,
            TI.type AS item_type,
            TI.test_complexity_id,
            COALESCE(TTIC.column_titles, '') AS column_title,
            TIO.id AS option_id,
            TIO.option,
            COALESCE(TIO.option_ro, TIO.option) AS option_ro,
            TIO.explanation,
            COALESCE(TIO.explanation_ro, TIO.explanation) AS explanation_ro,
            TIO.text_additional,
            TIO.correct
        FROM 
            summative_test_items STI
        INNER JOIN
            summative_tests ST ON STI.summative_test_id = ST.id
        INNER JOIN
            test_items TI ON STI.test_item_id = TI.id
        INNER JOIN
            test_item_options TIO ON TIO.test_item_id = TI.id
        LEFT JOIN 
            temp_test_item_columns TTIC ON TTIC.id = TI.id 
        ");

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
                            'option_ro' => $item->option_ro,
                            'explanation' => $item->explanation,
                            'explanation_ro' => $item->explanation_ro,
                            'text_additional' => $item->text_additional,
                            'correct' => $item->correct,
                        ];
                    }
                }

                $orderNumberOptions[] = [
                    'order_number' => $formativeTestDetails->order_number,
                    'test_item_id' => $formativeTestDetails->test_item_id,
                    'column_title' => $formativeTestDetails->column_title,
                    'test_item_task' => $formativeTestDetails->task,
                    'test_item_content' => $formativeTestDetails->content, 
                    'formative_test_id' => $formativeTestDetails->summative_test_id,
                    'image_path' => $formativeTestDetails->image_path,
                    'item_type' => $formativeTestDetails->item_type,
                    'test_item_complexity' => $formativeTestDetails->test_complexity_id,
                    'test_item_options' => $testItemOptions,
                ];
            }

            usort($orderNumberOptions, function ($a, $b) {
                return $a['order_number'] - $b['order_number'];
            });

            // Adăugăm array-ul cu opțiuni în array-ul final pentru fiecare formative_test_id
            $finalResult[] = [
                'formative_test_id' => $formativeTestGroup->first()->summative_test_id,
                'order_test' => $formativeTestGroup->first()->order_test,
                'type' => $formativeTestGroup->first()->type,
                'title' => $formativeTestGroup->first()->title,
                'path' => $formativeTestGroup->first()->path,
                'time' => $formativeTestGroup->first()->time,
                'order_number_options' => $orderNumberOptions,
            ];
        }

        usort($finalResult, function ($a, $b) {
            return $a['order_test'] - $b['order_test'];
        });

        return array_values($finalResult);
    }

    public static function allSummativeTestItems(Request $request)  {

        $student_id = $request->query('student');
        $disciplina = $request->query('disciplina');
        
        $params = [$student_id, $disciplina];

        $result = DB::select("
                SELECT 
                FT.teacher_topic_id AS teacher_topic_id,
                FTI.summative_test_id AS formative_test_id,
                FT.title AS name,
                FT.path,
                TI.type,
                1 AS order_formative_test,
                FTI.order_number AS order_item_test,
                TI.id AS test_item_id,
                TI.task,
                TI.type AS item_type,
                TI.test_complexity_id,
                COALESCE(SFTR.score*100/TC.level, 0) AS student_procent
            FROM  
                summative_test_items AS FTI
            INNER JOIN
                summative_tests FT ON FTI.summative_test_id = FT.id
            INNER JOIN 
                test_items TI ON FTI.test_item_id = TI.id
            LEFT JOIN
                student_summative_test_results SFTR ON SFTR.summative_test_item_id = FTI.id AND SFTR.student_id = ?
            INNER JOIN
                test_comlexities TC ON FT.test_complexity_id = TC.id
            WHERE FTI.status = 0 AND 
                LOWER(SUBSTRING(
                    FT.title,
                    INSTR(FT.title, 'la ') + 3,
                    LENGTH(FT.title) - INSTR(FT.title, 'la ') - 2
                )) = LOWER(?)
        ", $params);

        return array_values($result);
    }

}
