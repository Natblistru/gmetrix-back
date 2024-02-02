<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormativeTestItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentFormativeTestResult;

class StudentFormativeTestResultController extends Controller
{
    public static function index() {
        return StudentFormativeTestResult::all();
    }

    public static function show($id) {
        return StudentFormativeTestResult::find($id); 
    }

    public function getStudentFormativeTestScore(Request $request) {
        $itemId = $request->input('test_item_id');
        $formative_test_id = $request->input('formative_test_id');
        $studentId = $request->input('studentId');
    
        // Log::info('formative_test_id', ['formative_test_id' => $formative_test_id]);
        // Log::info('itemId', ['itemId' => $itemId]);

        $formativeTestItem = FormativeTestItem::where('test_item_id', $itemId)
            ->where('formative_test_id', $formative_test_id)
            ->first();
    
        $score = 0;
    
        if ($formativeTestItem) {
            $studentFormativeTestResult = StudentFormativeTestResult::where('student_id', $studentId)
                ->where('formative_test_item_id', $formativeTestItem->id)
                ->first();
    
            // Utilizați metoda value() pentru a obține direct valoarea 'score'
            $score = optional($studentFormativeTestResult)->score ?? 0;
        }
    
        return response()->json([
            'status' => 200,
            'score' => $score,
        ], 200);
    }
    

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|integer',
            'formative_test_id' => 'required|integer',
            'test_item_id' => 'required|integer',
            'type' => 'required|string|max:50',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Apelul procedurii stocate
        DB::select('CALL InsertOrUpdateStudentFormativeTestResult(?, ?, ?, ?)', [
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
            'formative_test_id' => 'nullable|exists:formative_tests,id',
            'test_item_id' => 'nullable|exists:test_items,id',
            'score' => 'required|integer|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $formative_test_item = FormativeTestItem::where('formative_test_id', $request->input('formative_test_id'))
                                                ->where('test_item_id', $request->input('test_item_id'))
                                                ->where('order_number', $request->input('order_number'))
                                                ->first();

        if ($formative_test_item) {
            $formative_test_item_id = $formative_test_item->id;
        } else {
            return response()->json([
                'status'=>404,
                'message'=>'No Formative Test Item Id Found',
            ]); 
        }
        $studentFormativeTestResult = StudentFormativeTestResult::where('formative_test_item_id', $formative_test_item_id)
                                                        ->where('student_id',  $request->input('student_id'))->first();;
        if($studentFormativeTestResult) {
            $studentFormativeTestResult->student_id = $request->input('student_id');
            $studentFormativeTestResult->formative_test_item_id = $formative_test_item_id;
            $studentFormativeTestResult->score = $request->input('score'); 
            $studentFormativeTestResult->status = $request->input('status'); 
            $studentFormativeTestResult->updated_at = now();             
            $studentFormativeTestResult->update();
            return response()->json([
                'status'=>200,
                'message'=>'Student Formative Test Result Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Student Formative Test Result Id Found',
            ]); 
        }
    
    }
}