<?php

namespace App\Http\Controllers;

use App\Models\Subtopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SubtopicController extends Controller
{
    public static function index() {
        $subtopics =  Subtopic::all();
        return response()->json([
            'status' => 200,
            'subtopics' => $subtopics,
        ]);
    }

    public static function show($id) {
        return Subtopic::find($id); 
    }

    public static function allSubtopics() {
        $subtopics =  Subtopic::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'subtopics' => $subtopics,
        ]);
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'teacher_topic_id' => 'required|integer|exists:teacher_topics,id',
            'audio' => 'nullable|mimes:mp3,wav|max:10240',
            'audio_path' => 'nullable|string|max:1000',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'name' => $request->input('name'),
            'teacher_topic_id' => $request->input('teacher_topic_id'),
            'audio_path' => $request->input('audio_path'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'name' => $data['name'],
            'teacher_topic_id' => $data['teacher_topic_id'],
        ];
    
        $existingRecord = Subtopic::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();

            if($request->hasFile('audio')) {
                $path = $existingRecord ->audio_path;
                if(File::exists($path)) {
                      File::delete($path);
                }
                $file = $request->file('audio');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/audioSubtopic/', $filename);
                $data['audio_path'] = 'uploads/audioSubtopic/' .$filename;
            }
    
            Subtopic::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            if($request->hasFile('audio')) {
                $file = $request->file('audio');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/audioSubtopic/', $filename);
                $data['audio_path'] = 'uploads/audioSubtopic/' .$filename;
            }
    
            Subtopic::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Subtopic Added successfully',
        ]);
    }

    public static function edit($id) {
        $subtopics =  Subtopic::find($id);
        if($subtopics) {
            return response()->json([
                'status' => 200,
                'subtopics' => $subtopics,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Subtopic Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'teacher_topic_id' => 'required|integer|exists:teacher_topics,id',
            'audio' => 'nullable|mimes:mp3,wav|max:10240',
            'audio_path' => 'required|string|max:1000',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $subtopic = Subtopic::find($id);
        if($subtopic) {
            $subtopic->name = $request->input('name');
            $subtopic->teacher_topic_id = $request->input('teacher_topic_id');
            // $subtopic->audio_path = $request->input('audio_path');           
            $subtopic->status = $request->input('status'); 
            $subtopic->updated_at = now();  
            
            if ($request->hasFile('audio')) {
                $path = $subtopic->audio_path;
    
                if (File::exists($path)) {
                    File::delete($path);
                }
    
                $file = $request->file('audio');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/audioSubtopic/', $filename);
                $subtopic->audio_path = 'uploads/audioSubtopic/' . $filename;
            }
            
            $subtopic->update();
            return response()->json([
                'status'=>200,
                'message'=>'Subtopic Updated successfully',
            ]); 
        }
        else
        {


            return response()->json([
                'status'=>404,
                'message'=>'No Subtopic Id Found',
            ]); 
        }
    }

}
