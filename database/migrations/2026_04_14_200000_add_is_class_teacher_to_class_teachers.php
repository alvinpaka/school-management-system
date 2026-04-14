<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_teachers', function (Blueprint $table) {
            $table->boolean('is_class_teacher')->default(false)->after('status');
            
            // Note: Partial unique indexes (WHERE is_class_teacher = true) are not supported in MySQL
            // The "only one class teacher per class-section" constraint is enforced in application code
            // See TeacherController::assignClass() which demotes existing class teachers before promoting a new one
        });
    }

    public function down(): void
    {
        Schema::table('class_teachers', function (Blueprint $table) {
            $table->dropColumn('is_class_teacher');
        });
    }
};
