<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Theme;
use App\Models\Topic;
use App\Models\TeacherTopic;
use Illuminate\Http\Request;
use App\Models\ThemeLearningProgram;

class TagController extends Controller
{
    public static function index() {
        return Tag::all();
    }
    public static function show($id) {
        return Tag::find($id); 
    }

    public static function allTags(Request $request) {

        $subject_study_level_id = $request->input('subject_study_level_id');
    
        $tags = Tag::where('subject_study_level_id', $subject_study_level_id)->where('status',0)->get();

        return response()->json([
            'status' => 200,
            'tags' => $tags,
        ]);
    }

    public function getPostsByTags(Request $request)
    {
        $tagIds = $request->input('tagIds');
    
        $tags = Tag::whereIn('id', $tagIds)->get();
    
        $themeLearningPrograms = [];
        $teacherTopics = [];
    
        $addedThemeLearningPrograms = [];
        $addedTeacherTopics = [];
    
        foreach ($tags as $tag) {
            if ($tag->taggable_type === 'App\Models\Theme') {
                $themeId = $tag->taggable_id;
                $newThemeLearningPrograms = ThemeLearningProgram::where('theme_id', $themeId)->get()->toArray();
    
                foreach ($newThemeLearningPrograms as $newThemeLearningProgram) {
                    if (!isset($addedThemeLearningPrograms[$newThemeLearningProgram['id']])) {
                        $themeLearningPrograms[] = $newThemeLearningProgram;
                        $addedThemeLearningPrograms[$newThemeLearningProgram['id']] = true;
                    }
                }
            } elseif ($tag->taggable_type === 'App\Models\Topic') {
                $topicId = $tag->taggable_id;
                $newTeacherTopics = TeacherTopic::where('topic_id', $topicId)->get()->toArray();
    
                foreach ($newTeacherTopics as $newTeacherTopic) {
                    if (!isset($addedTeacherTopics[$newTeacherTopic['id']])) {
                        $teacherTopics[] = $newTeacherTopic;
                        $addedTeacherTopics[$newTeacherTopic['id']] = true;
                    }
                }
            }
        }
    
        return response()->json([
            'status' => 200,
            'themes' => $themeLearningPrograms,
            'topics' => $teacherTopics,
        ]);
    }

}
