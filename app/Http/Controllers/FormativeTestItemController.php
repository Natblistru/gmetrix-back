<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormativeTestItem;
use Illuminate\Support\Facades\Validator;

class FormativeTestItemController extends Controller
{
    public static function index() {
        $formativeTestItem =  FormativeTestItem::all();
        return response()->json([
            'status' => 200,
            'formativeTestItem' => $formativeTestItem,
        ]);
    }

    public static function show($id) {
        return FormativeTestItem::find($id); 
    }

    public static function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'test_item_id' => 'required|exists:test_items,id',
            'formative_test_id' => 'required|exists:formative_tests,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }

        $data = [
            'order_number' => $request->input('order_number'),
            'test_item_id' => $request->input('test_item_id'),
            'formative_test_id' => $request->input('formative_test_id'),
            'status' => $request->input('status'),
        ];
    
        $combinatieColoane = [
            'order_number' => $data['order_number'],
            'test_item_id' => $data['test_item_id'],
            'formative_test_id' => $data['formative_test_id'],         
        ];
    
        $existingRecord = FormativeTestItem::where($combinatieColoane)->first();

        if ($existingRecord) {
            $data['updated_at'] = now();
    
            FormativeTestItem::where($combinatieColoane)->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
    
            FormativeTestItem::create($data);
        }
 
        return response()->json([
            'status'=>201,
            'message'=>'Formative Test Item Added successfully',
        ]);
    }

    public static function edit($id) {
        $formativeTests = FormativeTestItem::find($id);
       
        if ($formativeTests) {
            return response()->json([
                'status' => 200,
                'formativeTests' => $formativeTests,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Test Item Id Found',
            ]);
        }
    }

    public static function update(Request $request,$id,) {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|integer|min:1',
            'test_item_id' => 'required|exists:test_items,id',
            'formative_test_id' => 'required|exists:formative_tests,id',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $formativeTestItemss = FormativeTestItem::find($id);
        if($formativeTestItemss) {
            $formativeTestItemss->order_number = $request->input('order_number');
            $formativeTestItemss->test_item_id = $request->input('test_item_id');
            $formativeTestItemss->formative_test_id = $request->input('formative_test_id');
            $formativeTestItemss->status = $request->input('status'); 
            $formativeTestItemss->updated_at = now();             
            $formativeTestItemss->update();
            return response()->json([
                'status'=>200,
                'message'=>'Formative Test Item Updated successfully',
            ]); 
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Formative Test Item  Id Found',
            ]); 
        }
    }

}
