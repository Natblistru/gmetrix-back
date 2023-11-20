<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentEvaluationAnswer;

class StudentEvaluationAnswerController extends Controller
{
    public static function index() {
        return StudentEvaluationAnswer::all();
    }

    public static function show($id) {
        return StudentEvaluationAnswer::find($id); 
    }

}
