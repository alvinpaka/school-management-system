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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['general', 'holiday', 'exam', 'meeting', 'policy', 'event'])->default('general');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('target_audience', ['all', 'students', 'parents', 'teachers', 'staff'])->default('all');
            $table->date('published_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
