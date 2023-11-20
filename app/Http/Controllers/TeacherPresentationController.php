<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherPresentation;

class TeacherPresentationController extends Controller
{
    public static function index() {
        return TeacherPresentation::all();
    }

    public static function show($id) {
        return TeacherPresentation::find($id); 
    }
}
