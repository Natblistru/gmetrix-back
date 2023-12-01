<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TeacherTopic;

class TeacherTopicController extends Controller
{
    public static function index() {
        return TeacherTopic::all();
    }

    public static function show($id) {
        return TeacherTopic::find($id); 
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
            topics.path AS topic_path,
            topics.order_number AS topic_order_number,
            TT.id as teacher_topic_id,
            subtopics.id as subtopic_id,
            subtopics.name as subtopic_name,
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
            TS.topic_path,
            TS.topic_order_number,
            TS.teacher_topic_id,
            TS.subtopic_id,
            TS.subtopic_name,
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
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ? AND TLP.theme_id = ?;     
        ", $paramsGeneral);


        // Lista tuturor testelor formative
        DB::statement("
        CREATE TEMPORARY TABLE temp_all_tests AS 
        SELECT
            TT.id,
            TT.topic_id,
            FT.title,
            FT.path,
            FT.order_number,
            TC.name,
            TC.level,
            COUNT(COALESCE(SFTR.score, 0)) AS totalTests,
            AVG(COALESCE(SFTR.score, 0)) AS testResult
        FROM
            teacher_topics TT
        LEFT JOIN
            formative_tests FT ON FT.teacher_topic_id = TT.id
        INNER JOIN 
            formative_test_items FTI ON FTI.formative_test_id = FT.id
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
            COALESCE(PS.topic_path, TAT.topic_path) AS path,
            PS.subtopic_id,
            PS.subtopic_name,
            COALESCE(SI.path, '') AS subtopic_images,
            PSS.audio_path,
            FC.id AS flip_id,
            FC.task AS flip_task,
            FC.answer AS flip_answer,
            TATest.title AS test_title,
            TATest.path AS test_path,
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
                    'procentSubtopic' => $firstSubtopic->procentSubtopic,
                    'images' => $subtopicImages,
                ];
                $num_ord = $num_ord +1;
            }

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
                    'rezolvare' => $firstFlip->flip_answer,
                ];
            }

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
                'path' => $firstTopic->path,
                'theme_id' => $firstTopic->theme_id,
                'topic_id' => $firstTopic->topic_id,
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
        }

        return array_values($finalResult);

    }
}
