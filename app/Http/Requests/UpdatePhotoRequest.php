<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhotoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Users can update their own photo
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=300,max_width=800,min_height=400,max_height=800', // Flexible passport size: 300-800px width, 400-800px height
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'photo.required' => 'Please select a photo to upload.',
            'photo.image' => 'The file must be an image.',
            'photo.mimes' => 'The photo must be a JPEG, JPG, or PNG file.',
            'photo.max' => 'The photo may not be larger than 2MB.',
            'photo.dimensions' => 'The photo must be between 300-800px wide and 400-800px tall (passport size requirements).',
        ];
    }
}
