<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectStudyLevelRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:5|max:200',
            'path' => 'required|string|min:5|max:200',
            'img' => 'required|string|min:5|max:200',
            'study_level_id' => 'required|exists:study_levels,id',
            'subject_id' => 'required|exists:subjects,id',
        ];
    }
}
