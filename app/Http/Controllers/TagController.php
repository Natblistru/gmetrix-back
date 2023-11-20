<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public static function index() {
        return Tag::all();
    }
    public static function show($id) {
        return Tag::find($id); 
    }

}
