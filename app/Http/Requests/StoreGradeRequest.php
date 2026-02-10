<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasRole(['admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.marks_obtained' => 'required|integer|min:0',
            'grades.*.remarks' => 'nullable|string',
        ];
    }
}
