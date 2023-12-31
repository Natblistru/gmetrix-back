<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummativeTestItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SummativeTestItemController extends Controller
{
    public static function index(Request $request) {
        
        $sortColumn = $request->query('sortColumn');
        $sortOrder = $request->query('sortOrder');

        $allowedColumns = ['order_number', 'test_item_task', 'test_item_type', 'summative_test_title', 'teacher_topic_name', 'status'];

        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $sqlTemplate = "
            SELECT
                STI.id,
                ST.title summative_test_title,
                STI.order_number,
                TI.id AS test_item_id,
                TI.task test_item_task,
                TI.type AS test_item_type,
                TI.test_complexity_id,
                TT.name teacher_topic_name,
                STI.status
            FROM 
                summative_test_items STI
                INNER JOIN summative_tests ST ON STI.summative_test_id = ST.id
                INNER JOIN test_items TI ON STI.test_item_id = TI.id
                INNER JOIN teacher_topics TT ON ST.teacher_topic_id = TT.id
        ";
    
        $sqlWithSorting = $sqlTemplate . " ORDER BY $sortColumn $sortOrder";
    
        $summativeTestItem = DB::select($sqlWithSorting);
    
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
