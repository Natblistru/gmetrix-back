<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChapterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $method = $this->method();

        $commonRules = [
            'name' => 'sometimes|string|min:5|max:500',
            'subject_study_level_id' => 'sometimes|exists:subject_study_levels,id',
        ];

        if ($method === 'PUT') {
            $specificRules = [
                'name' => 'required|string|min:5|max:500',
                'subject_study_level_id' => 'required|exists:subject_study_levels,id',
            ];
            return $specificRules;
        }
        return $commonRules;
    }
}
