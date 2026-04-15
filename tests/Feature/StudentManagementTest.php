<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_admin_can_create_student()
    {
        $admin = User::find(1) ?? User::factory()->create();
        if (!$admin->hasRole('admin')) $admin->assignRole('admin');

        $class = AcademicClass::firstOrCreate(['code' => 'G10'], ['name' => 'Grade 10']);
        $section = Section::firstOrCreate(['name' => 'A', 'academic_class_id' => $class->id]);

        $response = $this->actingAs($admin)->post(route('students.store'), [
            'name' => 'New Student',
            'email' => 'newstudent@example.com',
            'password' => 'password',
            'admission_number' => 'STD101',
            'academic_class_id' => $class->id,
            'section_id' => $section->id,
            'roll_number' => '101',
            'date_of_birth' => '2010-01-01',
        ]);

        $response->assertRedirect(); // Flexible check OR
        // $response->assertRedirect(route('students.show', Student::latest()->first()->id)); 
        // Better yet, just check that it redirects successfully to ANY student show route if we can easily get the ID.
        // For now, I'll use a specific route and assume it's the first student in this clean test run.
        $this->assertDatabaseHas('users', ['email' => 'newstudent@example.com']);
        $student = \App\Models\Student::where('admission_number', 'STD101')->first();
        $response->assertRedirect(route('students.show', $student->id));
        $this->assertDatabaseHas('students', ['admission_number' => 'STD101']);
    }
}
