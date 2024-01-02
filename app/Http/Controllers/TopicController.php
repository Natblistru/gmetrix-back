<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\Topic;
use Illuminate\Http\Request;

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

    public function allPosts() {
        $themes = Theme::where('status', 0)->get();
        $topics = Topic::where('status', 0)->get();
        $allPosts = $themes->merge($topics);
        return response()->json([
            'status' => 200,
            'allPosts' => $allPosts,
        ]);
    }

}
