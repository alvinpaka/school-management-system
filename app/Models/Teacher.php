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
        return $this->belongsToMany(AcademicClass::class, 'class_teachers')
            ->withPivot('section_id', 'assigned_date', 'status', 'is_class_teacher');
    }

    public function classTeacherAssignments()
    {
        return $this->belongsToMany(AcademicClass::class, 'class_teachers')
            ->withPivot('section_id', 'assigned_date', 'status', 'is_class_teacher')
            ->wherePivot('is_class_teacher', true);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject')
            ->withPivot('academic_class_id', 'section_id', 'assigned_date', 'status');
    }

    /**
     * Check if this teacher is a class teacher for a specific class-section
     */
    public function isClassTeacherFor(int $classId, ?int $sectionId = null): bool
    {
        $query = $this->classes()
            ->wherePivot('is_class_teacher', true)
            ->where('academic_classes.id', $classId);
        
        if ($sectionId) {
            $query->wherePivot('section_id', $sectionId);
        }
        
        return $query->exists();
    }
}
