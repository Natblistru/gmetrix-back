<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudyLevel;

class StudyLevelController extends Controller
{
    public static function index() {
        return StudyLevel::all();
    }

    public static function show($id) {
        return StudyLevel::find($id); 
    }

}
