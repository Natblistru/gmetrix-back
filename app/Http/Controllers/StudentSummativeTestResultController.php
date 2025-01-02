<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummativeTestItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentSummativeTestResult;
use App\Models\StudentSummativeTest;

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

    public function reset(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|integer|exists:users,id', // Validăm că ID-ul studentului este valid
        ]);

        try {
            // Ștergem înregistrările pentru studentul specificat
            DB::table('student_summative_test_results')
                ->where('student_id', $validatedData['student_id'])
                ->delete();

            return response()->json([
                'message' => 'Summative test results reset successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while resetting summative test results.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeStudentRankings(Request $request)
    {
        // Validarea datelor de intrare
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'summative_test_id' => 'required|exists:summative_tests,id',
            'time' => 'required|integer',
            'score' => 'required|numeric|min:0',
        ]);
        // Verificăm dacă validarea a eșuat
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'score' => $request->input('score'),
            'time' => $request->input('time'),
            'summative_test_id' => $request->input('summative_test_id'),
            'student_id' => $request->input('student_id'),
        ];
    
        $combinatieColoane = [
            'student_id' => $data['student_id'],
            'summative_test_id' => $data['summative_test_id'],         
        ];
    
        $existingRecord = StudentSummativeTest::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            StudentSummativeTest::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            StudentSummativeTest::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Student Summative Test Added successfully',
        ]);
    }

    public function getStudentRankings(Request $request)
    {
        $summativeTestId = $request->query('summative_test_id'); // Obține parametrul din query string
    
        Log::info('Generare clasament pentru testul sumativ: ' . $summativeTestId);
    
        try {
            $finalQuery = "
                SELECT
                    tests.student_id,
                    students.name AS student_name,
                    tests.summative_test_id,
                    tests.score AS total_score,
                    tests.time AS timeTest
                FROM
                    student_summative_tests AS tests
                INNER JOIN
                    students
                ON
                    tests.student_id = students.id
                WHERE
                    tests.summative_test_id = :summative_test_id
                ORDER BY
                    total_score DESC,
                    timeTest ASC;
            ";
    
            $results = DB::select($finalQuery, ['summative_test_id' => $summativeTestId]);
    
            Log::info('Rezultate clasament studenți: ' . json_encode($results));
    
            return response()->json($results, 200);
        } catch (\Exception $e) {
            Log::error('Eroare la generarea clasamentului: ' . $e->getMessage());
    
            return response()->json([
                'message' => 'A apărut o eroare la generarea clasamentului.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
