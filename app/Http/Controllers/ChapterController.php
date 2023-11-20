<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;

class ChapterController extends Controller
{
    public static function index(Request $request) {
        $subjectStudyLevelId = $request->input('subject_study_level_id');

        if ($subjectStudyLevelId !== null) {
            $chapters = Chapter::where('subject_study_level_id', $subjectStudyLevelId)->get();
        } else {
            $chapters = Chapter::all();
        }
        return response()->json($chapters);
    }

    public static function show($id) {
        return Chapter::find($id); 
    }

    public function store(StoreChapterRequest $request)
    {
        $chapter = Chapter::create($request->validated());
        return response()->json($chapter, 201); 
    }
    public function update(UpdateChapterRequest $request, Chapter $chapter)
    {
        $chapter->update($request->validated());
        return response()->json($chapter, 200); 
    }
    public function destroy(Chapter $chapter)
    {
        $chapter->delete();
        return response()->json(null, 204);
    }

}
