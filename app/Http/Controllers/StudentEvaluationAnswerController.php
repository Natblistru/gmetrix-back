<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentEvaluationAnswer;

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

}
