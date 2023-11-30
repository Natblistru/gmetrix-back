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
        } else {
            $yearCondition = " LP.year = (SELECT MAX(LP2.year) 
                            FROM learning_programs LP2
                            JOIN subject_study_levels SSLev2 ON LP2.subject_study_level_id = SSLev2.id
                            WHERE SSLev2.subject_id = ? AND SSLev2.study_level_id = ?) ";
            $params = [$subjectId, $level, $subjectId, $level, $teacher, $theme];
            $paramsGeneral = [$subjectId, $level, $subjectId, $level, $theme];
            $paramsStudent = [$subjectId, $level, $student, $subjectId, $level, $teacher, $theme];
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
            COALESCE(PSS.progress_percentage, 0) AS procentSubtopic,
            COALESCE(PT.procentTopic, 0) AS procentTopic,
            COALESCE(PTh.procentTema, 0) AS procentTema    
        FROM temp_all_topics TAT
        LEFT JOIN
            temp_progress_all_subtopic PS ON TAT.theme_id = PS.theme_id AND 
                                             TAT.topic_id = PS.topic_id
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
                    'id' => $firstSubtopic->subtopic_id,
                    'procentSubtopic' => $firstSubtopic->procentSubtopic,
                    'images' => $subtopicImages,
                ];
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
            ];
        }

        return array_values($finalResult);

    }
}
