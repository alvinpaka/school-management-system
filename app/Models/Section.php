<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Section extends Model
{
    protected $fillable = ['academic_class_id', 'name'];

    protected $appends = ['class_teacher'];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the teachers assigned to this section.
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'class_teachers')
            ->withPivot('is_class_teacher', 'status', 'assigned_date');
    }

    /**
     * Get the lead educator (class teacher) for this section from the loaded teachers.
     */
    protected function classTeacher(): Attribute
    {
        return Attribute::make(
            get: function () {
                // If teachers are eager loaded, filter from the collection for performance
                return $this->teachers->first(function ($teacher) {
                    return $teacher->pivot->is_class_teacher;
                });
            },
        );
    }
}
