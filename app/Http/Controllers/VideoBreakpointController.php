<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\VideoBreakpoint;

use Illuminate\Http\Request;

class VideoBreakpointController extends Controller
{
    public static function index() {
        $breakpoints =  VideoBreakpoint::all();
        return response()->json([
            'status' => 200,
            'breakpoints' => $breakpoints,
        ]);
    }

    public static function show($id) {
        return VideoBreakpoint::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'video_id' => 'required|integer|min:0|exists:videos,id',
            'time' => ['required', 'string', 'max:10', 'regex:/^(?:[01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $timestamp = strtotime($request->input('time'));
        $seconds = date('H', $timestamp) * 3600 + date('i', $timestamp) * 60 + date('s', $timestamp); 

        $data = [
            'name' => $request->input('name'),
            'time' => $request->input('time'),
            'video_id' => $request->input('video_id'),
            'seconds' => strval($seconds),
            'status' => $request->input('status'),
        ];

        $combinatieColoane = [
            'name' => $data['name'],
            'video_id' => $data['video_id'],
        ];
    
        VideoBreakpoint::updateOrInsert(
            $combinatieColoane,
            $data
        );
        return response()->json([
            'status'=>201,
            'message'=>'Video Added successfully',
        ]);
    }

    public static function edit($id) {
        $breakpoint =  VideoBreakpoint::find($id);
        if($breakpoint) {
            return response()->json([
                'status' => 200,
                'breakpoint' => $breakpoint,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Breakpoint Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'video_id' => 'required|integer|min:0|exists:videos,id',
            'time' => ['required', 'string', 'max:10', 'regex:/^(?:[01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $breakpoint = VideoBreakpoint::find($id);
        if($breakpoint) {
            $breakpoint->name = $request->input('name');
            $breakpoint->time = $request->input('time');
            $breakpoint->video_id = $request->input('video_id');           
            $breakpoint->status = $request->input('status');            
            $breakpoint->update();
            return response()->json([
                'status'=>200,
                'message'=>'Breakpoint Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Breakpoint Id Found',
            ]); 
        }
    }

}
