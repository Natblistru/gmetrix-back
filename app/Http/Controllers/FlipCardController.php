<?php

namespace App\Http\Controllers;

use App\Models\FlipCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlipCardController extends Controller
{
    public static function index() {
        $flipCard =  FlipCard::all();
        return response()->json([
            'status' => 200,
            'flipCard' => $flipCard,
        ]);
    }

    public static function show($id) {
        return FlipCard::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:500',
            'answer' => 'required|string|max:5000',
            'teacher_topic_id' => 'required|exists:teacher_topics,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'task' => $request->input('task'),
            'answer' => $request->input('answer'),
            'teacher_topic_id' => $request->input('teacher_topic_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'teacher_topic_id' => $data['teacher_topic_id'], 
            'task' => $data['task'],       
        ];
    
        $existingRecord = FlipCard::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
 
            FlipCard::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            FlipCard::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Flip Card Added successfully',
        ]);
    }

    public static function edit($id) {
        $flipCard = FlipCard::with('teacher_topic')->find($id);
       
        if ($flipCard) {
            return response()->json([
                'status' => 200,
                'flipCard' => $flipCard,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Answer Source Id Found',
            ]);
        }
    }

    public static function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:500',
            'answer' => 'required|string|max:5000',
            'teacher_topic_id' => 'required|exists:teacher_topics,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $flipCard = FlipCard::find($id);
    
        if ($flipCard) {
            $flipCard->task = $request->input('task');
            $flipCard->answer = $request->input('answer');
            $flipCard->teacher_topic_id = $request->input('teacher_topic_id');
            $flipCard->status = $request->input('status');
    
            $flipCard->updated_at = now();
            $flipCard->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Flip Card Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Answer Id Found',
            ]);
        }
    }

}
