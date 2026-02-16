<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'parent_user_id',
        'academic_class_id',
        'section_id',
        'admission_number',
        'roll_number',
        'date_of_birth',
        'phone',
        'address',
        'gender',
        'admission_date',
        'parent_name',
        'parent_phone',
        'parent_email',
        'emergency_contact',
        'blood_group',
        'medical_conditions',
        'previous_school',
        'transfer_certificate',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function parentUser()
    {
        return $this->belongsTo(ParentUser::class);
    }
}
