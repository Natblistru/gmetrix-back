<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationFormPage;
use Illuminate\Support\Facades\Validator;

class EvaluationFormPageController extends Controller
{
    public static function index() {
        $evaluationFormPage =  EvaluationFormPage::all();
        return response()->json([
            'status' => 200,
            'evaluationFormPage' => $evaluationFormPage,
        ]);
    }

    public static function show($id) {
        return EvaluationFormPage::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'task' => 'required|string|max:1000',
            'hint' => 'nullable|string|max:2000',
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
            'hint' => $request->input('hint'),
            'evaluation_item_id' => $request->input('evaluation_item_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'evaluation_item_id' => $data['evaluation_item_id'], 
            'task' => $data['task'],       
        ];
    
        $existingRecord = EvaluationFormPage::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
 
            EvaluationFormPage::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            EvaluationFormPage::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Form Page Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationFormPage = EvaluationFormPage::with('evaluation_item')->find($id);
       
        if ($evaluationFormPage) {
            return response()->json([
                'status' => 200,
                'evaluationFormPage' => $evaluationFormPage,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Form Page Id Found',
            ]);
        }
    }

    public static function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'task' => 'required|string|max:1000',
            'hint' => 'nullable|string|max:2000',
            'evaluation_item_id' => 'required|exists:evaluation_items,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $evaluationFormPage = EvaluationFormPage::find($id);
    
        if ($evaluationFormPage) {
            $evaluationFormPage->order_number = $request->input('order_number');
            $evaluationFormPage->task = $request->input('task');
            $evaluationFormPage->hint = $request->input('hint');
            $evaluationFormPage->evaluation_item_id = $request->input('evaluation_item_id');
            $evaluationFormPage->status = $request->input('status');
    
            $evaluationFormPage->updated_at = now();
            $evaluationFormPage->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Evaluation Form Page Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Form Page Id Found',
            ]);
        }
    }

}
