<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentSubopicProgress;

class StudentSubopicProgressController extends Controller
{
    public static function index() {
        return StudentSubopicProgress::all();
    }

    public static function show($id) {
        return StudentSubopicProgress::find($id); 
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required',
            'subtopic_id' => 'required',
            'progress_percentage' => 'required',
        ]);

        $progress = StudentSubopicProgress::updateOrCreate(
            [
                'student_id' => $validatedData['student_id'],
                'subtopic_id' => $validatedData['subtopic_id'],
            ],
            ['progress_percentage' => $validatedData['progress_percentage']]
        );

        return response()->json($progress, 201);
    }

}
