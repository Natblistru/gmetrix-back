<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummativeTestItem;

class SummativeTestItemController extends Controller
{
    public static function index() {
        return SummativeTestItem::all();
    }

    public static function show($id) {
        return SummativeTestItem::find($id); 
    }

}
