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
                END AS acces,

                COALESCE(cr.id, 0) AS consultation_request_id,
                cr.name AS consultation_name,
                cr.phone AS consultation_phone,
                cr.email AS consultation_email,
                cr.scheduled_at AS consultation_scheduled_at,
                DATE_FORMAT(cr.scheduled_at, '%Y-%m-%dT%H:%i') AS consultation_scheduled_at_input,
                COALESCE(cr.status, 1) AS consultation_status,

                CASE 
                    WHEN cr.id IS NULL THEN 0
                    ELSE 1
                END AS consultatie_programata

            FROM `video_lessons` vl

            LEFT JOIN `video-lesson-students` vls
                ON vls.`videoLesson_id` = vl.id
                AND vls.student_id = ?
                AND vls.statut = 0

            LEFT JOIN (
                SELECT cr1.*
                FROM `consultation-requests` cr1
                INNER JOIN (
                    SELECT 
                        `videoLesson_id`,
                        student_id,
                        MAX(id) AS max_id
                    FROM `consultation-requests`
                    WHERE status = 0
                    GROUP BY `videoLesson_id`, student_id
                ) cr2
                    ON cr1.id = cr2.max_id
            ) cr
                ON cr.`videoLesson_id` = vl.id
                AND cr.student_id = ?

            WHERE vl.status = 0
        ";

        $params = [$student_id, $student_id];

        if ($subject_id) {
            $sql .= " AND vl.subject_id = ?";
            $params[] = $subject_id;
        }

        $sql .= " ORDER BY vl.id ASC";

        $lessons = DB::select($sql, $params);

        return response()->json($lessons);
    }
}