<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\TeacherThemeVideo;

class TeacherThemeVideoController extends Controller
{
    public static function index() {
        $teacherVideos =  TeacherThemeVideo::all();
        return response()->json([
            'status' => 200,
            'teacherVideos' => $teacherVideos,
        ]);
    }

    public static function show($id) {
        return TeacherThemeVideo::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'teacher_id' => 'required|integer|exists:teachers,id',
            'video_id' => 'required|integer|exists:videos,id',
            'theme_learning_program_id' => 'required|integer|exists:theme_learning_programs,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'name' => $request->input('name'),
            'teacher_id' => $request->input('teacher_id'),
            'video_id' => $request->input('video_id'),
            'theme_learning_program_id' => $request->input('theme_learning_program_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'teacher_id' => $data['teacher_id'],
            'video_id' => $data['video_id'],
            'theme_learning_program_id' => $data['theme_learning_program_id'],            
        ];
    
        $existingRecord = TeacherThemeVideo::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            TeacherThemeVideo::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            TeacherThemeVideo::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Teacher Video Added successfully',
        ]);
    }


    public static function edit($id) {
        $teacherVideos = TeacherThemeVideo::with('theme_learning_program')->find($id);
        if ($teacherVideos) {
            return response()->json([
                'status' => 200,
                'teacherVideos' => $teacherVideos,
                'learning_program_id' => $teacherVideos->theme_learning_program->learning_program_id ?? null,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Teacher Video Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'teacher_id' => 'required|integer|exists:teachers,id',
            'video_id' => 'required|integer|exists:videos,id',
            'theme_learning_program_id' => 'required|integer|exists:theme_learning_programs,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $teacherVideo = TeacherThemeVideo::find($id);
        if($teacherVideo) {
            $teacherVideo->name = $request->input('name');
            $teacherVideo->teacher_id = $request->input('teacher_id');
            $teacherVideo->video_id = $request->input('video_id');   
            $teacherVideo->theme_learning_program_id = $request->input('theme_learning_program_id');                     
            $teacherVideo->status = $request->input('status'); 
            $teacherVideo->updated_at = now();             
            $teacherVideo->update();
            return response()->json([
                'status'=>200,
                'message'=>'Teacher Topic Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Teacher Video Id Found',
            ]); 
        }
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
