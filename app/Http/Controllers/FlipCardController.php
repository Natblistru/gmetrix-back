<?php

namespace App\Http\Controllers;

use App\Models\FlipCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FlipCardController extends Controller
{
    // public static function index() {
    //     $flipCard =  FlipCard::all();
    //     return response()->json([
    //         'status' => 200,
    //         'flipCard' => $flipCard,
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
    
        $allowedColumns = ['id', 'task', 'topic_name', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'FC',
            'task' => 'FC',
            'topic_name' => 'VTT',
            'status' => 'FC',
        ];
    
        $sqlTemplate = "
        SELECT
            FC.id,
            FC.task,
            VTT.topic_name,
            TT.teacher_id AS teacher_id,
            TT.id AS topic_id,
            TH.chapter_id,  
            TLP.theme_id AS theme_id,
            LP.id AS program_id,
            FC.status
        FROM 
            flip_cards FC 
            INNER JOIN teacher_topics TT ON FC.teacher_topic_id = TT.id
            INNER JOIN topics ON TT.topic_id = topics.id    
            INNER JOIN theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
            INNER JOIN learning_programs LP ON TLP.learning_program_id = LP.id
            INNER JOIN themes TH ON TLP.theme_id = TH.id
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

        if ($filterTeacher) {
            $sqlWithSortingAndSearch .= " AND TT.teacher_id = $filterTeacher";
        }

        if ($filterChapter) {
            $sqlWithSortingAndSearch .= " AND TH.chapter_id = $filterChapter";
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
            'flipCard' => $rawResults,
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
        return FlipCard::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:500',
            'order_number' => 'required|integer|min:1',
            'answer' => 'required|string|max:5000',
            'teacher_topic_id' => 'required|exists:teacher_topics,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'task' => $request->input('task'),
            'order_number' => $request->input('order_number'), 
            'answer' => $request->input('answer'),
            'teacher_topic_id' => $request->input('teacher_topic_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'teacher_topic_id' => $data['teacher_topic_id'], 
            'task' => $data['task'],       
        ];
    
        $existingRecord = FlipCard::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
 
            FlipCard::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            FlipCard::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Flip Card Added successfully',
        ]);
    }

    public static function edit($id) {
        $flipCard = FlipCard::with('teacher_topic')->find($id);
       
        if ($flipCard) {
            return response()->json([
                'status' => 200,
                'flipCard' => $flipCard,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Answer Source Id Found',
            ]);
        }
    }

    public static function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:500',
            'answer' => 'required|string|max:5000',
            'teacher_topic_id' => 'required|exists:teacher_topics,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $flipCard = FlipCard::find($id);
    
        if ($flipCard) {
            $flipCard->task = $request->input('task');
            $flipCard->answer = $request->input('answer');
            $flipCard->teacher_topic_id = $request->input('teacher_topic_id');
            $flipCard->status = $request->input('status');
    
            $flipCard->updated_at = now();
            $flipCard->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Flip Card Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Answer Id Found',
            ]);
        }
    }

}
