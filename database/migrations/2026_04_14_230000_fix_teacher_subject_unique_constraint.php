<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop old unique constraint using raw SQL for MySQL
        try {
            DB::statement('ALTER TABLE teacher_subject DROP INDEX teacher_subject_class_unique');
        } catch (\Exception $e) {
            // Constraint might not exist
        }
        
        // Add new unique constraint including section_id
        // This allows same subject in different sections of same class
        Schema::table('teacher_subject', function (Blueprint $table) {
            $table->unique(['teacher_id', 'subject_id', 'academic_class_id', 'section_id'], 'teacher_subject_section_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_subject', function (Blueprint $table) {
            try {
                $table->dropUnique('teacher_subject_section_unique');
            } catch (\Exception $e) {
                // Constraint might not exist
            }
            
            // Restore old unique constraint
            $table->unique(['teacher_id', 'subject_id', 'academic_class_id'], 'teacher_subject_class_unique');
        });
    }
};
