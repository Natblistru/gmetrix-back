<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EvaluationSource;

class EvaluationSourceController extends Controller
{
    public static function index() {
        $evaluationSources =  EvaluationSource::all();
        return response()->json([
            'status' => 200,
            'evaluationSources' => $evaluationSources,
        ]);
    }

    public static function show($id) {
        return EvaluationSource::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'title' => 'nullable|string|max:200',
            'content' => 'required|json',
            'author' => 'nullable|string|max:200',
            'text_sourse' => 'nullable|string|max:200',
            'theme_id' => 'required|exists:themes,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'name' => $request->input('name'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'author' => $request->input('author'),
            'text_sourse' => $request->input('text_sourse'),
            'theme_id' => $request->input('theme_id'),
        ];
    
        $combinatieColoane = [
            'name' => $data['name'],
            'title' => $data['title'],
            'author' => $data['author'],    
            'text_sourse' => $data['text_sourse'],      
            'theme_id' => $data['theme_id'],      
        ];
    
        $existingRecord = EvaluationSource::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            EvaluationSource::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            EvaluationSource::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Source Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationSource = EvaluationSource::with('theme')->find($id);
        if ($evaluationSource) {
            return response()->json([
                'status' => 200,
                'evaluationSource' => $evaluationSource,
                'subject_study_level_id' => $evaluationSource->theme->chapter->subject_study_level_id ?? null,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Source Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'title' => 'nullable|string|max:200',
            'content' => 'required|json',
            'author' => 'nullable|string|max:200',
            'text_sourse' => 'nullable|string|max:200',
            'theme_id' => 'required|exists:themes,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $evaluationSource = EvaluationSource::find($id);
        if($evaluationSource) {
            $evaluationSource->name = $request->input('name');
            $evaluationSource->title = $request->input('title');
            $evaluationSource->content = $request->input('content');
            $evaluationSource->author = $request->input('author');
            $evaluationSource->text_sourse = $request->input('text_sourse');
            $evaluationSource->theme_id = $request->input('theme_id');                     
            $evaluationSource->status = $request->input('status'); 
            $evaluationSource->updated_at = now();             
            $evaluationSource->update();
            return response()->json([
                'status'=>200,
                'message'=>'Evaluation Source Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Evaluation Source Id Found',
            ]); 
        }
    }
}
