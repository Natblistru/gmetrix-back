<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubtopicImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SubtopicImageController extends Controller
{
    // public static function index() {
    //     $subtopicImage =  SubtopicImage::all();
    //     return response()->json([
    //         'status' => 200,
    //         'subtopicImage' => $subtopicImage,
    //     ]);
    // }

    public static function index(Request $request) {

        $search = $request->query('search');
        $sortColumn = $request->query('sortColumn');
        $sortOrder = $request->query('sortOrder');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $filterTopic = $request->query('filterTopic');
        $filterTheme = $request->query('filterTheme');
        $filterChapter = $request->query('filterChapter');
        $filterProgram = $request->query('filterProgram');
        $filterTeacher = $request->query('filterTeacher');
    
        $allowedColumns = ['id', 'path', 'name',  'topic_name', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'SI',
            'path' => 'SI',
            'name' => 'S',
            'topic_name' => 'VTT',
            'status' => 'SI',
        ];
    
        $sqlTemplate = "
        SELECT
            SI.id,
            SI.path,
            S.name,
            VTT.topic_name,
            VTT.teacher_id,
            VTT.teacher_topic_id,
            TH.chapter_id,  
            TLP.theme_id AS theme_id,
            LP.id AS program_id,
            SI.status
        FROM
            subtopic_images SI
            INNER JOIN subtopics S ON SI.subtopic_id = S.id
            INNER JOIN (
                SELECT 
                    TT.id,
                    TT.id AS teacher_topic_id,
                    TT.topic_id,
                    TT.name AS topic_name,
                    TT.teacher_id
                FROM teacher_topics TT
            ) AS VTT ON VTT.id = S.teacher_topic_id
            INNER JOIN topics ON VTT.topic_id = topics.id    
            INNER JOIN theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
            INNER JOIN learning_programs LP ON TLP.learning_program_id = LP.id
            INNER JOIN themes TH ON TLP.theme_id = TH.id
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

        if ($filterTeacher) {
            $sqlWithSortingAndSearch .= " AND VTT.teacher_id = $filterTeacher";
        }

        if ($filterChapter) {
            $sqlWithSortingAndSearch .= " AND TH.chapter_id = $filterChapter";
        }
     
        if ($searchConditions) {
            $sqlWithSortingAndSearch .= " AND $searchConditions";
        }

        if ($filterTopic) {
            $sqlWithSortingAndSearch .= " AND VTT.teacher_topic_id = $filterTopic";
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
            'subtopicImage' => $rawResults,
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
        return SubtopicImage::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'path' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'subtopic_id' => 'required|exists:subtopics,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'path' => $request->input('path'),
            'subtopic_id' => $request->input('subtopic_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'subtopic_id' => $data['subtopic_id'], 
            'path' => $data['path'],       
        ];
    
        $existingRecord = SubtopicImage::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();

            if($request->hasFile('image')) {
                $path = $existingRecord ->path;
                if(File::exists($path)) {
                      File::delete($path);
                }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/imageSubtopic/', $filename);
                $data['path'] = 'uploads/imageSubtopic/' .$filename;
            }
  
            SubtopicImage::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            if($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/imageSubtopic/', $filename);
                $data['path'] = 'uploads/imageSubtopic/' .$filename;
            }
    
            SubtopicImage::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Item Added successfully',
        ]);
    }

    public static function edit($id) {
        $subtopicImage = SubtopicImage::with('subtopic')->find($id);
       
        if ($subtopicImage) {
            return response()->json([
                'status' => 200,
                'subtopicImage' => $subtopicImage,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Subtopic Image Id Found',
            ]);
        }
    }

    public static function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'path' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'subtopic_id' => 'required|exists:subtopics,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $subtopicImage = SubtopicImage::find($id);
    
        if ($subtopicImage) {
            $subtopicImage->subtopic_id = $request->input('subtopic_id');
            $subtopicImage->status = $request->input('status');
    
            if ($request->hasFile('image')) {
                $path = $subtopicImage->path;
                    if (File::exists($path)) {
                    File::delete($path);
                }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/imageSubtopic/', $filename);
                $subtopicImage->path = 'uploads/imageSubtopic/' . $filename;
            }
    
            $subtopicImage->updated_at = now();
            $subtopicImage->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Subtopic Image Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Subtopic Image Id Found',
            ]);
        }
    }


}
