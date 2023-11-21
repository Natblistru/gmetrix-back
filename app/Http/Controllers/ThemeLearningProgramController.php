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

        DB::statement("
        CREATE TEMPORARY TABLE temp_themes_chapters AS
        SELECT
            TH.learning_program_id AS program_id,
            TH.id AS id,
            LP.year,
            SSLC.study_level_id AS study_level_id,
            S.name AS subject_name,
            themes.name AS tema_name,
            themes.id AS tema_id,
            themes.path AS path_tema,
            chapters.name AS capitol_name,
            chapters.id AS capitol_id,
            chapters.order_number AS capitol_ord,
            chapters.subject_study_level_id AS discipl_level_id
        FROM
            theme_learning_programs AS TH
        JOIN
            themes ON TH.theme_id = themes.id
        JOIN
            chapters ON themes.chapter_id = chapters.id
        JOIN
            subject_study_levels AS SSLC ON chapters.subject_study_level_id = SSLC.id
        JOIN
            learning_programs AS LP ON TH.learning_program_id = LP.id
        JOIN
            subject_study_levels AS SSLL ON LP.subject_study_level_id = SSLL.id
        JOIN 
            subjects AS S ON s.id = SSLC.subject_id AND SSLL.subject_id = s.id
        WHERE
            SSLC.study_level_id = ? AND
            SSLL.study_level_id = ? AND
            SSLC.subject_id = ? AND
            SSLL.subject_id = ?
        ", [$level, $level, $subjectId, $subjectId]);

        if (empty($student)) {
            $result = DB::select("
            SELECT 
                TC.program_id,
                TC.id,
                TC.year,
                TC.study_level_id,
                TC.subject_name,
                TC.tema_name,
                TC.tema_id,
                TC.path_tema,
                TC.capitol_name,
                TC.capitol_id,
                TC.capitol_ord,
                TC.discipl_level_id,
                0 AS capitol_media,
                0 AS tema_media
            FROM
                temp_themes_chapters AS TC
            ");
        } else {

            // Tabela temp_themes_topics_student
            DB::statement("
            CREATE TEMPORARY TABLE temp_themes_topics_student AS
            SELECT
                TC.program_id,
                TC.tema_name,
                TC.tema_id,
                TC.path_tema,
                TC.capitol_name,
                TC.capitol_id,
                topics.name AS topic_name,
                topics.id AS topic_id,
                TT.teacher_id,
                subtopics.name AS subtopic_name,
                subtopics.id AS subtopic_id,
                1 AS student_user_id, -- Valoarea constantă 1 pentru student_user
                SSP.student_id,
                COALESCE(SSP.progress_percentage, 0) AS progress_percentage
            FROM
                temp_themes_chapters AS TC
            JOIN
                topics ON TC.id = topics.theme_learning_program_id
            LEFT JOIN
                teacher_topics AS TT ON TT.topic_id = topics.id
            LEFT JOIN
                subtopics ON TT.id = subtopics.teacher_topic_id
            LEFT JOIN
                student_subopic_progress AS SSP ON SSP.subtopic_id = subtopics.id AND SSP.student_id = ?"
            , [$student]);

            //Calculul mediei progresului studentului pentru intreaga disciplina_nivel
            DB::statement("
            CREATE TEMPORARY TABLE temp_disciplina_student_progress AS
            SELECT
                student_user_id,
                program_id,
                SUM(progress_percentage),
                COUNT(progress_percentage),
                SUM(progress_percentage) / COUNT(progress_percentage) AS average_progress
            FROM
                temp_themes_topics_student
            GROUP BY
                student_user_id,
                program_id;
            ");

            // Calculul mediei progresului studentului pentru fiecare capitol_id
            DB::statement("
            CREATE TEMPORARY TABLE temp_capitole_student_progress AS
            SELECT
                student_user_id,
                capitol_id,
                SUM(progress_percentage),
                COUNT(progress_percentage),
                SUM(progress_percentage) / COUNT(progress_percentage) AS average_progress
            FROM
                temp_themes_topics_student
            GROUP BY
                student_user_id,
                capitol_id;        
            ");

            // Calculul mediei progresului studentului pentru fiecare tema_id
            DB::statement("
            CREATE TEMPORARY TABLE temp_teme_student_progress AS
            SELECT
                student_user_id,
                tema_id,
                SUM(progress_percentage),
                COUNT(progress_percentage),
                SUM(progress_percentage) / COUNT(progress_percentage) AS average_progress
            FROM
                temp_themes_topics_student
            GROUP BY
                student_user_id,
                tema_id;
                
            ");

            $result = DB::select("
            SELECT 
                TC.program_id,
                TC.year,
                TC.study_level_id,
                TC.subject_name,
                TC.tema_name,
                TC.tema_id,
                TC.path_tema,
                TC.capitol_name,
                TC.capitol_id,
                TC.capitol_ord,
                TC.discipl_level_id,
                COALESCE(DSP.average_progress, 0) AS disciplina_media,
                COALESCE(CSP.average_progress, 0) AS capitol_media,
                COALESCE(TSP.average_progress, 0) AS tema_media
            FROM
                temp_themes_chapters AS TC
            LEFT JOIN
	            temp_disciplina_student_progress DSP ON DSP.program_id = TC.program_id
            LEFT JOIN
                temp_capitole_student_progress CSP ON CSP.capitol_id = TC.capitol_id
            LEFT JOIN
                temp_teme_student_progress TSP ON TSP.tema_id = TC.tema_id
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

    
        // $result = DB::table('theme_learning_programs AS TH')
        // ->join('themes', 'TH.theme_id', '=', 'themes.id')
        // ->join('chapters', 'themes.chapter_id', '=', 'chapters.id')
        // ->join('subject_study_levels AS SSLC', 'chapters.subject_study_level_id', '=', 'SSLC.id')
        // ->join('learning_programs AS LP', 'TH.learning_program_id', '=', 'LP.id')
        // ->join('subject_study_levels AS SSLL', 'LP.subject_study_level_id', '=', 'SSLL.id')
        // ->select(
        //     'TH.learning_program_id as learning_program_id',
        //     'themes.name as denumire_tema',
        //     'themes.path',
        //     'chapters.name as denimire_capitol',
        //     'chapters.order_number as capitol_ord',
        //     'chapters.subject_study_level_id as discipl_level_id',
        //     'SSLL.name as discipl_level_name',
        //     'SSLL.id as discipl_level_id',
        //     'SSLL.subject_id as subject_id'
        // )
        // ->where('SSLC.study_level_id', $level)
        // ->where('SSLL.study_level_id', $level)
        // ->where('SSLC.subject_id', $subjectId)
        // ->where('SSLL.subject_id', $subjectId)
        // ->orderBy('SSLL.id')
        // ->orderBy('chapters.order_number')
        // ->get();
    
        // return $result;
    }

}
