<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EvaluationAnswerOption;
use Illuminate\Support\Facades\Validator;

class EvaluationAnswerOptionController extends Controller
{
    // public static function index() {
    //     $evaluationAnswerOption =  EvaluationAnswerOption::all();
    //     return response()->json([
    //         'status' => 200,
    //         'evaluationAnswerOption' => $evaluationAnswerOption,
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
        $filterEvaluationItem = $request->query('filterEvaluationItem');    

        $allowedColumns = ['id', 'task', 'label', 'points', 'item_task', 'title', 'theme_name', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'EAO',
            'task' => 'EA',
            'label' => 'EO',
            'points' => 'EO',
            'item_task' => 'EIV',
            'title' => 'ES',
            'theme_name' => 'TV',
            'status' => 'EA',
        ];

        $sqlTemplate = "
        SELECT
            EAO.id,
            EA.task,
            EO.label,
            EO.points,
            EIV.item_task,
            EIV.item_id,
            ES.title,
            TV.theme_name,
            TV.chapter_id,
            TLP.theme_id,
            LP.id program_id,
            EAO.status
        FROM 
        	evaluation_answer_options EAO
            INNER JOIN evaluation_options EO ON EO.id = EAO.evaluation_option_id
        	INNER JOIN evaluation_answers EA ON EA.id = EAO.evaluation_answer_id
            INNER JOIN (
                SELECT 
                    EI.task AS item_task,
                    EI.id AS item_id,
                	EI.evaluation_subject_id,
                	EI.theme_id
                FROM evaluation_items EI
            ) AS EIV ON EA.evaluation_item_id = EIV.item_id
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

        if ($filterEvaluationItem) {
            $sqlWithSortingAndSearch .= " AND EIV.item_id = $filterEvaluationItem";
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
            'evaluationAnswerOption' => $rawResults,
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
        return EvaluationAnswerOption::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'evaluation_option_id' => 'required|exists:evaluation_options,id',
            'evaluation_answer_id' => 'required|exists:evaluation_answers,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'evaluation_option_id' => $request->input('evaluation_option_id'),
            'evaluation_answer_id' => $request->input('evaluation_answer_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'evaluation_option_id' => $data['evaluation_option_id'], 
            'evaluation_answer_id' => $data['evaluation_answer_id'],       
        ];
    
        $existingRecord = EvaluationAnswerOption::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
 
            EvaluationAnswerOption::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            EvaluationAnswerOption::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Answer Option Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationAnswerOption = EvaluationAnswerOption::find($id);
       
        if ($evaluationAnswerOption) {
            return response()->json([
                'status' => 200,
                'evaluationAnswerOption' => $evaluationAnswerOption,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Option Id Found',
            ]);
        }
    }

    public static function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'evaluation_option_id' => 'required|exists:evaluation_options,id',
            'evaluation_answer_id' => 'required|exists:evaluation_answers,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $evaluationAnswerOption = EvaluationAnswerOption::find($id);
    
        if ($evaluationAnswerOption) {
            $evaluationAnswerOption->evaluation_option_id = $request->input('evaluation_option_id');
            $evaluationAnswerOption->evaluation_answer_id = $request->input('evaluation_answer_id');
            $evaluationAnswerOption->status = $request->input('status');
    
            $evaluationAnswerOption->updated_at = now();
            $evaluationAnswerOption->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Evaluation Answer Option Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Answer Option Id Found',
            ]);
        }
    }
}
