<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class VideoLessonController extends Controller
{
    public function index(Request $request)
    {
        $subject_id = $request->subject_id;
        $student_id = $request->student_id;

        $sql = "
            SELECT 
                vl.id,
                vl.title,
                vl.source,
                vl.status,
                vl.subject_id,

                COALESCE(vls.id, 0) AS video_lesson_student_id,
                COALESCE(vls.suma, 0) AS suma,
                COALESCE(vls.statut, 0) AS statut_student,

                CASE 
                    WHEN vls.id IS NULL THEN 0
                    ELSE 1
                END AS acces

            FROM `video-lessons` vl

            LEFT JOIN `video-lesson-students` vls
                ON vls.`videoLesson_id` = vl.id
                AND vls.student_id = ?
                AND vls.statut = 0

            WHERE vl.status = 0
        ";

        $params = [$student_id];

        if ($subject_id) {
            $sql .= " AND vl.subject_id = ?";
            $params[] = $subject_id;
        }

        $sql .= " ORDER BY vl.id ASC";

        $lessons = DB::select($sql, $params);

        return response()->json($lessons);
    }
}
