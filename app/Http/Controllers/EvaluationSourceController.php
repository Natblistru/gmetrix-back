<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationSource;

class EvaluationSourceController extends Controller
{
    public static function index() {
        return EvaluationSource::all();
    }

    public static function show($id) {
        return EvaluationSource::find($id); 
    }
}
