<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public static function index() {
        return Subject::all();
    }

    public static function show($id) {
        return Subject::find($id); 
    }

}
