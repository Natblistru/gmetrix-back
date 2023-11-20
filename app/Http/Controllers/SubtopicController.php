<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subtopic;

class SubtopicController extends Controller
{
    public static function index() {
        return Subtopic::all();
    }

    public static function show($id) {
        return Subtopic::find($id); 
    }

}
