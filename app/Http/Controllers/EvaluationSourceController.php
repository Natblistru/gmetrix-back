<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationSource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluationSourceController extends Controller
{
    // public static function index() {
    //     $evaluationSources =  EvaluationSource::all();
    //     return response()->json([
    //         'status' => 200,
    //         'evaluationSources' => $evaluationSources,
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

        $allowedColumns = ['id', 'name', 'theme_name', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'ES',
            'name' => 'ES',
            'theme_name' => 'TV',
            'status' => 'ES',
        ];

        $sqlTemplate = "
        SELECT
            ES.id,
            ES.name,
            TV.theme_name,
            TV.chapter_id,
            TLP.theme_id,
            LP.id program_id,
            ES.status
        FROM 
            evaluation_sources ES
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
            'evaluationSources' => $rawResults,
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
        return EvaluationSource::find($id); 
    }

    public static function allEvaluationSources() {
        $evaluationSources =  EvaluationSource::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'evaluationSources' => $evaluationSources,
        ]);
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'title' => 'nullable|string|max:200',
            'content' => 'required|json',
            'author' => 'nullable|string|max:200',
            'text_sourse' => 'nullable|string|max:200',
            'theme_id' => 'required|exists:themes,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'name' => $request->input('name'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'author' => $request->input('author'),
            'text_sourse' => $request->input('text_sourse'),
            'theme_id' => $request->input('theme_id'),
        ];
    
        $combinatieColoane = [
            'name' => $data['name'],
            'title' => $data['title'],
            'author' => $data['author'],    
            'text_sourse' => $data['text_sourse'],      
            'theme_id' => $data['theme_id'],      
        ];
    
        $existingRecord = EvaluationSource::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            EvaluationSource::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            EvaluationSource::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Source Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationSource = EvaluationSource::with('theme')->find($id);
        if ($evaluationSource) {
            return response()->json([
                'status' => 200,
                'evaluationSource' => $evaluationSource,
                'subject_study_level_id' => $evaluationSource->theme->chapter->subject_study_level_id ?? null,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Source Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'title' => 'nullable|string|max:200',
            'content' => 'required|json',
            'author' => 'nullable|string|max:200',
            'text_sourse' => 'nullable|string|max:200',
            'theme_id' => 'required|exists:themes,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $evaluationSource = EvaluationSource::find($id);
        if($evaluationSource) {
            $evaluationSource->name = $request->input('name');
            $evaluationSource->title = $request->input('title');
            $evaluationSource->content = $request->input('content');
            $evaluationSource->author = $request->input('author');
            $evaluationSource->text_sourse = $request->input('text_sourse');
            $evaluationSource->theme_id = $request->input('theme_id');                     
            $evaluationSource->status = $request->input('status'); 
            $evaluationSource->updated_at = now();             
            $evaluationSource->update();
            return response()->json([
                'status'=>200,
                'message'=>'Evaluation Source Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Evaluation Source Id Found',
            ]); 
        }
    }
}
