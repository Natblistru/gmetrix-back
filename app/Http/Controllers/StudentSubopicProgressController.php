<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentSubopicProgress;

class StudentSubopicProgressController extends Controller
{
    public static function index() {
        return StudentSubopicProgress::all();
    }

    public static function show($id) {
        return StudentSubopicProgress::find($id); 
    }

}
