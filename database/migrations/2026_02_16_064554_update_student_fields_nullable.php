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
        Schema::table('students', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('date_of_birth');
            $table->text('address')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('address');
            $table->date('admission_date')->nullable()->after('gender');
            $table->string('parent_name')->nullable()->after('admission_date');
            $table->string('parent_phone')->nullable()->after('parent_name');
            $table->string('parent_email')->nullable()->after('parent_phone');
            $table->string('emergency_contact')->nullable()->after('parent_email');
            $table->string('blood_group', 5)->nullable()->after('emergency_contact');
            $table->text('medical_conditions')->nullable()->after('blood_group');
            $table->string('previous_school')->nullable()->after('medical_conditions');
            $table->string('transfer_certificate')->nullable()->after('previous_school');
            $table->enum('status', ['active', 'inactive', 'graduated', 'transferred'])->default('active')->after('transfer_certificate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'address', 'gender', 'admission_date',
                'parent_name', 'parent_phone', 'parent_email', 'emergency_contact', 'blood_group',
                'medical_conditions', 'previous_school', 'transfer_certificate', 'status'
            ]);
        });
    }
};
