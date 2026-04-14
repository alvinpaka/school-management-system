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
            // Make section_id nullable to allow teachers to be assigned to classes without specific sections
            $table->foreignId('section_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_teachers', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable(false)->change();
        });
    }
};
