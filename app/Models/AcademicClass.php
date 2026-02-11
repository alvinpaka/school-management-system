<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;

class AcademicClass extends Model
{
    protected $fillable = ['name', 'code'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'class_teacher');
    }
}
