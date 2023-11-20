<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationOption;

class EvaluationOptionController extends Controller
{
    public static function index() {
        return EvaluationOption::all();
    }

    public static function show($id) {
        return EvaluationOption::find($id); 
    }

}
