<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\TeacherPresentation;

class TeacherPresentationController extends Controller
{
    public static function index() {
        return TeacherPresentation::all();
    }

    public static function show($id) {
        return TeacherPresentation::find($id); 
    }

    public static function teacherThemePresentation(Request $request)  {

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
            TP.teacher_id AS teacher_id,
            TLP.theme_id,
            TP.id as presentation_id,
            TP.name as presentation_title,
            TP.path as presentation_source
        FROM
            teacher_presentations TP
        JOIN
            theme_learning_programs TLP ON TLP.id = TP.theme_learning_program_id
        JOIN
            learning_programs LP ON TLP.learning_program_id = LP.id
        JOIN
            subject_study_levels SSLev ON LP.subject_study_level_id = SSLev.id
        WHERE
            {$yearCondition}
            AND SSLev.subject_id = ? AND SSLev.study_level_id = ? AND TP.teacher_id = ? AND TLP.theme_id = ?;  
        ", $params);

        // Array pentru a organiza datele într-o structură ierarhică
        $organizedData = [];

        foreach ($result as $item) {
            $presentation_id = $item->presentation_id;
        
            if (!isset($organizedData[$presentation_id])) {
                $organizedData[$presentation_id] = (array)$item;
            }
        }
        
        // Convertim array-ul asociativ într-un array indexat pentru a obține o structură ușor de parcurs
        $organizedArray = array_values($organizedData);
        
        return $organizedArray;
    }
}
