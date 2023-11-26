<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherThemeVideo;

class TeacherThemeVideoController extends Controller
{
    public static function index() {
        return TeacherThemeVideo::all();
    }

    public static function show($id) {
        return TeacherThemeVideo::find($id); 
    }

}
