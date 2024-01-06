<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormativeTestItem;
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

    public function store(Request $request) {
       // Validarea datelor de intrare
    $validator = Validator::make($request->all(), [
        'student_id' => 'required|integer',
        'formative_test_id' => 'required|integer',
        'test_item_id' => 'required|integer',
        'score' => 'required|numeric|min:0',
        'option' => 'required|string|max:500',
        'type' => 'required|string|max:50',
        'explanation' => 'required_if:type,snap|string|max:500',
    ]);
        // Verificăm dacă validarea a eșuat
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Apelul procedurii stocate
        DB::select('CALL InsertOrUpdateStudentFormativeTestOption(?, ?, ?, ?, ?, ?, ?)', [
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

        $studentFormativeTestOptions = StudentFormativeTestOption::where('formative_test_item_id', $formative_test_item_id)
                                                        ->where('student_id',  $request->input('student_id'))->get();;
        
        if($studentFormativeTestOptions->count() > 0) {
            foreach ($studentFormativeTestOptions as $option) {
                $option->student_id = $request->input('student_id');
                $option->formative_test_item_id = $formative_test_item_id;
                $option->test_item_option_id = $option->test_item_option_id;
                $option->score = $request->input('score'); 
                $option->status = $request->input('status'); 
                $option->updated_at = now();             
                $option->update();
            }
        
            return response()->json([
                'status' => 200,
                'message' => 'Opțiunile de test formativ ale studentului au fost actualizate cu succes',
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Student Formative Test Option Id Found',
            ]); 
        }        
    }
}
