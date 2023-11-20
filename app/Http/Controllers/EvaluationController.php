<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluation;

class EvaluationController extends Controller
{
    public static function index() {
        return Evaluation::all();
    }

    public static function show($id) {
        return Evaluation::find($id); 
    }

}
