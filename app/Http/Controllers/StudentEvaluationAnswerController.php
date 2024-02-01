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

    public static function getStudentEvaluationResults(Request $request) {
        try {
            $theme_id = $request->input('theme_id');
            $subject_id = $request->input('subject_id');
            $study_level_id = $request->input('study_level_id');
            $order_number = $request->input('order_number');
            $studentId = $request->input('studentId');
    
            $sqlTemplate = "
                SELECT
                    SEA.evaluation_answer_option_id,
                    EA.id answer_id,
                    ST.id student_id,
                    EA.evaluation_item_id,
                    EA.max_points,
                    EO.id option_id,
                    EO.points student_points
                FROM
                    student_evaluation_answers SEA 
                INNER JOIN
                    students ST ON ST.id = SEA.student_id AND ST.id = ?
                INNER JOIN
                    evaluation_answer_options EAO ON SEA.evaluation_answer_option_id = EAO.id
                INNER JOIN
                    evaluation_answers EA ON EAO.evaluation_answer_id = EA.id
                INNER JOIN
                    evaluation_items EI ON EA.evaluation_item_id = EI.id
                INNER JOIN
                    evaluation_options EO ON EAO.evaluation_option_id = EO.id
                INNER JOIN
                    evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
                    AND ES.order_number = ? 
                    AND EI.theme_id = ?
                INNER JOIN
                    evaluations E ON E.id = ES.evaluation_id
                INNER JOIN
                    subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
                    AND SSLev.subject_id = ? AND SSLev.study_level_id = ?;
            ";
    
            $rawResults = DB::select($sqlTemplate, [$studentId, $order_number, $theme_id, $subject_id, $study_level_id]);
    
            return response()->json([
                'status' => 200,
                'studentEvaluationResults' => $rawResults,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public static function getStudentEvaluationResultsAllThemes(Request $request) {
        try {
            $subject_id = $request->input('subject_id');
            $study_level_id = $request->input('study_level_id');
            $order_number = $request->input('order_number');
            $studentId = $request->input('studentId');
    
            $sqlTemplate = "
                SELECT
                    EI.theme_id,
                    SUM(EA.max_points) as total_max_points,
                    SUM(EO.points) as total_student_points
                FROM
                    student_evaluation_answers SEA 
                INNER JOIN
                    students ST ON ST.id = SEA.student_id AND ST.id = ?
                INNER JOIN
                    evaluation_answer_options EAO ON SEA.evaluation_answer_option_id = EAO.id
                INNER JOIN
                    evaluation_answers EA ON EAO.evaluation_answer_id = EA.id
                INNER JOIN
                    evaluation_items EI ON EA.evaluation_item_id = EI.id
                INNER JOIN
                    evaluation_options EO ON EAO.evaluation_option_id = EO.id
                INNER JOIN
                    evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
                    AND ES.order_number = ? 
                INNER JOIN
                    evaluations E ON E.id = ES.evaluation_id
                INNER JOIN
                    subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
                    AND SSLev.subject_id = ? 
                    AND SSLev.study_level_id = ?
                GROUP BY
                    EI.theme_id
                    ;
            ";
    
            $allResults = DB::select($sqlTemplate, [$studentId, $order_number, $subject_id, $study_level_id]);
    
            return response()->json([
                'status' => 200,
                'studentEvaluationResults' => $allResults,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    
    
}
