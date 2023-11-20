<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlipCard;

class FlipCardController extends Controller
{
    public static function index() {
        return FlipCard::all();
    }

    public static function show($id) {
        return FlipCard::find($id); 
    }

}
