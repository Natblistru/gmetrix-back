<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentSummativeTestOption;

class StudentSummativeTestOptionController extends Controller
{
    public static function index() {
        return StudentSummativeTestOption::all();
    }

    public static function show($id) {
        return StudentSummativeTestOption::find($id); 
    }

}
