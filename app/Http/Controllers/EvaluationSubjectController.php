<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationSubject;

class EvaluationSubjectController extends Controller
{
    public static function index() {
        return EvaluationSubject::all();
    }

    public static function show($id) {
        return EvaluationSubject::find($id); 
    }

}
