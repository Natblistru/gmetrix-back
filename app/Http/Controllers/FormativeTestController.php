<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormativeTest;

class FormativeTestController extends Controller
{
    public static function index() {
        return FormativeTest::all();
    }

    public static function show($id) {
        return FormativeTest::find($id); 
    }

}
