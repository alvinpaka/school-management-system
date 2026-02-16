<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Staff emails that should NOT receive the teacher role
        $staffEmails = [
            'admin@school.edu',
            'accountant@school.edu',
            'librarian@school.edu',
            'receptionist@school.edu',
        ];

        // Assign admin role
        $admin = User::where('email', 'admin@school.edu')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }

        // Assign teacher roles (school.edu emails, excluding admin and staff)
        $teachers = User::where('email', 'like', '%@school.edu')
            ->whereNotIn('email', $staffEmails)
            ->get();

        foreach ($teachers as $teacher) {
            $teacher->assignRole('teacher');
        }

        // Assign student roles
        $students = User::where('email', 'like', '%@student.edu')->get();
        foreach ($students as $student) {
            $student->assignRole('student');
        }

        // Assign parent roles
        $parents = User::where('email', 'like', '%@parent.edu')->get();
        foreach ($parents as $parent) {
            $parent->assignRole('parent');
        }

        // Assign accountant role
        $accountant = User::where('email', 'accountant@school.edu')->first();
        if ($accountant) {
            $accountant->assignRole('accountant');
        }

        // Assign librarian role
        $librarian = User::where('email', 'librarian@school.edu')->first();
        if ($librarian) {
            $librarian->assignRole('librarian');
        }

        // Assign receptionist role
        $receptionist = User::where('email', 'receptionist@school.edu')->first();
        if ($receptionist) {
            $receptionist->assignRole('receptionist');
        }
    }
}
