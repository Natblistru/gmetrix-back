<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationItem;

class EvaluationItemController extends Controller
{
    public static function index() {
        return EvaluationItem::all();
    }

    public static function show($id) {
        return EvaluationItem::find($id); 
    }
}
