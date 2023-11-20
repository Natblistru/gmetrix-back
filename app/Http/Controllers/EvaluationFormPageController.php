<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationFormPage;

class EvaluationFormPageController extends Controller
{
    public static function index() {
        return EvaluationFormPage::all();
    }

    public static function show($id) {
        return EvaluationFormPage::find($id); 
    }

}
