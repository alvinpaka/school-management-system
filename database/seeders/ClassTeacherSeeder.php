<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\AcademicClass;
use App\Models\Section;
use Illuminate\Database\Seeder;

class ClassTeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = Teacher::all();
        $classes = AcademicClass::all();
        $sections = Section::all();

        // Assign teachers to classes and sections
        foreach ($classes as $index => $class) {
            $teacher = $teachers[$index % $teachers->count()];
            
            // Assign teacher to all sections of this class
            $classSections = $sections->where('academic_class_id', $class->id);
            
            foreach ($classSections as $section) {
                // Check if assignment already exists
                $existing = \App\Models\ClassTeacher::where('teacher_id', $teacher->id)
                    ->where('academic_class_id', $class->id)
                    ->where('section_id', $section->id)
                    ->first();
                
                if (!$existing) {
                    \App\Models\ClassTeacher::create([
                        'teacher_id' => $teacher->id,
                        'academic_class_id' => $class->id,
                        'section_id' => $section->id,
                        'assigned_date' => now()->subMonths(rand(1, 6)),
                        'status' => 'active',
                    ]);
                }
            }
        }
    }
}
