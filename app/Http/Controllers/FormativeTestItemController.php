<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormativeTestItem;

class FormativeTestItemController extends Controller
{
    public static function index() {
        return FormativeTestItem::all();
    }

    public static function show($id) {
        return FormativeTestItem::find($id); 
    }

}
