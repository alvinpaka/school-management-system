<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\ParentUser;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $studentUsers = User::where('email', 'like', '%@student.edu')->get();
        $classes = AcademicClass::all();
        $sections = Section::all();
        $parentUsers = ParentUser::with('user')->get();

        foreach ($studentUsers as $index => $user) {
            $class = $classes[$index % $classes->count()];
            $section = $sections[$index % $sections->count()];
            $parentUser = $parentUsers[$index % $parentUsers->count()];

            Student::firstOrCreate([
                'user_id' => $user->id,
            ], [
                'parent_user_id' => $parentUser->id, // Connect to parent user
                'academic_class_id' => $class->id,
                'section_id' => $section->id,
                'admission_number' => 'STD' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'roll_number' => 'ROLL' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'phone' => '987654321' . ($index + 1),
                'address' => '456 Student Avenue, Learning District',
                'date_of_birth' => now()->subYears(rand(6, 18))->subDays(rand(1, 365)),
                'gender' => $index % 2 === 0 ? 'Male' : 'Female',
                'admission_date' => now()->subYears(rand(1, 5)),
                'parent_name' => $parentUser->user->name, // Use parent user's name
                'parent_phone' => $parentUser->phone ?? '555123456' . ($index + 1),
                'parent_email' => $parentUser->user->email, // Use parent user's email
                'emergency_contact' => 'Emergency Contact ' . ($index + 1),
                'blood_group' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'][rand(0, 7)],
                'medical_conditions' => $index % 5 === 0 ? 'None' : 'Mild Allergies',
                'previous_school' => $index % 3 === 0 ? 'Previous School ' . ($index + 1) : null,
                'transfer_certificate' => null,
                'status' => 'active',
            ]);
        }
    }
}
