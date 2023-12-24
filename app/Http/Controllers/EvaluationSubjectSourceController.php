<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EvaluationSubjectSource;

class EvaluationSubjectSourceController extends Controller
{
    public static function index() {
        $evaluationSubjectSources =  EvaluationSubjectSource::all();
        return response()->json([
            'status' => 200,
            'evaluationSubjectSources' => $evaluationSubjectSources,
        ]);
    }

    public static function show($id) {
        return EvaluationSubjectSource::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'evaluation_subject_id' => 'required|exists:evaluation_subjects,id',
            'evaluation_source_id' => 'required|exists:evaluation_sources,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'order_number' => $request->input('order_number'),
            'evaluation_subject_id' => $request->input('evaluation_subject_id'),
            'evaluation_source_id' => $request->input('evaluation_source_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'evaluation_subject_id' => $data['evaluation_subject_id'], 
            'evaluation_source_id' => $data['evaluation_source_id'],        
        ];
    
        $existingRecord = EvaluationSubjectSource::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            EvaluationSubjectSource::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            EvaluationSubjectSource::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Subject Source Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationSubjectSources = EvaluationSubjectSource::with('evaluation_subject')->with('evaluation_source')->find($id);
       
        if ($evaluationSubjectSources) {
            return response()->json([
                'status' => 200,
                'evaluationSubjectSources' => $evaluationSubjectSources,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Subject Source Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'evaluation_subject_id' => 'required|exists:evaluation_subjects,id',
            'evaluation_source_id' => 'required|exists:evaluation_sources,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $evaluationSubjectSource = EvaluationSubjectSource::find($id);
        if($evaluationSubjectSource) {
            $evaluationSubjectSource->order_number = $request->input('order_number');
            $evaluationSubjectSource->evaluation_subject_id = $request->input('evaluation_subject_id');
            $evaluationSubjectSource->evaluation_source_id = $request->input('evaluation_source_id');                     
            $evaluationSubjectSource->status = $request->input('status'); 
            $evaluationSubjectSource->updated_at = now();             
            $evaluationSubjectSource->update();
            return response()->json([
                'status'=>200,
                'message'=>'Evaluation Subject Sourse Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Evaluation Subject Id Found',
            ]); 
        }
    }
}
