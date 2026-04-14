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
        // Add section_id column
        Schema::table('teacher_subject', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable()->after('academic_class_id');
        });
        
        // Drop old unique constraint if exists (using raw SQL for safety)
        try {
            DB::statement('ALTER TABLE teacher_subject DROP INDEX IF EXISTS teacher_subject_class_unique');
        } catch (\Exception $e) {
            // Constraint might not exist
        }
        
        // Note: We don't add a new unique constraint here because existing data
        // might have duplicates. The application code enforces uniqueness.
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
            
            $table->dropColumn('section_id');
            
            // Restore old unique constraint
            $table->unique(['teacher_id', 'subject_id', 'academic_class_id'], 'teacher_subject_class_unique');
        });
    }
};
