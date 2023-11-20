<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestComlexity;

class TestComlexityController extends Controller
{
    public static function index() {
        return TestComlexity::all();
    }

    public static function show($id) {
        return TestComlexity::find($id); 
    }
}
