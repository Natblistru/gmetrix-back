<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestItemOption;

class TestItemOptionController extends Controller
{
    public static function index() {
        return TestItemOption::all();
    }

    public static function show($id) {
        return TestItemOption::find($id); 
    }

}
