<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $itemId = $request->input('itemId');
        $studentId = $request->input('studentId');

        $studentFormativeTestResult = StudentFormativeTestResult::where('student_id', $studentId)->where('formative_test_item_id', $itemId)->first();

        if ($studentFormativeTestResult) {
            return response()->json([
                'status' => 200,
                'score' => $studentFormativeTestResult->score,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'ID not found',
            ], 404);
        }
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
        DB::select('CALL InsertOrUpdateStudentFormativeTestResult(?, ?, ?, ?)', [
            $request->input('student_id'),
            $request->input('formative_test_id'),
            $request->input('test_item_id'),
            $request->input('type'),
        ]);

        return response()->json(['message' => 'Success'], 200);
    }
}
