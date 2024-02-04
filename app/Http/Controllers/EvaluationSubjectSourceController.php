<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EvaluationSubjectSource;
use Illuminate\Support\Facades\Validator;

class EvaluationSubjectSourceController extends Controller
{
    // public static function index() {
    //     $evaluationSubjectSources =  EvaluationSubjectSource::all();
    //     return response()->json([
    //         'status' => 200,
    //         'evaluationSubjectSources' => $evaluationSubjectSources,
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

        $allowedColumns = ['id', 'name', 'title', 'order_number', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'ESS',
            'name' => 'ES',
            'title' => 'ESub',
            'order_number' => 'ESS',            
            'status' => 'ES',
        ];

        $sqlTemplate = "
        SELECT
            ESS.id,
            ESS.order_number,
            ES.name,
            ESub.title,
            TV.theme_name,
            TV.chapter_id,
            TLP.theme_id,
            LP.id program_id
        FROM 
            evaluation_sources ES
            INNER JOIN evaluation_subject_sources ESS ON ESS.evaluation_source_id = ES.id
            INNER JOIN evaluation_subjects ESub ON ESS.evaluation_subject_id = ESub.id
            INNER JOIN (
                SELECT 
                    T.name AS theme_name,
                    T.chapter_id,
                    T.id
                FROM themes T
            ) AS TV ON ES.theme_id = TV.id
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
            'evaluationSubjectSources' => $rawResults,
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
        return EvaluationSubjectSource::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'evaluation_subject_id' => 'required|exists:evaluation_subjects,id',
            'evaluation_source_id' => 'required|exists:evaluation_sources,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'order_number' => $request->input('order_number'),
            'evaluation_subject_id' => $request->input('evaluation_subject_id'),
            'evaluation_source_id' => $request->input('evaluation_source_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'evaluation_subject_id' => $data['evaluation_subject_id'], 
            'evaluation_source_id' => $data['evaluation_source_id'],        
        ];
    
        $existingRecord = EvaluationSubjectSource::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            EvaluationSubjectSource::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            EvaluationSubjectSource::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Subject Source Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationSubjectSources = EvaluationSubjectSource::with('evaluation_subject')->with('evaluation_source')->find($id);
       
        if ($evaluationSubjectSources) {
            return response()->json([
                'status' => 200,
                'evaluationSubjectSources' => $evaluationSubjectSources,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Subject Source Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'evaluation_subject_id' => 'required|exists:evaluation_subjects,id',
            'evaluation_source_id' => 'required|exists:evaluation_sources,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $evaluationSubjectSource = EvaluationSubjectSource::find($id);
        if($evaluationSubjectSource) {
            $evaluationSubjectSource->order_number = $request->input('order_number');
            $evaluationSubjectSource->evaluation_subject_id = $request->input('evaluation_subject_id');
            $evaluationSubjectSource->evaluation_source_id = $request->input('evaluation_source_id');                     
            $evaluationSubjectSource->status = $request->input('status'); 
            $evaluationSubjectSource->updated_at = now();             
            $evaluationSubjectSource->update();
            return response()->json([
                'status'=>200,
                'message'=>'Evaluation Subject Sourse Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Evaluation Subject Id Found',
            ]); 
        }
    }
}
