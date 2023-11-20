<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectStudyLevelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        $method = $this->method();

        $commonRules = [
          'name' => 'sometimes|string|min:5|max:200',
          'path' => 'sometimes|string|min:5|max:200',
          'img' => 'sometimes|string|min:5|max:200',
          'study_level_id' => 'sometimes|exists:study_levels,id',
          'subject_id' => 'sometimes|exists:subjects,id',
        ];

        if ($method === 'PUT') {
            $specificRules = [
              'name' => 'required|string|min:5|max:200',
              'path' => 'required|string|min:5|max:200',
              'img' => 'required|string|min:5|max:200',
              'study_level_id' => 'required|exists:study_levels,id',
              'subject_id' => 'required|exists:subjects,id',
            ];
            return $specificRules;
        }
        return $commonRules;
    }
}
