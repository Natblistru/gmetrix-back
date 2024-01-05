<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummativeTestItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SummativeTestItemController extends Controller
{
    public static function index(Request $request) {
        $search = $request->query('search');
        $sortColumn = $request->query('sortColumn');
        $sortOrder = $request->query('sortOrder');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $filterTeacher = $request->query('filterTeacher');
        $filterTheme = $request->query('filterTheme');
        $filterProgram = $request->query('filterProgram');
    
        $allowedColumns = ['id', 'order_number', 'task', 'type', 'title', 'name', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'STI',
            'order_number' => 'STI',
            'task' => 'TI',
            'type' => 'TI',
            'title' => 'ST',
            'name' => 'TT',
            'status' => 'STI',
        ];
    
        $sqlTemplate = "
            SELECT
                STI.id,
                ST.title,
                STI.order_number,
                TI.task,
                TI.type,
                TT.name,
                TT.teacher_id teacher_id,
                TLP.theme_id theme_id,
                LP.id program_id,
                STI.status
            FROM 
                summative_test_items STI
                INNER JOIN summative_tests ST ON STI.summative_test_id = ST.id
                INNER JOIN test_items TI ON STI.test_item_id = TI.id
                INNER JOIN teacher_topics TT ON ST.teacher_topic_id = TT.id
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
            'summativeTestItem' => $rawResults,
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
        return SummativeTestItem::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'test_item_id' => 'required|exists:test_items,id',
            'summative_test_id' => 'required|exists:summative_tests,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'order_number' => $request->input('order_number'),
            'test_item_id' => $request->input('test_item_id'),
            'summative_test_id' => $request->input('summative_test_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'order_number' => $data['order_number'],
            'test_item_id' => $data['test_item_id'],
            'summative_test_id' => $data['summative_test_id'],         
        ];
    
        $existingRecord = SummativeTestItem::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            SummativeTestItem::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            SummativeTestItem::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Summative Test Item Added successfully',
        ]);
    }

    public static function edit($id) {
        $summativeTests = SummativeTestItem::find($id);
       
        if ($summativeTests) {
            return response()->json([
                'status' => 200,
                'summativeTests' => $summativeTests,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Test Item Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'test_item_id' => 'required|exists:test_items,id',
            'summative_test_id' => 'required|exists:summative_tests,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $summativeTestItems = SummativeTestItem::find($id);
        if($summativeTestItems) {
            $summativeTestItems->order_number = $request->input('order_number');
            $summativeTestItems->test_item_id = $request->input('test_item_id');
            $summativeTestItems->summative_test_id = $request->input('summative_test_id');
            $summativeTestItems->status = $request->input('status'); 
            $summativeTestItems->updated_at = now();             
            $summativeTestItems->update();
            return response()->json([
                'status'=>200,
                'message'=>'Summative Test Item Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Summative Test Item  Id Found',
            ]); 
        }
    }

}
