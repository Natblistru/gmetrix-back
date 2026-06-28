<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VideoLessonStudent;
use Illuminate\Http\Request;

class VideoLessonStudentController extends Controller
{
        public function index(Request $request)
    {
        $query = VideoLessonStudent::select(
            'id',
            'videoLesson_id',
            'student_id',
            'statut'
        )
        ->where('status', 0);

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('videoLesson_id')) {
            $query->where('videoLesson_id', $request->videoLesson_id);
        }

        $items = $query
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'videoLesson_id' => 'required|integer',
            'student_id' => 'required|integer',
            'statut' => 'required|string|max:50',
        ]);

        $item = VideoLessonStudent::updateOrCreate(
            [
                'videoLesson_id' => $request->videoLesson_id,
                'student_id' => $request->student_id,
            ],
            [
                'statut' => $request->statut,
            ]
        );

        return response()->json([
            'status' => 200,
            'message' => 'Statutul lecției pentru student a fost salvat.',
            'data' => $item,
        ]);
    }

    public function checkAccess(Request $request)
    {
        $request->validate([
            'videoLesson_id' => 'required|integer',
            'student_id' => 'required|integer',
        ]);

        $item = VideoLessonStudent::where('videoLesson_id', $request->videoLesson_id)
            ->where('student_id', $request->student_id)
            ->first();

        if (!$item) {
            return response()->json([
                'has_access' => false,
                'statut' => null,
            ]);
        }

        return response()->json([
            'has_access' => $item->statut === 'paid' || $item->statut === 'open' || $item->statut === 'active',
            'statut' => $item->statut,
            'data' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|string|max:50',
        ]);

        $item = VideoLessonStudent::findOrFail($id);

        $item->update([
            'statut' => $request->statut,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Statutul a fost actualizat.',
            'data' => $item,
        ]);
    }

    public function destroy($id)
    {
        $item = VideoLessonStudent::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Înregistrarea a fost ștearsă.',
        ]);
    }
}
