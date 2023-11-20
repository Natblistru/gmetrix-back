<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherTopic;

class TeacherTopicController extends Controller
{
    public static function index() {
        return TeacherTopic::all();
    }

    public static function show($id) {
        return TeacherTopic::find($id); 
    }

}
