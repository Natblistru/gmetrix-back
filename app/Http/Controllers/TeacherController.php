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

    public static function allTeachers() {
        $teachers =  Teacher::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'teachers' => $teachers,
        ]);
    }

}
