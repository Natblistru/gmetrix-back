<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TeacherThemeVideo;

class TeacherThemeVideoController extends Controller
{
    public static function index() {
        return TeacherThemeVideo::all();
    }

    public static function show($id) {
        return TeacherThemeVideo::find($id); 
    }

    public static function teacherThemeVideo(Request $request)  {

        $level = $request->query('level');
        $subjectId = $request->query('disciplina');
        $teacher = $request->query('teacher');
        $year = $request->query('year');
        $theme = $request->query('theme');

        if ($year) {
            $yearCondition = " LP.year = ? ";
            $params = [$year, $subjectId, $level, $teacher, $theme];
        } else {
            $yearCondition = " LP.year = (SELECT MAX(LP2.year) 
                            FROM learning_programs LP2
                            JOIN subject_study_levels SSLev2 ON LP2.subject_study_level_id = SSLev2.id
                            WHERE SSLev2.subject_id = ? AND SSLev2.study_level_id = ?) ";
            $params = [$subjectId, $level, $subjectId, $level, $teacher, $theme];
        }

        $result = DB::select("
        SELECT
            TTV.teacher_id AS teacher_id,
            TLP.theme_id,
            videos.id as video_id,
            videos.title as video_title,
            videos.source as video_source,
            Bpoint.id AS breakpoint_id,
            Bpoint.name AS name,
            Bpoint.time AS time,
            Bpoint.seconds AS seconds
        FROM
            teacher_theme_videos TTV
        JOIN
            videos ON TTV.video_id = videos.id      
        JOIN
            video_breakpoints Bpoint ON Bpoint.video_id = videos.id    
        JOIN
            theme_learning_programs TLP ON TLP.id = TTV.theme_learning_program_id
        JOIN
            learning_programs LP ON TLP.learning_program_id = LP.id
        JOIN
            subject_study_levels SSLev ON LP.subject_study_level_id = SSLev.id
        WHERE
            {$yearCondition}
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ? AND TTV.teacher_id = ? AND TLP.theme_id = ?;  
        ", $params);

        // Array pentru a organiza datele într-o structură ierarhică
        $organizedData = [];

        foreach ($result as $item) {
            $video_id = $item->video_id;
            $breakpoint_id = $item->breakpoint_id;

            if (!isset($organizedData[$video_id])) {
                $organizedData[$video_id] = (array)$item; 
                $organizedData[$video_id]['breakpoints'] = []; // Inițializam un array pentru teme
            }

            // Adăug video în array-ul de breakpoints al videoului
            $organizedData[$video_id]['breakpoints'][] = (array)$item; 
        }

        // Convertim array-ul asociativ într-un array indexat pentru a obține o structură ușor de parcurs
        $organizedArray = array_values($organizedData);

        return $organizedArray;
    }

}
