<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummativeTestItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentSummativeTestResult;

class StudentSummativeTestResultController extends Controller
{
    public static function index() {
        return StudentSummativeTestResult::all();
    }

    public static function show($id) {
        return StudentSummativeTestResult::find($id); 
    }

    public function store(Request $request)
    {
       // Validarea datelor de intrare
    $validator = Validator::make($request->all(), [
        'student_id' => 'required|integer',
        'formative_test_id' => 'required|integer',
        'test_item_id' => 'required|integer',
        'type' => 'required|string|max:50',
    ]);
        // Verificăm dacă validarea a eșuat
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Apelul procedurii stocate
        DB::select('CALL InsertOrUpdateStudentSummativeTestResult(?, ?, ?, ?)', [
            $request->input('student_id'),
            $request->input('formative_test_id'),
            $request->input('test_item_id'),
            $request->input('type'),
        ]);

        return response()->json(['message' => 'Success'], 200);
    }


    public static function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'score' => 'required|integer|min:0',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
    
        $summative_test_item = SummativeTestItem::where('summative_test_id', $request->input('summative_test_id'))
                                                ->where('test_item_id', $request->input('test_item_id'))
                                                ->where('order_number', $request->input('order_number'))
                                                ->first();
    
        if (!$summative_test_item) {
            return response()->json([
                'status'=>404,
                'message'=>'No Summative Test Item Id Found',
            ]); 
        }
    
        $studentSummativeTestResult = StudentSummativeTestResult::where('summative_test_item_id', $summative_test_item->id)
                                                                ->where('student_id',  $request->input('student_id'))
                                                                ->first();
    
        if($studentSummativeTestResult) {
            $studentSummativeTestResult->score = $request->input('score'); 
            $studentSummativeTestResult->updated_at = now();             
            $studentSummativeTestResult->update();
    
            return response()->json([
                'status'=>200,
                'message'=>'Student Summative Test Result Updated successfully',
            ]); 
        } else {
            return response()->json([
                'status'=>404,
                'message'=>'No Student Summative Test Result Id Found',
            ]); 
        }
    }
    
}
