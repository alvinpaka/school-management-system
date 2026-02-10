<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage users',
            'manage students',
            'view students',
            'manage teachers',
            'view teachers',
            'manage classes',
            'view classes',
            'manage subjects',
            'view subjects',
            'view dashboard',
            'view grades',
            'edit grades',
            'mark attendance',
            'view attendance',
            'manage fees',
            'view exams',
            'manage exams',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        $adminRole = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        $teacherRole = \Spatie\Permission\Models\Role::create(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'view dashboard',
            'view students',
            'mark attendance',
            'view attendance',
            'view subjects',
            'view exams',
            'edit grades',
        ]);

        $studentRole = \Spatie\Permission\Models\Role::create(['name' => 'student']);
        $studentRole->givePermissionTo([
            'view dashboard',
            'view attendance',
            'view exams',
            'view grades',
        ]);

        $parentRole = \Spatie\Permission\Models\Role::create(['name' => 'parent']);
        $parentRole->givePermissionTo([
            'view dashboard',
            'view attendance',
            'view grades',
        ]);

        // Create Academic Class and Section
        $class10 = \App\Models\AcademicClass::create([
            'name' => 'Grade 10',
            'code' => 'G10',
        ]);

        $sectionA = \App\Models\Section::create([
            'academic_class_id' => $class10->id,
            'name' => 'Section A',
        ]);

        // Create a root admin user
        $adminUser = \App\Models\User::factory()->create([
            'name' => 'School Admin',
            'email' => 'admin@school.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $adminUser->assignRole($adminRole);

        // Create a demo teacher
        $teacherUser = \App\Models\User::factory()->create([
            'name' => 'Demo Teacher',
            'email' => 'teacher@school.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $teacherUser->assignRole($teacherRole);
        \App\Models\Teacher::create([
            'user_id' => $teacherUser->id,
            'employee_id' => 'TCH001',
            'phone' => '1234567890',
            'specialization' => 'Mathematics',
        ]);

        // Create a demo student
        $studentUser = \App\Models\User::factory()->create([
            'name' => 'Demo Student',
            'email' => 'student@school.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $studentUser->assignRole($studentRole);
        \App\Models\Student::create([
            'user_id' => $studentUser->id,
            'academic_class_id' => $class10->id,
            'section_id' => $sectionA->id,
            'admission_number' => 'STD2026001',
            'date_of_birth' => '2010-05-15',
        ]);
    }
}
