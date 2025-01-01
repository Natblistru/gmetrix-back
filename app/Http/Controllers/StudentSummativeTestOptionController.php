<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummativeTestItem;
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

    public static function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'summative_test_id' => 'nullable|exists:summative_tests,id',
            'test_item_id' => 'nullable|exists:test_items,id',
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

        if ($summative_test_item) {
            $summative_test_item_id = $summative_test_item->id;
        } else {
            return response()->json([
                'status'=>404,
                'message'=>'No Summative Test Item Id Found',
            ]); 
        }

        $studentSummativeTestOptions = StudentSummativeTestOption::where('summative_test_item_id', $summative_test_item_id)
                                                        ->where('student_id',  $request->input('student_id'))->get();;
        
        if($studentSummativeTestOptions->count() > 0) {
            foreach ($studentSummativeTestOptions as $option) {
                $option->student_id = $request->input('student_id');
                $option->summative_test_item_id = $summative_test_item_id;
                $option->test_item_option_id = $option->test_item_option_id;
                $option->score = $request->input('score'); 
                $option->status = $request->input('status'); 
                $option->updated_at = now();             
                $option->update();
            }
        
            return response()->json([
                'status' => 200,
                'message' => 'Opțiunile de test summativ ale studentului au fost actualizate cu succes',
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Student Summative Test Option Id Found',
            ]); 
        }        
    }

    public function reset(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|integer|exists:users,id', // Validăm că ID-ul studentului este valid
        ]);

        try {
            // Ștergem înregistrările pentru studentul specificat
            DB::table('student_summative_test_options')
                ->where('student_id', $validatedData['student_id'])
                ->delete();

            return response()->json([
                'message' => 'Summative test options reset successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while resetting summative test options.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
