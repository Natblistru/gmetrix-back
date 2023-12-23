<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Evaluation;

class EvaluationController extends Controller
{
    public static function index() {
        $evaluations =  Evaluation::all();
        return response()->json([
            'status' => 200,
            'evaluations' => $evaluations,
        ]);
    }

    public static function show($id) {
        return Evaluation::find($id); 
    }

    public static function allEvaluations() {
        $evaluations =  Evaluation::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'evaluations' => $evaluations,
        ]);
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'subject_study_level_id' => 'required|exists:subject_study_levels,id',
            'type' => 'required|in:Pretestare,Testare de baza,Evaluare suplimentara,Teste pentru exersare1,Teste pentru exersare2',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'name' => $request->input('name'),
            'year' => $request->input('year'),
            'subject_study_level_id' => $request->input('subject_study_level_id'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'year' => $data['year'],
            'subject_study_level_id' => $data['subject_study_level_id'],
            'type' => $data['type'],            
        ];
    
        $existingRecord = Evaluation::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            Evaluation::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            Evaluation::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluations = Evaluation::find($id);
        if ($evaluations) {
            return response()->json([
                'status' => 200,
                'evaluations' => $evaluations,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'subject_study_level_id' => 'required|exists:subject_study_levels,id',
            'type' => 'required|in:Pretestare,Testare de baza,Evaluare suplimentara,Teste pentru exersare1,Teste pentru exersare2',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $evaluation = Evaluation::find($id);
        if($evaluation) {
            $evaluation->name = $request->input('name');
            $evaluation->year = $request->input('year');
            $evaluation->type = $request->input('type');
            $evaluation->subject_study_level_id = $request->input('subject_study_level_id');                     
            $evaluation->status = $request->input('status'); 
            $evaluation->updated_at = now();             
            $evaluation->update();
            return response()->json([
                'status'=>200,
                'message'=>'Evaluation Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Evaluation Id Found',
            ]); 
        }
    }
}
