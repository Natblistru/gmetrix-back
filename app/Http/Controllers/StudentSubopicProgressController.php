<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StudentSubopicProgress;

class StudentSubopicProgressController extends Controller
{
    public static function index() {
        return StudentSubopicProgress::all();
    }

    public static function show($id) {
        return StudentSubopicProgress::find($id); 
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required',
            'subtopic_id' => 'required',
            'progress_percentage' => 'required',
        ]);

        $progress = StudentSubopicProgress::updateOrCreate(
            [
                'student_id' => $validatedData['student_id'],
                'subtopic_id' => $validatedData['subtopic_id'],
            ],
            ['progress_percentage' => $validatedData['progress_percentage']]
        );

        return response()->json($progress, 201);
    }

    public static function getStudentProgressAllThemes(Request $request) {
        try {
            $subject_id = $request->input('subject_id');
            $study_level_id = $request->input('study_level_id');
            $studentId = $request->input('studentId');
    
            DB::statement("
            CREATE TEMPORARY TABLE temp_total_subtopics AS    
            SELECT
                T.id AS teacher_id,
                T.name as teache_name,
                TLP.theme_id,
                TT.id as teacher_topic_id,
                TT.name as teacher_topic_name,
                COUNT(subtopics.id) as subtopic_total
            FROM
                teacher_topics TT
            JOIN
                teachers T ON TT.teacher_id = T.id
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
                 LP.year = (SELECT MAX(LP2.year) 
                                FROM learning_programs LP2
                                JOIN subject_study_levels SSLev2 ON LP2.subject_study_level_id = SSLev2.id
                                WHERE SSLev2.subject_id = ? AND SSLev2.study_level_id = ?) 
                AND SSLev.subject_id = ? AND SSLev.study_level_id = ?
            GROUP BY T.id, T.name, TLP.theme_id, TT.id, TT.name;
            ", [$subject_id, $study_level_id, $subject_id, $study_level_id]);


            DB::statement("   
            CREATE TEMPORARY TABLE temp_subtopics_progress AS
            SELECT 
                subtopics.teacher_topic_id,
                COUNT(SST.subtopic_id) AS topic_pased
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
                LP.year = (SELECT MAX(LP2.year) 
                                FROM learning_programs LP2
                                JOIN subject_study_levels SSLev2 ON LP2.subject_study_level_id = SSLev2.id
                                WHERE SSLev2.subject_id = ? AND SSLev2.study_level_id = ?) 
                AND SST.student_id = ? AND SSLev.subject_id = ? AND SSLev.study_level_id = ?
            GROUP BY subtopics.teacher_topic_id;
            ", [$subject_id, $study_level_id, $studentId, $subject_id, $study_level_id]);

            $sqlTemplate = "
            SELECT 
                TTS.teacher_id,
                TTS.teache_name,
                TTS.theme_id,
                TTS.teacher_topic_id,
                TTS.teacher_topic_name,
                TTS.subtopic_total as subtopic_total,
                COALESCE(TSP.topic_pased, 0) topic_pased
            FROM temp_total_subtopics TTS
            LEFT JOIN temp_subtopics_progress TSP ON TSP.teacher_topic_id = TTS.teacher_topic_id

            ";

            $result = DB::select($sqlTemplate);
   
            return response()->json([
                'status' => 200,
                'studentProgress' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ]);
        }
    }

}
