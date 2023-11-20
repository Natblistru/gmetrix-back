<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummativeTest;

class SummativeTestController extends Controller
{
    public static function index() {
        return SummativeTest::all();
    }

    public static function show($id) {
        return SummativeTest::find($id); 
    }

}
