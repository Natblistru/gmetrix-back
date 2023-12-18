<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Video;

class VideoController extends Controller
{
    public static function index() {
        $video =  Video::all();
        return response()->json([
            'status' => 200,
            'video' => $video,
        ]);
    }

    public static function show($id) {
        return Video::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'source' => 'required|string|max:500',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $video = new Video;
        $video->title = $request->input('title');
        $video->source = $request->input('source');
        $video->status = $request->input('status');
        $video->save();
        return response()->json([
            'status'=>201,
            'message'=>'Video Added successfully',
        ]);

    }

}
