<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentFormativeTestOption;

class StudentFormativeTestOptionController extends Controller
{
    public static function index() {
        return StudentFormativeTestOption::all();
    }

    public static function show($id) {
        return StudentFormativeTestOption::find($id); 
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
    ]);
        // Verificăm dacă validarea a eșuat
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Apelul procedurii stocate
        DB::select('CALL InsertOrUpdateStudentFormativeTestOption(?, ?, ?, ?, ?)', [
            $request->input('student_id'),
            $request->input('formative_test_id'),
            $request->input('test_item_id'),
            $request->input('score'),
            $request->input('option'),
        ]);

        return response()->json(['message' => 'Success'], 200);
    }


}
