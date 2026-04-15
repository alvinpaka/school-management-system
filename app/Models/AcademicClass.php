<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;

class AcademicClass extends Model
{
    protected $fillable = ['name', 'code'];

    protected $appends = ['grade_level', 'lead_educator'];

    /**
     * Get the lead educator for the academic class from the loaded teachers.
     */
    protected function leadEducator(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->teachers->first(function ($teacher) {
                    return $teacher->pivot->is_class_teacher;
                });
            },
        );
    }

    /**
     * Get the grade level from the class name (e.g., S2 -> 2).
     */
    protected function gradeLevel(): Attribute
    {
        return Attribute::make(
            get: function () {
                preg_match('/\d+/', $this->name, $matches);
                return $matches[0] ?? null;
            },
        );
    }


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
        return $this->belongsToMany(Teacher::class, 'class_teachers')
            ->withPivot('section_id', 'assigned_date', 'status', 'is_class_teacher');
    }
}
