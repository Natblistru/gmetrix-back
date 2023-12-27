<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubtopicImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SubtopicImageController extends Controller
{
    public static function index() {
        $subtopicImage =  SubtopicImage::all();
        return response()->json([
            'status' => 200,
            'subtopicImage' => $subtopicImage,
        ]);
    }

    public static function show($id) {
        return SubtopicImage::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'path' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'subtopic_id' => 'required|exists:subtopics,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'path' => $request->input('path'),
            'subtopic_id' => $request->input('subtopic_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'subtopic_id' => $data['subtopic_id'], 
            'path' => $data['path'],       
        ];
    
        $existingRecord = SubtopicImage::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();

            if($request->hasFile('image')) {
                $path = $existingRecord ->path;
                if(File::exists($path)) {
                      File::delete($path);
                }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/imageSubtopic/', $filename);
                $data['path'] = 'uploads/imageSubtopic/' .$filename;
            }
  
            SubtopicImage::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            if($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/imageSubtopic/', $filename);
                $data['path'] = 'uploads/imageSubtopic/' .$filename;
            }
    
            SubtopicImage::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Item Added successfully',
        ]);
    }

    public static function edit($id) {
        $subtopicImage = SubtopicImage::with('subtopic')->find($id);
       
        if ($subtopicImage) {
            return response()->json([
                'status' => 200,
                'subtopicImage' => $subtopicImage,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Subtopic Image Id Found',
            ]);
        }
    }

    public static function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'path' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'subtopic_id' => 'required|exists:subtopics,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $subtopicImage = SubtopicImage::find($id);
    
        if ($subtopicImage) {
            $subtopicImage->subtopic_id = $request->input('subtopic_id');
            $subtopicImage->status = $request->input('status');
    
            if ($request->hasFile('image')) {
                $path = $subtopicImage->path;
                    if (File::exists($path)) {
                    File::delete($path);
                }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/imageSubtopic/', $filename);
                $subtopicImage->path = 'uploads/imageSubtopic/' . $filename;
            }
    
            $subtopicImage->updated_at = now();
            $subtopicImage->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Subtopic Image Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Subtopic Image Id Found',
            ]);
        }
    }


}
