<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentEvaluationSolution;
use Illuminate\Support\Facades\Validator;

class StudentEvaluationSolutionController extends Controller
{
    public function index()
    {
        // Retrieve all solutions
        $solutions = StudentEvaluationSolution::all();
        return response()->json($solutions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'solution' => 'required|string|max:3000',
            'evaluation_item_id' => 'required|exists:evaluation_items,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $status = $request->input('status') !== null ? $request->input('status') : 0;
        $data = [
            'solution' => $request->input('solution'),
            'student_id' => $request->input('student_id'),       
            'evaluation_item_id' => $request->input('evaluation_item_id'),
            'status' => $status,
        ];
    
        $combinatieColoane = [
            'evaluation_item_id' => $data['evaluation_item_id'], 
            'student_id' => $data['student_id'],       
        ];
    
        $existingRecord = StudentEvaluationSolution::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
 
            StudentEvaluationSolution::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            StudentEvaluationSolution::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Student Evaluation Solution Added successfully',
        ]);
    }

    public function show($id)
    {
        // Retrieve a single solution
        $solution = StudentEvaluationSolution::findOrFail($id);
        return response()->json($solution);
    }

    public function update(Request $request, $id)
    {
        // Update a solution
        $solution = StudentEvaluationSolution::findOrFail($id);
        $solution->update($request->all());
        return response()->json($solution, 200);
    }

    public function destroy($id)
    {
        // Delete a solution
        $solution = StudentEvaluationSolution::findOrFail($id);
        $solution->delete();
        return response()->json(null, 204);
    }
}