<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentSummativeTestOption;

class StudentSummativeTestOptionController extends Controller
{
    public static function index() {
        return StudentSummativeTestOption::all();
    }

    public static function show($id) {
        return StudentSummativeTestOption::find($id); 
    }

    public function store(Request $request)
    {
       // Validarea datelor de intrare
    $validator = Validator::make($request->all(), [
        'student_id' => 'required|integer',
        'formative_test_id' => 'required|integer',
        'test_item_id' => 'required|integer',
        'score' => 'required|numeric|min:0',
        'option' => 'required|string|max:500',
        'type' => 'required|string|max:50',
        'explanation' => 'required_if:type,snap|string|max:5000',
    ]);
        // Verificăm dacă validarea a eșuat
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Apelul procedurii stocate
        DB::select('CALL InsertOrUpdateStudentSummativeTestOption(?, ?, ?, ?, ?, ?, ?)', [
            $request->input('student_id'),
            $request->input('formative_test_id'),
            $request->input('test_item_id'),
            $request->input('score'),
            $request->input('option'),
            $request->input('type'),
            $request->input('explanation'),
        ]);

        return response()->json(['message' => 'Success'], 200);
    }

}
