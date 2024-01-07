<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EvaluationAnswerOption;
use App\Models\StudentEvaluationAnswer;
use Illuminate\Support\Facades\Validator;

class StudentEvaluationAnswerController extends Controller
{
    public static function index() {
        return StudentEvaluationAnswer::all();
    }

    public static function show($id) {
        return StudentEvaluationAnswer::find($id); 
    }

    public function store(Request $request)
    {
       // Validarea datelor de intrare
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|integer',
            'evaluation_answer_option_id' => 'required|integer',
            'points' => 'required|integer',
            'answer_id' => 'required|integer',
        ]);

        // Verificăm dacă validarea a eșuat
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::select('CALL InsertOrUpdateStudentEvaluationAnswer(?, ?, ?, ?)', [
            $request->input('student_id'),
            $request->input('evaluation_answer_option_id'),
            $request->input('points'),
            $request->input('answer_id'),
        ]);

        // Returnam un răspuns corespunzător către frontend
        return response()->json(['message' => 'Success'], 200);
    }

    public static function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'evaluation_answer_option_id' => 'required|exists:evaluation_answer_options,id',
            'evaluation_answer_id' => 'required|exists:evaluation_answers,id',
            'points' => 'required|integer|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $evaluation_answer_option_id = EvaluationAnswerOption::join('evaluation_answers as EA', 'evaluation_answer_options.evaluation_answer_id', '=', 'EA.id')
                                ->join('evaluation_options as EO', 'evaluation_answer_options.evaluation_option_id', '=', 'EO.id')
                                ->where('EA.id', $request->input('evaluation_answer_id'))
                                ->where('EO.points', 0)
                                ->select('evaluation_answer_options.id as evaluation_answer_option_id')
                                ->first();

        if ($evaluation_answer_option_id) {
            $evaluation_answer_option_id = $evaluation_answer_option_id->evaluation_answer_option_id;
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Evaluation Answer Option Id Found',
            ]); 
        }

        $studentEvaluationAnswer = StudentEvaluationAnswer::where('evaluation_answer_option_id', $request->input('evaluation_answer_option_id'))
                                            ->where('student_id', $request->input('student_id'))
                                            ->first();
        
        if ($studentEvaluationAnswer) {
            $studentEvaluationAnswer->student_id = $request->input('student_id');
            $studentEvaluationAnswer->evaluation_answer_option_id = $evaluation_answer_option_id;
            $studentEvaluationAnswer->points = $request->input('points'); 
            $studentEvaluationAnswer->status = $request->input('status'); 
            $studentEvaluationAnswer->update();
        
            return response()->json([
                'status' => 200,
                'message' => 'Student Evaluation Answer Updated successfully',
            ]); 
        }
    
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Student Evaluation Answer Id Found',
            ]); 
        }
    
    }

}
