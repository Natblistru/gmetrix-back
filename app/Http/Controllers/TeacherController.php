<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherController extends Controller
{
    public static function index() {
        return Teacher::all();
    }
    public static function show($id) {
        return Teacher::find($id); 
    }

}
