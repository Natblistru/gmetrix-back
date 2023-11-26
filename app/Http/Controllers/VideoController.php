<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class VideoController extends Controller
{
    public static function index() {
        return Video::all();
    }

    public static function show($id) {
        return Video::find($id); 
    }

}
