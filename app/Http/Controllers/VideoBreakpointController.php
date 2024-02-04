<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\VideoBreakpoint;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VideoBreakpointController extends Controller
{
    // public static function index() {
    //     $breakpoints =  VideoBreakpoint::all();
    //     return response()->json([
    //         'status' => 200,
    //         'breakpoints' => $breakpoints,
    //     ]);
    // }

    public static function index(Request $request) {
        $search = $request->query('search');
        $sortColumn = $request->query('sortColumn');
        $sortOrder = $request->query('sortOrder');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $filterTeacher = $request->query('filterTeacher');
        $filterTheme = $request->query('filterTheme');
        $filterProgram = $request->query('filterProgram');

        $allowedColumns = ['id', 'name', 'time', 'title', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }

        $columnTableMapping = [
            'id' => 'VB',
            'name' => 'VB',
            'time' => 'VB',
            'title' => 'V',
            'status' => 'VB',
        ];

        $sqlTemplate = "
        SELECT
            VB.id,        
            VB.name,
            VB.time,
            VB.status,
            V.title,
            TTV.teacher_id,
            TLP.theme_id theme_id,
            LP.id program_id
        FROM 
            video_breakpoints VB
            INNER JOIN videos V ON V.id=VB.video_id
            INNER JOIN teacher_theme_videos TTV ON TTV.video_id = V.id
            INNER JOIN theme_learning_programs TLP ON TLP.id = TTV.theme_learning_program_id
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

        if ($filterTeacher) {
            $sqlWithSortingAndSearch .= " AND TTV.teacher_id = $filterTeacher";
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
            'breakpoints' => $rawResults,
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
        return VideoBreakpoint::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'video_id' => 'required|integer|min:0|exists:videos,id',
            'time' => ['required', 'string', 'max:10', 'regex:/^(?:[01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $timestamp = strtotime($request->input('time'));
        $seconds = date('H', $timestamp) * 3600 + date('i', $timestamp) * 60 + date('s', $timestamp); 

        $data = [
            'name' => $request->input('name'),
            'time' => $request->input('time'),
            'video_id' => $request->input('video_id'),
            'seconds' => strval($seconds),
            'status' => $request->input('status'),
        ];

        $combinatieColoane = [
            'name' => $data['name'],
            'video_id' => $data['video_id'],
        ];
    
        $existingRecord = VideoBreakpoint::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            VideoBreakpoint::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            VideoBreakpoint::create($data);
        }
    
        return response()->json([
            'status'=>201,
            'message'=>'Video Added successfully',
        ]);
    }

    public static function edit($id) {
        $breakpoint =  VideoBreakpoint::find($id);
        if($breakpoint) {
            return response()->json([
                'status' => 200,
                'breakpoint' => $breakpoint,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Breakpoint Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'video_id' => 'required|integer|min:0|exists:videos,id',
            'time' => ['required', 'string', 'max:10', 'regex:/^(?:[01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $breakpoint = VideoBreakpoint::find($id);
        if($breakpoint) {
            $breakpoint->name = $request->input('name');
            $breakpoint->time = $request->input('time');
            $breakpoint->video_id = $request->input('video_id');           
            $breakpoint->status = $request->input('status'); 
            $breakpoint->updated_at = now();             
            $breakpoint->update();
            return response()->json([
                'status'=>200,
                'message'=>'Breakpoint Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Breakpoint Id Found',
            ]); 
        }
    }

}
