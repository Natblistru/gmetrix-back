<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Theme;
use App\Models\Topic;
use App\Models\TeacherTopic;
use Illuminate\Http\Request;
use App\Models\ThemeLearningProgram;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tag_name' => 'required|string|max:50',
            'taggable_id' => 'required|integer',
            'taggable_type' => 'required|string|max:255',
            'subject_study_level_id' => 'required|exists:subject_study_levels,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }

        $data = [
            'tag_name' => $request->input('tag_name'),
            'taggable_id' => $request->input('taggable_id'),
            'taggable_type' => $request->input('taggable_type'),
            'subject_study_level_id' => $request->input('subject_study_level_id'),
            'status' => $request->input('status'),
        ];

        $combinationColumns = [
            'tag_name' => $data['tag_name'],
            'taggable_id' => $data['taggable_id'],
            'taggable_type' => $data['taggable_type'],
            'subject_study_level_id' => $data['subject_study_level_id'],
        ];

        $existingRecord = Tag::where($combinationColumns)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();

            Tag::where($combinationColumns)->update($data);
            $updatedRecord = Tag::where($combinationColumns)->first();
            return response()->json([
                'status' => 201,
                'message' => 'Tag Updated successfully',
                'tag' => $updatedRecord,
            ]);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            $newTag = Tag::create($data);
            return response()->json([
                'status' => 201,
                'message' => 'Tag Added successfully',
                'tag' => $newTag,
            ]);
        }
    }

}
