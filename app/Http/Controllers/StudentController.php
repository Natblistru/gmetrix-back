<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public static function index() {
        return Student::all();
    }
    public static function show($id) {
        return Student::find($id); 
    }

}
