<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestItemOption;
use Illuminate\Support\Facades\Validator;

class TestItemOptionController extends Controller
{
    public static function index() {
        $testItemOptions =  TestItemOption::all();
        return response()->json([
            'status' => 200,
            'testItemOptions' => $testItemOptions,
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
