<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theme;

class ThemeController extends Controller
{
    public static function index() {
        return Theme::all();
    }
    public static function show($id) {
        return Theme::find($id); 
    }
}

