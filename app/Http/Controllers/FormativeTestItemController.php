<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormativeTestItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormativeTestItemController extends Controller
{
    // public static function index() {
    //     $formativeTestItem =  FormativeTestItem::all();
    //     return response()->json([
    //         'status' => 200,
    //         'formativeTestItem' => $formativeTestItem,
    //     ]);
    // }

    public static function index(Request $request) {

        $search = $request->query('search');
        $sortColumn = $request->query('sortColumn');
        $sortOrder = $request->query('sortOrder');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $filterTopic = $request->query('filterTopic');
        $filterTheme = $request->query('filterTheme');
        $filterProgram = $request->query('filterProgram');
        $filterChapter = $request->query('filterChapter');
        $filterTeacher = $request->query('filterTeacher');
    
        $allowedColumns = ['id', 'order_number', 'task', 'title', 'type', 'topic_name','status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'FTI',
            'order_number' => 'FTI',            
            'task' => 'TI',            
            'type' => 'TI',
            'title' => 'FT',
            'topic_name' => 'VTT',
            'status' => 'FTI',
        ];
    
        $sqlTemplate = "
        SELECT
            FTI.id,
            FTI.order_number,
            TI.task,
            TI.type,
            FT.title,
            VTT.topic_name,
            TT.teacher_id AS teacher_id,
            TT.id as topic_id,
            TH.chapter_id, 
            LP.id AS program_id,
            TI.status
        FROM 
        	formative_test_items FTI
            INNER JOIN test_items TI ON FTI.test_item_id = TI.id
            INNER JOIN formative_tests FT ON FTI.formative_test_id = FT.id
            INNER JOIN teacher_topics TT ON TI.teacher_topic_id = TT.id
            INNER JOIN topics ON TT.topic_id = topics.id    
            INNER JOIN theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
            INNER JOIN themes TH ON TLP.theme_id = TH.id
            INNER JOIN learning_programs LP ON TLP.learning_program_id = LP.id
            INNER JOIN (
                SELECT 
                    TT.id AS topic_id,
                    TT.name AS topic_name
                FROM teacher_topics TT
            ) AS VTT ON VTT.topic_id = TT.id
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

        if ($filterTeacher) {
            $sqlWithSortingAndSearch .= " AND TT.teacher_id = $filterTeacher";
        }

        if ($filterChapter) {
            $sqlWithSortingAndSearch .= " AND TH.chapter_id = $filterChapter";
        }
    
        if ($searchConditions) {
            $sqlWithSortingAndSearch .= " AND $searchConditions";
        }

        if ($filterTopic) {
            $sqlWithSortingAndSearch .= " AND TT.id = $filterTopic";
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
            'formativeTestItem' => $rawResults,
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
        return FormativeTestItem::find($id); 
    }

    public static function allTests() {
        $tests =  FormativeTestItem::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'tests' => $tests,
        ]);
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'test_item_id' => 'required|exists:test_items,id',
            'formative_test_id' => 'required|exists:formative_tests,id',
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
            'formative_test_id' => $request->input('formative_test_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'order_number' => $data['order_number'],
            'test_item_id' => $data['test_item_id'],
            'formative_test_id' => $data['formative_test_id'],         
        ];
    
        $existingRecord = FormativeTestItem::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            FormativeTestItem::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            FormativeTestItem::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Formative Test Item Added successfully',
        ]);
    }

    public static function edit($id) {
        $formativeTests = FormativeTestItem::find($id);
       
        if ($formativeTests) {
            return response()->json([
                'status' => 200,
                'formativeTests' => $formativeTests,
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
            'test_item_id' => 'required|exists:test_items,id',
            'formative_test_id' => 'required|exists:formative_tests,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $formativeTestItemss = FormativeTestItem::find($id);
        if($formativeTestItemss) {
            $formativeTestItemss->order_number = $request->input('order_number');
            $formativeTestItemss->test_item_id = $request->input('test_item_id');
            $formativeTestItemss->formative_test_id = $request->input('formative_test_id');
            $formativeTestItemss->status = $request->input('status'); 
            $formativeTestItemss->updated_at = now();             
            $formativeTestItemss->update();
            return response()->json([
                'status'=>200,
                'message'=>'Formative Test Item Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Formative Test Item  Id Found',
            ]); 
        }
    }

}
