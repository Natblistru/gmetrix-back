<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationFormPage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluationFormPageController extends Controller
{
    // public static function index() {
    //     $evaluationFormPage =  EvaluationFormPage::all();
    //     return response()->json([
    //         'status' => 200,
    //         'evaluationFormPage' => $evaluationFormPage,
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

        $allowedColumns = ['id', 'order_number', 'task', 'item_task', 'title', 'theme_name', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'EFP',
            'order_number' => 'EFP',            
            'task' => 'EFP',
            'item_task' => 'EIV',
            'title' => 'ES',
            'theme_name' => 'TV',
            'status' => 'EA',
        ];

        $sqlTemplate = "
        SELECT
            EFP.id,
            EFP.order_number,            
            EFP.task,
            EIV.item_task,
            EIV.item_id,
            ES.title,
            TV.theme_name,
            TV.chapter_id,
            TLP.theme_id,
            LP.id program_id,
            EFP.status
        FROM 
        	evaluation_form_pages EFP
            INNER JOIN (
                SELECT 
                    EI.task AS item_task,
                    EI.id AS item_id,
                	EI.evaluation_subject_id,
                	EI.theme_id
                FROM evaluation_items EI
            ) AS EIV ON EFP.evaluation_item_id = EIV.item_id
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
            'evaluationFormPage' => $rawResults,
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
        return EvaluationFormPage::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'task' => 'required|string|max:1000',
            'hint' => 'nullable|string|max:2000',
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
            'hint' => $request->input('hint'),
            'evaluation_item_id' => $request->input('evaluation_item_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'evaluation_item_id' => $data['evaluation_item_id'], 
            'task' => $data['task'],       
        ];
    
        $existingRecord = EvaluationFormPage::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
 
            EvaluationFormPage::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            EvaluationFormPage::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Form Page Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationFormPage = EvaluationFormPage::with('evaluation_item')->find($id);
       
        if ($evaluationFormPage) {
            return response()->json([
                'status' => 200,
                'evaluationFormPage' => $evaluationFormPage,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Form Page Id Found',
            ]);
        }
    }

    public static function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'task' => 'required|string|max:1000',
            'hint' => 'nullable|string|max:2000',
            'evaluation_item_id' => 'required|exists:evaluation_items,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $evaluationFormPage = EvaluationFormPage::find($id);
    
        if ($evaluationFormPage) {
            $evaluationFormPage->order_number = $request->input('order_number');
            $evaluationFormPage->task = $request->input('task');
            $evaluationFormPage->hint = $request->input('hint');
            $evaluationFormPage->evaluation_item_id = $request->input('evaluation_item_id');
            $evaluationFormPage->status = $request->input('status');
    
            $evaluationFormPage->updated_at = now();
            $evaluationFormPage->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Evaluation Form Page Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Form Page Id Found',
            ]);
        }
    }

}
