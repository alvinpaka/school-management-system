<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'code', 'type', 'level'];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject')
            ->withPivot('academic_class_id', 'assigned_date', 'status');
    }
}
