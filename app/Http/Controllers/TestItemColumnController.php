<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestItemColumn;
use Illuminate\Support\Facades\Validator;

class TestItemColumnController extends Controller
{
    public static function index() {
        $testItemColumns =  TestItemColumn::all();
        return response()->json([
            'status' => 200,
            'testItemColumns' => $testItemColumns,
        ]);
    }

    public static function show($id) {
        return TestItemColumn::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'order_number' => 'required|integer|min:0',
            'test_item_id' => 'required|exists:test_items,id',
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
            'test_item_id' => $request->input('test_item_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'order_number' => $data['order_number'],
            'test_item_id' => $data['test_item_id'],         
        ];
    
        $existingRecord = TestItemColumn::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            TestItemColumn::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            TestItemColumn::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Test Item Column Added successfully',
        ]);
    }

    public static function edit($id) {
        $testItemColumn = TestItemColumn::find($id);
        if ($testItemColumn) {
            return response()->json([
                'status' => 200,
                'testItemColumn' => $testItemColumn,
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
            'title' => 'required|string|max:100',
            'order_number' => 'required|integer|min:0',
            'test_item_id' => 'required|exists:test_items,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $testItemColumn = TestItemColumn::find($id);
        if($testItemColumn) {
            $testItemColumn->title = $request->input('title');
            $testItemColumn->order_number = $request->input('order_number');
            $testItemColumn->test_item_id = $request->input('test_item_id');
            $testItemColumn->status = $request->input('status'); 
            $testItemColumn->updated_at = now();             
            $testItemColumn->update();
            return response()->json([
                'status'=>200,
                'message'=>'Test Item Column Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No test Item Column Id Found',
            ]); 
        }
    }

}
