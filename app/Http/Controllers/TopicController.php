<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;

class TopicController extends Controller
{
    public static function index() {
        return Topic::all();
    }

    public static function show($id) {
        return Topic::find($id); 
    }

    public static function allTopics() {
        $topics =  Topic::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'topics' => $topics,
        ]);
    }

}
