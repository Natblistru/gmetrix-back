<?php

namespace App\Http\Controllers;

use App\Models\TestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class TestItemController extends Controller
{
    // public static function index() {
    //     $testItem =  TestItem::all();
    //     return response()->json([
    //         'status' => 200,
    //         'testItem' => $testItem,
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
        $filterProgram = $request->query('filterProgram');
        $filterChapter = $request->query('filterChapter');
        $filterTeacher = $request->query('filterTeacher');
    
        $allowedColumns = ['id', 'task', 'name', 'type', 'topic_name','status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'TI',
            'task' => 'TI',            
            'type' => 'TI',
            'name' => 'TC',
            'topic_name' => 'VTT',
            'status' => 'TI',
        ];
    
        $sqlTemplate = "
        SELECT
            TI.id,
            TI.task,
            TI.type,
            TC.name,
            VTT.topic_name,
            TT.teacher_id AS teacher_id,
            TT.id as topic_id,
            TH.chapter_id, 
            LP.id AS program_id,
            TI.status
        FROM 
            test_items TI  
            INNER JOIN test_comlexities TC ON TI.test_complexity_id = TC.id
            INNER JOIN teacher_topics TT ON TI.teacher_topic_id = TT.id
            INNER JOIN topics ON TT.topic_id = topics.id    
            INNER JOIN theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
            INNER JOIN themes TH ON TLP.theme_id = TH.id
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
            'testItem' => $rawResults,
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
        return TestItem::find($id); 
    }

    
    public static function allTestItems() {
        $testItems =  TestItem::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'testItems' => $testItems,
        ]);
    }

    public function findTestItemByTask($task) {
        $testItem = TestItem::where('task', $task)->first();

        if ($testItem) {
            return response()->json(['testItem' => $testItem], 200);
        } else {
            return response()->json(['message' => 'TestItem nu a fost găsit'], 404);
        }
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:10000',
            'content' => 'required|string|max:10000',
            'image_path' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'type' => 'required|in:quiz,check,snap,words,dnd,dnd_chrono,dnd_chrono_double,dnd_group',
            'test_complexity_id' => 'required|exists:test_comlexities,id',
            'teacher_topic_id' => 'required|exists:teacher_topics,id',
            'language' => 'required|in:en,ro',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'task' => $request->input('task'),
            'image_path' => $request->input('image_path'),
            'type' => $request->input('type'),
            'test_complexity_id' => $request->input('test_complexity_id'),
            'teacher_topic_id' => $request->input('teacher_topic_id'),
            'status' => $request->input('status'),
        ];

        $contentKey = $request->input('language') == 'en' ? 'en' : 'ro';
        $contentValue = $request->input('content');
   
        $combinatieColoane = [
            'task' => $data['task'],
            'type' => $data['type'],
            'teacher_topic_id' => $data['teacher_topic_id'],         
        ];
    
        $existingRecord = TestItem::where($combinatieColoane)->first();

        if ($existingRecord) {
            $existingContent = json_decode($existingRecord->content, true);
            
            $existingContent[$contentKey] = $contentValue;
            
            // Actualizăm coloana content cu noul conținut
            $existingRecord->content = json_encode($existingContent);
            
            // Actualizăm data ultimei modificări
            $existingRecord->updated_at = now();
        
            // Manipularea imaginilor
            if($request->hasFile('image')) {
                $path = $existingRecord ->image_path;
                if(File::exists($path)) {
                      File::delete($path);
                }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/testItem/', $filename);
                $existingRecord->image_path = 'uploads/testItem/' .$filename;
            }
        
            // Salvăm înregistrarea actualizată
            $existingRecord->save();
            $updatedTestItem = TestItem::where($combinatieColoane)->first();
            return response()->json([
                'status' => 201,
                'message' => 'Test Item Updated successfully',
                'testItem' => $updatedTestItem,
            ]);
        
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            if($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/testItem/', $filename);
                $data['image_path'] = 'uploads/testItem/' .$filename;
            }

            $data['content'] = json_encode([$contentKey => $contentValue]);
    
            $newTestItem = TestItem::create($data);
            return response()->json([
                'status'=>201,
                'message'=>'Test Item Added successfully',
                'testItem' => $newTestItem,
            ]);
        }
 

    }

    public static function edit($id) {
        $testItem = TestItem::with('teacher_topic')->find($id);
       
        if ($testItem) {
            return response()->json([
                'status' => 200,
                'testItem' => $testItem,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Test Item Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:1000',
            'type' => 'required|in:quiz,check,snap,words,dnd,dnd_chrono,dnd_chrono_double,dnd_group',
            'test_complexity_id' => 'required|exists:test_comlexities,id',
            'teacher_topic_id' => 'required|exists:teacher_topics,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $testItem = TestItem::find($id);
        if($testItem) {
            $testItem->task = $request->input('task');
            $testItem->type = $request->input('type');
            $testItem->test_complexity_id = $request->input('test_complexity_id');
            $testItem->teacher_topic_id = $request->input('teacher_topic_id');                     
            $testItem->status = $request->input('status'); 
            $testItem->updated_at = now();             
            $testItem->update();
            return response()->json([
                'status'=>200,
                'message'=>'Test Item Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Test Item Subject Id Found',
            ]); 
        }
    }

}
