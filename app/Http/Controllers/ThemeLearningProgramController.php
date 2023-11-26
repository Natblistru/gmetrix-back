<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ThemeLearningProgram;

class ThemeLearningProgramController extends Controller
{
    public static function index() {
        return ThemeLearningProgram::all();
    }

    public static function show($id) {
        return ThemeLearningProgram::find($id); 
    }

    public static function capitoleDisciplina(Request $request)  {

        $level = $request->query('level');
        $subjectId = $request->query('disciplina');
        $student = $request->query('student');
        $teacher = $request->query('teacher');
        $year = $request->query('year');

        if ($year) {
            $yearCondition = " LP.year = ? ";
            $params = [$year, $subjectId, $level];
        } else {
            $yearCondition = " LP.year = (SELECT MAX(LP2.year) 
                            FROM learning_programs LP2
                            JOIN subject_study_levels SSLev2 ON LP2.subject_study_level_id = SSLev2.id
                            WHERE SSLev2.subject_id = ? AND SSLev2.study_level_id = ?) ";
            $params = [$subjectId, $level, $subjectId, $level];
        }
    
        DB::statement("
        CREATE TEMPORARY TABLE temp_themes_chapters_topics AS
        SELECT
            TH.learning_program_id AS program_id,
            TH.id AS theme_learning_programs_id,
            SSLev.study_level_id AS study_level_id,
            S.name AS subject_name,
            S.id AS subject_id,
            chapters.name AS capitol_name,
            chapters.id AS capitol_id,
            chapters.subject_study_level_id AS discipl_level_id,
            chapters.order_number AS capitol_ord,
            themes.name AS tema_name,
            themes.id AS tema_id,
            themes.path AS path_tema,
            LP.year,
            chapters.order_number as chap_order,
            themes.order_number as them_order
        FROM
            theme_learning_programs AS TH
        JOIN
            learning_programs LP ON TH.learning_program_id = LP.id
        JOIN
            subject_study_levels SSLev ON LP.subject_study_level_id = SSLev.id
        JOIN 
            subjects AS S ON S.id = SSLev.subject_id
        JOIN
            themes ON TH.theme_id = themes.id
        JOIN
            chapters ON themes.chapter_id = chapters.id and chapters.subject_study_level_id = LP.subject_study_level_id
        WHERE
            {$yearCondition}
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ?
        ", $params);

        if (empty($student)) {
            $result = DB::select("
            SELECT
                TC.program_id,
                TC.theme_learning_programs_id,
                TC.study_level_id,
                TC.subject_name,
                TC.subject_id,
                TC.capitol_name,
                TC.capitol_id,
                TC.discipl_level_id,
                TC.capitol_ord,
                TC.tema_name,
                TC.tema_id,
                TC.path_tema,
                TC.year,
                TC.chap_order,
                TC.them_order,
                0 AS disciplina_media,
                0 AS capitol_media,
                0 AS tema_media
            FROM
                temp_themes_chapters_topics AS TC
            ");
        } else {

            if ($year) {
                $paramStudent = [$year, $student, $subjectId, $level];
                if ($teacher) {
                    $teacherCondition = " AND TT.teacher_id = ?";
                    $paramsTeacher = [$year, $subjectId, $level, $teacher];
                } else {
                    $teacherCondition = "";
                    $paramsTeacher = [$year, $subjectId, $level];
                }
            } else {
                $paramStudent = [$subjectId, $level, $student, $subjectId, $level];
                if ($teacher) {
                    $teacherCondition = " AND TT.teacher_id = ?";
                    $paramsTeacher = [$subjectId, $level, $subjectId, $level, $teacher];
                } else {
                    $teacherCondition = "";
                    $paramsTeacher = [$subjectId, $level, $subjectId, $level];
                }
            } 

            // Subtopicurile trecute de student
            DB::statement("
            CREATE TEMPORARY TABLE temp_subtopics_progress AS
            SELECT 
                TT.teacher_id AS teacher_id,
                TLP.theme_id,
                topics.id AS topic_id,
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
                AND SST.student_id = ? AND SSLev.subject_id = ? AND SSLev.study_level_id = ?
            ", $paramStudent);

            //Obținem subtopicurile profesorilor
            DB::statement("
            CREATE TEMPORARY TABLE temp_teacher_subtopics AS
            SELECT
                TT.teacher_id AS teacher_id,
                TLP.theme_id,
                topics.id AS topic_id,
                TT.id as teacher_topic_id,
                subtopics.id as subtopic_id
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
            JOIN
                subtopics ON subtopics.teacher_topic_id = TT.id
            WHERE
                {$yearCondition}
                AND SSLev.subject_id = ? AND SSLev.study_level_id = ? {$teacherCondition}; 
            ", $paramsTeacher);

            // Progresului studentului pentru toate subtopicurile
            DB::statement("
            CREATE TEMPORARY TABLE temp_progress_all_subtopic AS
            SELECT
                TS.teacher_id,
                TS.theme_id,
                TS.topic_id,
                TS.teacher_topic_id,
                TS.subtopic_id,
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

            // Calculul mediei progresului studentului pentru fiecare tema a profesorilor
            DB::statement("
            CREATE TEMPORARY TABLE temp_progress_avg_all_themes AS
            SELECT 
                T.teacher_id,
                T.theme_id,
                SUM(T.progress_percentage) / COUNT(T.progress_percentage) AS average_progress
            FROM temp_progress_all_subtopic T
                        GROUP BY
                            T.teacher_id,
                            T.theme_id;
            ");

            // Calculul mediei maximale a progresului studentului pentru fiecare tema
            DB::statement("
            CREATE TEMPORARY TABLE temp_progress_theme AS              
            SELECT 
                TCT.capitol_name,
                TCT.capitol_id,
                TCT.tema_id,
                TCT.tema_name,
                MAX(COALESCE(PA.average_progress, 0)) AS procentThema
            FROM
                temp_themes_chapters_topics TCT
            LEFT JOIN
                temp_progress_avg_all_themes PA ON PA.theme_id = TCT.tema_id
            GROUP BY
                TCT.capitol_name,
                TCT.capitol_id,
                TCT.tema_name,
                TCT.tema_id
            ORDER BY
                TCT.chap_order,
                TCT.them_order;           
            ");

            // Calculul mediei progresului studentului pentru fiecare capitol_id
            DB::statement("
            CREATE TEMPORARY TABLE temp_progress_capitol AS    
            SELECT 
                TCT.capitol_name,
                TCT.capitol_id,
                AVG(TCT.procentThema) AS procentCapitol
            FROM
                temp_progress_theme TCT
            GROUP BY
                TCT.capitol_name,
                TCT.capitol_id
            ORDER BY
                TCT.capitol_id;    
            ");

            // Calculul mediei progresului studentului pentru toată disciplina
            DB::statement("
            CREATE TEMPORARY TABLE temp_progress_disciplina AS  
            SELECT 
                AVG(TCT.procentCapitol) AS procentdisciplina
            FROM
                temp_progress_capitol TCT;            
            ");

            $result = DB::select("
            SELECT
                TC.program_id,
                TC.theme_learning_programs_id,
                TC.study_level_id,
                TC.subject_name,
                TC.subject_id,
                TC.capitol_name,
                TC.capitol_id,
                TC.discipl_level_id,
                TC.capitol_ord,
                TC.tema_name,
                TC.tema_id,
                TC.path_tema,
                TC.year,
                TC.chap_order,
                TC.them_order,
                COALESCE(PD.procentdisciplina, 0) AS disciplina_media,
                COALESCE(PC.procentCapitol, 0) AS capitol_media,
                COALESCE(PT.procentThema, 0) AS tema_media
            FROM
                temp_themes_chapters_topics AS TC
            CROSS JOIN
                temp_progress_disciplina PD
            LEFT JOIN
                temp_progress_capitol PC ON PC.capitol_id = TC.capitol_id
            LEFT JOIN
                temp_progress_theme PT ON PT.tema_id = TC.tema_id;
            ");
        }

        // Array pentru a organiza datele într-o structură ierarhică
        $organizedData = [];

        foreach ($result as $item) {
            $capitol_id = $item->capitol_id;
            $tema_id = $item->tema_id;

            if (!isset($organizedData[$capitol_id])) {
                $organizedData[$capitol_id] = (array)$item; 
                $organizedData[$capitol_id]['subtitles'] = []; // Inițializam un array pentru teme
            }

            // Adăug tema în array-ul de teme al capitolului
            $organizedData[$capitol_id]['subtitles'][] = (array)$item; 
        }

        // Convertim array-ul asociativ într-un array indexat pentru a obține o structură ușor de parcurs
        $organizedArray = array_values($organizedData);

        return $organizedArray;


    }

}
