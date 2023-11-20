<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentSummativeTestResult;

class StudentSummativeTestResultController extends Controller
{
    public static function index() {
        return StudentSummativeTestResult::all();
    }

    public static function show($id) {
        return StudentSummativeTestResult::find($id); 
    }

}
