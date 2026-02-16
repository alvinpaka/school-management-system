<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ─── Create Permissions ───────────────────────────────────────
        $permissions = [
            // Dashboard
            'view dashboard',

            // Students
            'manage students',
            'view students',
            'create students',

            // Teachers
            'manage teachers',
            'view teachers',

            // Parents
            'manage parents',
            'view parents',
            'create parents',

            // Library
            'manage library',
            'view library',

            // Fees / Account
            'manage fees',
            'view fees',

            // Classes
            'manage classes',
            'view classes',

            // Subjects
            'manage subjects',
            'view subjects',

            // Class Routine / Timetable
            'view class routine',

            // Attendance
            'mark attendance',
            'view attendance',

            // Exams
            'manage exams',
            'view exams',

            // Grades
            'edit grades',
            'view grades',

            // Transport
            'manage transport',
            'view transport',
            'create transport',

            // Notices
            'manage notices',
            'view notices',
            'create notices',

            // Map
            'view map',

            // Report Cards
            'manage report cards',
            'view report cards',
            'create report cards',

            // Users & System
            'manage users',

            // Visitors
            'manage visitors',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ─── ADMIN (Full System Control) ──────────────────────────────
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // ─── TEACHER ─────────────────────────────────────────────────
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $teacherRole->syncPermissions([
            'view dashboard',
            'view students',
            'view teachers',
            'view library',
            'view classes',
            'view subjects',
            'view class routine',
            'mark attendance',
            'view attendance',
            'view exams',
            'edit grades',
            'view grades',
            'create notices',
            'view notices',
            'view map',
            'view report cards',
            'create report cards',
        ]);

        // ─── STUDENT ─────────────────────────────────────────────────
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->syncPermissions([
            'view dashboard',
            'view students',     // own profile only (enforced in controller)
            'view teachers',
            'view library',
            'view fees',         // own fees only
            'view classes',
            'view subjects',
            'view class routine',
            'view attendance',   // own attendance only
            'view exams',        // own results only
            'view grades',
            'view transport',
            'view notices',
            'view map',
            'view report cards', // own only
        ]);

        // ─── PARENT ──────────────────────────────────────────────────
        $parentRole = Role::firstOrCreate(['name' => 'parent']);
        $parentRole->syncPermissions([
            'view dashboard',
            'view students',     // linked child only
            'view teachers',
            'view parents',      // own profile
            'view library',
            'view fees',         // child fees
            'view classes',
            'view subjects',
            'view class routine',
            'view attendance',   // child attendance
            'view exams',
            'view grades',
            'view transport',
            'view notices',
            'view map',
            'view report cards',
        ]);

        // ─── ACCOUNTANT ──────────────────────────────────────────────
        $accountantRole = Role::firstOrCreate(['name' => 'accountant']);
        $accountantRole->syncPermissions([
            'view dashboard',
            'view students',
            'view teachers',
            'view parents',
            'manage fees',
            'view fees',
            'view classes',
            'create transport',  // transport payments
            'view transport',
            'create notices',    // finance notices
            'view notices',
            'view report cards',
        ]);

        // ─── LIBRARIAN ───────────────────────────────────────────────
        $librarianRole = Role::firstOrCreate(['name' => 'librarian']);
        $librarianRole->syncPermissions([
            'view dashboard',
            'view students',
            'view teachers',
            'manage library',
            'view library',
            'view notices',
        ]);

        // ─── RECEPTIONIST ────────────────────────────────────────────
        $receptionistRole = Role::firstOrCreate(['name' => 'receptionist']);
        $receptionistRole->syncPermissions([
            'view dashboard',
            'view students',
            'create students',   // register new students
            'view teachers',
            'view parents',
            'create parents',    // register parents
            'view classes',
            'view class routine',
            'create transport',  // transport assignments
            'view transport',
            'view notices',
            'view map',
            'manage visitors',
        ]);
    }
}
