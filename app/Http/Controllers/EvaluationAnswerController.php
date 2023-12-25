<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationAnswer;
use Illuminate\Support\Facades\Validator;

class EvaluationAnswerController extends Controller
{
    public static function index() {
        $evaluationAnswer =  EvaluationAnswer::all();
        return response()->json([
            'status' => 200,
            'evaluationAnswer' => $evaluationAnswer,
        ]);
    }
    
    public static function show($id) {
        return EvaluationAnswer::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'task' => 'required|string|max:1000',
            'content' => 'nullable|string|max:2000',
            'max_points' => 'required|integer|min:1',
            'evaluation_item_id' => 'required|exists:evaluation_items,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'order_number' => $request->input('order_number'),
            'task' => $request->input('task'),
            'content' => $request->input('content'),
            'max_points' => $request->input('max_points'),            
            'evaluation_item_id' => $request->input('evaluation_item_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'evaluation_item_id' => $data['evaluation_item_id'], 
            'task' => $data['task'],       
        ];
    
        $existingRecord = EvaluationAnswer::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
 
            EvaluationAnswer::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            EvaluationAnswer::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Answer Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationAnswer = EvaluationAnswer::with('evaluation_item')->find($id);
       
        if ($evaluationAnswer) {
            return response()->json([
                'status' => 200,
                'evaluationAnswer' => $evaluationAnswer,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Answer Source Id Found',
            ]);
        }
    }

    public static function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'task' => 'required|string|max:1000',
            'content' => 'nullable|string|max:2000',
            'max_points' => 'required|integer|min:1',
            'evaluation_item_id' => 'required|exists:evaluation_items,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $evaluationAnswer = EvaluationAnswer::find($id);
    
        if ($evaluationAnswer) {
            $evaluationAnswer->order_number = $request->input('order_number');
            $evaluationAnswer->task = $request->input('task');
            $evaluationAnswer->content = $request->input('content');
            $evaluationAnswer->max_points = $request->input('max_points');
            $evaluationAnswer->evaluation_item_id = $request->input('evaluation_item_id');
            $evaluationAnswer->status = $request->input('status');
    
            $evaluationAnswer->updated_at = now();
            $evaluationAnswer->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Evaluation Answer Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Answer Id Found',
            ]);
        }
    }

}
