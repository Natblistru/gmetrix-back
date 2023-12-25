<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationItem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;


class EvaluationItemController extends Controller
{
    public static function index() {
        $evaluationItem =  EvaluationItem::all();
        return response()->json([
            'status' => 200,
            'evaluationItem' => $evaluationItem,
        ]);
    }

    public static function show($id) {
        return EvaluationItem::find($id); 
    }

    public static function allEvaluationItems() {
        $evaluationItems =  EvaluationItem::where('status',0)->get();
        return response()->json([
            'status' => 200,
            'evaluationItems' => $evaluationItems,
        ]);
    }

    

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'task' => 'required|string|max:1000',
            'statement' => 'nullable|string|max:1000',
            'image_path' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'editable_image_path' => 'nullable|string|max:1000',
            'editableImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',   
            'nota' => 'nullable|string|max:2000',
            'procent_paper' => 'required|string|max:5|regex:/^[0-9]+%$/',
            'evaluation_subject_id' => 'required|exists:evaluation_subjects,id',
            'theme_id' => 'required|exists:themes,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'order_number' => $request->input('order_number'),
            'task' => $request->input('task'),
            'statement' => $request->input('statement'),
            'image_path' => $request->input('image_path'),
            'editable_image_path' => $request->input('editable_image_path'),
            'nota' => $request->input('nota'),
            'procent_paper' => $request->input('procent_paper'),            
            'evaluation_subject_id' => $request->input('evaluation_subject_id'),
            'theme_id' => $request->input('theme_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'evaluation_subject_id' => $data['evaluation_subject_id'], 
            'theme_id' => $data['theme_id'],  
            'task' => $data['task'],       
        ];
    
        $existingRecord = EvaluationItem::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();

            if($request->hasFile('image')) {
                $path = $existingRecord ->image_path;
                if(File::exists($path)) {
                      File::delete($path);
                }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/evaluationItem/', $filename);
                $data['image_path'] = 'uploads/evaluationItem/' .$filename;
            }
            if($request->hasFile('editableImage')) {
                $path = $existingRecord ->editable_image_path;
                if(File::exists($path)) {
                      File::delete($path);
                }
                $file = $request->file('editableImage');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/evaluationItem/', $filename);
                $data['editable_image_path'] = 'uploads/evaluationItem/' .$filename;
            }
  
            EvaluationItem::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            if($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/evaluationItem/', $filename);
                $data['image_path'] = 'uploads/evaluationItem/' .$filename;
            }
            if($request->hasFile('editableImage')) {
                $file = $request->file('editableImage');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/evaluationItem/', $filename);
                $data['editable_image_path'] = 'uploads/evaluationItem/' .$filename;
            }
     
            EvaluationItem::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Evaluation Item Added successfully',
        ]);
    }

    public static function edit($id) {
        $evaluationItem = EvaluationItem::with('evaluation_subject')->with('theme')->find($id);
       
        if ($evaluationItem) {
            return response()->json([
                'status' => 200,
                'evaluationItem' => $evaluationItem,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Subject Source Id Found',
            ]);
        }
    }

    public static function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'task' => 'required|string|max:1000',
            'statement' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_path' => 'nullable|string|max:1000',
            'editableImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',   
            'editable_image_path' => 'nullable|string|max:1000',
            'nota' => 'nullable|string|max:2000',
            'procent_paper' => 'required|string|max:5|regex:/^[0-9]+%$/',
            'evaluation_subject_id' => 'required|exists:evaluation_subjects,id',
            'theme_id' => 'required|exists:themes,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    
        $evaluationItem = EvaluationItem::find($id);
    
        if ($evaluationItem) {
            $evaluationItem->order_number = $request->input('order_number');
            $evaluationItem->task = $request->input('task');
            $evaluationItem->nota = $request->input('nota');
            $evaluationItem->statement = $request->input('statement');
            // $evaluationItem->editable_image_path = $request->input('editable_image_path');
            $evaluationItem->procent_paper = $request->input('procent_paper');
            $evaluationItem->evaluation_subject_id = $request->input('evaluation_subject_id');
            $evaluationItem->theme_id = $request->input('theme_id');
            $evaluationItem->status = $request->input('status');
    
            if ($request->hasFile('image')) {
                $path = $evaluationItem->image_path;
                    if (File::exists($path)) {
                    File::delete($path);
                }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/evaluationItem/', $filename);
                $evaluationItem->image_path = 'uploads/evaluationItem/' . $filename;
            }
            if ($request->hasFile('editableImage')) {
                $path = $evaluationItem->editable_image_path;
                    if (File::exists($path)) {
                    File::delete($path);
                }
                $file = $request->file('editableImage');
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // numele original fără extensie
                $filename = $originalName . '_' . time() . '.' . $extension;
                $file->move('uploads/evaluationItem/', $filename);
                $evaluationItem->editable_image_path = 'uploads/evaluationItem/' . $filename;
            }
    
            $evaluationItem->updated_at = now();
            $evaluationItem->update();
    
            return response()->json([
                'status' => 200,
                'message' => 'Evaluation Subject Source Updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Evaluation Subject Id Found',
            ]);
        }
    }
    

}
