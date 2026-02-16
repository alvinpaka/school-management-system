<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'section_id' => 'required|exists:sections,id',
            'admission_number' => 'required|string|unique:students,admission_number',
            'roll_number' => 'required|string|unique:students,roll_number',
            'date_of_birth' => 'nullable|date|date_format:Y-m-d',
            'gender' => 'nullable|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'admission_date' => 'nullable|date|date_format:Y-m-d',
            'status' => 'nullable|in:active,inactive,graduated,transferred',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'parent_name' => 'nullable|string|max:255',
            'parent_email' => 'nullable|email|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'medical_conditions' => 'nullable|string|max:1000',
            'previous_school' => 'nullable|string|max:255',
            'transfer_certificate' => 'nullable|string|max:255',
        ];
    }

    protected function prepareForValidation()
    {
        // Convert empty strings to null for all nullable fields
        $this->merge([
            'date_of_birth' => $this->date_of_birth === '' ? null : $this->date_of_birth,
            'admission_date' => $this->admission_date === '' ? null : $this->admission_date,
            'phone' => $this->phone === '' ? null : $this->phone,
            'address' => $this->address === '' ? null : $this->address,
            'parent_name' => $this->parent_name === '' ? null : $this->parent_name,
            'parent_email' => $this->parent_email === '' ? null : $this->parent_email,
            'parent_phone' => $this->parent_phone === '' ? null : $this->parent_phone,
            'emergency_contact' => $this->emergency_contact === '' ? null : $this->emergency_contact,
            'blood_group' => $this->blood_group === '' ? null : $this->blood_group,
            'medical_conditions' => $this->medical_conditions === '' ? null : $this->medical_conditions,
            'previous_school' => $this->previous_school === '' ? null : $this->previous_school,
            'transfer_certificate' => $this->transfer_certificate === '' ? null : $this->transfer_certificate,
        ]);
    }
}
