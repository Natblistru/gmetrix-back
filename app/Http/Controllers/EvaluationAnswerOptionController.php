<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationAnswerOption;
use Illuminate\Support\Facades\Validator;

class EvaluationAnswerOptionController extends Controller
{
    public static function index() {
        $evaluationAnswerOption =  EvaluationAnswerOption::all();
        return response()->json([
            'status' => 200,
            'evaluationAnswerOption' => $evaluationAnswerOption,
        ]);
    }

    public static function show($id) {
        return EvaluationAnswerOption::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'evaluation_option_id' => 'required|exists:evaluation_options,id',
            'evaluation_answer_id' => 'required|exists:evaluation_answers,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'evaluation_option_id' => $request->input('evaluation_option_id'),
            'evaluation_answer_id' => $request->input('evaluation_answer_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'evaluation_option_id' => $data['evaluation_option_id'], 
            'evaluation_answer_id' => $data['evaluation_answer_id'],       
        ];
    
        $existingRecord = EvaluationAnswerOption::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
 
            EvaluationAnswerOption::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            EvaluationAnswerOption::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Answer Option Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationAnswerOption = EvaluationAnswerOption::find($id);
       
        if ($evaluationAnswerOption) {
            return response()->json([
                'status' => 200,
                'evaluationAnswerOption' => $evaluationAnswerOption,
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
            'evaluation_option_id' => 'required|exists:evaluation_options,id',
            'evaluation_answer_id' => 'required|exists:evaluation_answers,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $evaluationAnswerOption = EvaluationAnswerOption::find($id);
    
        if ($evaluationAnswerOption) {
            $evaluationAnswerOption->evaluation_option_id = $request->input('evaluation_option_id');
            $evaluationAnswerOption->evaluation_answer_id = $request->input('evaluation_answer_id');
            $evaluationAnswerOption->status = $request->input('status');
    
            $evaluationAnswerOption->updated_at = now();
            $evaluationAnswerOption->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Evaluation Answer Option Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Answer Option Id Found',
            ]);
        }
    }
}
