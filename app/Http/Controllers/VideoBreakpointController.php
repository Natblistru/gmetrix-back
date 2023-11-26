<?php

namespace App\Http\Controllers;
use App\Models\VideoBreakpoint;

use Illuminate\Http\Request;

class VideoBreakpointController extends Controller
{
    public static function index() {
        return VideoBreakpoint::all();
    }

    public static function show($id) {
        return VideoBreakpoint::find($id); 
    }

}
