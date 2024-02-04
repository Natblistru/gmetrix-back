<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    // public static function index(Request $request) {
    //     $video =  Video::all();
    //     return response()->json([
    //         'status' => 200,
    //         'video' => $video,
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

        $allowedColumns = ['id', 'title', 'source', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'V',
            'title' => 'V',
            'source' => 'V',
            'status' => 'V',
        ];

        $sqlTemplate = "
        SELECT
            V.id,
            V.title,
            V.source,
            V.status,
            TTV.teacher_id,
            TLP.theme_id theme_id,
            LP.id program_id
        FROM 
            videos V 
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
            'videos' => $rawResults,
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
        return Video::find($id); 
    }

    public static function allvideos() {
        $video =  Video::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'video' => $video,
        ]);
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'source' => 'required|string|max:500',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'title' => $request->input('title'),
            'source' => $request->input('source'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'title' => $data['title'],
            'source' => $data['source'],
        ];
    
    
        $existingRecord = Video::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            Video::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            Video::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Video Added successfully',
        ]);
    }

    public static function edit($id) {
        $video =  Video::find($id);
        if($video) {
            return response()->json([
                'status' => 200,
                'video' => $video,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Video Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'source' => 'required|string|max:500',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $video = Video::find($id);
        if($video) {
            $video->title = $request->input('title');
            $video->source = $request->input('source');
            $video->status = $request->input('status');  
            $video->updated_at = now();          
            $video->update();
            return response()->json([
                'status'=>200,
                'message'=>'Video Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Video Id Found',
            ]); 
        }
    }
}
