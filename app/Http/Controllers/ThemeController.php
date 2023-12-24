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

    public static function allChapters() {
        $themes =  Theme::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'themes' => $themes,
        ]);
    }
}

