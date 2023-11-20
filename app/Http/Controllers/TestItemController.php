<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestItem;

class TestItemController extends Controller
{
    public static function index() {
        return TestItem::all();
    }

    public static function show($id) {
        return TestItem::find($id); 
    }

}
