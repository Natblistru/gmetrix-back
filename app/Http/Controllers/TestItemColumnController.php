<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestItemColumn;

class TestItemColumnController extends Controller
{
    public static function index() {
        return TestItemColumn::all();
    }

    public static function show($id) {
        return TestItemColumn::find($id); 
    }

}
