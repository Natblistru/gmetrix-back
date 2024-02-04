<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\EvaluationSubject;

class EvaluationSubjectController extends Controller
{
    // public static function index() {
    //     $evaluationSubjects =  EvaluationSubject::all();
    //     return response()->json([
    //         'status' => 200,
    //         'evaluationSubjects' => $evaluationSubjects,
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
        $filterEvaluation = $request->query('filterEvaluation');

        $allowedColumns = ['id', 'name', 'evaluation_name', 'status'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'ES',
            'name' => 'ES',
            'evaluation_name' => 'EV',
            'status' => 'E',
        ];

        $sqlTemplate = "
        SELECT
            ES.id,
            ES.name,
            EV.type,
            EV.year,
            EV.evaluation_name,
            EV.evaluation_id,
            SSLev.id subject_study_level_id,            
            ES.status
        FROM 
        	evaluation_subjects ES 
            INNER JOIN (
                SELECT 
                    E.name AS evaluation_name,
                    E.id AS evaluation_id,
                    E.year,
                    E.type,
                	E.subject_study_level_id
                FROM evaluations E
            ) AS EV ON EV.evaluation_id = ES.evaluation_id
            INNER JOIN subject_study_levels SSLev ON EV.subject_study_level_id = SSLev.id
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
                $sqlWithSortingAndSearch .= " AND EV.type = '$filterType'";
            }
    
            if ($filterYear) {
                $sqlWithSortingAndSearch .= " AND EV.year = $filterYear";
            }
        
            if ($filterProgram) {
                $sqlWithSortingAndSearch .= " AND SSLev.id = $filterProgram";
            }
    
            if ($filterEvaluation) {
                $sqlWithSortingAndSearch .= " AND EV.evaluation_id = $filterEvaluation";
            }

            $sqlWithSortingAndSearch .= " ORDER BY $sortColumn $sortOrder";
    
            $totalResults = DB::select("SELECT COUNT(*) as total FROM ($sqlWithSortingAndSearch) as countTable")[0]->total;
        
            $lastPage = ceil($totalResults / $perPage);
        
            $offset = ($page - 1) * $perPage;
        
            $rawResults = DB::select("$sqlWithSortingAndSearch LIMIT $perPage OFFSET $offset");
    
    
            return response()->json([
                'status' => 200,
                'evaluationSubjects' => $rawResults,
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
        return EvaluationSubject::find($id); 
    }

    public static function allEvaluationSubjects() {
        $evaluationSubjects =  EvaluationSubject::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'evaluationSubjects' => $evaluationSubjects,
        ]);
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|in:Subiectul I,Subiectul II,Subiectul III',
            'title' => 'required|string|max:500',
            'order_number' => 'required|integer|in:1,2,3',
            'evaluation_id' => 'required|exists:evaluations,id',
            'path' => 'required|string|max:200',
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
            'order_number' => $request->input('order_number'),
            'evaluation_id' => $request->input('evaluation_id'),
            'path' => $request->input('path'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'name' => $data['name'],
            'title' => $data['title'],            
            'evaluation_id' => $data['evaluation_id'],         
        ];
    
        $existingRecord = EvaluationSubject::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            EvaluationSubject::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            EvaluationSubject::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Subject Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationSubjects = EvaluationSubject::find($id);
        if ($evaluationSubjects) {
            return response()->json([
                'status' => 200,
                'evaluationSubjects' => $evaluationSubjects,
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
            'name' => 'required|in:Subiectul I,Subiectul II,Subiectul III',
            'order_number' => 'required|integer|in:1,2,3',
            'evaluation_id' => 'required|exists:evaluations,id',
            'path' => 'required|string|max:200',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $evaluation = EvaluationSubject::find($id);
        if($evaluation) {
            $evaluation->name = $request->input('name');
            $evaluation->order_number = $request->input('order_number');
            $evaluation->path = $request->input('path');
            $evaluation->evaluation_id = $request->input('evaluation_id');                     
            $evaluation->status = $request->input('status'); 
            $evaluation->updated_at = now();             
            $evaluation->update();
            return response()->json([
                'status'=>200,
                'message'=>'Evaluation Subject Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Evaluation Subject Id Found',
            ]); 
        }
    }


    public static function themeEvaluations(Request $request)  {

        $level = $request->query('level');
        $subjectId = $request->query('disciplina');
        $theme = $request->query('theme');

        $params = [$theme, $subjectId, $level];

        $result = DB::select("
        SELECT 
            ES.order_number AS id,
            ES.path,
            ES.name
        FROM evaluation_subjects ES
        INNER JOIN
            evaluation_items EI ON EI.evaluation_subject_id = ES.id AND EI.theme_id = ?
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?
        GROUP BY
            ES.order_number, ES.path, ES.name
        ORDER BY
            ES.order_number;
        ", $params);

        return array_values($result);
    }


    public static function themeEvaluation1(Request $request)  {

        $level = $request->query('level');
        $subjectId = $request->query('disciplina');
        $theme = $request->query('theme');

        $params = [$theme, $subjectId, $level];

        DB::statement("
        CREATE TEMPORARY TABLE temp_student_answers AS
        SELECT
            EA.id answer_id,
            ST.id student_id,
            EA.evaluation_item_id,
            EA.max_points,
            EO.id option_id,
            EO.points student_points
        FROM
            student_evaluation_answers SEA 
        INNER JOIN
            students ST ON ST.id = SEA.student_id
        INNER JOIN
            evaluation_answer_options EAO ON SEA.evaluation_answer_option_id = EAO.id
        INNER JOIN
            evaluation_answers EA ON EAO.evaluation_answer_id = EA.id
        INNER JOIN
            evaluation_items EI ON EA.evaluation_item_id = EI.id
        INNER JOIN
            evaluation_options EO ON EAO.evaluation_option_id = EO.id
        INNER JOIN
            evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
            AND ES.order_number = 1 
            AND EI.theme_id = ?
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?;
        ", $params);

        DB::statement("
        CREATE TEMPORARY TABLE temp_student_points AS
        SELECT 
            EI.theme_id,
            EI.evaluation_subject_id subject_id,
            EI.id AS item_id,
            EA.id answer_id,
            EA.max_points,
            EA.order_number answer_order,
            COALESCE(ST.student_points, 0) student_points
        FROM
            evaluation_items EI
        INNER JOIN
            evaluation_answers EA ON EA.evaluation_item_id = EI.id
        INNER JOIN
            evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
            AND ES.order_number = 1 
            AND EI.theme_id = ?
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?
        LEFT JOIN 
            temp_student_answers ST ON ST.answer_id = EA.id; 
        ", $params);

        DB::statement("
        CREATE TEMPORARY TABLE temp_student_procent AS
        SELECT 
            theme_id,
            subject_id,
            item_id,
            sum(student_points) AS student_points,
            SUM(max_points) AS item_maxpoints,
            sum(student_points) *100/ sum(max_points) AS student_procent
        FROM temp_student_points 
        GROUP BY 
            theme_id,
            subject_id,
            item_id;        
        ");

        $result = DB::select("
        SELECT 
            EI.theme_id,
            EFP.task form_task,
            EFP.hint form_hint,
            EFP.id AS form_id,
            EI.evaluation_subject_id,
            ES.name AS evaluation_subject_name,
            EI.id AS item_id,
            EA.id answer_id,
            EA.order_number answer_order,
            EI.task,
            EI.statement,
            EI.image_path,
            EI.procent_paper,
            EA.content answer_text,
            EA.task answer_task,
            EA.max_points,
            EO.id option_id,
            EO.label,
            EO.points,
            EAO.id evaluation_answer_id,
            ST.item_maxpoints AS maxPoints,
            COALESCE(ST.student_points, 0) AS student_points,
            COALESCE(ST.student_procent, 0) AS student_procent 
        FROM
            evaluation_items EI
        INNER JOIN
            evaluation_form_pages EFP ON EFP.evaluation_item_id = EI.id
        INNER JOIN
            temp_student_procent ST ON ST.item_id = EI.id
        INNER JOIN
            evaluation_answers EA ON EA.evaluation_item_id = EI.id
        INNER JOIN
            evaluation_answer_options EAO ON EAO.evaluation_answer_id = EA.id
        INNER JOIN
            evaluation_options EO ON EAO.evaluation_option_id = EO.id
        INNER JOIN
            evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
            AND ES.order_number = 1 
            AND EI.theme_id = ?
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?;
        ", $params);

        // Transformăm rezultatul în colecție Laravel pentru a folosi metoda groupBy
        $collection = collect($result);

        // Inițializăm un array pentru rezultatul final
        $finalResult = [];

        // Grupăm inițial pe item_id
        $groupedByItem = $collection->groupBy('item_id');

        // Iterăm prin fiecare grup de item_id
        foreach ($groupedByItem as $itemGroup) {
            // Inițializăm array-ul pentru răspunsurile din acest item_id
            $answers = [];
            $form = [];

            $groupedByForm = $itemGroup->groupBy('form_id');
            foreach ($groupedByForm as $formGroup) {
                $firstForm = $formGroup->first();

                $form[] = [
                    'cerinte' => $firstForm->form_task,
                    'hint' => $firstForm->form_hint,
                ];
            }

            // Grupăm acum pe answer_id în cadrul fiecărui item_id
            $groupedByAnswer = $itemGroup->groupBy('answer_id');

            // Iterăm prin fiecare grup de answer_id
            foreach ($groupedByAnswer as $answerGroup) {
                // Inițializăm array-ul pentru opțiunile din acest answer_id
                $options = [];

                // Grupăm acum pe option_id în cadrul fiecărui answer_id
                $groupedByOption = $answerGroup->groupBy('option_id');

                // Iterăm prin fiecare grup de option_id
                foreach ($groupedByOption as $optionGroup) {
                    // Extragem prima intrare pentru a obține date comune
                    $firstOption = $optionGroup->first();

                    // Adăugăm array-ul pentru opțiune în array-ul final
                    $options[] = [
                        'option_id' => $firstOption->option_id,
                        'label' => $firstOption->label,
                        'points' => $firstOption->points,
                        'evaluation_answer_id' => $firstOption->evaluation_answer_id,
                    ];
                }

                // Extragem prima intrare pentru a obține date comune
                $firstAnswer = $answerGroup->first();

                // Adăugăm array-ul pentru answer în array-ul final
                $answers[] = [
                    'answer_id' => $firstAnswer->answer_id,
                    'theme_id' => $firstAnswer->theme_id,
                    'subject_id' => $firstAnswer->evaluation_subject_id,
                    'answer_order' => $firstAnswer->answer_order,
                    'statement' => $firstAnswer->statement,
                    'answer_text' => $firstAnswer->answer_text,
                    'task' => $firstAnswer->answer_task,
                    'max_points' => $firstAnswer->max_points,
                    'options' => $options,
                ];
            }

            // Extragem prima intrare pentru a obține date comune
            $firstItem = $itemGroup->first();

            // Adăugăm array-ul pentru item în array-ul final
            $finalResult[] = [
                'item_id' => $firstItem->item_id,
                'theme_id' => $firstItem->theme_id,
                'subject_id' => $firstItem->evaluation_subject_id,
                'name' => $firstAnswer->evaluation_subject_name,
                'cerinta' => $firstAnswer->task,
                'procent_paper' => $firstAnswer->procent_paper,
                'img' => $firstAnswer->image_path,
                'maxPoints' => $firstItem->maxPoints,
                'student_points' => $firstItem->student_points,
                'student_procent' => $firstItem->student_procent,
                'answers' => $answers,
                'form' => $form,
            ];
        }


        return array_values($finalResult);

    }

    public static function themeEvaluation2(Request $request)  {

        $level = $request->query('level');
        $subjectId = $request->query('disciplina');
        $theme = $request->query('theme');

        $params = [$theme, $subjectId, $level];

        DB::statement("
        CREATE TEMPORARY TABLE temp_student_answers AS
        SELECT
            EA.id answer_id,
            ST.id student_id,
            EA.evaluation_item_id,
            EA.max_points,
            EO.id option_id,
            EO.points student_points
        FROM
            student_evaluation_answers SEA 
        INNER JOIN
            students ST ON ST.id = SEA.student_id
        INNER JOIN
            evaluation_answer_options EAO ON SEA.evaluation_answer_option_id = EAO.id
        INNER JOIN
            evaluation_answers EA ON EAO.evaluation_answer_id = EA.id
        INNER JOIN
            evaluation_items EI ON EA.evaluation_item_id = EI.id
        INNER JOIN
            evaluation_options EO ON EAO.evaluation_option_id = EO.id
        INNER JOIN
            evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
            AND ES.order_number = 2 
            AND EI.theme_id = ?
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?;
        ", $params);

        DB::statement("
        CREATE TEMPORARY TABLE temp_student_points AS
        SELECT 
            EI.theme_id,
            EI.evaluation_subject_id subject_id,
            EI.id AS item_id,
            EA.id answer_id,
            EA.max_points,
            EA.order_number answer_order,
            COALESCE(ST.student_points, 0) student_points
        FROM
            evaluation_items EI
        INNER JOIN
            evaluation_answers EA ON EA.evaluation_item_id = EI.id
        INNER JOIN
            evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
            AND ES.order_number = 2 
            AND EI.theme_id = ?
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?
        LEFT JOIN 
            temp_student_answers ST ON ST.answer_id = EA.id; 
        ", $params);

        DB::statement("
        CREATE TEMPORARY TABLE temp_student_procent AS
        SELECT 
            theme_id,
            subject_id,
            item_id,
            SUM(max_points) AS item_maxpoints,
            sum(student_points) AS student_points,
            sum(student_points) *100/ sum(max_points) AS student_procent
        FROM temp_student_points 
        GROUP BY 
            theme_id,
            subject_id,
            item_id;        
        ");

        $result = DB::select("
        SELECT 
            EI.theme_id,
            EFP.task form_task,
            EFP.hint form_hint,
            EFP.id AS form_id,
            EI.evaluation_subject_id,
            ES.name AS evaluation_subject_name,
            EI.id AS item_id,
            EA.id answer_id,
            EA.order_number answer_order,
            EI.task,
            EI.statement,
            EI.image_path,
            EI.editable_image_path,
            EI.procent_paper,
            EI.nota,
            ESo.id AS source_id,
            ESS.order_number AS source_order,
            ESo.title AS source_title,
            ESo.content AS source_content,
            Eso.author,
            Eso.text_sourse,
            EA.content answer_text,
            EA.task answer_task,
            EA.max_points,
            EO.id option_id,
            EO.label,
            EO.points,
            EAO.id evaluation_answer_id,
            ST.item_maxpoints AS maxPoints,
            COALESCE(ST.student_points, 0) AS student_points,
            COALESCE(ST.student_procent, 0) AS student_procent 
        FROM
            evaluation_items EI
        INNER JOIN
            evaluation_form_pages EFP ON EFP.evaluation_item_id = EI.id
        INNER JOIN
            temp_student_procent ST ON ST.item_id = EI.id
        INNER JOIN
            evaluation_answers EA ON EA.evaluation_item_id = EI.id
        INNER JOIN
            evaluation_answer_options EAO ON EAO.evaluation_answer_id = EA.id
        INNER JOIN
            evaluation_options EO ON EAO.evaluation_option_id = EO.id
        INNER JOIN
            evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
            AND ES.order_number = 2 
            AND EI.theme_id = ?
        INNER JOIN
        	evaluation_subject_sources ESS ON ES.id = ESS.evaluation_subject_id
        INNER JOIN
        	evaluation_sources ESo ON ESo.id = ESS.evaluation_source_id
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?;
        ", $params);

        // Transformăm rezultatul în colecție Laravel pentru a folosi metoda groupBy
        $collection = collect($result);

        // Inițializăm un array pentru rezultatul final
        $finalResult = [];

        // Grupăm inițial pe item_id
        $groupedByItem = $collection->groupBy('item_id');

        // Iterăm prin fiecare grup de item_id
        foreach ($groupedByItem as $itemGroup) {
            // Inițializăm array-ul pentru răspunsurile din acest item_id
            $answers = [];
            $form = [];
            $source = [];

            $groupedByForm = $itemGroup->groupBy('form_id');
            foreach ($groupedByForm as $formGroup) {
                $firstForm = $formGroup->first();

                $form[] = [
                    'cerinte' => $firstForm->form_task,
                    'hint' => $firstForm->form_hint,
                ];
            }

            $groupedBySource = $itemGroup->groupBy('source_id');
            foreach ($groupedBySource as $sourceGroup) {
                $firstSource = $sourceGroup->first();

                $source[] = [
                    'order' => $firstSource->source_order,
                    'title' => $firstSource->source_title,
                    'content' => $firstSource->source_content,                    
                    'author' => $firstSource->author,
                    'sursaText' => $firstSource->text_sourse,
                ];
            }

            // Grupăm acum pe answer_id în cadrul fiecărui item_id
            $groupedByAnswer = $itemGroup->groupBy('answer_id');

            // Iterăm prin fiecare grup de answer_id
            foreach ($groupedByAnswer as $answerGroup) {
                // Inițializăm array-ul pentru opțiunile din acest answer_id
                $options = [];

                // Grupăm acum pe option_id în cadrul fiecărui answer_id
                $groupedByOption = $answerGroup->groupBy('option_id');

                // Iterăm prin fiecare grup de option_id
                foreach ($groupedByOption as $optionGroup) {
                    // Extragem prima intrare pentru a obține date comune
                    $firstOption = $optionGroup->first();

                    // Adăugăm array-ul pentru opțiune în array-ul final
                    $options[] = [
                        'option_id' => $firstOption->option_id,
                        'label' => $firstOption->label,
                        'points' => $firstOption->points,
                        'evaluation_answer_id' => $firstOption->evaluation_answer_id,
                    ];
                }

                // Extragem prima intrare pentru a obține date comune
                $firstAnswer = $answerGroup->first();

                // Adăugăm array-ul pentru answer în array-ul final
                $answers[] = [
                    'answer_id' => $firstAnswer->answer_id,
                    'theme_id' => $firstAnswer->theme_id,
                    'subject_id' => $firstAnswer->evaluation_subject_id,
                    'answer_order' => $firstAnswer->answer_order,
                    'answer_text' => $firstAnswer->answer_text,
                    'task' => $firstAnswer->answer_task,
                    'max_points' => $firstAnswer->max_points,
                    'options' => $options,
                ];
            }

            // Extragem prima intrare pentru a obține date comune
            $firstItem = $itemGroup->first();

            // Adăugăm array-ul pentru item în array-ul final
            $finalResult[] = [
                'item_id' => $firstItem->item_id,
                'theme_id' => $firstItem->theme_id,
                'subject_id' => $firstItem->evaluation_subject_id,
                'name' => $firstAnswer->evaluation_subject_name,
                'cerinta' => $firstAnswer->task,
                'afirmatie' => $firstAnswer->statement,
                'procent_paper' => $firstAnswer->procent_paper,
                'nota' => $firstAnswer->nota,
                'img' => $firstAnswer->image_path,
                'harta' => $firstAnswer->editable_image_path,
                'maxPoints' => $firstItem->maxPoints,
                'student_points' => $firstItem->student_points,
                'student_procent' => $firstItem->student_procent,
                'answers' => $answers,
                'form' => $form,
                'source' => $source,
            ];
        }


        return array_values($finalResult);

    }

    public static function themeEvaluation3(Request $request)  {

        $level = $request->query('level');
        $subjectId = $request->query('disciplina');
        $theme = $request->query('theme');

        $params = [$theme, $subjectId, $level];

        DB::statement("
        CREATE TEMPORARY TABLE temp_student_answers AS
        SELECT
            EA.id answer_id,
            ST.id student_id,
            EA.evaluation_item_id,
            EA.max_points,
            EO.id option_id,
            EO.points student_points
        FROM
            student_evaluation_answers SEA 
        INNER JOIN
            students ST ON ST.id = SEA.student_id
        INNER JOIN
            evaluation_answer_options EAO ON SEA.evaluation_answer_option_id = EAO.id
        INNER JOIN
            evaluation_answers EA ON EAO.evaluation_answer_id = EA.id
        INNER JOIN
            evaluation_items EI ON EA.evaluation_item_id = EI.id
        INNER JOIN
            evaluation_options EO ON EAO.evaluation_option_id = EO.id
        INNER JOIN
            evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
            AND ES.order_number = 3 
            AND EI.theme_id = ?
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?;
        ", $params);

        DB::statement("
        CREATE TEMPORARY TABLE temp_student_points AS
        SELECT 
            EI.theme_id,
            EI.evaluation_subject_id subject_id,
            EI.id AS item_id,
            EA.id answer_id,
            EA.max_points,
            EA.order_number answer_order,
            COALESCE(ST.student_points, 0) student_points
        FROM
            evaluation_items EI
        INNER JOIN
            evaluation_answers EA ON EA.evaluation_item_id = EI.id
        INNER JOIN
            evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
            AND ES.order_number = 3 
            AND EI.theme_id = ?
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?
        LEFT JOIN 
            temp_student_answers ST ON ST.answer_id = EA.id; 
        ", $params);

        DB::statement("
        CREATE TEMPORARY TABLE temp_student_procent AS
        SELECT 
            theme_id,
            subject_id,
            item_id,
            SUM(max_points) AS item_maxpoints,
            sum(student_points) AS student_points,
            sum(student_points) *100/ sum(max_points) AS student_procent
        FROM temp_student_points 
        GROUP BY 
            theme_id,
            subject_id,
            item_id;        
        ");

        $result = DB::select("
        SELECT 
            EI.theme_id,
            EFP.task form_task,
            EFP.hint form_hint,
            EFP.id AS form_id,
            EI.evaluation_subject_id,
            ES.name AS evaluation_subject_name,
            EI.id AS item_id,
            EA.id answer_id,
            EA.order_number answer_order,
            EI.task,
            EI.statement,
            EI.image_path,
            EI.editable_image_path,
            EI.procent_paper,
            EI.nota,
            ESo.id AS source_id,
            ESS.order_number AS source_order,
            ESo.title AS source_title,
            ESo.content AS source_content,
            Eso.author,
            Eso.text_sourse,
            EA.content answer_text,
            EA.task answer_task,
            EA.max_points,
            EO.id option_id,
            EO.label,
            EO.points,
            EAO.id evaluation_answer_id,
            ST.item_maxpoints AS maxPoints,
            COALESCE(ST.student_points, 0) AS student_points,
            COALESCE(ST.student_procent, 0) AS student_procent 
        FROM
            evaluation_items EI
        INNER JOIN
            evaluation_form_pages EFP ON EFP.evaluation_item_id = EI.id
        INNER JOIN
            temp_student_procent ST ON ST.item_id = EI.id
        INNER JOIN
            evaluation_answers EA ON EA.evaluation_item_id = EI.id
        INNER JOIN
            evaluation_answer_options EAO ON EAO.evaluation_answer_id = EA.id
        INNER JOIN
            evaluation_options EO ON EAO.evaluation_option_id = EO.id
        INNER JOIN
            evaluation_subjects ES ON ES.id = EI.evaluation_subject_id 
            AND ES.order_number = 3 
            AND EI.theme_id = ?
        INNER JOIN
        	evaluation_subject_sources ESS ON ES.id = ESS.evaluation_subject_id
        INNER JOIN
        	evaluation_sources ESo ON ESo.id = ESS.evaluation_source_id
        INNER JOIN
            evaluations E ON E.id = ES.evaluation_id
        INNER JOIN
            subject_study_levels SSLev ON SSLev.id = E.subject_study_level_id 
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?;
        ", $params);

        // Transformăm rezultatul în colecție Laravel pentru a folosi metoda groupBy
        $collection = collect($result);

        // Inițializăm un array pentru rezultatul final
        $finalResult = [];

        // Grupăm inițial pe item_id
        $groupedByItem = $collection->groupBy('item_id');

        // Iterăm prin fiecare grup de item_id
        foreach ($groupedByItem as $itemGroup) {
            // Inițializăm array-ul pentru răspunsurile din acest item_id
            $answers = [];
            $form = [];
            $source = [];

            $groupedByForm = $itemGroup->groupBy('form_id');
            foreach ($groupedByForm as $formGroup) {
                $firstForm = $formGroup->first();

                $form[] = [
                    'cerinte' => $firstForm->form_task,
                    'hint' => $firstForm->form_hint,
                ];
            }

            $groupedBySource = $itemGroup->groupBy('source_id');
            foreach ($groupedBySource as $sourceGroup) {
                $firstSource = $sourceGroup->first();

                $source[] = [
                    'order' => $firstSource->source_order,
                    'title' => $firstSource->source_title,
                    'content' => $firstSource->source_content,                    
                    'author' => $firstSource->author,
                    'sursaText' => $firstSource->text_sourse,
                ];
            }

            // Grupăm acum pe answer_id în cadrul fiecărui item_id
            $groupedByAnswer = $itemGroup->groupBy('answer_id');

            // Iterăm prin fiecare grup de answer_id
            foreach ($groupedByAnswer as $answerGroup) {
                // Inițializăm array-ul pentru opțiunile din acest answer_id
                $options = [];

                // Grupăm acum pe option_id în cadrul fiecărui answer_id
                $groupedByOption = $answerGroup->groupBy('option_id');

                // Iterăm prin fiecare grup de option_id
                foreach ($groupedByOption as $optionGroup) {
                    // Extragem prima intrare pentru a obține date comune
                    $firstOption = $optionGroup->first();

                    // Adăugăm array-ul pentru opțiune în array-ul final
                    $options[] = [
                        'option_id' => $firstOption->option_id,
                        'label' => $firstOption->label,
                        'points' => $firstOption->points,
                        'evaluation_answer_id' => $firstOption->evaluation_answer_id,
                    ];
                }

                // Extragem prima intrare pentru a obține date comune
                $firstAnswer = $answerGroup->first();

                // Adăugăm array-ul pentru answer în array-ul final
                $answers[] = [
                    'answer_id' => $firstAnswer->answer_id,
                    'theme_id' => $firstAnswer->theme_id,
                    'subject_id' => $firstAnswer->evaluation_subject_id,
                    'answer_order' => $firstAnswer->answer_order,
                    'answer_text' => $firstAnswer->answer_text,
                    'task' => $firstAnswer->answer_task,
                    'max_points' => $firstAnswer->max_points,
                    'options' => $options,
                ];
            }

            // Extragem prima intrare pentru a obține date comune
            $firstItem = $itemGroup->first();

            // Adăugăm array-ul pentru item în array-ul final
            $finalResult[] = [
                'item_id' => $firstItem->item_id,
                'theme_id' => $firstItem->theme_id,
                'subject_id' => $firstItem->evaluation_subject_id,
                'name' => $firstAnswer->evaluation_subject_name,
                'cerinta' => $firstAnswer->task,
                'afirmatie' => $firstAnswer->statement,
                'procent_paper' => $firstAnswer->procent_paper,
                'nota' => $firstAnswer->nota,
                'img' => $firstAnswer->image_path,
                'harta' => $firstAnswer->editable_image_path,
                'maxPoints' => $firstItem->maxPoints,
                'student_points' => $firstItem->student_points,
                'student_procent' => $firstItem->student_procent,
                'answers' => $answers,
                'form' => $form,
                'source' => $source,
            ];
        }


        return array_values($finalResult);

    }
}