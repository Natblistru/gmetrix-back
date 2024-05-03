<?php

namespace App\Http\Controllers;

use App\Models\TeacherTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TeacherTopicController extends Controller
{
    // public static function index() {
    //     $teacherTopics =  TeacherTopic::all();
    //     return response()->json([
    //         'status' => 200,
    //         'teacherTopics' => $teacherTopics,
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
        $filterTeacher = $request->query('filterTeacher');
        
    
        $allowedColumns = ['id', 'order_number', 'name','theme_name', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'TT',
            'order_number' => 'TT',
            'name' => 'TT',
            'theme_name' => 'VT',
            'status' => 'TT',
        ];
    
        $sqlTemplate = "
            SELECT
            TT.id,
            TT.order_number,
            TT.name,
            VT.theme_name, 
            TH.chapter_id,          
            TT.teacher_id teacher_id,
            TLP.theme_id theme_id,
            LP.id program_id,
            TT.status
        FROM 
            teacher_topics TT
            INNER JOIN topics ON TT.topic_id = topics.id    
            INNER JOIN theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
            INNER JOIN learning_programs LP ON TLP.learning_program_id = LP.id
            INNER JOIN themes TH ON TLP.theme_id = TH.id
            INNER JOIN (
                SELECT 
                    topics.id AS topic_id,
                    TLP.name AS theme_name
                FROM theme_learning_programs TLP
                INNER JOIN topics ON TLP.id = topics.theme_learning_program_id
            ) AS VT ON VT.topic_id = TT.topic_id
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
    
        if ($searchConditions) {
            $sqlWithSortingAndSearch .= " AND $searchConditions";
        }

        if ($filterChapter) {
            $sqlWithSortingAndSearch .= " AND TH.chapter_id = $filterChapter";
        }

        if ($filterTheme) {
            $sqlWithSortingAndSearch .= " AND TLP.theme_id = $filterTheme";
        }
    
        if ($filterProgram) {
            $sqlWithSortingAndSearch .= " AND LP.id = $filterProgram";
        }

        $sqlWithSortingAndSearch .= " ORDER BY $sortColumn $sortOrder";

        //  Log::info('select', [$sqlWithSortingAndSearch]);
        
        $totalResults = DB::select("SELECT COUNT(*) as total FROM ($sqlWithSortingAndSearch) as countTable")[0]->total;
    
        $lastPage = ceil($totalResults / $perPage);
    
        $offset = ($page - 1) * $perPage;
    
        $rawResults = DB::select("$sqlWithSortingAndSearch LIMIT $perPage OFFSET $offset");
    
        return response()->json([
            'status' => 200,
            'teacherTopics' => $rawResults,
            'pagination' => [
                'last_page' => $lastPage,
                'current_page' => $page,
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $totalResults),
                'total' => $totalResults,
            ],
        ]);
    }



    public static function index_teacher(Request $request) {
        $search = $request->query('search');
        $sortColumn = $request->query('sortColumn');
        $sortOrder = $request->query('sortOrder');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $filterChapter = $request->query('filterChapter');
        $filterTheme = $request->query('filterTheme');
        $filterProgram = $request->query('filterProgram');
        $paramTeacher = $request->query('paramTeacher');
        
    
        $allowedColumns = ['id', 'order_number', 'name','theme_name', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'TT',
            'order_number' => 'TT',
            'name' => 'TT',
            'theme_name' => 'VT',
            'status' => 'TT',
        ];
    
        $sqlTemplate = "
            SELECT
            TT.id,
            TT.order_number,
            TT.name,
            VT.theme_name, 
            TH.chapter_id,          
            TT.teacher_id teacher_id,
            TLP.theme_id theme_id,
            LP.id program_id,
            TT.status
        FROM 
            teacher_topics TT
            INNER JOIN topics ON TT.topic_id = topics.id    
            INNER JOIN theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
            INNER JOIN learning_programs LP ON TLP.learning_program_id = LP.id
            INNER JOIN themes TH ON TLP.theme_id = TH.id
            INNER JOIN (
                SELECT 
                    topics.id AS topic_id,
                    TLP.name AS theme_name
                FROM theme_learning_programs TLP
                INNER JOIN topics ON TLP.id = topics.theme_learning_program_id
            ) AS VT ON VT.topic_id = TT.topic_id
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

        if ($filterChapter) {
            $sqlWithSortingAndSearch .= " AND TH.chapter_id = $filterChapter";
        }

        if ($filterTheme) {
            $sqlWithSortingAndSearch .= " AND TLP.theme_id = $filterTheme";
        }
    
        if ($filterProgram) {
            $sqlWithSortingAndSearch .= " AND LP.id = $filterProgram";
        }

        $sqlWithSortingAndSearch .= " ORDER BY $sortColumn $sortOrder";

        //  Log::info('select', [$sqlWithSortingAndSearch]);
        
        $totalResults = DB::select("SELECT COUNT(*) as total FROM ($sqlWithSortingAndSearch) as countTable")[0]->total;
    
        $lastPage = ceil($totalResults / $perPage);
    
        $offset = ($page - 1) * $perPage;
    
        $rawResults = DB::select("$sqlWithSortingAndSearch LIMIT $perPage OFFSET $offset");
    
        return response()->json([
            'status' => 200,
            'teacherTopics' => $rawResults,
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
        return TeacherTopic::find($id); 
    }

    public static function allTeacherTopics() {
        $teacherTopics =  TeacherTopic::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'teacherTopics' => $teacherTopics,
        ]);
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'order_number' => 'required|integer|min:1',
            'teacher_id' => 'required|integer|min:0|exists:teachers,id',
            'topic_id' => 'required|integer|min:0|exists:topics,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'name' => $request->input('name'),
            'order_number' => $request->input('order_number'),
            'teacher_id' => $request->input('teacher_id'),
            'topic_id' => $request->input('topic_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'teacher_id' => $data['teacher_id'],
            'topic_id' => $data['topic_id'],
        ];
    
        $existingRecord = TeacherTopic::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            TeacherTopic::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            TeacherTopic::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Teacher Topic Added successfully',
        ]);
    }

    public static function edit($id) {
        $teacherTopics =  TeacherTopic::find($id);
        if($teacherTopics) {
            return response()->json([
                'status' => 200,
                'teacherTopics' => $teacherTopics,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Teacher Topic Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'order_number' => 'required|integer|min:1',
            'teacher_id' => 'required|integer|min:0|exists:teachers,id',
            'topic_id' => 'required|integer|min:0|exists:topics,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $teacherTopic = TeacherTopic::find($id);
        if($teacherTopic) {
            $teacherTopic->name = $request->input('name');
            $teacherTopic->order_number = $request->input('order_number');            
            $teacherTopic->teacher_id = $request->input('teacher_id');
            $teacherTopic->topic_id = $request->input('topic_id');           
            $teacherTopic->status = $request->input('status'); 
            $teacherTopic->updated_at = now();             
            $teacherTopic->update();
            return response()->json([
                'status'=>200,
                'message'=>'Teacher Topic Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Teacher Topic Id Found',
            ]); 
        }
    }

    public function getAllTeachersWithThemes()
    {
        $teacherThemes = TeacherTopic::select(
            'teachers.id as teacher_id',
            'teachers.name as teacher_name',
            'theme_learning_programs.id as theme_learning_programs_id',
        )
            ->join('topics', 'teacher_topics.topic_id', '=', 'topics.id')
            ->join('theme_learning_programs', 'topics.theme_learning_program_id', '=', 'theme_learning_programs.id')
            ->join('teachers', function ($join) {
                $join->on('teacher_topics.teacher_id', '=', 'teachers.id')
                     ->where('teacher_topics.status', '=', 0);
            })            ->distinct()
            ->get();
    
        // Grupare după teme
        $groupedTeacherThemes = $teacherThemes->groupBy('theme_learning_programs_id');
    
        return response()->json(['groupedTeacherThemes' => $groupedTeacherThemes]);
    }
    

    public static function teacherTheme(Request $request)  {

        $level = $request->query('level');
        $subjectId = $request->query('disciplina');
        $student = $request->query('student');
        $teacher = $request->query('teacher');
        $year = $request->query('year');
        $theme = $request->query('theme');

        if ($year) {
            $yearCondition = " LP.year = ? ";
            $params = [$year, $subjectId, $level, $teacher, $theme];
            $paramsGeneral = [$year, $subjectId, $level, $theme];
            $paramsStudent = [$year, $student, $subjectId, $level, $teacher, $theme];
            $paramsStudentTest = [$student, $year, $subjectId, $level, $teacher, $theme];
        } else {
            $yearCondition = " LP.year = (SELECT MAX(LP2.year) 
                            FROM learning_programs LP2
                            JOIN subject_study_levels SSLev2 ON LP2.subject_study_level_id = SSLev2.id
                            WHERE SSLev2.subject_id = ? AND SSLev2.study_level_id = ?) ";
            $params = [$subjectId, $level, $subjectId, $level, $teacher, $theme];
            $paramsGeneral = [$subjectId, $level, $subjectId, $level, $theme];
            $paramsStudent = [$subjectId, $level, $student, $subjectId, $level, $teacher, $theme];
            $paramsStudentTest = [$student, $subjectId, $level, $subjectId, $level, $teacher, $theme];
        }

        DB::statement("
        CREATE TEMPORARY TABLE temp_teacher_subtopics AS
        SELECT
            TT.teacher_id AS teacher_id,
            TLP.theme_id,
            topics.id AS topic_id,
            topics.name AS topic_name,
            topics.name_ro AS topic_name_ro,
            topics.path AS topic_path,
            topics.order_number AS topic_order_number,
            TT.id as teacher_topic_id,
            subtopics.id as subtopic_id,
            subtopics.name as subtopic_name,
            subtopics.order_number as subtopic_order,
            subtopics.audio_path
        FROM
            teacher_topics TT
        JOIN
            topics ON TT.topic_id = topics.id    
        JOIN
            theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
        JOIN
            learning_programs LP ON TLP.learning_program_id = LP.id
        JOIN
            subject_study_levels SSLev ON LP.subject_study_level_id = SSLev.id
        LEFT JOIN
            subtopics ON subtopics.teacher_topic_id = TT.id
        WHERE
            {$yearCondition}
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ? AND TT.teacher_id = ? AND TLP.theme_id = ?; 
        ", $params);


        DB::statement("
        CREATE TEMPORARY TABLE temp_subtopics_progress AS
        SELECT 
            TT.teacher_id AS teacher_id,
            TLP.theme_id,
            topics.id AS topic_id,
            topics.name AS topic_name,
            subtopics.teacher_topic_id,
            SST.subtopic_id,
            SST.progress_percentage
        FROM 
            student_subopic_progress SST
        JOIN
            subtopics ON SST.subtopic_id = subtopics.id
        JOIN
            teacher_topics TT ON subtopics.teacher_topic_id = TT.id
        JOIN
            topics ON TT.topic_id = topics.id
        JOIN
            theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
        JOIN
            learning_programs LP ON TLP.learning_program_id = LP.id
        JOIN
            subject_study_levels SSLev ON LP.subject_study_level_id = SSLev.id    
        WHERE
            {$yearCondition}
            AND SST.student_id = ? AND SSLev.subject_id = ? AND SSLev.study_level_id = ? AND TT.teacher_id = ? AND TLP.theme_id = ?; 
        ", $paramsStudent);

        // Progresele la toate subtopucurile
        DB::statement("
        CREATE TEMPORARY TABLE temp_progress_all_subtopic AS
        SELECT
            TS.teacher_id,
            TS.theme_id,
            TS.topic_id,
            TS.topic_name,
            TS.topic_name_ro,
            TS.topic_path,
            TS.topic_order_number,
            TS.teacher_topic_id,
            TS.subtopic_id,
            TS.subtopic_name,
            TS.subtopic_order,
            TS.audio_path,
            COALESCE(SP.progress_percentage, 0) AS progress_percentage
        FROM
            temp_teacher_subtopics TS
        LEFT JOIN
            temp_subtopics_progress SP ON 
                TS.teacher_id = SP.teacher_id AND 
                TS.topic_id = SP.topic_id AND
                TS.teacher_topic_id = SP.teacher_topic_id AND
                TS.theme_id = SP.theme_id AND
                TS.subtopic_id = SP.subtopic_id;
        ");

        // Calcularea mediei progresului pe topucuri
        DB::statement("
        CREATE TEMPORARY TABLE temp_progress_topics AS
        SELECT 
            T.theme_id,
            T.topic_id,
            T.teacher_topic_id,
            T.topic_name,
            SUM(T.progress_percentage) / COUNT(T.progress_percentage) AS procentTopic
        FROM temp_progress_all_subtopic T
                    GROUP BY
                        T.theme_id,
                        T.topic_name,
                        T.topic_id,
                        T.teacher_topic_id;
        ");

        // Calcularea mediei progresului pe tema
        DB::statement("
        CREATE TEMPORARY TABLE temp_progress_theme AS 
        SELECT 
            PT.theme_id,
            AVG(PT.procentTopic) AS procentTema
        FROM
            temp_progress_topics PT
        GROUP BY
            PT.theme_id;
        ");

        // Lista tuturor topicurilor temei
        DB::statement("
        CREATE TEMPORARY TABLE temp_all_topics AS 
        SELECT 
            TLP.theme_id AS theme_id,
            topics.id AS topic_id,
            topics.name AS topic_name,
            topics.name_ro AS topic_name_ro,
            topics.path AS topic_path,
            topics.order_number AS topic_order_number
        FROM
            topics     
        JOIN
            theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
        JOIN
            learning_programs LP ON TLP.learning_program_id = LP.id
        JOIN
            subject_study_levels SSLev ON LP.subject_study_level_id = SSLev.id
        WHERE
            {$yearCondition}
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ? AND TLP.theme_id = ? AND topics.status = 0
        ORDER BY topics.order_number;     
        ", $paramsGeneral);


        // Lista tuturor testelor formative
        DB::statement("
        CREATE TEMPORARY TABLE temp_all_tests AS 
        SELECT
            TT.id,
            TT.topic_id,
            FT.title,
            FT.path,
            FT.type AS testType,
            FT.order_number,
            TC.name,
            TC.level,
            COUNT(COALESCE(SFTR.score, 0)) AS totalTests,
            AVG(COALESCE(SFTR.score/TC.level, 0)) AS testResult
        FROM
            teacher_topics TT
        LEFT JOIN
            formative_tests FT ON FT.teacher_topic_id = TT.id
        INNER JOIN 
            formative_test_items FTI ON FTI.formative_test_id = FT.id AND FTI.status = 0
        LEFT JOIN
            student_formative_test_results SFTR ON SFTR.formative_test_item_id = FTI.id AND SFTR.student_id = ?
        INNER JOIN
            test_comlexities TC ON FT.test_complexity_id = TC.id
        JOIN
            topics ON TT.topic_id = topics.id    
        JOIN
            theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
        JOIN
            learning_programs LP ON TLP.learning_program_id = LP.id
        JOIN
            subject_study_levels SSLev ON LP.subject_study_level_id = SSLev.id
        WHERE
            {$yearCondition} 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ? AND TT.teacher_id = ? AND TLP.theme_id = ?
        GROUP BY 
            TT.id,
            TT.topic_id,
            FT.order_number,
            FT.title,
            FT.path,
            FT.type,
            TC.name,
            TC.level
        ORDER BY
            FT.order_number;
        ", $paramsStudentTest);

        DB::insert("
		INSERT INTO temp_all_tests
        SELECT
            TT.id,
            TT.topic_id,
            ST.title,
            ST.path,
            null,
            11 AS order_number,
            TC.name,
            TC.level,
            COUNT(COALESCE(SSTR.score, 0)) AS totalTests,
            AVG(COALESCE(SSTR.score, 0)) AS testResult
        FROM
            teacher_topics TT
        LEFT JOIN
            summative_tests ST ON ST.teacher_topic_id = TT.id
         INNER JOIN 
            summative_test_items STI ON STI.summative_test_id = ST.id
        LEFT JOIN
            student_summative_test_results SSTR ON SSTR.summative_test_item_id = STI.id AND SSTR.student_id = ? 
        INNER JOIN
            test_comlexities TC ON ST.test_complexity_id = TC.id
        JOIN
            topics ON TT.topic_id = topics.id    
        JOIN
            theme_learning_programs TLP ON TLP.id = topics.theme_learning_program_id
        JOIN
            learning_programs LP ON TLP.learning_program_id = LP.id
        JOIN
            subject_study_levels SSLev ON LP.subject_study_level_id = SSLev.id
        WHERE
            {$yearCondition} 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ? AND TT.teacher_id = ? AND TLP.theme_id = ?
        GROUP BY 
            TT.id,
            TT.topic_id,
            ST.title,
            ST.path,
            TC.name,
            TC.level;
        ", $paramsStudentTest);

        $result = DB::select("
        SELECT 
            COALESCE(PS.theme_id, TAT.theme_id) AS theme_id,
            COALESCE(PS.topic_id, TAT.topic_id) AS topic_id,
            COALESCE(PS.topic_order_number, TAT.topic_order_number) AS id,
            COALESCE(PS.topic_name, TAT.topic_name) AS name,
            COALESCE(PS.topic_name_ro, TAT.topic_name_ro) AS name_ro,
            COALESCE(PS.topic_path, TAT.topic_path) AS path,
            PS.subtopic_id,
            PS.teacher_topic_id,
            PS.teacher_id,
            PS.subtopic_name,
            PS.subtopic_order,
            COALESCE(SI.path, '') AS subtopic_images,
            PSS.audio_path,
            FC.id AS flip_id,
            FC.task AS flip_task,
            FC.order_number AS flip_order,
            FC.answer AS flip_answer,
            TATest.title AS test_title,
            TATest.path AS test_path,
            TATest.testType,
            TATest.name AS complexity,
            TATest.level AS complexity_level,
            TATest.totalTests AS total_test_items,
            TATest.testResult AS testResult,
            COALESCE(PSS.progress_percentage, 0) AS procentSubtopic,
            COALESCE(PT.procentTopic, 0) AS procentTopic,
            COALESCE(PTh.procentTema, 0) AS procentTema    
        FROM temp_all_topics TAT
        LEFT JOIN
            temp_progress_all_subtopic PS ON TAT.theme_id = PS.theme_id AND 
                                             TAT.topic_id = PS.topic_id
        LEFT JOIN 
            temp_all_tests TATest ON TATest.topic_id = PS.topic_id
        LEFT JOIN
            temp_progress_topics PT ON PT.topic_id = PS.topic_id
        LEFT JOIN
            flip_cards FC ON FC.teacher_topic_id = PS.teacher_topic_id
        LEFT JOIN
            temp_progress_theme PTh ON PTh.theme_id = PS.theme_id
        LEFT JOIN
            temp_progress_all_subtopic PSS ON PS.subtopic_id = PSS.subtopic_id
        LEFT JOIN
        	subtopic_images SI ON SI.subtopic_id = PSS.subtopic_id;
        ");

        // Convertim rezultatele într-o colecție Laravel
        $collection = collect($result);

        // Inițializăm un array pentru rezultatul final
        $finalResult = [];

        // Grupăm pe topic_id
        $groupedByTopic = $collection->groupBy('topic_id');

        // Iterăm prin fiecare grup de topic_id
        foreach ($groupedByTopic as $topicGroup) {
            // Inițializăm array-urile pentru subgrupurile de date
            $subtitles = [];
            $flip_cards = [];
            $tests = [];
            $num_ord = 1;
            $num_ord_test = 1;

            // Grupăm acum pe subtopic_id în cadrul fiecărui topic_id
            $groupedBySubtopic = $topicGroup->groupBy('subtopic_id');

            // Iterăm prin fiecare grup de subtopic_id
            foreach ($groupedBySubtopic as $subtopicGroup) {
                // Inițializăm array-urile pentru subgrupurile de imagini
                $subtopicImages = [];

                // Grupăm acum pe subtopic_images în cadrul fiecărui subtopic_id
                $groupedByImages = $subtopicGroup->groupBy('subtopic_images');

                // Iterăm prin fiecare grup de subtopic_images
                foreach ($groupedByImages as $imagesGroup) {
                    // Extragem prima intrare pentru a obține date comune
                    $firstImage = $imagesGroup->first();

                    // Adăugăm array-ul pentru imagini în array-ul intermediar
                    $subtopicImages[] = [
                        'path' => $firstImage->subtopic_images,
                    ];
                }

                // Extragem prima intrare pentru a obține date comune pentru subtopic
                $firstSubtopic = $subtopicGroup->first();

                // Adăugăm array-ul pentru subtopic în array-ul intermediar
                $subtitles[] = [
                    'subtopic_id' => $firstSubtopic->subtopic_id,
                    'subtopic_name' => $firstSubtopic->subtopic_name,
                    'audio_path' => $firstSubtopic->audio_path,
                    'id' => $num_ord,
                    'subtopic_order' => $firstSubtopic->subtopic_order,
                    'procentSubtopic' => $firstSubtopic->procentSubtopic,
                    'images' => $subtopicImages,
                ];
                $num_ord = $num_ord +1;
            }

            usort($subtitles, function ($a, $b) {
                return $a['subtopic_order'] - $b['subtopic_order'];
            });

            $groupedByFlipCards = $topicGroup->groupBy('flip_id');

            // Iterăm prin fiecare grup de subtopic_id
            foreach ($groupedByFlipCards as $flipGroup) {
                // Inițializăm array-urile pentru subgrupurile de imagini

                // Extragem prima intrare pentru a obține date comune pentru subtopic
                $firstFlip = $flipGroup->first();

                // Adăugăm array-ul pentru subtopic în array-ul intermediar
                $flip_cards[] = [
                    'card_id' => $firstFlip->flip_id,                    
                    'sarcina' => $firstFlip->flip_task,
                    'order' => $firstFlip->flip_order,
                    'rezolvare' => $firstFlip->flip_answer,
                ];
            }

            usort($flip_cards, function ($a, $b) {
                return $a['order'] - $b['order'];
            });

            $groupedByTests = $topicGroup->groupBy('test_title');

            // Iterăm prin fiecare grup de subtopic_id
            foreach ($groupedByTests as $testGroup) {
                // Inițializăm array-urile pentru subgrupurile de imagini

                // Extragem prima intrare pentru a obține date comune pentru subtopic
                $firstTest = $testGroup->first();

                // Adăugăm array-ul pentru subtopic în array-ul intermediar
                $tests[] = [
                    'id' => $num_ord_test,
                    'path' => $firstTest->path,
                    'type' => $firstTest->testType ?? 'testGeneralizator',
                    'name' => $firstTest->test_title,                    
                    'complexity' => $firstTest->complexity,
                    'complexityNumber' => $firstTest->complexity_level,
                    'addressTest' => $firstTest->test_path,
                    'length' => $firstTest->total_test_items,
                    'testResult' => $firstTest->testResult,
                ];
                $num_ord_test = $num_ord_test +1;
            }

            // Extragem prima intrare pentru a obține date comune pentru topic
            $firstTopic = $topicGroup->first();

            // Adăugăm array-ul pentru topic în array-ul final
            $finalResult[] = [
                'id' => $firstTopic->id,
                'name' => $firstTopic->name,
                'name_ro' => $firstTopic->name_ro,               
                'path' => $firstTopic->path,
                'theme_id' => $firstTopic->theme_id,
                'topic_id' => $firstTopic->topic_id,
                'teacher_id' => $firstTopic->teacher_id,
                'teacher_topic_id' => $firstTopic->teacher_topic_id,
                'subtopic_id' => $firstSubtopic->subtopic_id,
                'subtopic_name' => $firstSubtopic->subtopic_name,
                'audio_path' => $firstSubtopic->audio_path,
                'procentSubtopic' => $firstSubtopic->procentSubtopic,
                'procentTopic' => $firstTopic->procentTopic,
                'procentTema' => $firstTopic->procentTema,
                'subtitles' => $subtitles,
                'flip_cards' => $flip_cards,
                'tests' => $tests
            ];

            usort($finalResult, function ($a, $b) {
                return $a['id'] - $b['id'];
            });

        }

        return array_values($finalResult);

    }
}
