<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with fresh test data.
     */
    public function run(): void
    {
        $this->command->info('Truncating all tables...');
        $this->truncateAllTables();
        
        $this->command->info('Seeding database with test data...');
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            UserRoleSeeder::class,
            AcademicClassSeeder::class,
            SubjectSeeder::class,
            TeacherSeeder::class,
            ParentUserSeeder::class,
            StudentSeeder::class,
            ClassTeacherSeeder::class,
            ExamSeeder::class,
            GradeSeeder::class,
            AttendanceSeeder::class,
            FeeSeeder::class,
        ]);
        
        $this->command->info('Database seeded successfully!');
        $this->printSummary();
    }
    
    /**
     * Truncate all tables except migrations.
     */
    private function truncateAllTables(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        $tables = [
            'fees',
            'grades',
            'exams',
            'attendances',
            'class_teacher',
            'students',
            'teachers',
            'parent_users',
            'subjects',
            'sections',
            'academic_classes',
            'model_has_roles',
            'model_has_permissions',
            'role_has_permissions',
            'roles',
            'permissions',
            'users',
        ];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->command->info("  - Truncated: {$table}");
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
    
    /**
     * Print summary of seeded data.
     */
    private function printSummary(): void
    {
        $this->command->newLine();
        $this->command->info('╔════════════════════════════════════════════════╗');
        $this->command->info('║         DATABASE SEEDED SUCCESSFULLY           ║');
        $this->command->info('╠════════════════════════════════════════════════╣');
        $this->command->info('║  Users:                                        ║');
        $this->command->info('║    - Admin: admin@school.edu (password)      ║');
        $this->command->info('║    - Teachers: 8 users                         ║');
        $this->command->info('║    - Students: 26 users                        ║');
        $this->command->info('║    - Parents: 10 users                         ║');
        $this->command->info('║    - Staff: accountant, librarian, receptionist║');
        $this->command->info('║                                                ║');
        $this->command->info('║  Academic:                                     ║');
        $this->command->info('║    - Classes: S1, S2, S3, S4, S5, S6           ║');
        $this->command->info('║    - Sections: East, West, North, South        ║');
        $this->command->info('║    - Subjects: 30 (O-Level & A-Level)          ║');
        $this->command->info('║                                                ║');
        $this->command->info('║  Data:                                         ║');
        $this->command->info('║    - Students enrolled in classes              ║');
        $this->command->info('║    - Teachers assigned specializations         ║');
        $this->command->info('║    - Exams scheduled                           ║');
        $this->command->info('║    - Fees generated                            ║');
        $this->command->info('║    - Attendance records                        ║');
        $this->command->info('╚════════════════════════════════════════════════╝');
        $this->command->newLine();
    }
}
