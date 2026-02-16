<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teacherUsers = User::where('email', 'like', '%@school.edu')
            ->where('email', '!=', 'admin@school.edu')
            ->take(8)
            ->get();

        $specializations = [
            'Mathematics',
            'English', 
            'Science',
            'History',
            'Geography',
            'Physics',
            'Chemistry',
            'Biology'
        ];

        foreach ($teacherUsers as $index => $user) {
            Teacher::firstOrCreate([
                'user_id' => $user->id,
            ], [
                'employee_id' => 'TCH' . str_pad($index + 2, 3, '0', STR_PAD_LEFT), // Start from TCH002
                'phone' => '123456789' . ($index + 1),
                'specialization' => $specializations[$index] ?? 'General',
            ]);
        }
    }
}
