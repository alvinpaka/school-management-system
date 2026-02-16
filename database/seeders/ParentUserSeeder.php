<?php

namespace Database\Seeders;

use App\Models\ParentUser;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParentUserSeeder extends Seeder
{
    public function run(): void
    {
        // Get all parent users
        $parentUsers = User::where('email', 'like', '%@parent.edu')->get();
        
        // Get all students
        $students = Student::all();
        
        // Assign parents to students (distribute students among parents)
        foreach ($parentUsers as $index => $parentUser) {
            // Create ParentUser record
            $parentRecord = ParentUser::firstOrCreate([
                'user_id' => $parentUser->id,
            ], [
                'phone' => '555123456' . ($index + 1),
                'address' => '123 Parent Street, Family City',
                'occupation' => ['Engineer', 'Teacher', 'Doctor', 'Business Owner'][$index % 4],
                'relationship_to_student' => 'Father',
            ]);
            
            // Assign 2-3 students to each parent
            $assignedStudents = $students->slice($index * 3, 3);
            
            foreach ($assignedStudents as $student) {
                $student->update(['parent_user_id' => $parentRecord->id]);
            }
        }
    }
}
