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
        'employment_type',
        'status',
        'specialization',
        'qualification',
        'experience',
        'date_of_birth',
        'gender',
        'address',
        'joining_date',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'blood_group',
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
