<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationSubjectSource;

class EvaluationSubjectSourceController extends Controller
{
    public static function index() {
        return EvaluationSubjectSource::all();
    }

    public static function show($id) {
        return EvaluationSubjectSource::find($id); 
    }
}
