<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationAnswerOption;

class EvaluationAnswerOptionController extends Controller
{
    public static function index() {
        return EvaluationAnswerOption::all();
    }

    public static function show($id) {
        return EvaluationAnswerOption::find($id); 
    }
}
