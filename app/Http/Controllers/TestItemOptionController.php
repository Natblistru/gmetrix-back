<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestItemOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TestItemOptionController extends Controller
{
    // public static function index() {
    //     $testItemOptions =  TestItemOption::all();
    //     return response()->json([
    //         'status' => 200,
    //         'testItemOptions' => $testItemOptions,
    //     ]);
    // }

    public static function index(Request $request) {
        $search = $request->query('search');
        $sortColumn = $request->query('sortColumn');
        $sortOrder = $request->query('sortOrder');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $filterTeacher = $request->query('filterTeacher');
        $filterTheme = $request->query('filterTheme');
        $filterProgram = $request->query('filterProgram');
    
        $allowedColumns = ['id', 'option', 'correct','task', 'type', 'name', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'TIO',
            'option' => 'TIO',
            'correct' => 'TIO',
            'task' => 'TI',
            'type' => 'TI',
            'name' => 'TT',
            'status' => 'TIO',
        ];
    
        $sqlTemplate = "
            SELECT
            TIO.id,
            TIO.option,
            TIO.correct,
            TI.task,
            TI.type,
            TT.name,
            TT.teacher_id teacher_id,
            TLP.theme_id theme_id,
            LP.id program_id,
            TIO.status
        FROM 
            test_item_options TIO 
            INNER JOIN test_items TI ON TIO.test_item_id = TI.id
            INNER JOIN teacher_topics TT ON TI.teacher_topic_id = TT.id
            INNER JOIN topics ON TT.topic_id = topics.id    
            INNER JOIN theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
            INNER JOIN learning_programs LP ON TLP.learning_program_id = LP.id
        WHERE true
        ";
    
        $searchConditions = '';
        if ($search) {
            $searchLower = strtolower($search);
    
            $hiddenVariants = ['i','d','e','n','hi', 'hid', 'id', 'idd', 'dd','dde', 'hidd', 'hidde', 'de', 'den', 'en'];
            $shownVariants = ['s','o','w','sh','ho','sho', 'show', 'wn', 'ow', 'own'];
    
            if ($searchLower === 'hidden' || in_array($searchLower, $hiddenVariants)) {
                foreach ($allowedColumns as $column) {
                    $table = $columnTableMapping[$column];
                    $searchConditions .= ($column === 'status') ? "$table.$column = 1 OR " : "LOWER($table.$column) LIKE '%$searchLower%' OR ";
                }
            } elseif ($searchLower === 'shown' || in_array($searchLower, $shownVariants)) {
                foreach ($allowedColumns as $column) {
                    $table = $columnTableMapping[$column];
                    $searchConditions .= ($column === 'status') ? "$table.$column = 0 OR " : "LOWER($table.$column) LIKE '%$searchLower%' OR ";
                }
            } else {
                foreach ($allowedColumns as $column) {
                    $table = $columnTableMapping[$column];
                    $searchConditions .= "LOWER($table.$column) LIKE '%$searchLower%' OR ";
                }
            }
            $searchConditions = rtrim($searchConditions, ' OR ');
        }
    
        $sqlWithSortingAndSearch = $sqlTemplate;
    
        if ($searchConditions) {
            $sqlWithSortingAndSearch .= " AND $searchConditions";
        }

        if ($filterTeacher) {
            $sqlWithSortingAndSearch .= " AND TT.teacher_id = $filterTeacher";
        }

        if ($filterTheme) {
            $sqlWithSortingAndSearch .= " AND TLP.theme_id = $filterTheme";
        }
    
        if ($filterProgram) {
            $sqlWithSortingAndSearch .= " AND LP.id = $filterProgram";
        }

        $sqlWithSortingAndSearch .= " ORDER BY $sortColumn $sortOrder";

        $totalResults = DB::select("SELECT COUNT(*) as total FROM ($sqlWithSortingAndSearch) as countTable")[0]->total;
    
        $lastPage = ceil($totalResults / $perPage);
    
        $offset = ($page - 1) * $perPage;
    
        $rawResults = DB::select("$sqlWithSortingAndSearch LIMIT $perPage OFFSET $offset");
    
        return response()->json([
            'status' => 200,
            'testItemOptions' => $rawResults,
            'pagination' => [
                'last_page' => $lastPage,
                'current_page' => $page,
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $totalResults),
                'total' => $totalResults,
            ],
        ]);
    }

    public static function show($id) {
        return TestItemOption::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'option' => 'required|string|max:500',
            'explanation' => 'nullable|string|max:500',
            'text_additional' => 'nullable|json',
            'correct' => 'required|integer|min:0',
            'test_item_id' => 'required|exists:test_items,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'option' => $request->input('option'),
            'explanation' => $request->input('explanation'),
            'text_additional' => $request->input('text_additional'),
            'correct' => $request->input('correct'),
            'test_item_id' => $request->input('test_item_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'option' => $data['option'],
            'test_item_id' => $data['test_item_id'],         
        ];
    
        $existingRecord = TestItemOption::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            TestItemOption::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            TestItemOption::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Test Item Column Added successfully',
        ]);
    }

    public static function edit($id) {
        $testItemOption = TestItemOption::find($id);
        if ($testItemOption) {
            return response()->json([
                'status' => 200,
                'testItemOption' => $testItemOption,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Test Item Column Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'option' => 'required|string|max:500',
            'explanation' => 'nullable|string|max:500',
            'text_additional' => 'nullable|json',
            'correct' => 'required|integer|min:0',
            'test_item_id' => 'required|exists:test_items,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $testItemOption = TestItemOption::find($id);
        if($testItemOption) {
            $testItemOption->option = $request->input('option');
            $testItemOption->explanation = $request->input('explanation');
            $testItemOption->text_additional = $request->input('text_additional');
            $testItemOption->correct = $request->input('correct');            
            $testItemOption->test_item_id = $request->input('test_item_id');
            $testItemOption->status = $request->input('status'); 
            $testItemOption->updated_at = now();             
            $testItemOption->update();
            return response()->json([
                'status'=>200,
                'message'=>'Test Item Option Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No test Item Option Id Found',
            ]); 
        }
    }
    

}
