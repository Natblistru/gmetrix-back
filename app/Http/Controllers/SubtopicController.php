<?php

namespace App\Http\Controllers;

use App\Models\Subtopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SubtopicController extends Controller
{
    public static function index() {
        $subtopics =  Subtopic::all();
        return response()->json([
            'status' => 200,
            'subtopics' => $subtopics,
        ]);
    }

    public static function index_teacher(Request $request) {
        $search = $request->query('search');
        $sortColumn = $request->query('sortColumn');
        $sortOrder = $request->query('sortOrder');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $filterTopic = $request->query('filterTopic');
        $filterTheme = $request->query('filterTheme');
        $filterProgram = $request->query('filterProgram');
        $paramTeacher = $request->query('paramTeacher');
    
        $allowedColumns = ['id', 'name',  'topic_name','audio_path','status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'S',
            'name' => 'S',
            'topic_name' => 'VTT',
            'audio_path' => 'S',
            'status' => 'S',
        ];
    
        $sqlTemplate = "
        SELECT
            S.id,
            S.audio_path,
            S.name,
            VTT.topic_name,
            TT.teacher_id AS teacher_id,
            TT.id AS topic_id,
            TLP.theme_id AS theme_id,
            LP.id AS program_id,
            S.status
        FROM 
            subtopics S 
            INNER JOIN teacher_topics TT ON S.teacher_topic_id = TT.id
            INNER JOIN topics ON TT.topic_id = topics.id    
            INNER JOIN theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
            INNER JOIN learning_programs LP ON TLP.learning_program_id = LP.id
            INNER JOIN (
                SELECT 
                    TT.id AS topic_id,
                    TT.name AS topic_name
                FROM teacher_topics TT
            ) AS VTT ON VTT.topic_id = TT.id
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

        if ($paramTeacher) {
            $sqlWithSortingAndSearch .= " AND TT.teacher_id = $paramTeacher";
        }
     
        if ($searchConditions) {
            $sqlWithSortingAndSearch .= " AND $searchConditions";
        }

        if ($filterTopic) {
            $sqlWithSortingAndSearch .= " AND TT.id = $filterTopic";
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
            'subtopics' => $rawResults,
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
        return Subtopic::find($id); 
    }

    public function findSubtopicByName($name) {
        $subtopic = Subtopic::where('name', $name)->first();

        if ($subtopic) {
            return response()->json(['subtopic' => $subtopic], 200);
        } else {
            return response()->json(['message' => 'Subtopicul nu a fost găsit'], 404);
        }
    }

    public static function allSubtopics() {
        $subtopics =  Subtopic::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'subtopics' => $subtopics,
        ]);
    }

    public static function store(Request $request) {
        //     \Log::info($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'teacher_topic_id' => 'required|integer|exists:teacher_topics,id',
            'audio' => 'nullable|mimes:mp3,wav|max:1024000',
            'audio_path' => 'nullable|string|max:1000',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'name' => $request->input('name'),
            'teacher_topic_id' => $request->input('teacher_topic_id'),
            'audio_path' => $request->input('audio_path'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'name' => $data['name'],
            'teacher_topic_id' => $data['teacher_topic_id'],
        ];
    
        $existingRecord = Subtopic::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();

            if($request->hasFile('audio')) {
                $path = $existingRecord ->audio_path;
                if(File::exists($path)) {
                      File::delete($path);
                }
                $file = $request->file('audio');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/audioSubtopic/', $filename);
                $data['audio_path'] = 'uploads/audioSubtopic/' .$filename;
            }
    
            Subtopic::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            if($request->hasFile('audio')) {
                $file = $request->file('audio');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/audioSubtopic/', $filename);
                $data['audio_path'] = 'uploads/audioSubtopic/' .$filename;
            }
    
            Subtopic::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Subtopic Added successfully',
        ]);
    }

    public static function edit($id) {
        $subtopics =  Subtopic::find($id);
        if($subtopics) {
            return response()->json([
                'status' => 200,
                'subtopics' => $subtopics,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Subtopic Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'teacher_topic_id' => 'required|integer|exists:teacher_topics,id',
            'audio' => 'nullable|mimes:mp3,wav|max:10240',
            'audio_path' => 'required|string|max:1000',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $subtopic = Subtopic::find($id);
        if($subtopic) {
            $subtopic->name = $request->input('name');
            $subtopic->teacher_topic_id = $request->input('teacher_topic_id');
            // $subtopic->audio_path = $request->input('audio_path');           
            $subtopic->status = $request->input('status'); 
            $subtopic->updated_at = now();  
            
            if ($request->hasFile('audio')) {
                $path = $subtopic->audio_path;
    
                if (File::exists($path)) {
                    File::delete($path);
                }
    
                $file = $request->file('audio');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/audioSubtopic/', $filename);
                $subtopic->audio_path = 'uploads/audioSubtopic/' . $filename;
            }
            
            $subtopic->update();
            return response()->json([
                'status'=>200,
                'message'=>'Subtopic Updated successfully',
            ]); 
        }
        else
        {


            return response()->json([
                'status'=>404,
                'message'=>'No Subtopic Id Found',
            ]); 
        }
    }

}
