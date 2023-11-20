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
