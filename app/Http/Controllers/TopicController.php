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

}
