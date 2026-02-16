<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassTeacher extends Model
{
    protected $fillable = [
        'teacher_id',
        'academic_class_id',
        'section_id',
        'assigned_date',
        'status'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
