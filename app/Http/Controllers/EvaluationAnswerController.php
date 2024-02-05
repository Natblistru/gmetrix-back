<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluationAnswerController extends Controller
{
    // public static function index() {
    //     $evaluationAnswer =  EvaluationAnswer::all();
    //     return response()->json([
    //         'status' => 200,
    //         'evaluationAnswer' => $evaluationAnswer,
    //     ]);
    // }

    public static function index(Request $request) {

        $search = $request->query('search');
        $sortColumn = $request->query('sortColumn');
        $sortOrder = $request->query('sortOrder');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $filterChapter = $request->query('filterChapter');
        $filterTheme = $request->query('filterTheme');
        $filterProgram = $request->query('filterProgram');

        $allowedColumns = ['id', 'order_number', 'task', 'max_points', 'item_task', 'title', 'theme_name', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'EA',
            'order_number' => 'EA',
            'task' => 'EA',
            'max_points' => 'EA',
            'item_task' => 'EIV',
            'title' => 'ES',
            'theme_name' => 'TV',
            'status' => 'EA',
        ];

        $sqlTemplate = "
        SELECT
            EA.id,
            EA.order_number,
            EA.task,
            EA.max_points,
            EIV.item_task,
            ES.title,
            TV.theme_name,
            TV.chapter_id,
            TLP.theme_id,
            LP.id program_id,
            EA.status
        FROM 
        	evaluation_answers EA 
            INNER JOIN (
                SELECT 
                    EI.task AS item_task,
                    EI.id,
                	EI.evaluation_subject_id,
                	EI.theme_id
                FROM evaluation_items EI
            ) AS EIV ON EA.evaluation_item_id = EIV.id
            INNER JOIN evaluation_subjects ES ON EIV.evaluation_subject_id = ES.id
            INNER JOIN (
                SELECT 
                    T.name AS theme_name,
                    T.chapter_id,
                    T.id
                FROM themes T
            ) AS TV ON EIV.theme_id = TV.id
            INNER JOIN theme_learning_programs TLP ON TLP.theme_id = TV.id
            INNER JOIN learning_programs LP ON TLP.learning_program_id = LP.id
        WHERE true
        ";

        $searchConditions = '';
        if ($search) {
            $searchLower = strtolower($search);
    
            $hiddenVariants = ['i','d','e','n','hi', 'hid', 'id', 'idd', 'dd','dde', 'hidd', 'hidde', 'de', 'den', 'en'];
            $shownVariants = ['s','o','w','sh','ho','sho', 'show', 'wn', 'ow', 'own'];
    
            if ($searchLower === 'hidden' || in_array($searchLower, $hiddenVariants)) {
                foreach ($allowedColumns as $column) {
                    $table = $columnTableMapping[$column];
                    $searchConditions .= ($column === 'status') ? "$table.$column = 1 OR " : "LOWER($table.$column) LIKE '%$searchLower%' OR ";
                }
            } elseif ($searchLower === 'shown' || in_array($searchLower, $shownVariants)) {
                foreach ($allowedColumns as $column) {
                    $table = $columnTableMapping[$column];
                    $searchConditions .= ($column === 'status') ? "$table.$column = 0 OR " : "LOWER($table.$column) LIKE '%$searchLower%' OR ";
                }
            } else {
                foreach ($allowedColumns as $column) {
                    $table = $columnTableMapping[$column];
                    $searchConditions .= "LOWER($table.$column) LIKE '%$searchLower%' OR ";
                }
            }
            $searchConditions = rtrim($searchConditions, ' OR ');
        }
    
        $sqlWithSortingAndSearch = $sqlTemplate;
    
        if ($searchConditions) {
            $sqlWithSortingAndSearch .= " AND $searchConditions";
        }

        if ($filterChapter) {
            $sqlWithSortingAndSearch .= " AND TV.chapter_id = $filterChapter";
        }

        if ($filterTheme) {
            $sqlWithSortingAndSearch .= " AND TLP.theme_id = $filterTheme";
        }
    
        if ($filterProgram) {
            $sqlWithSortingAndSearch .= " AND LP.id = $filterProgram";
        }

        $sqlWithSortingAndSearch .= " ORDER BY $sortColumn $sortOrder";

        $totalResults = DB::select("SELECT COUNT(*) as total FROM ($sqlWithSortingAndSearch) as countTable")[0]->total;
    
        $lastPage = ceil($totalResults / $perPage);
    
        $offset = ($page - 1) * $perPage;
    
        $rawResults = DB::select("$sqlWithSortingAndSearch LIMIT $perPage OFFSET $offset");

        return response()->json([
            'status' => 200,
            'evaluationAnswer' => $rawResults,
            'pagination' => [
                'last_page' => $lastPage,
                'current_page' => $page,
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $totalResults),
                'total' => $totalResults,
            ],
        ]);
    }
    
    public static function show($id) {
        return EvaluationAnswer::find($id); 
    }

    public static function allEvaluationAnswers() {
        $evaluationAnswers =  EvaluationAnswer::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'evaluationAnswers' => $evaluationAnswers,
        ]);
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'task' => 'required|string|max:1000',
            'content' => 'nullable|string|max:2000',
            'max_points' => 'required|integer|min:1',
            'evaluation_item_id' => 'required|exists:evaluation_items,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'order_number' => $request->input('order_number'),
            'task' => $request->input('task'),
            'content' => $request->input('content'),
            'max_points' => $request->input('max_points'),            
            'evaluation_item_id' => $request->input('evaluation_item_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'evaluation_item_id' => $data['evaluation_item_id'], 
            'task' => $data['task'],       
        ];
    
        $existingRecord = EvaluationAnswer::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
 
            EvaluationAnswer::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            EvaluationAnswer::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Answer Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationAnswer = EvaluationAnswer::with('evaluation_item')->find($id);
       
        if ($evaluationAnswer) {
            return response()->json([
                'status' => 200,
                'evaluationAnswer' => $evaluationAnswer,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Answer Source Id Found',
            ]);
        }
    }

    public static function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'task' => 'required|string|max:1000',
            'content' => 'nullable|string|max:2000',
            'max_points' => 'required|integer|min:1',
            'evaluation_item_id' => 'required|exists:evaluation_items,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $evaluationAnswer = EvaluationAnswer::find($id);
    
        if ($evaluationAnswer) {
            $evaluationAnswer->order_number = $request->input('order_number');
            $evaluationAnswer->task = $request->input('task');
            $evaluationAnswer->content = $request->input('content');
            $evaluationAnswer->max_points = $request->input('max_points');
            $evaluationAnswer->evaluation_item_id = $request->input('evaluation_item_id');
            $evaluationAnswer->status = $request->input('status');
    
            $evaluationAnswer->updated_at = now();
            $evaluationAnswer->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Evaluation Answer Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Answer Id Found',
            ]);
        }
    }

}
