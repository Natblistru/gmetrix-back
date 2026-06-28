<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VideoLesson;


class VideoLessonController extends Controller
{
    public function index(Request $request)
    {
        $query = VideoLesson::select(
            'id',
            'title',
            'source',
            'status',
            'subject_id'
        );

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $lessons = $query
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($lessons);
    }
}
