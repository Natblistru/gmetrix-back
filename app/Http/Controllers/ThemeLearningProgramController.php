<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ThemeLearningProgram;

class ThemeLearningProgramController extends Controller
{
    public static function index() {
        return ThemeLearningProgram::all();
    }

    public static function show($id) {
        return ThemeLearningProgram::find($id); 
    }

    public static function capitoleDisciplina(Request $request)  {

        $learnProgrId = $request->query('program');
        $subjectId = $request->query('disciplina');
    
        $result = DB::table('theme_learning_programs AS TH')
        ->join('themes', 'TH.theme_id', '=', 'themes.id')
        ->join('chapters', 'themes.chapter_id', '=', 'chapters.id')
        ->join('subject_study_levels AS SSLC', 'chapters.subject_study_level_id', '=', 'SSLC.id')
        ->join('learning_programs AS LP', 'TH.learning_program_id', '=', 'LP.id')
        ->join('subject_study_levels AS SSLL', 'LP.subject_study_level_id', '=', 'SSLL.id')
        ->select(
            'TH.learning_program_id as learning_program_id',
            'themes.name as denumire_tema',
            'themes.path',
            'chapters.name as denimire_capitol',
            'chapters.order_number as capitol_ord',
            'chapters.subject_study_level_id as discipl_level_id',
            'SSLL.name as discipl_level_name',
            'SSLL.id as discipl_level_id',
            'SSLL.subject_id as subject_id'
        )
        ->where('SSLC.study_level_id', $learnProgrId)
        ->where('SSLL.study_level_id', $learnProgrId)
        ->where('SSLC.subject_id', $subjectId)
        ->where('SSLL.subject_id', $subjectId)
        ->orderBy('SSLL.id')
        ->orderBy('chapters.order_number')
        ->get();
    
    return $result;
    
        return $result;
    }

}
