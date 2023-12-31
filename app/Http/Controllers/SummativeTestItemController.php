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
    
        $allowedColumns = ['id','order_number', 'task', 'type', 'title', 'name', 'status'];
    
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
                STI.status
            FROM 
                summative_test_items STI
                INNER JOIN summative_tests ST ON STI.summative_test_id = ST.id
                INNER JOIN test_items TI ON STI.test_item_id = TI.id
                INNER JOIN teacher_topics TT ON ST.teacher_topic_id = TT.id
        ";
    
        $searchConditions = '';
        if ($search) {
            $searchLower = strtolower($search);
    
            // Variantele posibile pentru "Hidden"
            $hiddenVariants = ['i','d','e','n','hi', 'hid', 'id', 'idd', 'dd','dde', 'hidd', 'hidde', 'de', 'den', 'en'];
    
            // Variantele posibile pentru "Shown"
            $shownVariants = ['s','o','w','sh','ho','sho', 'show', 'wn', 'ow', 'own'];
    
            // Verificăm dacă valoarea de căutare se potrivește cu una dintre variantele posibile pentru "Hidden"
            if ($searchLower === 'hidden' || in_array($searchLower, $hiddenVariants)) {
                foreach ($allowedColumns as $column) {
                    $table = $columnTableMapping[$column];
    
                    if ($column === 'status') {
                        $searchConditions .= "$table.$column = 1 OR ";
                    } else {
                        $searchConditions .= "LOWER($table.$column) LIKE '%$searchLower%' OR ";
                    }
                }
            }
            // Verificăm dacă valoarea de căutare se potrivește cu una dintre variantele posibile pentru "Shown"
            elseif ($searchLower === 'shown' || in_array($searchLower, $shownVariants)) {
                foreach ($allowedColumns as $column) {
                    $table = $columnTableMapping[$column];
    
                    if ($column === 'status') {
                        $searchConditions .= "$table.$column = 0 OR ";
                    } else {
                        $searchConditions .= "LOWER($table.$column) LIKE '%$searchLower%' OR ";
                    }
                }
            }
            else {
                foreach ($allowedColumns as $column) {
                    $table = $columnTableMapping[$column];
                    $searchConditions .= "LOWER($table.$column) LIKE '%$searchLower%' OR ";
                }
            }
            // Eliminăm ultimul " OR " din șirul de condiții
            $searchConditions = rtrim($searchConditions, ' OR ');
        }
    
        $sqlWithSortingAndSearch = $sqlTemplate;
    
        if ($searchConditions) {
            $sqlWithSortingAndSearch .= " WHERE $searchConditions";
        }
    
        $sqlWithSortingAndSearch .= " ORDER BY $sortColumn $sortOrder";

        $sqlWithSortingAndSearch .= " LIMIT $perPage OFFSET " . ($page - 1) * $perPage;
    
        $summativeTestItem = DB::select($sqlWithSortingAndSearch);
    
        return response()->json([
            'status' => 200,
            'summativeTestItem' => $summativeTestItem,
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

    public static function update(Request $request,$id,) {
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
