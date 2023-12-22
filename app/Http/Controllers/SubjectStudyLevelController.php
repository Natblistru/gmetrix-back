<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectStudyLevel;
use App\Http\Requests\StoreSubjectStudyLevelRequest;
use App\Http\Requests\UpdateSubjectStudyLevelRequest;

class SubjectStudyLevelController extends Controller
{
    public static function index() {
        return SubjectStudyLevel::all();
    }

    public static function show($id) {
        return SubjectStudyLevel::find($id); 
    }

    public static function allSubjectStudyLevel() {
        $subject =  SubjectStudyLevel::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'subject' => $subject 
        ]);
    }

    public function store(StoreSubjectStudyLevelRequest $request)
    {
        $varModel = SubjectStudyLevel::create($request->validated());
        return response()->json($varModel, 201); 
    }
    public function update(UpdateSubjectStudyLevelRequest $request, SubjectStudyLevel $varModel)
    {
        $varModel->update($request->validated());
        return response()->json($varModel, 200); 
    } 

}
