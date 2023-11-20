<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentFormativeTestResult;

class StudentFormativeTestResultController extends Controller
{
    public static function index() {
        return StudentFormativeTestResult::all();
    }

    public static function show($id) {
        return StudentFormativeTestResult::find($id); 
    }

}
