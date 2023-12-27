<?php

namespace App\Http\Controllers;

use App\Models\TestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestItemController extends Controller
{
    public static function index() {
        $testItem =  TestItem::all();
        return response()->json([
            'status' => 200,
            'testItem' => $testItem,
        ]);
    }

    public static function show($id) {
        return TestItem::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:1000',
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
            'task' => $request->input('task'),
            'type' => $request->input('type'),
            'test_complexity_id' => $request->input('test_complexity_id'),
            'teacher_topic_id' => $request->input('teacher_topic_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'task' => $data['task'],
            'type' => $data['type'],
            'teacher_topic_id' => $data['teacher_topic_id'],         
        ];
    
        $existingRecord = TestItem::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            TestItem::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            TestItem::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Subject Added successfully',
        ]);
    }

    public static function edit($id) {
        $testItem = TestItem::with('teacher_topic')->find($id);
       
        if ($testItem) {
            return response()->json([
                'status' => 200,
                'testItem' => $testItem,
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
            'task' => 'required|string|max:1000',
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
        $testItem = TestItem::find($id);
        if($testItem) {
            $testItem->task = $request->input('task');
            $testItem->type = $request->input('type');
            $testItem->test_complexity_id = $request->input('test_complexity_id');
            $testItem->teacher_topic_id = $request->input('teacher_topic_id');                     
            $testItem->status = $request->input('status'); 
            $testItem->updated_at = now();             
            $testItem->update();
            return response()->json([
                'status'=>200,
                'message'=>'Test Item Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Test Item Subject Id Found',
            ]); 
        }
    }

}
