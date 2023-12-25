<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationOption;
use Illuminate\Support\Facades\Validator;

class EvaluationOptionController extends Controller
{
    public static function index() {
        $evaluationOption =  EvaluationOption::all();
        return response()->json([
            'status' => 200,
            'evaluationOption' => $evaluationOption,
        ]);
    }

    public static function show($id) {
        return EvaluationOption::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'points' => 'required|integer|min:0',
            'label' => 'required|string|max:500',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'points' => $request->input('points'),
            'label' => $request->input('label'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'points' => $data['points'], 
            'label' => $data['label'],       
        ];
    
        $existingRecord = EvaluationOption::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
 
            EvaluationOption::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            EvaluationOption::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Answer Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationOption = EvaluationOption::find($id);
       
        if ($evaluationOption) {
            return response()->json([
                'status' => 200,
                'evaluationOption' => $evaluationOption,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Option Id Found',
            ]);
        }
    }

    public static function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'points' => 'required|integer|min:0',
            'label' => 'required|string|max:500',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $evaluationOption = EvaluationOption::find($id);
    
        if ($evaluationOption) {
            $evaluationOption->points = $request->input('points');
            $evaluationOption->label = $request->input('label');
            $evaluationOption->status = $request->input('status');
    
            $evaluationOption->updated_at = now();
            $evaluationOption->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Evaluation Option Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Option Id Found',
            ]);
        }
    }

}
