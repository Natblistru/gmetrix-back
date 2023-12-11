<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        'summative_test_id' => 'required|integer',
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
            $request->input('summative_test_id'),
            $request->input('test_item_id'),
            $request->input('type'),
        ]);

        return response()->json(['message' => 'Success'], 200);
    }
}
