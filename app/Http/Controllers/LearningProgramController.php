<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningProgram;

class LearningProgramController extends Controller
{
    public static function index() {
        return LearningProgram::all();
    }

    public static function show($id) {
        return LearningProgram::find($id); 
    }

}
