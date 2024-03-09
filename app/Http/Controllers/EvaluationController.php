<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller
{
    // public static function index() {
    //     $evaluations =  Evaluation::all();
    //     return response()->json([
    //         'status' => 200,
    //         'evaluations' => $evaluations,
    //     ]);
    // }

    public static function index(Request $request) {
        $search = $request->query('search');
        $sortColumn = $request->query('sortColumn');
        $sortOrder = $request->query('sortOrder');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $filterType = $request->query('filterType');
        $filterYear = $request->query('filterYear');
        $filterProgram = $request->query('filterProgram');

        $allowedColumns = ['id', 'year', 'name', 'type', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'E',
            'year' => 'E',
            'name' => 'SSLev',
            'type' => 'E',
            'status' => 'E',
        ];

        $sqlTemplate = "
        SELECT
            E.id,
            E.year,
            E.type,
            SSLev.name,
            SSLev.id subject_study_level_id,            
            E.status
        FROM 
            evaluations E
            INNER JOIN subject_study_levels SSLev ON E.subject_study_level_id = SSLev.id
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

        if ($filterType) {
            $sqlWithSortingAndSearch .= " AND E.type = '$filterType'";
        }

        if ($filterYear) {
            $sqlWithSortingAndSearch .= " AND E.year = $filterYear";
        }
    
        if ($filterProgram) {
            $sqlWithSortingAndSearch .= " AND SSLev.id = $filterProgram";
        }

        $sqlWithSortingAndSearch .= " ORDER BY $sortColumn $sortOrder";

        $totalResults = DB::select("SELECT COUNT(*) as total FROM ($sqlWithSortingAndSearch) as countTable")[0]->total;
    
        $lastPage = ceil($totalResults / $perPage);
    
        $offset = ($page - 1) * $perPage;
    
        $rawResults = DB::select("$sqlWithSortingAndSearch LIMIT $perPage OFFSET $offset");


        return response()->json([
            'status' => 200,
            'evaluations' => $rawResults,
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
        return Evaluation::find($id); 
    }

    public static function allEvaluations() {
        $evaluations = Evaluation::where('status',0)->get();
    
        $years = $evaluations->pluck('year')->unique()->values()->all();
        $types = $evaluations->pluck('type')->unique()->values()->all();
    
        return response()->json([
            'status' => 200,
            'years' => $years,
            'types' => $types,
            'evaluations' => $evaluations,
        ]);
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'subject_study_level_id' => 'required|exists:subject_study_levels,id',
            'type' => 'required|in:Pretestare,Testare de baza,Evaluare suplimentara,Teste pentru exersare1,Teste pentru exersare2,Teste preparatorii,Teste preparatorii2,Teste preparatorii3,Teste preparatorii4,Manual',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'name' => $request->input('name'),
            'year' => $request->input('year'),
            'subject_study_level_id' => $request->input('subject_study_level_id'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'year' => $data['year'],
            'subject_study_level_id' => $data['subject_study_level_id'],
            'type' => $data['type'],            
        ];
    
        $existingRecord = Evaluation::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            Evaluation::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            Evaluation::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluations = Evaluation::find($id);
        if ($evaluations) {
            return response()->json([
                'status' => 200,
                'evaluations' => $evaluations,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'subject_study_level_id' => 'required|exists:subject_study_levels,id',
            'type' => 'required|in:Pretestare,Testare de baza,Evaluare suplimentara,Teste pentru exersare1,Teste pentru exersare2,Teste preparatorii,Teste preparatorii2,Teste preparatorii3,Teste preparatorii4,Manual',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $evaluation = Evaluation::find($id);
        if($evaluation) {
            $evaluation->name = $request->input('name');
            $evaluation->year = $request->input('year');
            $evaluation->type = $request->input('type');
            $evaluation->subject_study_level_id = $request->input('subject_study_level_id');                     
            $evaluation->status = $request->input('status'); 
            $evaluation->updated_at = now();             
            $evaluation->update();
            return response()->json([
                'status'=>200,
                'message'=>'Evaluation Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Evaluation Id Found',
            ]); 
        }
    }
}
