<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $teacher = $this->route('teacher');
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'password' => 'nullable|string|min:8',
            'employee_id' => 'required|string|unique:teachers,employee_id,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
        ];
    }
}
