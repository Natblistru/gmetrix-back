<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationAnswer;

class EvaluationAnswerController extends Controller
{
    public static function index() {
        return EvaluationAnswer::all();
    }

    public static function show($id) {
        return EvaluationAnswer::find($id); 
    }

}
