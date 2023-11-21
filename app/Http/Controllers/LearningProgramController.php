<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningProgram;
use Illuminate\Support\Facades\DB;

class LearningProgramController extends Controller
{
    public static function index() {
        return LearningProgram::all();
    }

    public static function show($id) {
        return LearningProgram::find($id); 
    }

    public static function disciplineAni(Request $request)  {

        $year = $request->query('year');
        $level = $request->query('level');

        $query = DB::table('learning_programs AS LL')
        ->select('LL.year', 'SL.name', 'SL.path', 'SL.subject_id','SL.id', 'SL.img', 'SL.study_level_id')
        ->join('subject_study_levels AS SL', 'SL.id', '=', 'LL.subject_study_level_id');

        if (empty($year)) {
            $query->where('LL.year', '=', function ($subquery) {
                $subquery->select(DB::raw('MAX(year)'))
                    ->from('learning_programs AS LP')
                    ->whereRaw('LP.subject_study_level_id = SL.id');
            });
        } else {
            $query->where('LL.year', $year);
        }

        if (empty($level)) {
            $query->where('SL.study_level_id', 1);
        } else {
            $query->where('SL.study_level_id', $level);
        }

        $result = $query->get();

        return $result;
    }
}
