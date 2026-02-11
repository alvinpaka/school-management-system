<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AcademicClass;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'phone',
        'specialization'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->belongsToMany(AcademicClass::class, 'class_teacher');
    }
}
