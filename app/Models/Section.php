<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['academic_class_id', 'name'];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
