<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'phone' => $this->phone,
            'address' => $this->address,
            'occupation' => $this->occupation,
            'relationship_to_student' => $this->relationship_to_student,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'photo' => $this->user->photo,
            ],
            'students' => $this->students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'user_id' => $student->user_id,
                    'parent_user_id' => $student->parent_user_id,
                    'academic_class_id' => $student->academic_class_id,
                    'section_id' => $student->section_id,
                    'admission_number' => $student->admission_number,
                    'roll_number' => $student->roll_number,
                    'user' => [
                        'id' => $student->user->id,
                        'name' => $student->user->name,
                        'email' => $student->user->email,
                        'photo' => $student->user->photo,
                    ],
                    'academicClass' => [
                        'id' => $student->academicClass->id,
                        'name' => $student->academicClass->name,
                        'code' => $student->academicClass->code,
                    ],
                    'section' => $student->section ? [
                        'id' => $student->section->id,
                        'name' => $student->section->name,
                    ] : null,
                ];
            }),
        ];
    }
}
