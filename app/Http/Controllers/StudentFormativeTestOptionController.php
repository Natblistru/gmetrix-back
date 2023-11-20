<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentFormativeTestOption;

class StudentFormativeTestOptionController extends Controller
{
    public static function index() {
        return StudentFormativeTestOption::all();
    }

    public static function show($id) {
        return StudentFormativeTestOption::find($id); 
    }

}
