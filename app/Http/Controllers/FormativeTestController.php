<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormativeTest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormativeTestController extends Controller
{
    public static function index() {
        $formativeTest =  FormativeTest::all();
        return response()->json([
            'status' => 200,
            'formativeTest' => $formativeTest,
        ]);
    }

    public static function show($id) {
        return FormativeTest::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'title' => 'required|string|max:500',
            'path' => 'required|string|max:500',
            'type' => 'required|in:quiz,check,snap,words,dnd,dnd_chrono,dnd_chrono_double,dnd_group',
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
            'order_number' => $request->input('order_number'),
            'path' => $request->input('path'),
            'type' => $request->input('type'),
            'test_complexity_id' => $request->input('test_complexity_id'),
            'teacher_topic_id' => $request->input('teacher_topic_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'title' => $data['title'],
            'type' => $data['type'],
            'teacher_topic_id' => $data['teacher_topic_id'],         
        ];
    
        $existingRecord = FormativeTest::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            FormativeTest::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            FormativeTest::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Formative Test Added successfully',
        ]);
    }

    public static function edit($id) {
        $formativeTest = FormativeTest::with('teacher_topic')->find($id);
       
        if ($formativeTest) {
            return response()->json([
                'status' => 200,
                'formativeTest' => $formativeTest,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Test Item Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'title' => 'required|string|max:500',
            'path' => 'required|string|max:500',
            'type' => 'required|in:quiz,check,snap,words,dnd,dnd_chrono,dnd_chrono_double,dnd_group',
            'test_complexity_id' => 'required|exists:test_comlexities,id',
            'teacher_topic_id' => 'required|exists:teacher_topics,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $formativeTest = FormativeTest::find($id);
        if($formativeTest) {
            $formativeTest->title = $request->input('title');
            $formativeTest->order_number = $request->input('order_number');            
            $formativeTest->type = $request->input('type');
            $formativeTest->path = $request->input('path');
            $formativeTest->test_complexity_id = $request->input('test_complexity_id');
            $formativeTest->teacher_topic_id = $request->input('teacher_topic_id');                     
            $formativeTest->status = $request->input('status'); 
            $formativeTest->updated_at = now();             
            $formativeTest->update();
            return response()->json([
                'status'=>200,
                'message'=>'Formative Test Updated successfully',
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
                    'formative_test_id' => $formativeTestDetails->formative_test_id,
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
