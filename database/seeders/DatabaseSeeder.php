<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            UserRoleSeeder::class,
            ParentUserSeeder::class,
            AcademicClassSeeder::class,
            SubjectSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            ClassTeacherSeeder::class,
            ExamSeeder::class,
            GradeSeeder::class,
            AttendanceSeeder::class,
            FeeSeeder::class,
        ]);
    }
}
