<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubtopicImage;

class SubtopicImageController extends Controller
{
    public static function index() {
        return SubtopicImage::all();
    }

    public static function show($id) {
        return SubtopicImage::find($id); 
    }

}
