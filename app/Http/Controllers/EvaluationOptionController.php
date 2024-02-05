<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluationOptionController extends Controller
{
    // public static function index() {
    //     $evaluationOption =  EvaluationOption::all();
    //     return response()->json([
    //         'status' => 200,
    //         'evaluationOption' => $evaluationOption,
    //     ]);
    // }

    public static function index(Request $request) {

        $search = $request->query('search');
        $sortColumn = $request->query('sortColumn');
        $sortOrder = $request->query('sortOrder');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);

        $allowedColumns = ['id', 'label', 'points', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'EO',
            'label' => 'EO',
            'points' => 'EO',
            'status' => 'EO',
        ];

        $sqlTemplate = "
        SELECT
            EO.id,
            EO.label,
            EO.points,
            EO.status
        FROM 
        	evaluation_options EO 
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

        $sqlWithSortingAndSearch .= " ORDER BY $sortColumn $sortOrder";

        $totalResults = DB::select("SELECT COUNT(*) as total FROM ($sqlWithSortingAndSearch) as countTable")[0]->total;
    
        $lastPage = ceil($totalResults / $perPage);
    
        $offset = ($page - 1) * $perPage;
    
        $rawResults = DB::select("$sqlWithSortingAndSearch LIMIT $perPage OFFSET $offset");

        return response()->json([
            'status' => 200,
            'evaluationOption' => $rawResults,
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
        return EvaluationOption::find($id); 
    }

    public static function allEvaluationOptions() {
        $evaluationOptions =  EvaluationOption::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'evaluationOptions' => $evaluationOptions,
        ]);
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'points' => 'required|integer|min:0',
            'label' => 'required|string|max:500',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'points' => $request->input('points'),
            'label' => $request->input('label'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'points' => $data['points'], 
            'label' => $data['label'],       
        ];
    
        $existingRecord = EvaluationOption::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
 
            EvaluationOption::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            EvaluationOption::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Answer Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationOption = EvaluationOption::find($id);
       
        if ($evaluationOption) {
            return response()->json([
                'status' => 200,
                'evaluationOption' => $evaluationOption,
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
            'points' => 'required|integer|min:0',
            'label' => 'required|string|max:500',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $evaluationOption = EvaluationOption::find($id);
    
        if ($evaluationOption) {
            $evaluationOption->points = $request->input('points');
            $evaluationOption->label = $request->input('label');
            $evaluationOption->status = $request->input('status');
    
            $evaluationOption->updated_at = now();
            $evaluationOption->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Evaluation Option Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Option Id Found',
            ]);
        }
    }

}
