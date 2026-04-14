<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('class_teachers', function (Blueprint $table) {
            // Drop the incorrect unique constraint if it exists
            try {
                $table->dropUnique('class_section_teacher_unique');
            } catch (\Exception $e) {
                // Constraint might not exist
            }
            
            // Add correct unique constraint: one teacher can only be assigned once to a class-section
            $table->unique(['teacher_id', 'academic_class_id', 'section_id'], 'teacher_class_section_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_teachers', function (Blueprint $table) {
            try {
                $table->dropUnique('teacher_class_section_unique');
            } catch (\Exception $e) {
                // Constraint might not exist
            }
        });
    }
};
